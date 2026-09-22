<?php

namespace App\Services;

use App\Models\ShippingCost;
use Illuminate\Support\Collection;

/**
 * Single source of truth for cart/checkout shipping, used by both
 * ApiCartController@index (what the customer sees) and
 * ApiOrderController@place (what the order is saved with).
 *
 * Rules (product_shippings is set in admin -> Product -> Shipping Configuration):
 *  1) shipping_type = free          -> 0, no area charge for this product.
 *  2) shipping_type = local_pickup  -> 0, no area charge for this product.
 *  3) shipping_type = flat_rate and shipping_cost > 0
 *     -> the product's own delivery charge.
 *  4) flat_rate with cost 0 (the default) -> uses the global shipping area
 *     (shipping_costs.amount), charged ONCE per order and split across those
 *     lines so the order_details sum equals the order shipping_cost.
 *  5) Order-level free / pickup -> everything 0.
 */
class CartShippingService
{
    /**
     * @param  Collection  $cartItems  Cart models with product.shippings loaded
     * @return array{items: array<int, array{cost: float, source: string}>, total: float, needs_area: bool}
     */
    public static function calculate(Collection $cartItems, ?ShippingCost $area, bool $isFreeOrPickup = false): array
    {
        $items = [];
        $areaItemIds = [];

        foreach ($cartItems as $item) {
            $productShippingRow = $item->product?->shippings?->first();
            $shippingType       = $productShippingRow?->shipping_type ?? 'flat_rate';
            $productShipping    = (float) ($productShippingRow?->shipping_cost ?? 0);

            if ($shippingType === 'free') {
                $items[$item->id] = ['cost' => 0.0, 'source' => 'product_free'];
            } elseif ($shippingType === 'local_pickup') {
                $items[$item->id] = ['cost' => 0.0, 'source' => 'pickup'];
            } elseif ($productShipping > 0) {
                $items[$item->id] = ['cost' => $productShipping, 'source' => 'product'];
            } else {
                $items[$item->id] = ['cost' => 0.0, 'source' => $area ? 'area' : 'none'];
                $areaItemIds[] = $item->id;
            }
        }

        // Global area charge: once per order, split across items without product shipping
        if ($area && count($areaItemIds) > 0) {
            $areaAmount = (float) $area->amount;
            $count = count($areaItemIds);
            $share = round($areaAmount / $count, 2);

            foreach ($areaItemIds as $i => $id) {
                // last item takes the rounding remainder so the sum is exact
                $items[$id]['cost'] = $i === $count - 1
                    ? round($areaAmount - $share * ($count - 1), 2)
                    : $share;
            }
        }

        if ($isFreeOrPickup) {
            foreach ($items as $id => $row) {
                $items[$id] = ['cost' => 0.0, 'source' => 'free'];
            }
        }

        return [
            'items'      => $items,
            'total'      => round(array_sum(array_column($items, 'cost')), 2),
            'needs_area' => count($areaItemIds) > 0,
        ];
    }
}
