<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Order;
use App\Models\Admin\Product;
use App\Models\Admin\ProductInventory;
use App\Models\Admin\ProductVarient;
use App\Models\Admin\Review;
use App\Models\ShippingCost;
use App\Models\ChatMessage;
use App\Models\OrderDetail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class OrderChatController extends Controller
{
    public function index()
    {
        $messages = ChatMessage::where('admin_id', auth()->id())
            ->latest('id')
            ->limit(50)
            ->get()
            ->reverse()
            ->values();

        return view('backend.sales.order_chat.index', compact('messages'));
    }

    public function ask(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $question = trim($request->message);
        $lower    = strtolower($question);
        $adminId  = auth()->id();

        Log::info('OrderChat request', [
            'auth_id'  => $adminId,
            'question' => $question,
        ]);

        try {
            $last  = ChatMessage::where('admin_id', $adminId)->latest('id')->first();
            $state = $last->meta['state'] ?? 'idle';
            $ctx   = $last->meta['ctx']   ?? [];

            $result = null;

            if (in_array($lower, ['reset', 'cancel', 'start over', 'restart'])) {
                $result = $this->reply(
                    'reset',
                    '🔄 Conversation reset. Product link paste korun.',
                    ['state' => 'idle']
                );
            } elseif ($state === 'awaiting_variant') {
                $result = $this->handleVariantSelection($lower, $ctx);
            } elseif ($state === 'awaiting_qty') {
                $result = $this->handleQtyInput($lower, $ctx);
            } elseif ($state === 'awaiting_more_items') {
                $result = $this->handleMoreItemsInput($question, $lower, $ctx);
            } elseif ($state === 'awaiting_shipping_area') {
                $result = $this->handleShippingAreaInput($question, $ctx);
            } elseif ($state === 'awaiting_info') {
                $result = $this->handleCustomerInfo($question, $ctx);
            } else {
                $product = $this->extractProductFromMessage($question);
                if ($product) {
                    $result = $this->startOrderFlow($product, []);
                } else {
                    $result = $this->resolveAnswer($lower);
                }
            }

            $chat = ChatMessage::create([
                'admin_id' => $adminId,
                'question' => $question,
                'answer'   => $result['answer'],
                'intent'   => $result['intent'],
                'meta'     => [
                    'state' => $result['meta']['state'] ?? 'idle',
                    'ctx'   => $result['meta']['ctx']   ?? [],
                ],
            ]);

            return response()->json([
                'success'      => true,
                'answer'       => $chat->answer,
                'intent'       => $chat->intent,
                'chat_id'      => $chat->id,
                'state'        => $result['meta']['state'] ?? 'idle',
                'suggestions'  => $result['suggestions']  ?? null,
                'input_fields' => $result['input_fields'] ?? null,
            ]);
        } catch (\Throwable $e) {
            Log::error('OrderChat FAILED', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
            ]);

            return response()->json([
                'success' => false,
                'answer'  => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }

    // ═══════════════════════════════════════════════════════════════
    // ORDER FLOW — MULTI ITEM
    // ═══════════════════════════════════════════════════════════════

    private function extractProductFromMessage(string $message): ?Product
    {
        $msg = trim($message);

        if (preg_match('#/products?/([A-Za-z0-9\-_]+)#i', $msg, $m)) {
            $slug = $m[1];
            return Product::where('slug', $slug)
                ->orWhere('id', is_numeric($slug) ? $slug : 0)
                ->first();
        }

        if (preg_match('#/p/(\d+)#i', $msg, $m)) {
            return Product::find((int) $m[1]);
        }

        if (preg_match('/^(?:product|item)\s+(\d+)$/i', $msg, $m)) {
            return Product::find((int) $m[1]);
        }

        return null;
    }

    private function startOrderFlow(Product $product, array $existingItems = []): array
    {
        $product->load(['variants', 'inventory', 'price', 'shippings']);

        $regular = (float) ($product->price->regular_price ?? 0);
        $sale    = (float) ($product->price->sale_price ?? $regular);

        $priceLine = ($sale > 0 && $sale < $regular)
            ? "~~৳" . number_format($regular, 2) . "~~ <strong>৳" . number_format($sale, 2) . "</strong>"
            : "৳" . number_format($regular, 2);

        // VARIANT PRODUCT
        if ($product->is_variant && $product->variants->isNotEmpty()) {

            $html  = "🛒 <strong>" . e($product->name) . "</strong><br>";
            $html .= "Price: {$priceLine}<br><br>";
            $html .= "Ei product er <strong>variant</strong> ache. Kon ta order korte chan?<br><br>";

            $variantList        = [];
            $variantSuggestions = [];

            foreach ($product->variants as $i => $v) {
                $num = $i + 1;

                $label = '';
                if (!empty($v->attribute_value)) {
                    $attrs = is_array($v->attribute_value)
                        ? $v->attribute_value
                        : (json_decode($v->attribute_value, true) ?? []);
                    $parts = [];
                    foreach ($attrs as $k => $val) {
                        $parts[] = "{$k}: {$val}";
                    }
                    $label = implode(', ', $parts);
                }
                if ($label === '') {
                    $label = $v->sku ?? ('Variant #' . $num);
                }

                $vPrice = (float) ($v->price ?? 0);
                $stock  = (int) ($v->quantity ?? 0);
                $stockLabel = $stock > 0 ? "stock: {$stock}" : "❌ out of stock";

                $html .= "{$num}. <strong>" . e($label) . "</strong> — ৳" . number_format($vPrice, 2)
                    . " <small>({$stockLabel})</small><br>";

                $variantList[] = [
                    'id'    => $v->id,
                    'label' => $label,
                    'price' => $vPrice,
                    'stock' => $stock,
                    'sku'   => $v->sku,
                ];

                if ($stock > 0) {
                    $variantSuggestions[] = [
                        'label' => $num . '. ' . $label . ' — ৳' . number_format($vPrice, 2),
                        'value' => (string) $num,
                    ];
                }
            }

            $variantSuggestions[] = ['label' => '❌ Cancel', 'value' => 'cancel'];

            $html .= "<br>👉 Variant select korun:";

            return $this->reply('order_variant_list', $html, [
                'state' => 'awaiting_variant',
                'ctx'   => [
                    'product_id'       => $product->id,
                    'product_name'     => $product->name,
                    'variants'         => $variantList,
                    'product_shipping' => $this->getProductShippingInfo($product),
                    'items'            => $existingItems,
                ],
            ], $variantSuggestions);
        }

        // SIMPLE PRODUCT
        $stock = (int) ($product->inventory->stock ?? 0);
        if ($stock <= 0) {
            return $this->reply(
                'order_out_of_stock',
                "❌ <strong>" . e($product->name) . "</strong> ekhon out of stock. Onno product try korun.",
                [
                    'state' => empty($existingItems) ? 'idle' : 'awaiting_more_items',
                    'ctx'   => ['items' => $existingItems],
                ],
                empty($existingItems)
                    ? [['label' => '🔄 Reset', 'value' => 'reset']]
                    : [
                        ['label' => '✅ Done, proceed to checkout', 'value' => 'no'],
                        ['label' => '❌ Cancel', 'value' => 'cancel'],
                    ]
            );
        }

        return $this->askQuantity($product->id, $product->name, [
            'variant_id'       => null,
            'variant_label'    => null,
            'variant_sku'      => null,
            'price'            => $sale > 0 ? $sale : $regular,
            'stock'            => $stock,
            'product_shipping' => $this->getProductShippingInfo($product),
            'items'            => $existingItems,
        ], $priceLine);
    }

    private function askQuantity(int $productId, string $productName, array $ctx, string $priceLine = ''): array
    {
        $stock = (int) ($ctx['stock'] ?? 0);

        $html  = "🛒 <strong>" . e($productName) . "</strong>";
        if ($ctx['variant_label'] ?? null) {
            $html .= " — " . e($ctx['variant_label']);
        }
        $html .= "<br>";
        if ($priceLine !== '') {
            $html .= "Price: {$priceLine}<br>";
        } elseif (!empty($ctx['price'])) {
            $html .= "Price: ৳" . number_format((float) $ctx['price'], 2) . "<br>";
        }
        $html .= "Available stock: <strong>{$stock}</strong><br><br>";
        $html .= "👉 <strong>কয়টা</strong> কয়টা অর্ডার করতে চান?";

        $ctx['product_id']   = $productId;
        $ctx['product_name'] = $productName;

        $suggestions = [];
        $max = min(5, max(1, $stock));
        for ($i = 1; $i <= $max; $i++) {
            $suggestions[] = ['label' => (string) $i, 'value' => (string) $i];
        }
        $suggestions[] = ['label' => '❌ Cancel', 'value' => 'cancel'];

        return $this->reply('order_ask_qty', $html, [
            'state' => 'awaiting_qty',
            'ctx'   => $ctx,
        ], $suggestions, ['qty']);
    }

    private function handleQtyInput(string $lower, array $ctx): array
    {
        if (in_array($lower, ['cancel', 'no', 'exit'])) {
            return $this->reply(
                'order_cancelled',
                '❌ Order cancelled. Product link paste korun abar.',
                ['state' => 'idle'],
                [['label' => '🔄 Reset', 'value' => 'reset']]
            );
        }

        if (!preg_match('/^\d+$/', trim($lower))) {
            return $this->reply(
                'order_qty_invalid',
                "⚠️ Ekta valid number likhun (jemon: <strong>2</strong>) ba <strong>cancel</strong>.",
                ['state' => 'awaiting_qty', 'ctx' => $ctx]
            );
        }

        $qty   = (int) $lower;
        $stock = (int) ($ctx['stock'] ?? 0);

        if ($qty < 1) {
            return $this->reply(
                'order_qty_invalid',
                "⚠️ Minimum 1 piece order korte hobe.",
                ['state' => 'awaiting_qty', 'ctx' => $ctx]
            );
        }

        if ($qty > $stock) {
            return $this->reply(
                'order_qty_invalid',
                "⚠️ Ei product er stock matro <strong>{$stock}</strong>. Er beshi order kora jabe na.",
                ['state' => 'awaiting_qty', 'ctx' => $ctx]
            );
        }

        $items   = $ctx['items'] ?? [];
        $items[] = [
            'product_id'       => $ctx['product_id'],
            'product_name'     => $ctx['product_name'],
            'variant_id'       => $ctx['variant_id'] ?? null,
            'variant_label'    => $ctx['variant_label'] ?? null,
            'variant_sku'      => $ctx['variant_sku'] ?? null,
            'price'            => (float) $ctx['price'],
            'qty'              => $qty,
            'product_shipping' => $ctx['product_shipping'] ?? ['cost' => 0, 'type' => 'free'], // ← carry product shipping
        ];

        $html  = "✅ Added to order: <strong>" . e($ctx['product_name']);
        if (!empty($ctx['variant_label'])) {
            $html .= " — " . e($ctx['variant_label']);
        }
        $html .= "</strong> × {$qty}<br><br>";
        $html .= $this->renderCartHtml($items);
        $html .= "<br>👉 <strong>Aro product kinte chan?</strong>";

        return $this->reply('order_more_items', $html, [
            'state' => 'awaiting_more_items',
            'ctx'   => ['items' => $items],
        ], [
            ['label' => '✅ Done, proceed to checkout', 'value' => 'no'],
            ['label' => '❌ Cancel', 'value' => 'cancel'],
        ]);
    }

    private function handleMoreItemsInput(string $raw, string $lower, array $ctx): array
    {
        if (in_array($lower, ['cancel', 'exit'])) {
            return $this->reply(
                'order_cancelled',
                '❌ Order cancelled. Product link paste korun abar.',
                ['state' => 'idle'],
                [['label' => '🔄 Reset', 'value' => 'reset']]
            );
        }

        if (in_array($lower, ['no', 'na', 'nope', 'done', 'finish', 'confirm', 'checkout'])) {
            $items = $ctx['items'] ?? [];
            if (empty($items)) {
                return $this->reply(
                    'order_empty',
                    '⚠️ Kono item nai. Product link paste korun.',
                    ['state' => 'idle']
                );
            }
            return $this->askShippingArea(['items' => $items]);
        }

        $product = $this->extractProductFromMessage($raw);

        if (!$product) {
            return $this->reply(
                'order_more_items',
                "⚠️ Product link detect korte parlam na. Abar paste korun, ba <strong>Done</strong> click korun.<br><br>"
                    . $this->renderCartHtml($ctx['items'] ?? []),
                ['state' => 'awaiting_more_items', 'ctx' => $ctx],
                [
                    ['label' => '✅ Done, proceed to checkout', 'value' => 'no'],
                    ['label' => '❌ Cancel', 'value' => 'cancel'],
                ]
            );
        }

        return $this->startOrderFlow($product, $ctx['items'] ?? []);
    }

    // ═══════════════════════════════════════════════════════════════
    // SHIPPING — product shipping priority, then area
    // ═══════════════════════════════════════════════════════════════

    /**
     * Ask shipping area — but first, honor product-level shipping.
     *
     * Rule (matches CartShippingService):
     *  1. If product has its own flat_rate shipping_cost > 0  → use product cost, ignore area
     *  2. If product shipping_type is free / pickup           → 0 cost
     *  3. Otherwise (no product shipping)                     → ask area, use area cost
     */
    private function askShippingArea(array $ctx): array
    {
        $items = $ctx['items'] ?? [];

        // Categorize items
        $itemsWithOwnShip = []; // idx => ['cost' => X, 'source' => 'product'|'free'|'pickup']
        $itemsNeedingArea = []; // [idx, idx, ...]

        foreach ($items as $idx => $it) {
            $ps   = $it['product_shipping'] ?? ['cost' => 0, 'type' => 'free'];
            $type = $ps['type'] ?? 'free';
            $cost = (float) ($ps['cost'] ?? 0);

            if ($type === 'free') {
                $itemsWithOwnShip[$idx] = ['cost' => 0, 'source' => 'free'];
            } elseif ($type === 'pickup') {
                $itemsWithOwnShip[$idx] = ['cost' => 0, 'source' => 'pickup'];
            } elseif ($type === 'flat_rate' && $cost > 0) {
                $itemsWithOwnShip[$idx] = ['cost' => $cost, 'source' => 'product'];
            } else {
                // no product shipping → needs area
                $itemsNeedingArea[] = $idx;
            }
        }

        // CASE 1: No item needs area → skip area, use product shipping only
        if (empty($itemsNeedingArea)) {
            $totalShip = 0;
            foreach ($itemsWithOwnShip as $info) {
                $totalShip += (float) $info['cost'];
            }

            $ctx['shipping_cost']       = $totalShip;
            $ctx['shipping_area_id']    = null;
            $ctx['shipping_area_label'] = $totalShip > 0 ? 'Product shipping' : 'Free';
            $ctx['shipping_breakdown']  = $itemsWithOwnShip;

            $html  = $totalShip > 0
                ? "🚚 Shipping: <strong>৳" . number_format($totalShip, 2) . "</strong> (product shipping)<br><br>"
                : "🚚 Shipping: <strong>Free</strong><br><br>";
            $html .= $this->infoPromptHtml($ctx);

            return $this->reply('order_ask_info', $html, [
                'state' => 'awaiting_info',
                'ctx'   => $ctx,
            ], [], ['name', 'phone', 'address']);
        }

        // CASE 2: Some items need area → ask area
        $areas = ShippingCost::orderBy('name')->limit(30)->get();

        if ($areas->isEmpty()) {
            // No areas configured → treat missing as free
            $totalShip = 0;
            foreach ($itemsWithOwnShip as $info) {
                $totalShip += (float) $info['cost'];
            }
            $breakdown = $itemsWithOwnShip;
            foreach ($itemsNeedingArea as $idx) {
                $breakdown[$idx] = ['cost' => 0, 'source' => 'free'];
            }

            $ctx['shipping_cost']       = $totalShip;
            $ctx['shipping_area_id']    = null;
            $ctx['shipping_area_label'] = 'Free (no area configured)';
            $ctx['shipping_breakdown']  = $breakdown;

            $html  = "🚚 Kono shipping area configured nai. Baki items er shipping <strong>free</strong>.<br><br>";
            $html .= $this->infoPromptHtml($ctx);

            return $this->reply('order_ask_info', $html, [
                'state' => 'awaiting_info',
                'ctx'   => $ctx,
            ], [], ['name', 'phone', 'address']);
        }

        $html            = "🚚 <strong>Delivery area select korun</strong><br><br>";
        $areaList        = [];
        $areaSuggestions = [];

        foreach ($areas as $i => $a) {
            $num  = $i + 1;
            $cost = (float) ($a->cost ?? $a->amount ?? 0);

            $html .= "{$num}. <strong>" . e($a->name) . "</strong> — ৳" . number_format($cost, 2) . "<br>";

            $areaList[] = [
                'id'   => $a->id,
                'name' => $a->name,
                'cost' => $cost,
            ];

            $areaSuggestions[] = [
                'label' => $num . '. ' . $a->name . ' — ৳' . number_format($cost, 2),
                'value' => (string) $num,
            ];
        }

        $areaSuggestions[] = ['label' => '❌ Cancel', 'value' => 'cancel'];

        $html .= "<br>👉 Area select korun:";

        $ctx['shipping_areas']      = $areaList;
        $ctx['items_with_own_ship'] = $itemsWithOwnShip;
        $ctx['items_needing_area']  = $itemsNeedingArea;

        return $this->reply('order_ask_shipping_area', $html, [
            'state' => 'awaiting_shipping_area',
            'ctx'   => $ctx,
        ], $areaSuggestions);
    }

    private function handleShippingAreaInput(string $raw, array $ctx): array
    {
        $lower = strtolower(trim($raw));

        if (in_array($lower, ['cancel', 'no', 'exit'])) {
            return $this->reply(
                'order_cancelled',
                '❌ Order cancelled. Product link paste korun abar.',
                ['state' => 'idle'],
                [['label' => '🔄 Reset', 'value' => 'reset']]
            );
        }

        if (!preg_match('/\d+/', $lower, $m)) {
            return $this->reply(
                'order_shipping_invalid',
                "⚠️ Ekta area number likhun ba niche theke select korun.",
                ['state' => 'awaiting_shipping_area', 'ctx' => $ctx]
            );
        }

        $num   = (int) $m[0];
        $areas = $ctx['shipping_areas'] ?? [];

        if ($num < 1 || $num > count($areas)) {
            return $this->reply(
                'order_shipping_invalid',
                "⚠️ Number ta valid na. 1 theke " . count($areas) . " er modhye din.",
                ['state' => 'awaiting_shipping_area', 'ctx' => $ctx]
            );
        }

        $picked = $areas[$num - 1];

        // Build breakdown:
        //  - items_with_own_ship → keep their own cost
        //  - items_needing_area  → apply area cost
        $itemsWithOwnShip = $ctx['items_with_own_ship'] ?? [];
        $itemsNeedingArea = $ctx['items_needing_area'] ?? [];

        $breakdown     = [];
        $totalShipping = 0;

        foreach ($itemsWithOwnShip as $idx => $info) {
            $breakdown[$idx] = $info;
            $totalShipping  += (float) $info['cost'];
        }

        foreach ($itemsNeedingArea as $idx) {
            $breakdown[$idx] = ['cost' => (float) $picked['cost'], 'source' => 'area'];
            $totalShipping  += (float) $picked['cost'];
        }

        $ctx['shipping_cost']       = $totalShipping;
        $ctx['shipping_area_id']    = $picked['id'];
        $ctx['shipping_area_label'] = $picked['name'];
        $ctx['shipping_breakdown']  = $breakdown;

        $html  = "✅ Delivery: <strong>" . e($picked['name']) . "</strong>";
        $html .= " — Total shipping: <strong>৳" . number_format($totalShipping, 2) . "</strong><br><br>";
        $html .= $this->infoPromptHtml($ctx);

        return $this->reply('order_ask_info', $html, [
            'state' => 'awaiting_info',
            'ctx'   => $ctx,
        ], [], ['name', 'phone', 'address']);
    }

    private function renderCartHtml(array $items): string
    {
        if (empty($items)) {
            return "<em>Cart khali.</em>";
        }

        $html  = "🛍 <strong>Current Order</strong><br>";
        $subtotal = 0;
        foreach ($items as $i => $it) {
            $lineTotal = (float) $it['price'] * (int) $it['qty'];
            $subtotal += $lineTotal;

            $html .= ($i + 1) . ". " . e($it['product_name']);
            if (!empty($it['variant_label'])) {
                $html .= " — " . e($it['variant_label']);
            }
            $html .= " × " . (int) $it['qty']
                . " = ৳" . number_format($lineTotal, 2);

            // Show product shipping if present
            $ps = $it['product_shipping'] ?? null;
            if (is_array($ps) && ($ps['cost'] ?? 0) > 0) {
                $html .= " <small>(+ ship ৳" . number_format((float) $ps['cost'], 2) . ")</small>";
            }
            $html .= "<br>";
        }
        $html .= "<strong>Subtotal: ৳" . number_format($subtotal, 2) . "</strong>";
        return $html;
    }

    private function infoPromptHtml(array $ctx): string
    {
        $items     = $ctx['items'] ?? [];
        $ship      = (float) ($ctx['shipping_cost'] ?? 0);
        $breakdown = $ctx['shipping_breakdown'] ?? [];

        $subtotal = 0;
        $html     = "📋 <strong>Order Summary</strong><br>";
        foreach ($items as $i => $it) {
            $lineTotal = (float) $it['price'] * (int) $it['qty'];
            $subtotal += $lineTotal;

            $html .= "• " . e($it['product_name']);
            if (!empty($it['variant_label'])) {
                $html .= " — " . e($it['variant_label']);
            }
            $html .= " × " . (int) $it['qty']
                . " = ৳" . number_format($lineTotal, 2);

            $perItemShip = $breakdown[$i]['cost'] ?? null;
            if ($perItemShip !== null && $perItemShip > 0) {
                $srcLabel = $breakdown[$i]['source'] ?? '';
                $srcLabel = $srcLabel === 'product' ? 'product' : ($srcLabel === 'area' ? 'area' : '');
                $html .= " <small>(+ ship ৳" . number_format($perItemShip, 2)
                    . ($srcLabel ? " · {$srcLabel}" : "") . ")</small>";
            }
            $html .= "<br>";
        }

        $total = $subtotal + $ship;

        $html .= "• Shipping: ৳" . number_format($ship, 2) . "<br>";
        $html .= "• <strong>Total: ৳" . number_format($total, 2) . "</strong><br><br>";
        $html .= "👉 Niche <strong>name, phone, address</strong> puron korun:";

        return $html;
    }

    private function handleVariantSelection(string $lower, array $ctx): array
    {
        if (in_array($lower, ['cancel', 'no', 'na', 'exit'])) {
            return $this->reply(
                'order_cancelled',
                '❌ Order cancelled. Product link paste korun abar.',
                ['state' => 'idle'],
                [['label' => '🔄 Reset', 'value' => 'reset']]
            );
        }

        if (!preg_match('/\d+/', $lower, $m)) {
            return $this->reply(
                'order_variant_invalid',
                "⚠️ Ekta variant number likhun ba niche theke select korun.",
                ['state' => 'awaiting_variant', 'ctx' => $ctx]
            );
        }

        $num      = (int) $m[0];
        $variants = $ctx['variants'] ?? [];

        if ($num < 1 || $num > count($variants)) {
            return $this->reply(
                'order_variant_invalid',
                "⚠️ Number ta valid na. 1 theke " . count($variants) . " er modhye din.",
                ['state' => 'awaiting_variant', 'ctx' => $ctx]
            );
        }

        $picked = $variants[$num - 1];

        if (($picked['stock'] ?? 0) <= 0) {
            return $this->reply(
                'order_variant_out_of_stock',
                "❌ Ei variant ta out of stock. Onno number din.",
                ['state' => 'awaiting_variant', 'ctx' => $ctx]
            );
        }

        return $this->askQuantity($ctx['product_id'], $ctx['product_name'], [
            'variant_id'       => $picked['id'],
            'variant_label'    => $picked['label'],
            'variant_sku'      => $picked['sku'] ?? null,
            'price'            => (float) $picked['price'],
            'stock'            => (int) $picked['stock'],
            'product_shipping' => $ctx['product_shipping'] ?? ['cost' => 0, 'type' => 'flat_rate'],
            'items'            => $ctx['items'] ?? [],
        ], '৳' . number_format((float) $picked['price'], 2));
    }

    private function handleCustomerInfo(string $raw, array $ctx): array
    {
        $lower = strtolower(trim($raw));

        if (in_array($lower, ['cancel', 'no', 'exit'])) {
            return $this->reply(
                'order_cancelled',
                '❌ Order cancelled. Product link paste korun abar.',
                ['state' => 'idle'],
                [['label' => '🔄 Reset', 'value' => 'reset']]
            );
        }

        $parts = [];
        $json  = json_decode($raw, true);

        if (is_array($json) && isset($json['name'], $json['phone'], $json['address'])) {
            $parts = [
                trim($json['name']),
                trim($json['phone']),
                trim($json['address']),
            ];
        } else {
            $parts = array_map('trim', preg_split('/\s*[,，]\s*/', $raw));
        }

        if (count($parts) < 3 || $parts[0] === '' || $parts[1] === '' || $parts[2] === '') {
            return $this->reply(
                'order_info_invalid',
                "⚠️ 3 ta info lagbe: <strong>name, phone, address</strong>",
                ['state' => 'awaiting_info', 'ctx' => $ctx],
                [],
                ['name', 'phone', 'address']
            );
        }

        $name    = $parts[0];
        $phone   = $parts[1];
        $address = implode(', ', array_slice($parts, 2));

        if (!preg_match('/^01[3-9]\d{8}$/', preg_replace('/\D/', '', $phone))) {
            return $this->reply(
                'order_info_invalid',
                "⚠️ Phone number ta valid na. Bangladeshi format din (jemon: 01712345678).",
                ['state' => 'awaiting_info', 'ctx' => $ctx],
                [],
                ['name', 'phone', 'address']
            );
        }

        try {
            $order = $this->createChatOrder($ctx, [
                'name'    => $name,
                'phone'   => $phone,
                'address' => $address,
            ]);

            $items = $ctx['items'] ?? [];
            $ship  = (float) ($ctx['shipping_cost'] ?? 0);

            $html  = "✅ <strong>Order placed successfully!</strong><br><br>";
            $html .= "🧾 Order Code: <strong>" . e($order->code) . "</strong><br>";
            $html .= "👤 Name: " . e($name) . "<br>";
            $html .= "📞 Phone: " . e($phone) . "<br>";
            $html .= "📍 Address: " . e($address) . "<br>";
            $html .= "🚚 Area: " . e($ctx['shipping_area_label'] ?? 'N/A') . "<br><br>";
            $html .= $this->renderCartHtml($items);
            $html .= "<br>🚚 Shipping: ৳" . number_format($ship, 2) . "<br>";
            $html .= "💰 <strong>Total: ৳" . number_format((float) $order->grand_total, 2) . "</strong><br>";
            $html .= "💳 Payment: Cash on Delivery<br><br>";
            $html .= "Aro order korte product link paste korun.";

            return $this->reply('order_placed', $html, ['state' => 'idle'], [
                ['label' => '🔄 Reset', 'value' => 'reset'],
            ]);
        } catch (\Throwable $e) {
            Log::error('Chat order failed', ['error' => $e->getMessage()]);
            return $this->reply(
                'order_failed',
                "❌ Order place korte problem hoyeche: " . e($e->getMessage()),
                ['state' => 'idle'],
                [['label' => '🔄 Reset', 'value' => 'reset']]
            );
        }
    }

    /**
     * Get product's own shipping info.
     * Returns ['cost' => float, 'type' => 'flat_rate'|'free'|'pickup']
     */
    private function getProductShippingInfo(Product $product): array
    {
        $ship = $product->shippings->first();

        if (!$ship) {
            return ['cost' => 0, 'type' => 'free'];
        }

        $type = $ship->shipping_type ?? 'flat_rate';

        // free / pickup → cost 0
        if (in_array($type, ['free', 'pickup'], true)) {
            return ['cost' => 0, 'type' => $type];
        }

        // flat_rate → cost from product_shippings.shipping_cost
        return [
            'cost' => (float) ($ship->shipping_cost ?? 0),
            'type' => 'flat_rate',
        ];
    }

    private function createChatOrder(array $ctx, array $customer): Order
    {
        return DB::transaction(function () use ($ctx, $customer) {

            $items     = $ctx['items'] ?? [];
            $shipCost  = (float) ($ctx['shipping_cost'] ?? 0);
            $breakdown = $ctx['shipping_breakdown'] ?? [];

            if (empty($items)) {
                throw new \RuntimeException('No items in order.');
            }

            $subtotal = 0;
            foreach ($items as $it) {
                $subtotal += (float) $it['price'] * (int) $it['qty'];
            }
            $total = $subtotal + $shipCost;

            $orderCode = 'CHAT-' . strtoupper(Str::random(8)) . '-' . time();

            $order = Order::create([
                'user_id'               => null,
                'guest_id'              => 'admin_chat_' . auth()->id(),
                'shipping_address'      => $customer['address'],
                'shipping_type'         => 'flat_rate',
                'shipping_cost'         => $shipCost,
                'shipping_area_id'      => $ctx['shipping_area_id'] ?? null,
                'coupon_discount'       => 0,
                'discount'              => 0,
                'grand_total'           => $total,
                'code'                  => $orderCode,
                'notes'                 => 'Order via admin chat assistant',
                'name'                  => $customer['name'],
                'email_address'         => null,
                'phone_number'          => $customer['phone'],
                'payment_type'          => 'cod',
                'payment_status'        => 'unpaid',
                'delivery_status'       => 'pending',
                'date'                  => now(),
                'viewed'                => 0,
                'delivery_viewed'       => 0,
                'payment_status_viewed' => 0,
                'commission_calculated' => 0,
                'order_type'            => 'normal',
            ]);

            foreach ($items as $idx => $it) {
                $product = Product::find($it['product_id']);
                if (!$product) continue;

                $qty      = (int) $it['qty'];
                $price    = (float) $it['price'];
                $itemShip = (float) ($breakdown[$idx]['cost'] ?? 0);

                OrderDetail::create([
                    'order_id'              => $order->id,
                    'seller_id'             => $product->vendor_id ?? null,
                    'product_id'            => $product->id,
                    'sku'                   => $it['variant_sku'] ?? ($product->inventory->sku ?? null),
                    'variation'             => $it['variant_label'] ?? null,
                    'price'                 => $price,
                    'tax'                   => 0,
                    'shipping_cost'         => $itemShip,
                    'quantity'              => $qty,
                    'payment_status'        => 'unpaid',
                    'delivery_status'       => 'pending',
                    'shipping_type'         => 'flat_rate',
                    'product_referral_code' => null,
                ]);

                if (!empty($it['variant_id'])) {
                    $variant = ProductVarient::find($it['variant_id']);
                    if ($variant && $variant->quantity >= $qty) {
                        $variant->decrement('quantity', $qty);
                    }
                } else {
                    $inv = $product->inventory;
                    if ($inv && $inv->stock >= $qty) {
                        $inv->decrement('stock', $qty);
                    }
                }
            }

            return $order;
        });
    }

    // ═══════════════════════════════════════════════════════════════
    // ANALYTICS ENGINE — unchanged
    // ═══════════════════════════════════════════════════════════════

    private function resolveAnswer(string $message): array
    {
        $msg = strtolower(trim($message));

        $now = Carbon::now();

        $rangeStart = null;
        $rangeEnd   = null;
        $rangeLabel = 'All time';

        if (preg_match('/last\s+(\d+)\s+day/', $msg, $m)) {
            $rangeStart = $now->copy()->subDays((int) $m[1])->startOfDay();
            $rangeLabel = "Last {$m[1]} days";
        } elseif (preg_match('/(\d+)\s+day/', $msg, $m)) {
            $rangeStart = $now->copy()->subDays((int) $m[1])->startOfDay();
            $rangeLabel = "Last {$m[1]} days";
        } elseif (str_contains($msg, 'today')) {
            $rangeStart = $now->copy()->startOfDay();
            $rangeEnd   = $now->copy()->endOfDay();
            $rangeLabel = 'Today';
        } elseif (str_contains($msg, 'yesterday')) {
            $rangeStart = $now->copy()->subDay()->startOfDay();
            $rangeEnd   = $now->copy()->subDay()->endOfDay();
            $rangeLabel = 'Yesterday';
        } elseif (str_contains($msg, 'this week')) {
            $rangeStart = $now->copy()->startOfWeek();
            $rangeLabel = 'This week';
        } elseif (str_contains($msg, 'last week')) {
            $rangeStart = $now->copy()->subWeek()->startOfWeek();
            $rangeEnd   = $now->copy()->subWeek()->endOfWeek();
            $rangeLabel = 'Last week';
        } elseif (str_contains($msg, 'this month')) {
            $rangeStart = $now->copy()->startOfMonth();
            $rangeLabel = 'This month';
        } elseif (str_contains($msg, 'last month')) {
            $rangeStart = $now->copy()->subMonth()->startOfMonth();
            $rangeEnd   = $now->copy()->subMonth()->endOfMonth();
            $rangeLabel = 'Last month';
        } elseif (str_contains($msg, 'this year')) {
            $rangeStart = $now->copy()->startOfYear();
            $rangeLabel = 'This year';
        } elseif (str_contains($msg, 'last year')) {
            $rangeStart = $now->copy()->subYear()->startOfYear();
            $rangeEnd   = $now->copy()->subYear()->endOfYear();
            $rangeLabel = 'Last year';
        } elseif (str_contains($msg, 'month')) {
            $rangeStart = $now->copy()->startOfMonth();
            $rangeLabel = 'This month';
        }

        $build = function () use ($rangeStart, $rangeEnd) {
            $q = Order::query();
            if ($rangeStart && $rangeEnd) {
                $q->whereBetween('created_at', [$rangeStart, $rangeEnd]);
            } elseif ($rangeStart) {
                $q->where('created_at', '>=', $rangeStart);
            }
            return $q;
        };

        $isPending   = str_contains($msg, 'pending');
        $isDelivered = str_contains($msg, 'deliver');
        $isShipped   = str_contains($msg, 'ship');
        $isCancelled = str_contains($msg, 'cancel');
        $isPaid      = str_contains($msg, 'paid') && !str_contains($msg, 'unpaid');
        $isUnpaid    = str_contains($msg, 'unpaid');
        $isRefunded  = str_contains($msg, 'refund');

        $isRevenue   = str_contains($msg, 'revenue')
            || str_contains($msg, 'sale')
            || str_contains($msg, 'amount')
            || str_contains($msg, 'total')
            || str_contains($msg, 'earn');
        $isCount     = str_contains($msg, 'how many')
            || str_contains($msg, 'count')
            || str_contains($msg, 'number')
            || str_contains($msg, 'total order');
        $isAvg       = str_contains($msg, 'average') || str_contains($msg, 'avg');
        $isTop       = str_contains($msg, 'top') || str_contains($msg, 'best');
        $isList      = str_contains($msg, 'list') || str_contains($msg, 'show');
        $isSummary   = str_contains($msg, 'summary') || str_contains($msg, 'report')
            || str_contains($msg, 'overview') || str_contains($msg, 'breakdown');

        $isVendor    = str_contains($msg, 'vendor') || str_contains($msg, 'supplier');
        $isProfit    = str_contains($msg, 'profit') || str_contains($msg, 'margin');

        $isDashboard  = str_contains($msg, 'dashboard') || str_contains($msg, 'full report') || str_contains($msg, 'everything');
        $isCustomer   = str_contains($msg, 'customer') || str_contains($msg, 'user') || str_contains($msg, 'buyer');
        $isProduct    = str_contains($msg, 'product') || str_contains($msg, 'item');
        $isStock      = str_contains($msg, 'stock') || str_contains($msg, 'inventory');
        $isCategory   = str_contains($msg, 'category') || str_contains($msg, 'categories');
        $isBrand      = str_contains($msg, 'brand');
        $isCoupon     = str_contains($msg, 'coupon') || str_contains($msg, 'discount') || str_contains($msg, 'promo');
        $isSearch     = str_contains($msg, 'search') || str_contains($msg, 'keyword');
        $isPayment    = str_contains($msg, 'payment method') || str_contains($msg, 'payment type') || str_contains($msg, 'cod');
        $isCourier    = str_contains($msg, 'courier') || str_contains($msg, 'pathao') || str_contains($msg, 'steadfast') || str_contains($msg, 'redx');
        $isGrowth     = str_contains($msg, 'growth') || str_contains($msg, 'trend') || str_contains($msg, 'compare');
        $isPeak       = str_contains($msg, 'peak') || str_contains($msg, 'busy hour') || str_contains($msg, 'rush hour');
        $isReview     = str_contains($msg, 'review') || str_contains($msg, 'rating') || str_contains($msg, 'satisfaction');
        $isWishlist   = str_contains($msg, 'wishlist') || str_contains($msg, 'favorite');
        $isConversion = str_contains($msg, 'conversion') || str_contains($msg, 'cvr') || str_contains($msg, 'ltv');

        if ($isTop && $isVendor && $isProfit) {
            $rows = \DB::table('vendor_order_products as vop')
                ->join('vendors', 'vendors.id', '=', 'vop.vendor_id')
                ->leftJoin('products', 'products.id', '=', 'vop.product_id')
                ->leftJoin('product_prices as pp', 'pp.product_id', '=', 'products.id')
                ->where('vop.status', 1)
                ->when($rangeStart, fn($q) => $q->where('vop.created_at', '>=', $rangeStart))
                ->when($rangeEnd,   fn($q) => $q->where('vop.created_at', '<=', $rangeEnd))
                ->select(
                    'vop.vendor_id',
                    'vendors.name as vendor_name',
                    \DB::raw('SUM(vop.total_amount) as sales'),
                    \DB::raw('SUM(vop.quantity * COALESCE(pp.wholesale_price, 0)) as cost')
                )
                ->groupBy('vop.vendor_id', 'vendors.name')
                ->get()
                ->map(function ($r) {
                    $r->sales  = (float) $r->sales;
                    $r->cost   = (float) $r->cost;
                    $r->profit = $r->sales - $r->cost;
                    return $r;
                })
                ->sortByDesc('profit')
                ->take(10)
                ->values();

            if ($rows->isEmpty()) {
                return $this->reply('top_vendor_profit', "No vendor profit data for {$rangeLabel}.", []);
            }

            $totalProfit = $rows->sum('profit');

            $html = "🏭 <strong>Top Vendor Profit — {$rangeLabel}</strong><br>";
            $html .= "<small>Profit = Sales − Wholesale Cost</small><br><br>";
            foreach ($rows as $i => $r) {
                $html .= ($i + 1) . ". <strong>" . e($r->vendor_name) . "</strong><br>"
                    . "&nbsp;&nbsp;&nbsp;• Sales: ৳" . number_format($r->sales, 2)
                    . " · Cost: ৳" . number_format($r->cost, 2)
                    . "<br>&nbsp;&nbsp;&nbsp;• <strong>Profit: ৳"
                    . number_format($r->profit, 2) . "</strong><br>";
            }
            $html .= "<br>💰 Top-10 total profit: <strong>৳" . number_format($totalProfit, 2) . "</strong>";

            return $this->reply('top_vendor_profit', $html, ['range' => $rangeLabel]);
        }

        if ($isTop && $isVendor && ($isRevenue || str_contains($msg, 'sale'))) {
            $rows = \DB::table('vendor_order_products as vop')
                ->join('vendors', 'vendors.id', '=', 'vop.vendor_id')
                ->where('vop.status', 1)
                ->when($rangeStart, fn($q) => $q->where('vop.created_at', '>=', $rangeStart))
                ->when($rangeEnd,   fn($q) => $q->where('vop.created_at', '<=', $rangeEnd))
                ->select(
                    'vop.vendor_id',
                    'vendors.name as vendor_name',
                    \DB::raw('COUNT(DISTINCT vop.order_id) as orders'),
                    \DB::raw('SUM(vop.quantity) as qty'),
                    \DB::raw('SUM(vop.total_amount) as sales')
                )
                ->groupBy('vop.vendor_id', 'vendors.name')
                ->orderByDesc('sales')
                ->limit(10)
                ->get();

            if ($rows->isEmpty()) {
                return $this->reply('top_vendor_sale', "No vendor sales data for {$rangeLabel}.", []);
            }

            $totalSales = $rows->sum('sales');

            $html = "🏭 <strong>Top Vendor Sales — {$rangeLabel}</strong><br><br>";
            foreach ($rows as $i => $r) {
                $html .= ($i + 1) . ". <strong>" . e($r->vendor_name) . "</strong><br>"
                    . "&nbsp;&nbsp;&nbsp;• Orders: " . (int) $r->orders
                    . " · Items: " . (int) $r->qty
                    . "<br>&nbsp;&nbsp;&nbsp;• <strong>Sales: ৳"
                    . number_format((float) $r->sales, 2) . "</strong><br>";
            }
            $html .= "<br>💰 Top-10 total sales: <strong>৳" . number_format($totalSales, 2) . "</strong>";

            return $this->reply('top_vendor_sale', $html, ['range' => $rangeLabel]);
        }

        if ($isTop && $isVendor) {
            $rows = \DB::table('vendor_order_products as vop')
                ->join('vendors', 'vendors.id', '=', 'vop.vendor_id')
                ->where('vop.status', 1)
                ->when($rangeStart, fn($q) => $q->where('vop.created_at', '>=', $rangeStart))
                ->when($rangeEnd,   fn($q) => $q->where('vop.created_at', '<=', $rangeEnd))
                ->select(
                    'vop.vendor_id',
                    'vendors.name as vendor_name',
                    \DB::raw('COUNT(DISTINCT vop.order_id) as orders'),
                    \DB::raw('SUM(vop.quantity) as qty'),
                    \DB::raw('SUM(vop.total_amount) as sales')
                )
                ->groupBy('vop.vendor_id', 'vendors.name')
                ->orderByDesc('orders')
                ->limit(10)
                ->get();

            if ($rows->isEmpty()) {
                return $this->reply('top_vendor_order', "No vendor order data for {$rangeLabel}.", []);
            }

            $html = "🏭 <strong>Top Vendors (by order count) — {$rangeLabel}</strong><br><br>";
            foreach ($rows as $i => $r) {
                $html .= ($i + 1) . ". <strong>" . e($r->vendor_name) . "</strong> — <strong>"
                    . (int) $r->orders . " orders</strong> ("
                    . (int) $r->qty . " items, ৳"
                    . number_format((float) $r->sales, 2) . ")<br>";
            }

            return $this->reply('top_vendor_order', $html, ['range' => $rangeLabel]);
        }

        if ($isVendor && ($isList || str_contains($msg, 'vendor list') || str_contains($msg, 'all vendor') || str_contains($msg, 'vendor name'))) {
            $rows = \DB::table('vendor_order_products as vop')
                ->join('vendors', 'vendors.id', '=', 'vop.vendor_id')
                ->where('vop.status', 1)
                ->when($rangeStart, fn($q) => $q->where('vop.created_at', '>=', $rangeStart))
                ->when($rangeEnd,   fn($q) => $q->where('vop.created_at', '<=', $rangeEnd))
                ->select(
                    'vop.vendor_id',
                    'vendors.name as vendor_name',
                    \DB::raw('COUNT(DISTINCT vop.order_id) as orders'),
                    \DB::raw('SUM(vop.quantity) as qty'),
                    \DB::raw('SUM(vop.total_amount) as sales')
                )
                ->groupBy('vop.vendor_id', 'vendors.name')
                ->orderByDesc('sales')
                ->get();

            if ($rows->isEmpty()) {
                return $this->reply('vendor_list', "No vendors with sales in {$rangeLabel}.", []);
            }

            $totalVendors = $rows->count();
            $totalSales   = $rows->sum('sales');
            $totalOrders  = $rows->sum('orders');

            $html = "🏭 <strong>Vendor List — {$rangeLabel}</strong><br>";
            $html .= "<small>{$totalVendors} vendors · {$totalOrders} orders · ৳" . number_format((float) $totalSales, 2) . "</small><br><br>";

            foreach ($rows as $i => $r) {
                $html .= ($i + 1) . ". <strong>" . e($r->vendor_name) . "</strong> "
                    . "(ID: {$r->vendor_id})<br>"
                    . "&nbsp;&nbsp;&nbsp;• Orders: " . (int) $r->orders
                    . " · Items: " . (int) $r->qty
                    . " · Sales: ৳" . number_format((float) $r->sales, 2) . "<br>";
            }

            return $this->reply('vendor_list', $html, [
                'count'  => $totalVendors,
                'orders' => $totalOrders,
                'sales'  => $totalSales,
            ]);
        }

        if ($isDashboard) {
            $totalSales   = Order::where('payment_status', 'paid')->sum(DB::raw('grand_total - shipping_cost'));
            $totalOrders  = Order::count();
            $todayOrders  = Order::whereDate('created_at', today())->count();
            $weekOrders   = Order::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count();
            $todayRev     = Order::whereDate('created_at', today())->where('payment_status', 'paid')->sum('grand_total');
            $weekRev      = Order::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->where('payment_status', 'paid')->sum('grand_total');
            $monthRev     = Order::whereMonth('created_at', now()->month)->where('payment_status', 'paid')->sum('grand_total');
            $yearRev      = Order::whereYear('created_at', date('Y'))->where('payment_status', 'paid')->sum('grand_total');

            $totalProducts   = Product::count();
            $published       = Product::where('is_published', 1)->count();
            $lowStock        = ProductInventory::whereColumn('stock', '<=', 'low_stock_qty')->where('stock', '>', 0)->count();
            $outOfStock      = ProductInventory::where('stock', 0)->count();

            $totalCustomers  = User::count();
            $newToday        = User::whereDate('created_at', today())->count();
            $newMonth        = User::whereMonth('created_at', now()->month)->count();

            $pending    = Order::where('delivery_status', 'pending')->count();
            $shipped    = Order::where('delivery_status', 'shipped')->count();
            $delivered  = Order::where('delivery_status', 'delivered')->count();
            $cancelled  = Order::where('delivery_status', 'cancelled')->count();
            $paid       = Order::where('payment_status', 'paid')->count();
            $unpaid     = Order::where('payment_status', 'unpaid')->count();
            $refunded   = Order::where('payment_status', 'refunded')->count();

            $avgOrderValue = $totalOrders > 0 ? round($totalSales / $totalOrders, 2) : 0;

            $html = "📊 <strong>Full Dashboard Report</strong><br><br>";
            $html .= "<strong>💰 Revenue</strong><br>";
            $html .= "&nbsp;&nbsp;• Total (paid): ৳" . number_format((float) $totalSales, 2) . "<br>";
            $html .= "&nbsp;&nbsp;• Today: ৳" . number_format((float) $todayRev, 2) . "<br>";
            $html .= "&nbsp;&nbsp;• This week: ৳" . number_format((float) $weekRev, 2) . "<br>";
            $html .= "&nbsp;&nbsp;• This month: ৳" . number_format((float) $monthRev, 2) . "<br>";
            $html .= "&nbsp;&nbsp;• This year: ৳" . number_format((float) $yearRev, 2) . "<br>";
            $html .= "&nbsp;&nbsp;• Avg order value: ৳" . number_format($avgOrderValue, 2) . "<br><br>";

            $html .= "<strong>📦 Orders</strong><br>";
            $html .= "&nbsp;&nbsp;• Total: {$totalOrders}<br>";
            $html .= "&nbsp;&nbsp;• Today: {$todayOrders}<br>";
            $html .= "&nbsp;&nbsp;• This week: {$weekOrders}<br>";
            $html .= "&nbsp;&nbsp;• Pending: {$pending} · Shipped: {$shipped}<br>";
            $html .= "&nbsp;&nbsp;• Delivered: {$delivered} · Cancelled: {$cancelled}<br>";
            $html .= "&nbsp;&nbsp;• Paid: {$paid} · Unpaid: {$unpaid} · Refunded: {$refunded}<br><br>";

            $html .= "<strong>🛒 Products</strong><br>";
            $html .= "&nbsp;&nbsp;• Total: {$totalProducts} (Published: {$published})<br>";
            $html .= "&nbsp;&nbsp;• Low stock: {$lowStock} · Out of stock: {$outOfStock}<br><br>";

            $html .= "<strong>👥 Customers</strong><br>";
            $html .= "&nbsp;&nbsp;• Total: {$totalCustomers}<br>";
            $html .= "&nbsp;&nbsp;• New today: {$newToday} · This month: {$newMonth}<br>";

            return $this->reply('dashboard', $html, []);
        }

        if ($isCustomer && !$isTop && ($isCount || str_contains($msg, 'stats') || str_contains($msg, 'total'))) {
            $total       = User::count();
            $today       = User::whereDate('created_at', today())->count();
            $week        = User::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count();
            $month       = User::whereMonth('created_at', now()->month)->count();
            $verified    = User::whereNotNull('email_verified_at')->count();
            $withOrders  = User::has('orders')->count();
            $returning   = User::has('orders', '>', 1)->count();
            $oneTime     = User::has('orders', '=', 1)->count();

            $html = "👥 <strong>Customer Stats</strong><br>";
            $html .= "• Total: <strong>{$total}</strong><br>";
            $html .= "• New today: <strong>{$today}</strong><br>";
            $html .= "• New this week: <strong>{$week}</strong><br>";
            $html .= "• New this month: <strong>{$month}</strong><br>";
            $html .= "• Verified: <strong>{$verified}</strong><br>";
            $html .= "• With orders: <strong>{$withOrders}</strong><br>";
            $html .= "• Returning (>1 order): <strong>{$returning}</strong><br>";
            $html .= "• One-time: <strong>{$oneTime}</strong><br>";

            return $this->reply('customer_stats', $html, []);
        }

        if ($isProduct && $isCount && !$isTop) {
            $total     = Product::count();
            $published = Product::where('is_published', 1)->count();
            $draft     = Product::where('is_published', 0)->count();
            $lowStock  = ProductInventory::whereColumn('stock', '<=', 'low_stock_qty')->where('stock', '>', 0)->count();
            $outStock  = ProductInventory::where('stock', 0)->count();

            $html = "📦 <strong>Product Stats</strong><br>";
            $html .= "• Total: <strong>{$total}</strong><br>";
            $html .= "• Published: <strong>{$published}</strong><br>";
            $html .= "• Draft: <strong>{$draft}</strong><br>";
            $html .= "• Low stock: <strong>{$lowStock}</strong><br>";
            $html .= "• Out of stock: <strong>{$outStock}</strong><br>";

            return $this->reply('product_stats', $html, []);
        }

        if ($isStock) {
            $stockValue = DB::table('products')
                ->join('product_inventories', 'products.id', '=', 'product_inventories.product_id')
                ->join('product_prices', 'products.id', '=', 'product_prices.product_id')
                ->sum(DB::raw('product_inventories.stock * product_prices.regular_price'));

            $lowStock = ProductInventory::whereColumn('stock', '<=', 'low_stock_qty')->where('stock', '>', 0)->count();
            $outStock = ProductInventory::where('stock', 0)->count();
            $total    = ProductInventory::sum('stock');

            $html = "📦 <strong>Inventory / Stock</strong><br>";
            $html .= "• Total stock units: <strong>" . number_format((int) $total) . "</strong><br>";
            $html .= "• Stock value: <strong>৳" . number_format((float) $stockValue, 2) . "</strong><br>";
            $html .= "• Low stock items: <strong>{$lowStock}</strong><br>";
            $html .= "• Out of stock items: <strong>{$outStock}</strong><br>";

            return $this->reply('stock_stats', $html, []);
        }

        if ($isTop && $isCategory) {
            $rows = DB::table('products')
                ->join('categories', 'products.category_id', '=', 'categories.id')
                ->join('order_details', 'products.id', '=', 'order_details.product_id')
                ->when($rangeStart, fn($q) => $q->where('order_details.created_at', '>=', $rangeStart))
                ->select(
                    'categories.category_name',
                    DB::raw('SUM(order_details.quantity) as qty'),
                    DB::raw('SUM(order_details.price * order_details.quantity) as revenue')
                )
                ->groupBy('categories.id', 'categories.category_name')
                ->orderByDesc('qty')
                ->limit(10)
                ->get();

            if ($rows->isEmpty()) {
                return $this->reply('top_categories', "No category data for {$rangeLabel}.", []);
            }

            $html = "📂 <strong>Top Categories — {$rangeLabel}</strong><br>";
            foreach ($rows as $i => $r) {
                $html .= ($i + 1) . ". " . e($r->category_name)
                    . " — <strong>" . (int) $r->qty . " sold</strong> (৳"
                    . number_format((float) $r->revenue, 2) . ")<br>";
            }
            return $this->reply('top_categories', $html, ['range' => $rangeLabel]);
        }

        if ($isTop && $isBrand) {
            $rows = DB::table('products')
                ->join('brands', 'products.brand_id', '=', 'brands.id')
                ->join('order_details', 'products.id', '=', 'order_details.product_id')
                ->when($rangeStart, fn($q) => $q->where('order_details.created_at', '>=', $rangeStart))
                ->select(
                    'brands.name',
                    DB::raw('SUM(order_details.quantity) as qty'),
                    DB::raw('SUM(order_details.price * order_details.quantity) as revenue')
                )
                ->groupBy('brands.id', 'brands.name')
                ->orderByDesc('qty')
                ->limit(10)
                ->get();

            if ($rows->isEmpty()) {
                return $this->reply('top_brands', "No brand data for {$rangeLabel}.", []);
            }

            $html = "🏷 <strong>Top Brands — {$rangeLabel}</strong><br>";
            foreach ($rows as $i => $r) {
                $html .= ($i + 1) . ". " . e($r->name)
                    . " — <strong>" . (int) $r->qty . " sold</strong> (৳"
                    . number_format((float) $r->revenue, 2) . ")<br>";
            }
            return $this->reply('top_brands', $html, ['range' => $rangeLabel]);
        }

        if ($isTop && (str_contains($msg, 'product') || str_contains($msg, 'item') || str_contains($msg, 'selling'))) {
            $rows = DB::table('order_details')
                ->join('orders', 'orders.id', '=', 'order_details.order_id')
                ->when($rangeStart, fn($q) => $q->where('orders.created_at', '>=', $rangeStart))
                ->when($rangeEnd,   fn($q) => $q->where('orders.created_at', '<=', $rangeEnd))
                ->join('products', 'products.id', '=', 'order_details.product_id')
                ->select('products.name', DB::raw('SUM(order_details.quantity) as qty'), DB::raw('SUM(order_details.price * order_details.quantity) as total'))
                ->groupBy('products.id', 'products.name')
                ->orderByDesc('qty')
                ->limit(10)
                ->get();

            if ($rows->isEmpty()) {
                return $this->reply('top_products', "No sales data for {$rangeLabel}.", []);
            }

            $html = "🏆 <strong>Top Selling Products — {$rangeLabel}</strong><br>";
            foreach ($rows as $i => $r) {
                $html .= ($i + 1) . ". " . e($r->name)
                    . " — <strong>" . (int) $r->qty . " sold</strong> (৳"
                    . number_format((float) $r->total, 2) . ")<br>";
            }
            return $this->reply('top_products', $html, ['range' => $rangeLabel]);
        }

        if ($isTop && (str_contains($msg, 'customer') || str_contains($msg, 'buyer') || str_contains($msg, 'client'))) {
            $rows = Order::query()
                ->when($rangeStart, fn($q) => $q->where('created_at', '>=', $rangeStart))
                ->when($rangeEnd,   fn($q) => $q->where('created_at', '<=', $rangeEnd))
                ->whereNotNull('user_id')
                ->select('user_id', DB::raw('COUNT(*) as orders'), DB::raw('SUM(grand_total) as total'))
                ->groupBy('user_id')
                ->orderByDesc('total')
                ->limit(10)
                ->with('user:id,name,email,phone')
                ->get();

            if ($rows->isEmpty()) {
                return $this->reply('top_customers', "No customer data for {$rangeLabel}.", []);
            }

            $html = "👑 <strong>Top Customers — {$rangeLabel}</strong><br>";
            foreach ($rows as $i => $r) {
                $name = $r->user->name ?? 'Guest';
                $html .= ($i + 1) . ". " . e($name)
                    . " — <strong>" . (int) $r->orders . " orders</strong> (৳"
                    . number_format((float) $r->total, 2) . ")<br>";
            }
            return $this->reply('top_customers', $html, ['range' => $rangeLabel]);
        }

        if ($isPayment) {
            $rows = Order::when($rangeStart, fn($q) => $q->where('created_at', '>=', $rangeStart))
                ->when($rangeEnd,   fn($q) => $q->where('created_at', '<=', $rangeEnd))
                ->select('payment_type', DB::raw('COUNT(*) as orders'), DB::raw('SUM(grand_total) as total'))
                ->groupBy('payment_type')
                ->orderByDesc('total')
                ->get();

            if ($rows->isEmpty()) {
                return $this->reply('payment_breakdown', "No payment data for {$rangeLabel}.", []);
            }

            $total = $rows->sum('total');

            $html = "💳 <strong>Payment Methods — {$rangeLabel}</strong><br>";
            foreach ($rows as $r) {
                $pct = $total > 0 ? round(($r->total / $total) * 100, 1) : 0;
                $html .= "• <strong>" . e($r->payment_type ?? 'N/A') . "</strong> — "
                    . (int) $r->orders . " orders · ৳"
                    . number_format((float) $r->total, 2) . " ({$pct}%)<br>";
            }
            return $this->reply('payment_breakdown', $html, ['range' => $rangeLabel]);
        }

        if ($isCourier) {
            $rows = Order::when($rangeStart, fn($q) => $q->where('created_at', '>=', $rangeStart))
                ->whereNotNull('courier_name')
                ->select(
                    'courier_name',
                    DB::raw('COUNT(*) as total'),
                    DB::raw("SUM(CASE WHEN delivery_status = 'delivered' THEN 1 ELSE 0 END) as delivered"),
                    DB::raw("SUM(CASE WHEN delivery_status = 'cancelled' THEN 1 ELSE 0 END) as cancelled")
                )
                ->groupBy('courier_name')
                ->orderByDesc('total')
                ->get();

            if ($rows->isEmpty()) {
                return $this->reply('courier_stats', "No courier data found.", []);
            }

            $html = "🚚 <strong>Courier Performance — {$rangeLabel}</strong><br>";
            foreach ($rows as $r) {
                $successRate = $r->total > 0 ? round(($r->delivered / $r->total) * 100, 1) : 0;
                $html .= "• <strong>" . e($r->courier_name) . "</strong><br>"
                    . "&nbsp;&nbsp;&nbsp;• Total: " . (int) $r->total
                    . " · Delivered: " . (int) $r->delivered
                    . " · Cancelled: " . (int) $r->cancelled . "<br>"
                    . "&nbsp;&nbsp;&nbsp;• Success rate: <strong>{$successRate}%</strong><br>";
            }
            return $this->reply('courier_stats', $html, ['range' => $rangeLabel]);
        }

        if ($isCoupon) {
            $totalDiscount = Order::when($rangeStart, fn($q) => $q->where('created_at', '>=', $rangeStart))
                ->sum('coupon_discount');
            $ordersWithCoupon = Order::when($rangeStart, fn($q) => $q->where('created_at', '>=', $rangeStart))
                ->where('coupon_discount', '>', 0)->count();
            $totalOrders = Order::when($rangeStart, fn($q) => $q->where('created_at', '>=', $rangeStart))->count();
            $usageRate = $totalOrders > 0 ? round(($ordersWithCoupon / $totalOrders) * 100, 1) : 0;

            $html = "🎟 <strong>Coupon Usage — {$rangeLabel}</strong><br>";
            $html .= "• Total discount given: <strong>৳" . number_format((float) $totalDiscount, 2) . "</strong><br>";
            $html .= "• Orders with coupon: <strong>{$ordersWithCoupon}</strong><br>";
            $html .= "• Usage rate: <strong>{$usageRate}%</strong><br>";

            return $this->reply('coupon_usage', $html, ['range' => $rangeLabel]);
        }

        if ($isSearch) {
            $totalSearches  = \App\Models\Search::sum('count');
            $uniqueKeywords = \App\Models\Search::count();
            $topKeywords    = \App\Models\Search::orderBy('count', 'desc')->limit(10)->get();

            $html = "🔍 <strong>Search Analytics</strong><br>";
            $html .= "• Total searches: <strong>" . number_format((int) $totalSearches) . "</strong><br>";
            $html .= "• Unique keywords: <strong>{$uniqueKeywords}</strong><br><br>";
            $html .= "<strong>Top searched keywords:</strong><br>";
            foreach ($topKeywords as $i => $kw) {
                $html .= ($i + 1) . ". " . e($kw->keyword ?? $kw->term ?? 'N/A')
                    . " — " . (int) $kw->count . " searches<br>";
            }
            return $this->reply('search_analytics', $html, []);
        }

        if ($isReview) {
            $avgRating    = Review::avg('rating') ?? 0;
            $totalReviews = Review::count();
            $positive     = Review::where('rating', '>=', 4)->count();
            $positiveRate = $totalReviews > 0 ? round(($positive / $totalReviews) * 100, 1) : 0;

            $html = "⭐ <strong>Reviews & Satisfaction</strong><br>";
            $html .= "• Average rating: <strong>" . round($avgRating, 2) . " / 5</strong><br>";
            $html .= "• Total reviews: <strong>{$totalReviews}</strong><br>";
            $html .= "• Positive reviews (≥4★): <strong>{$positive}</strong><br>";
            $html .= "• Positive rate: <strong>{$positiveRate}%</strong><br>";

            return $this->reply('review_stats', $html, []);
        }

        if ($isWishlist) {
            $rows = Product::withCount('wishlists')
                ->orderBy('wishlists_count', 'desc')
                ->limit(10)
                ->get();

            if ($rows->isEmpty()) {
                return $this->reply('wishlist_stats', "No wishlist data.", []);
            }

            $html = "❤️ <strong>Most Wishlisted Products</strong><br>";
            foreach ($rows as $i => $p) {
                $html .= ($i + 1) . ". " . e($p->name)
                    . " — <strong>" . (int) $p->wishlists_count . " wishlists</strong><br>";
            }
            return $this->reply('wishlist_stats', $html, []);
        }

        if ($isPeak) {
            $hourly = Order::select(DB::raw('HOUR(created_at) as hour'), DB::raw('COUNT(*) as count'))
                ->when($rangeStart, fn($q) => $q->where('created_at', '>=', $rangeStart))
                ->groupBy('hour')
                ->orderByDesc('count')
                ->limit(5)
                ->get();

            if ($hourly->isEmpty()) {
                return $this->reply('peak_hours', "No order data.", []);
            }

            $html = "⏰ <strong>Peak Hours — {$rangeLabel}</strong><br>";
            foreach ($hourly as $i => $h) {
                $label = str_pad($h->hour, 2, '0', STR_PAD_LEFT) . ':00 – ' . str_pad($h->hour + 1, 2, '0', STR_PAD_LEFT) . ':00';
                $html .= ($i + 1) . ". <strong>{$label}</strong> — "
                    . (int) $h->count . " orders<br>";
            }
            return $this->reply('peak_hours', $html, ['range' => $rangeLabel]);
        }

        if ($isGrowth) {
            $thisMonth = Order::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->where('payment_status', 'paid')
                ->sum('grand_total');
            $lastMonth = Order::whereMonth('created_at', now()->subMonth()->month)
                ->whereYear('created_at', now()->subMonth()->year)
                ->where('payment_status', 'paid')
                ->sum('grand_total');

            $thisMonthOrders = Order::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)->count();
            $lastMonthOrders = Order::whereMonth('created_at', now()->subMonth()->month)
                ->whereYear('created_at', now()->subMonth()->year)->count();

            $revGrowth    = $lastMonth > 0 ? round((($thisMonth - $lastMonth) / $lastMonth) * 100, 1) : 0;
            $orderGrowth  = $lastMonthOrders > 0 ? round((($thisMonthOrders - $lastMonthOrders) / $lastMonthOrders) * 100, 1) : 0;
            $arrow        = $revGrowth >= 0 ? '📈' : '📉';

            $html = "{$arrow} <strong>Growth — This Month vs Last Month</strong><br>";
            $html .= "• Revenue: ৳" . number_format((float) $lastMonth, 2) . " → ৳"
                . number_format((float) $thisMonth, 2)
                . " (<strong>" . ($revGrowth >= 0 ? '+' : '') . "{$revGrowth}%</strong>)<br>";
            $html .= "• Orders: {$lastMonthOrders} → {$thisMonthOrders}"
                . " (<strong>" . ($orderGrowth >= 0 ? '+' : '') . "{$orderGrowth}%</strong>)<br>";

            return $this->reply('growth', $html, []);
        }

        if ($isConversion || $isAvg) {
            $totalOrders  = Order::count();
            $totalSales   = Order::where('payment_status', 'paid')->sum(DB::raw('grand_total - shipping_cost'));
            $totalCustomers = User::count();

            $avgOrderValue = $totalOrders > 0 ? round($totalSales / $totalOrders, 2) : 0;
            $customerLTV   = $totalCustomers > 0 ? round($totalSales / $totalCustomers, 2) : 0;
            $customersWithOrders = User::has('orders')->count();
            $avgOrderFreq = $customersWithOrders > 0 ? round($totalOrders / $customersWithOrders, 2) : 0;

            $html = "📊 <strong>Key Business Metrics</strong><br>";
            $html .= "• Average Order Value (AOV): <strong>৳" . number_format($avgOrderValue, 2) . "</strong><br>";
            $html .= "• Customer Lifetime Value (LTV): <strong>৳" . number_format($customerLTV, 2) . "</strong><br>";
            $html .= "• Avg order frequency: <strong>{$avgOrderFreq} orders/customer</strong><br>";
            $html .= "• Total customers: <strong>{$totalCustomers}</strong><br>";
            $html .= "• Total orders: <strong>{$totalOrders}</strong><br>";

            return $this->reply('business_metrics', $html, []);
        }

        if ($isRevenue && !$isCount) {
            $q = $build();
            if ($isPending)   $q->where('delivery_status', 'pending');
            if ($isDelivered) $q->where('delivery_status', 'delivered');
            if ($isShipped)   $q->where('delivery_status', 'shipped');
            if ($isCancelled) $q->where('delivery_status', 'cancelled');
            if ($isPaid)      $q->where('payment_status', 'paid');
            if ($isUnpaid)    $q->where('payment_status', 'unpaid');
            if ($isRefunded)  $q->where('payment_status', 'refunded');

            $sum   = (float) $q->sum('grand_total');
            $count = (clone $q)->count();
            $avg   = $count > 0 ? $sum / $count : 0;

            $label = $rangeLabel;
            if ($isPending)   $label .= ' pending';
            if ($isDelivered) $label .= ' delivered';
            if ($isShipped)   $label .= ' shipped';
            if ($isCancelled) $label .= ' cancelled';
            if ($isPaid)      $label .= ' paid';
            if ($isUnpaid)    $label .= ' unpaid';

            if ($isAvg) {
                return $this->reply(
                    'avg_revenue',
                    "📊 <strong>{$label}</strong> average order value: <strong>৳" . number_format($avg, 2) . "</strong>",
                    ['avg' => $avg, 'count' => $count]
                );
            }

            return $this->reply(
                'revenue',
                "💰 <strong>{$label}</strong> revenue: <strong>৳" . number_format($sum, 2) . "</strong><br>
             • Orders: <strong>{$count}</strong><br>
             • Average order: <strong>৳" . number_format($avg, 2) . "</strong>",
                ['total' => $sum, 'count' => $count, 'avg' => $avg]
            );
        }

        if ($isCount) {
            $q = $build();
            if ($isPending)   $q->where('delivery_status', 'pending');
            if ($isDelivered) $q->where('delivery_status', 'delivered');
            if ($isShipped)   $q->where('delivery_status', 'shipped');
            if ($isCancelled) $q->where('delivery_status', 'cancelled');
            if ($isPaid)      $q->where('payment_status', 'paid');
            if ($isUnpaid)    $q->where('payment_status', 'unpaid');
            if ($isRefunded)  $q->where('payment_status', 'refunded');

            $count = $q->count();

            $label = $rangeLabel;
            if ($isPending)   $label .= ' pending';
            if ($isDelivered) $label .= ' delivered';
            if ($isShipped)   $label .= ' shipped';
            if ($isCancelled) $label .= ' cancelled';
            if ($isPaid)      $label .= ' paid';
            if ($isUnpaid)    $label .= ' unpaid';

            return $this->reply(
                'count',
                "📦 <strong>{$label}</strong> orders: <strong>{$count}</strong>",
                ['count' => $count, 'label' => $label]
            );
        }

        if ($isPending || $isDelivered || $isShipped || $isCancelled || $isPaid || $isUnpaid) {
            $q = $build();
            if ($isPending)   $q->where('delivery_status', 'pending');
            if ($isDelivered) $q->where('delivery_status', 'delivered');
            if ($isShipped)   $q->where('delivery_status', 'shipped');
            if ($isCancelled) $q->where('delivery_status', 'cancelled');
            if ($isPaid)      $q->where('payment_status', 'paid');
            if ($isUnpaid)    $q->where('payment_status', 'unpaid');

            $count = $q->count();
            $sum   = (float) (clone $q)->sum('grand_total');

            $label = $rangeLabel;
            if ($isPending)   $label .= ' pending';
            if ($isDelivered) $label .= ' delivered';
            if ($isShipped)   $label .= ' shipped';
            if ($isCancelled) $label .= ' cancelled';
            if ($isPaid)      $label .= ' paid';
            if ($isUnpaid)    $label .= ' unpaid';

            return $this->reply(
                'status_summary',
                "📊 <strong>{$label}</strong> orders: <strong>{$count}</strong><br>
             • Total value: <strong>৳" . number_format($sum, 2) . "</strong>",
                ['count' => $count, 'total' => $sum, 'label' => $label]
            );
        }

        if ($isSummary || empty($msg) || $rangeStart) {
            $base = $build();
            $total      = (clone $base)->count();
            $pending    = (clone $base)->where('delivery_status', 'pending')->count();
            $delivered  = (clone $base)->where('delivery_status', 'delivered')->count();
            $shipped    = (clone $base)->where('delivery_status', 'shipped')->count();
            $cancelled  = (clone $base)->where('delivery_status', 'cancelled')->count();
            $paid       = (clone $base)->where('payment_status', 'paid')->count();
            $unpaid     = (clone $base)->where('payment_status', 'unpaid')->count();
            $revenue    = (float) (clone $base)->where('payment_status', 'paid')->sum('grand_total');
            $allValue   = (float) (clone $base)->sum('grand_total');
            $avg        = $total > 0 ? $allValue / $total : 0;

            return $this->reply(
                'summary',
                "📅 <strong>{$rangeLabel} — Order Summary</strong><br>
             • Total orders: <strong>{$total}</strong><br>
             • Pending: <strong>{$pending}</strong><br>
             • Shipped: <strong>{$shipped}</strong><br>
             • Delivered: <strong>{$delivered}</strong><br>
             • Cancelled: <strong>{$cancelled}</strong><br>
             • Paid: <strong>{$paid}</strong> · Unpaid: <strong>{$unpaid}</strong><br>
             • Paid revenue: <strong>৳" . number_format($revenue, 2) . "</strong><br>
             • All order value: <strong>৳" . number_format($allValue, 2) . "</strong><br>
             • Average order: <strong>৳" . number_format($avg, 2) . "</strong>",
                compact('total', 'pending', 'shipped', 'delivered', 'cancelled', 'paid', 'unpaid', 'revenue', 'allValue', 'avg')
            );
        }

        return $this->reply(
            'help',
            "🤔 I can answer 100+ questions. Try these:<br>
         <strong>🛒 Place Order:</strong> Just paste a product link (e.g. /product/iphone-case)<br>
         <strong>Time ranges:</strong> today, yesterday, this week, last week, this month, last month, last 7 days<br>
         <strong>Status:</strong> pending orders, delivered orders, shipped orders, cancelled orders<br>
         <strong>Money:</strong> today revenue, this month sales, average order value<br>
         <strong>Top:</strong> top products, top customers, top vendors, top categories, top brands<br>
         <strong>Dashboard:</strong> dashboard, customer stats, product stats, stock stats<br>
         <strong>Insights:</strong> reviews, peak hours, growth, business metrics<br>
         <strong>Reports:</strong> today summary, this month report",
            []
        );
    }

    // ═══════════════════════════════════════════════════════════════
    // HELPERS
    // ═══════════════════════════════════════════════════════════════

    private function reply(
        string $intent,
        string $answer,
        array $meta = [],
        array $suggestions = [],
        array $inputFields = []
    ): array {
        return [
            'intent'       => $intent,
            'answer'       => $answer,
            'meta'         => $meta ?: null,
            'suggestions'  => $suggestions ?: null,
            'input_fields' => $inputFields ?: null,
        ];
    }

    public function clear(Request $request)
    {
        try {
            $adminId = auth()->id();
            $deleted = ChatMessage::where('admin_id', $adminId)->delete();

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'deleted' => $deleted,
                    'message' => $deleted . ' chat message(s) cleared.',
                ]);
            }

            flash(translate('Chat history cleared successfully!'))->success();
            return redirect()->route('admin.order-chat.index');
        } catch (\Throwable $e) {
            \Log::error('OrderChat clear failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }
}
