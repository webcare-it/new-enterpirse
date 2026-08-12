<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\OrdersTemplateExport;
use App\Models\Admin\Order;
use App\Models\Admin\Product;
use App\Models\Admin\ProductInventory;
use App\Models\Admin\ProductVarient;
use App\Models\OrderDetail;

class OrderCsvController extends Controller
{
    /**
     * Show CSV import form
     */
    public function showImportForm()
    {
        return view('backend.sales.orders.import');
    }

    /**
     * Download CSV template
     */
    public function downloadTemplate()
    {
        $headers = [
            'order_code',
            'customer_name',
            'customer_email',
            'customer_phone',
            'shipping_address',
            'products',
            'payment_type',
            'delivery_status',
            'payment_status',
            'notes'
        ];

        $sampleProducts = json_encode([
            [
                'sku' => 'SKU001',
                'name' => 'Sample Product 1',
                'quantity' => 2,
                'price' => 100.00
            ],
            [
                'sku' => 'SKU002',
                'name' => 'Sample Product 2',
                'quantity' => 1,
                'price' => 150.00
            ]
        ]);

        $sample = [
            'ORD-001',
            'John Doe',
            'john@example.com',
            '01712345678',
            'House #12, Road #5, Dhaka',
            $sampleProducts,
            'cash_on_delivery',
            'pending',
            'unpaid',
            'Test order with multiple products'
        ];

        return Excel::download(new OrdersTemplateExport($headers, $sample), 'order_import_template.xlsx');
    }

    /**
     * Import orders from CSV
     */
    public function import(Request $request)
    {
        if (!$request->hasFile('csv_file')) {
            flash(translate('Please select a file to upload.'))->error();
            return back();
        }

        $file = $request->file('csv_file');
        $extension = strtolower($file->getClientOriginalExtension());
        $allowedExtensions = ['csv', 'xls', 'xlsx'];

        if (!in_array($extension, $allowedExtensions)) {
            flash(translate('Invalid file type. Please upload CSV, XLS, or XLSX file.'))->error();
            return back();
        }

        if ($file->getSize() > 5120 * 1024) {
            flash(translate('File size must not exceed 5MB.'))->error();
            return back();
        }

        $skipFirstRow = $request->has('skip_first_row');

        try {
            $result = $this->processImportFile($file, $skipFirstRow);

            $message = sprintf(
                'Import completed! %d orders processed. (Success: %d, Errors: %d)',
                $result['summary']['total'] ?? 0,
                $result['summary']['success'] ?? 0,
                $result['summary']['errors'] ?? 0
            );

            flash(translate($message))->success();

            if (!empty($result['summary']['error_list'])) {
                return redirect()->route('orders.index')->with('import_summary', $result['summary']);
            }

            return redirect()->route('orders.index');
        } catch (\Exception $e) {
            Log::error('Order import failed: ' . $e->getMessage());
            flash(translate('Import failed: ' . $e->getMessage()))->error();
            return back()->withInput();
        }
    }

    /**
     * Process import file
     */
    private function processImportFile($file, $skipFirstRow)
    {
        $rows = $this->readCsvFileContent($file, $skipFirstRow);

        Log::info('CSV Rows count: ' . count($rows));

        $successCount = 0;
        $errorCount = 0;
        $errors = [];
        $totalRows = count($rows);

        if ($totalRows == 0) {
            return [
                'summary' => [
                    'total' => 0,
                    'success' => 0,
                    'errors' => 1,
                    'error_list' => [['row' => 0, 'message' => 'No data to import. Please check that your CSV file has data and correct headers.']]
                ]
            ];
        }

        // Process each row (each row is one order with multiple products)
        foreach ($rows as $index => $rowData) {
            $currentRow = ($skipFirstRow ? 1 : 0) + $index + 1;

            if (empty(array_filter($rowData))) {
                continue;
            }

            try {
                DB::beginTransaction();

                $result = $this->importSingleOrderWithProducts($rowData);

                if ($result['success']) {
                    DB::commit();
                    $successCount++;
                    Log::info("Row {$currentRow}: Successfully imported order with {$result['product_count']} products");
                } else {
                    DB::rollBack();
                    $errorCount++;
                    $errors[] = [
                        'row' => $currentRow,
                        'message' => $result['error']
                    ];
                    Log::error("Row {$currentRow}: Import failed - " . $result['error']);
                }
            } catch (\Exception $e) {
                DB::rollBack();
                $errorCount++;
                $errors[] = [
                    'row' => $currentRow,
                    'message' => $e->getMessage()
                ];
                Log::error("Row {$currentRow}: Exception - " . $e->getMessage());
            }
        }

        return [
            'summary' => [
                'total' => $totalRows,
                'success' => $successCount,
                'errors' => $errorCount,
                'error_list' => array_slice($errors, 0, 100)
            ]
        ];
    }

    /**
     * Read CSV file content
     */
    private function readCsvFileContent($file, $skipFirstRow)
    {
        $rows = [];
        $extension = strtolower($file->getClientOriginalExtension());

        if (in_array($extension, ['xls', 'xlsx'])) {
            try {
                $data = Excel::toArray([], $file);

                if (empty($data) || empty($data[0])) {
                    return [];
                }

                $rows = $data[0];

                if (!empty($rows)) {
                    $headers = array_shift($rows);
                    $headers = array_map('trim', $headers);

                    $associativeRows = [];
                    foreach ($rows as $rowIndex => $row) {
                        $associativeRow = [];
                        foreach ($headers as $index => $header) {
                            if (!empty($header)) {
                                $associativeRow[$header] = isset($row[$index]) ? trim($row[$index]) : null;
                            }
                        }
                        if (!empty(array_filter($associativeRow))) {
                            $associativeRows[] = $associativeRow;
                        }
                    }
                    $rows = $associativeRows;
                }
            } catch (\Exception $e) {
                Log::error('Excel reading error: ' . $e->getMessage());
                return [];
            }
        } else {
            if (($handle = fopen($file->getPathname(), 'r')) !== false) {
                $headers = null;
                $rowIndex = 0;

                while (($data = fgetcsv($handle, 10000, ',')) !== false) {
                    $data = array_map('trim', $data);

                    if (count($data) == 1 && empty($data[0])) {
                        continue;
                    }

                    if ($skipFirstRow && $rowIndex === 0) {
                        $headers = $data;
                        $rowIndex++;
                        continue;
                    }

                    if ($headers === null) {
                        $associativeRow = [];
                        foreach ($data as $index => $value) {
                            $associativeRow['column_' . $index] = $value;
                        }
                        $rows[] = $associativeRow;
                    } else {
                        $associativeRow = [];
                        foreach ($headers as $index => $header) {
                            $header = trim($header);
                            if (!empty($header)) {
                                $associativeRow[$header] = isset($data[$index]) ? $data[$index] : null;
                            }
                        }
                        if (!empty(array_filter($associativeRow))) {
                            $rows[] = $associativeRow;
                        }
                    }
                    $rowIndex++;
                }
                fclose($handle);
            } else {
                Log::error('Could not open file: ' . $file->getPathname());
            }
        }

        return $rows;
    }

    /**
     * Import single order with multiple products from JSON
     */
    private function importSingleOrderWithProducts($data)
    {
        // Validate required fields
        $requiredFields = ['order_code', 'customer_name', 'shipping_address', 'products'];
        $missingFields = [];

        foreach ($requiredFields as $field) {
            if (empty($data[$field])) {
                $missingFields[] = $field;
            }
        }

        if (!empty($missingFields)) {
            return ['success' => false, 'error' => 'Missing required fields: ' . implode(', ', $missingFields)];
        }

        try {
            // Parse products JSON
            $products = $this->parseProductsJson($data['products']);

            if (empty($products)) {
                return ['success' => false, 'error' => 'No valid products found in the products field'];
            }

            // Find or create customer
            $user = $this->getOrCreateCustomer($data);

            // Calculate totals
            $grandTotal = 0;
            $productCount = 0;
            $orderDetailsData = [];

            // Process each product
            foreach ($products as $productIndex => $productData) {
                // Validate product data
                if (empty($productData['sku'])) {
                    Log::warning("Product " . ($productIndex + 1) . ": Skipping - no SKU");
                    continue;
                }

                if (empty($productData['quantity']) || !is_numeric($productData['quantity']) || $productData['quantity'] <= 0) {
                    Log::warning("Product " . ($productIndex + 1) . ": Skipping - invalid quantity");
                    continue;
                }

                if (empty($productData['price']) || !is_numeric($productData['price']) || $productData['price'] < 0) {
                    Log::warning("Product " . ($productIndex + 1) . ": Skipping - invalid price");
                    continue;
                }

                // Find product by SKU
                $product = $this->findProductBySku($productData['sku']);

                if (!$product) {
                    Log::warning("Product " . ($productIndex + 1) . ": Product with SKU '{$productData['sku']}' not found");
                    continue;
                }

                // Check stock availability
                $inventory = ProductInventory::where('product_id', $product->id)->first();
                if ($inventory && $inventory->stock < $productData['quantity']) {
                    Log::warning("Product " . ($productIndex + 1) . ": Insufficient stock for '{$product->name}'. Available: {$inventory->stock}");
                    continue;
                }

                $unitPrice = (float)$productData['price'];
                $quantity = (int)$productData['quantity'];
                $subtotal = $unitPrice * $quantity;

                $orderDetailsData[] = [
                    'product' => $product,
                    'inventory' => $inventory,
                    'price' => $unitPrice,
                    'quantity' => $quantity,
                    'subtotal' => $subtotal,
                    'product_name' => $productData['name'] ?? $product->name
                ];

                $grandTotal += $subtotal;
                $productCount++;
            }

            // Check if any valid products found
            if (empty($orderDetailsData)) {
                return ['success' => false, 'error' => 'No valid products found in this order'];
            }

            // Check if order already exists
            $orderCode = $data['order_code'];
            $existingOrder = Order::where('code', $orderCode)->first();

            if ($existingOrder) {
                // Update existing order
                $existingOrder->update([
                    'grand_total' => $existingOrder->grand_total + $grandTotal,
                    'updated_at' => now()
                ]);
                $order = $existingOrder;
            } else {
                // Create new order
                $order = Order::create([
                    'user_id' => $user->id,
                    'shipping_address' => $data['shipping_address'] ?? '',
                    'delivery_status' => $data['delivery_status'] ?? 'pending',
                    'payment_type' => $data['payment_type'] ?? 'cash_on_delivery',
                    'manual_payment' => ($data['payment_type'] ?? '') == 'manual' ? true : false,
                    'payment_status' => $data['payment_status'] ?? 'unpaid',
                    'grand_total' => $grandTotal,
                    'shipping_cost' => 0,
                    'coupon_discount' => 0,
                    'discount' => 0,
                    'code' => $orderCode,
                    'notes' => $data['notes'] ?? null,
                    'date' => now()->timestamp,
                    'viewed' => false,
                    'delivery_viewed' => false,
                    'payment_status_viewed' => false,
                    'commission_calculated' => false,
                    'shipping_type' => 'home_delivery',
                    'order_type' => 'physical'
                ]);
            }

            // Create order details for each product
            foreach ($orderDetailsData as $detail) {
                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $detail['product']->id,
                    'variation' => null,
                    'price' => $detail['price'],
                    'quantity' => $detail['quantity'],
                    'tax' => 0,
                    'shipping_cost' => 0,
                    'payment_status' => $data['payment_status'] ?? 'unpaid',
                    'delivery_status' => $data['delivery_status'] ?? 'pending',
                    'shipping_type' => 'home_delivery'
                ]);

                // Update stock
                if ($detail['inventory']) {
                    $detail['inventory']->decrement('stock', $detail['quantity']);
                }
            }

            Log::info("Order {$orderCode} imported successfully with {$productCount} products");

            return [
                'success' => true,
                'order_id' => $order->id,
                'order_code' => $orderCode,
                'product_count' => $productCount
            ];
        } catch (\Exception $e) {
            Log::error('Error importing order: ' . $e->getMessage(), [
                'data' => $data,
                'trace' => $e->getTraceAsString()
            ]);
            return ['success' => false, 'error' => 'Database error: ' . $e->getMessage()];
        }
    }

    /**
     * Parse products JSON
     */
    private function parseProductsJson($productsJson)
    {
        if (empty($productsJson)) {
            return [];
        }

        // If it's already an array (from Excel parsing)
        if (is_array($productsJson)) {
            return $productsJson;
        }

        // Clean the JSON string
        $jsonString = trim($productsJson);
        $jsonString = str_replace('""', '"', $jsonString);
        $jsonString = stripslashes($jsonString);

        try {
            $products = json_decode($jsonString, true);

            // Check if json_decode was successful
            if (json_last_error() === JSON_ERROR_NONE && is_array($products)) {
                return $products;
            }

            // Try to fix common JSON issues
            $jsonString = preg_replace('/["\']sku["\']\s*:\s*["\']([^"\']+)["\']/', '"sku":"$1"', $jsonString);
            $jsonString = preg_replace('/["\']name["\']\s*:\s*["\']([^"\']+)["\']/', '"name":"$1"', $jsonString);
            $jsonString = preg_replace('/["\']quantity["\']\s*:\s*([0-9.]+)/', '"quantity":$1', $jsonString);
            $jsonString = preg_replace('/["\']price["\']\s*:\s*([0-9.]+)/', '"price":$1', $jsonString);

            $products = json_decode($jsonString, true);

            if (json_last_error() === JSON_ERROR_NONE && is_array($products)) {
                return $products;
            }

            // If still failing, try to extract using regex
            return $this->extractProductsFromText($productsJson);
        } catch (\Exception $e) {
            Log::error('JSON parsing error: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Fallback: Extract products from text using regex
     */
    private function extractProductsFromText($text)
    {
        $products = [];

        // Pattern to match product data
        preg_match_all('/sku["\']?\s*[:=]\s*["\']?([^"\'}\],]+)["\']?/i', $text, $skuMatches);
        preg_match_all('/name["\']?\s*[:=]\s*["\']?([^"\'}\],]+)["\']?/i', $text, $nameMatches);
        preg_match_all('/quantity["\']?\s*[:=]\s*([0-9.]+)/i', $text, $qtyMatches);
        preg_match_all('/price["\']?\s*[:=]\s*([0-9.]+)/i', $text, $priceMatches);

        $count = max(
            count($skuMatches[1] ?? []),
            count($nameMatches[1] ?? []),
            count($qtyMatches[1] ?? []),
            count($priceMatches[1] ?? [])
        );

        for ($i = 0; $i < $count; $i++) {
            $product = [];

            if (isset($skuMatches[1][$i])) {
                $product['sku'] = trim($skuMatches[1][$i]);
            }
            if (isset($nameMatches[1][$i])) {
                $product['name'] = trim($nameMatches[1][$i]);
            }
            if (isset($qtyMatches[1][$i])) {
                $product['quantity'] = (float)trim($qtyMatches[1][$i]);
            }
            if (isset($priceMatches[1][$i])) {
                $product['price'] = (float)trim($priceMatches[1][$i]);
            }

            if (!empty($product)) {
                $products[] = $product;
            }
        }

        return $products;
    }

    /**
     * Get or create customer
     */
    private function getOrCreateCustomer($data)
    {
        $user = null;

        if (!empty($data['customer_email'])) {
            $user = User::where('email', $data['customer_email'])->first();
        }

        if (!$user && !empty($data['customer_phone'])) {
            $user = User::where('phone', $data['customer_phone'])->first();
        }

        if (!$user) {
            $userData = [
                'name' => $data['customer_name'] ?? 'Guest User',
                'email' => $data['customer_email'] ?? 'guest_' . time() . rand(1000, 9999) . '@example.com',
                'phone' => $data['customer_phone'] ?? null,
                'password' => bcrypt(uniqid()),
                'email_verified_at' => now()
            ];

            $user = User::create($userData);
            Log::info('Created new user for order import: ' . $user->id);
        }

        return $user;
    }

    /**
     * Find product by SKU
     */
    private function findProductBySku($sku)
    {
        $inventory = ProductInventory::where('sku', $sku)->first();

        if ($inventory) {
            return Product::find($inventory->product_id);
        }

        $variant = ProductVarient::where('sku', $sku)->first();

        if ($variant) {
            return Product::find($variant->product_id);
        }

        return null;
    }
}
