<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Brand;
use App\Models\Admin\Category;
use App\Models\Admin\Product;
use App\Models\Admin\ProductInventory;
use App\Models\Admin\ProductPrice;
use App\Models\Admin\ProductSeo;
use App\Models\Admin\ProductShipping;
use App\Models\Admin\ProductTax;
use App\Models\Admin\ProductVarient;
use App\Models\Admin\SubCategory;
use Illuminate\Support\Str;

use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProductsTemplateExport;
use App\Models\Upload;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;

class ProductCsvController extends Controller
{
    /**
     * Show CSV import form
     */
    public function showImportForm()
    {
        return view('backend.product.products.import');
    }

    /**
     * Download CSV template
     */
    public function downloadTemplate()
    {
        $firstCategory = Category::first();
        $firstBrand = Brand::first();
        $firstSubCategory = SubCategory::first(); // discount

        $headers = [
            'name',
            'slug',
            'category_id',
            'subcategory_id',
            'brand_id',
            'regular_price',
            'purchase_price',
            'discount',
            'wholesale_price',
            'discount_type',
            'stock',
            'sku',
            'barcode',
            'unit',
            'low_stock_qty',
            'thumbnail_img',
            'photos',
            'tags',
            'short_description',
            'description',
            'status',
            'is_published',
            'is_featured',
            'best_selling',
            'is_new_arrival',
            'todays_deal',
            'is_variant',
            'video_link',
            'badge_name',
            'batch_no',
            'shipping_type',
            'shipping_cost',
            'weight',
            'length',
            'width',
            'height',
            'meta_title',
            'meta_description',
            'meta_img',
            'position',
            'variant_attributes'
        ];

        $sampleVariantJson = json_encode([
            "variant_attributes" => [
                [
                    "attributes" => "Small",
                    "price" => "500",
                    "sku" => "VAR001",
                    "quantity" => "250",
                    "image" => "1"
                ],
                [
                    "attributes" => "Medium",
                    "price" => "500",
                    "sku" => "VAR002",
                    "quantity" => "250",
                    "image" => "2"
                ]
            ]
        ]);

        $sample = [
            'Sample Product',
            '',
            $firstCategory ? $firstCategory->id : '1',
            $firstSubCategory ? $firstSubCategory->id : '',
            $firstBrand ? $firstBrand->id : '',
            '100.00',
            '80.00',
            '10',
            'percent',
            '100',
            'SKU001',
            '123456789',
            'Pc',
            '5',
            '1',
            '1,2',
            'new,popular',
            'Short description',
            'Full description',
            '1',
            '1',
            '0',
            '0',
            '0',
            '0',
            '1',
            'https://youtube.com/watch?v=xyz',
            'Hot',
            '1',
            'flat_rate',
            '10.00',
            '0.5',
            '10',
            '5',
            '5',
            'Sample Meta Title',
            'Sample meta description',
            '1',
            '0',
            $sampleVariantJson
        ];

        return Excel::download(new ProductsTemplateExport($headers, $sample), 'product_import_template.xlsx');
    }

    /**
     * Import products from CSV
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

        $importMode = $request->input('import_mode', 'insert');
        $skipFirstRow = $request->has('skip_first_row');
        $sendNotification = $request->has('send_notification');

        try {
            $result = $this->processImportFile($file, $skipFirstRow, $importMode, $request->user());

            if ($sendNotification && $request->user() && $request->user()->email) {
                $this->sendImportNotification($request->user()->email, $result);
            }

            $message = sprintf(
                'Import completed! %d products processed. (Success: %d, Errors: %d, Inserted: %d, Updated: %d)',
                $result['summary']['total'] ?? 0,
                $result['summary']['success'] ?? 0,
                $result['summary']['errors'] ?? 0,
                $result['summary']['inserted'] ?? 0,
                $result['summary']['updated'] ?? 0
            );

            flash(translate($message))->success();

            if (!empty($result['summary']['error_list'])) {
                return redirect()->route('products.index')->with('import_summary', $result['summary']);
            }

            return redirect()->route('products.index');
        } catch (\Exception $e) {
            Log::error('Product import failed: ' . $e->getMessage());
            flash(translate('Import failed: ' . $e->getMessage()))->error();
            return back()->withInput();
        }
    }

    /**
     * Process import file
     */
    private function processImportFile($file, $skipFirstRow, $importMode, $user)
    {
        if (!$file || !file_exists($file->getPathname())) {
            return [
                'summary' => [
                    'total' => 0,
                    'success' => 0,
                    'errors' => 1,
                    'updated' => 0,
                    'inserted' => 0,
                    'error_list' => [['row' => 0, 'message' => 'File not found or not readable']]
                ]
            ];
        }

        $rows = $this->readCsvFileContent($file, $skipFirstRow);

        Log::info('CSV Rows count: ' . count($rows));

        $successCount = 0;
        $errorCount = 0;
        $updateCount = 0;
        $insertCount = 0;
        $errors = [];
        $totalRows = count($rows);

        if ($totalRows == 0) {
            $directRows = $this->readCsvFileContentDirect($file);

            if (count($directRows) > 0) {
                $rows = $directRows;
                $totalRows = count($rows);
                Log::info('Fallback reading found ' . $totalRows . ' rows');
            } else {
                return [
                    'summary' => [
                        'total' => 0,
                        'success' => 0,
                        'errors' => 1,
                        'updated' => 0,
                        'inserted' => 0,
                        'error_list' => [['row' => 0, 'message' => 'No data to import. Please check that your CSV file has data and correct headers.']]
                    ]
                ];
            }
        }

        foreach ($rows as $index => $rowData) {
            $currentRow = ($skipFirstRow ? 1 : 0) + $index + 1;

            if (empty(array_filter($rowData))) {
                Log::info('Skipping empty row: ' . $currentRow);
                continue;
            }

            try {
                DB::beginTransaction();

                $result = $this->importSingleProductFromRow($rowData, $importMode);

                if ($result['success']) {
                    DB::commit();
                    $successCount++;
                    if ($result['action'] == 'update') {
                        $updateCount++;
                    } else {
                        $insertCount++;
                    }
                    Log::info("Row {$currentRow}: Successfully imported product ID {$result['product_id']}");
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
                'updated' => $updateCount,
                'inserted' => $insertCount,
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

        Log::info('Reading file with extension: ' . $extension);

        if (in_array($extension, ['xls', 'xlsx'])) {
            try {
                $data = Excel::toArray([], $file);

                if (empty($data) || empty($data[0])) {
                    Log::error('Excel file is empty');
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

        Log::info('Total rows read: ' . count($rows));
        return $rows;
    }

    /**
     * Direct fallback method to read CSV file
     */
    private function readCsvFileContentDirect($file)
    {
        $rows = [];

        if (($handle = fopen($file->getPathname(), 'r')) !== false) {
            $headers = null;
            $rowIndex = 0;

            while (($data = fgetcsv($handle, 10000, ',')) !== false) {
                $data = array_map('trim', $data);

                if (empty(array_filter($data))) {
                    continue;
                }

                if ($rowIndex === 0) {
                    $headers = $data;
                    $rowIndex++;
                    continue;
                }

                $associativeRow = [];
                foreach ($headers as $index => $header) {
                    $header = trim($header);
                    if (!empty($header)) {
                        $associativeRow[$header] = $data[$index] ?? null;
                    }
                }
                if (!empty(array_filter($associativeRow))) {
                    $rows[] = $associativeRow;
                }
                $rowIndex++;
            }
            fclose($handle);
        }

        return $rows;
    }

    /**
     * Clean and encode URL - Convert spaces to %20 and handle special characters
     */
    private function cleanAndEncodeUrl($url)
    {
        if (empty($url)) {
            return null;
        }

        // Trim whitespace
        $url = trim($url);

        // Decode HTML entities
        $url = html_entity_decode($url);

        // First, decode any URL encoding to get the original string
        $url = urldecode($url);

        // Replace spaces with %20 (this is the key fix for your issue)
        $url = str_replace(' ', '%20', $url);

        // Also handle other special characters
        $url = str_replace(['%20', '+'], '%20', $url);

        // Remove multiple %20
        $url = preg_replace('/%20+/', '%20', $url);

        // Remove any malicious characters
        $url = filter_var($url, FILTER_SANITIZE_URL);

        // If URL doesn't have protocol, add https
        if (!preg_match('/^https?:\/\//', $url)) {
            $url = 'https://' . $url;
        }

        // Remove trailing slash if exists
        $url = rtrim($url, '/');

        Log::info("Cleaned URL: {$url}");

        return $url;
    }

    /**
     * Download image from URL and save to uploads table
     */
    private function downloadAndSaveImage($url, $userId = null)
    {
        if (empty($url)) {
            return null;
        }

        // Clean and encode URL - This will convert spaces to %20
        $cleanUrl = $this->cleanAndEncodeUrl($url);

        Log::info("Attempting to download image from URL: {$cleanUrl}");

        try {
            // Check if image already exists by URL hash
            $urlHash = md5($cleanUrl);
            $existingUpload = Upload::where('file_original_name', $urlHash)->first();
            if ($existingUpload) {
                Log::info("Image already exists: {$cleanUrl} -> Upload ID: {$existingUpload->id}");
                return $existingUpload->id;
            }

            // Download image using cURL with proper encoding
            $imageContent = $this->downloadImageContent($cleanUrl);

            if (!$imageContent) {
                Log::error("Failed to download image from URL: {$cleanUrl}");
                return null;
            }

            // Get image info
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_buffer($finfo, $imageContent);
            finfo_close($finfo);

            // Determine extension
            $extension = $this->getExtensionFromMime($mimeType);
            if (!$extension) {
                $extension = $this->getExtensionFromUrl($cleanUrl);
                if (!$extension) {
                    $extension = 'jpg';
                }
            }

            // Generate unique file name
            $fileName = Str::random(40) . '.' . $extension;
            $filePath = 'uploads/all/' . $fileName;

            // Save file to storage
            $fullPath = public_path($filePath);
            $directory = dirname($fullPath);
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }

            $bytesWritten = file_put_contents($fullPath, $imageContent);
            if ($bytesWritten === false) {
                Log::error("Failed to save image to disk: {$fullPath}");
                return null;
            }

            // Create upload record
            $upload = Upload::create([
                'file_original_name' => $urlHash,
                'file_name' => $filePath,
                'user_id' => $userId ?? auth()->id(),
                'file_size' => strlen($imageContent),
                'extension' => $extension,
                'type' => 'image',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            Log::info("Image downloaded and saved: {$cleanUrl} -> Upload ID: {$upload->id}");

            return $upload->id;
        } catch (\Exception $e) {
            Log::error("Error downloading image: " . $e->getMessage() . " for URL: {$cleanUrl}");
            return null;
        }
    }

    /**
     * Download image content with proper encoding
     */
    private function downloadImageContent($url)
    {
        // Method 1: cURL
        $content = $this->downloadWithCurl($url);
        if ($content) {
            return $content;
        }

        // Method 2: file_get_contents
        $content = $this->downloadWithFileGetContents($url);
        if ($content) {
            return $content;
        }

        return null;
    }

    /**
     * Download with cURL
     */
    private function downloadWithCurl($url)
    {
        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_TIMEOUT, 60);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');
            curl_setopt($ch, CURLOPT_ENCODING, '');
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Accept: image/webp,image/apng,image/*,*/*;q=0.8',
                'Accept-Language: en-US,en;q=0.9',
                'Connection: keep-alive',
            ]);

            $content = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = curl_error($ch);
            curl_close($ch);

            if ($httpCode == 200 && $content) {
                return $content;
            }

            Log::info("cURL failed for {$url}: HTTP {$httpCode}, Error: {$error}");
            return null;
        } catch (\Exception $e) {
            Log::error("cURL exception for {$url}: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Download with file_get_contents
     */
    private function downloadWithFileGetContents($url)
    {
        try {
            $options = [
                'http' => [
                    'method' => 'GET',
                    'header' => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36\r\n" .
                        "Accept: image/webp,image/apng,image/*,*/*;q=0.8\r\n",
                    'timeout' => 60,
                    'follow_location' => 1,
                ],
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                ]
            ];

            $context = stream_context_create($options);
            $content = @file_get_contents($url, false, $context);

            if ($content !== false) {
                return $content;
            }
            return null;
        } catch (\Exception $e) {
            Log::error("file_get_contents failed for {$url}: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Get extension from MIME type
     */
    private function getExtensionFromMime($mimeType)
    {
        $mimeMap = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/gif' => 'gif',
            'image/webp' => 'webp',
            'image/bmp' => 'bmp',
            'image/svg+xml' => 'svg',
            'image/tiff' => 'tiff',
            'image/x-icon' => 'ico',
        ];

        return $mimeMap[$mimeType] ?? null;
    }

    /**
     * Get extension from URL
     */
    private function getExtensionFromUrl($url)
    {
        $path = parse_url($url, PHP_URL_PATH);
        if ($path) {
            $extension = pathinfo($path, PATHINFO_EXTENSION);
            if ($extension) {
                return strtolower($extension);
            }
        }
        return null;
    }

    /**
     * Get upload ID from URL or existing upload
     */
    private function getUploadId($value, $userId = null)
    {
        if (empty($value)) {
            return null;
        }

        // If it's numeric, return as is (already an upload ID)
        if (is_numeric($value)) {
            $uploadExists = Upload::where('id', (int)$value)->exists();
            return $uploadExists ? (int)$value : null;
        }

        // If it's a URL, download and save
        if (filter_var($value, FILTER_VALIDATE_URL) || strpos($value, 'http') !== false) {
            $uploadId = $this->downloadAndSaveImage($value, $userId);
            if ($uploadId) {
                return $uploadId;
            }

            // If download failed, try to find existing upload by URL
            $urlHash = md5($this->cleanAndEncodeUrl($value));
            $upload = Upload::where('file_original_name', $urlHash)->first();
            if ($upload) {
                return $upload->id;
            }

            Log::warning("Failed to process image: {$value}");
            return null;
        }

        // Try to find existing upload by file name
        $upload = Upload::where('file_name', 'LIKE', '%' . $value . '%')
            ->orWhere('file_original_name', 'LIKE', '%' . $value . '%')
            ->first();

        return $upload ? $upload->id : null;
    }

    /**
     * Extract URLs from any format (string, array, or JSON) - Universal Parser
     */
    private function extractUrlsFromPhotos($photoData)
    {
        $urls = [];

        if (empty($photoData)) {
            return $urls;
        }

        // If it's a string, try to parse as JSON
        if (is_string($photoData)) {
            $trimmed = trim($photoData);

            // Try JSON decode
            $decoded = json_decode($trimmed, true);
            if ($decoded !== null && is_array($decoded)) {
                // It's valid JSON - extract URLs from array
                return $this->extractUrlsFromArray($decoded);
            } else {
                // Not JSON - treat as comma separated
                $items = explode(',', $trimmed);
                foreach ($items as $item) {
                    $item = trim($item);
                    if (filter_var($item, FILTER_VALIDATE_URL) || strpos($item, 'http') !== false) {
                        $urls[] = $item;
                    }
                }
            }
        } elseif (is_array($photoData)) {
            // It's already an array
            return $this->extractUrlsFromArray($photoData);
        }

        return $urls;
    }

    /**
     * Extract URLs from array recursively - Handles any nested structure
     */
    private function extractUrlsFromArray($array, $depth = 0)
    {
        $urls = [];

        // Prevent infinite recursion
        if ($depth > 10) {
            return $urls;
        }

        foreach ($array as $key => $value) {
            if (is_array($value)) {
                // Recursive call for nested arrays
                $nestedUrls = $this->extractUrlsFromArray($value, $depth + 1);
                $urls = array_merge($urls, $nestedUrls);
            } elseif (is_string($value) && (filter_var($value, FILTER_VALIDATE_URL) || strpos($value, 'http') !== false)) {
                $urls[] = $value;
            } elseif (is_string($key) && (filter_var($key, FILTER_VALIDATE_URL) || strpos($key, 'http') !== false)) {
                $urls[] = $key;
            }
        }

        return $urls;
    }

    /**
     * Parse photos from any format - Main function
     */
    private function parsePhotos($photoData)
    {
        $photosArray = [];

        if (empty($photoData)) {
            return $photosArray;
        }

        Log::info('Parsing photos - Input type: ' . gettype($photoData));
        Log::info('Parsing photos - Input value: ' . json_encode($photoData));

        // Extract all URLs
        $urls = $this->extractUrlsFromPhotos($photoData);

        Log::info('Extracted URLs: ' . json_encode($urls));

        // Process each URL - download and save to uploads
        foreach ($urls as $url) {
            $photoId = $this->getUploadId($url, auth()->id());
            if ($photoId) {
                $photosArray[] = $photoId;
                Log::info('Photo uploaded with ID: ' . $photoId . ' from URL: ' . $url);
            } else {
                Log::warning('Failed to upload photo: ' . $url);
            }
        }

        return $photosArray;
    }

    /**
     * Generate SKU for variant - Auto generate when SKU is empty
     */
    private function generateVariantSku($productName, $productId, $counter = 1, $forceUnique = false)
    {
        // Get product prefix (first 3 characters of product name)
        $prefix = strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $productName), 0, 3));
        if (empty($prefix)) {
            $prefix = 'PRD';
        }

        // Generate base SKU
        $baseSku = $prefix . '-' . $productId . '-VAR';

        // If counter is provided, append it
        if ($counter > 0) {
            $sku = $baseSku . $counter;
        } else {
            $sku = $baseSku;
        }

        // If force unique or SKU already exists, add random suffix
        if ($forceUnique || ProductVarient::where('sku', $sku)->exists()) {
            $sku = $baseSku . rand(100, 999);
            // Keep trying if still exists
            while (ProductVarient::where('sku', $sku)->exists()) {
                $sku = $baseSku . rand(100, 999);
            }
        }

        return $sku;
    }

    /**
     * Process variant image - Download or use existing
     */
    private function processVariantImage($imageValue, $thumbnailId, $userId = null)
    {
        // Default to thumbnail
        $variantImageId = $thumbnailId;

        if (empty($imageValue)) {
            return $variantImageId;
        }

        // Check if it's numeric (already an upload ID)
        if (is_numeric($imageValue)) {
            $uploadExists = Upload::where('id', (int)$imageValue)->exists();
            if ($uploadExists) {
                $variantImageId = (int)$imageValue;
                Log::info('Using existing upload ID for variant: ' . $variantImageId);
                return $variantImageId;
            }
        }

        // Check if it's a URL
        if (filter_var($imageValue, FILTER_VALIDATE_URL) || strpos($imageValue, 'http') !== false) {
            $uploadId = $this->getUploadId($imageValue, $userId);
            if ($uploadId) {
                $variantImageId = $uploadId;
                Log::info('Variant image downloaded and saved with ID: ' . $uploadId);
            } else {
                // If download fails, use thumbnail
                $variantImageId = $thumbnailId;
                Log::warning('Failed to download variant image, using thumbnail: ' . $thumbnailId);
            }
            return $variantImageId;
        }

        // Try to find existing upload by file name
        $upload = Upload::where('file_name', 'LIKE', '%' . $imageValue . '%')
            ->orWhere('file_original_name', 'LIKE', '%' . $imageValue . '%')
            ->first();
        if ($upload) {
            $variantImageId = $upload->id;
            Log::info('Found existing upload for variant: ' . $upload->id);
            return $variantImageId;
        }

        return $variantImageId;
    }

    /**
     * Import single product from row data
     */
    private function importSingleProductFromRow111($data, $importMode)
    {
        $existingProduct = null;

        if ($importMode == 'update') {
            if (!empty($data['sku'])) {
                $existingProduct = Product::whereHas('inventory', function ($q) use ($data) {
                    $q->where('sku', $data['sku']);
                })->first();
            }

            if (!$existingProduct && !empty($data['slug'])) {
                $existingProduct = Product::where('slug', $data['slug'])->first();
            }
        }

        $isVariant = isset($data['is_variant']) ? (int)$data['is_variant'] : 0;

        // Parse variant attributes from JSON
        $variantAttributes = [];
        if ($isVariant == 1 && !empty($data['variant_attributes'])) {
            try {
                if (is_string($data['variant_attributes'])) {
                    $parsed = json_decode($data['variant_attributes'], true);
                    if (isset($parsed['variant_attributes']) && is_array($parsed['variant_attributes'])) {
                        $variantAttributes = $parsed['variant_attributes'];
                    } elseif (is_array($parsed)) {
                        $variantAttributes = $parsed;
                    }
                } elseif (is_array($data['variant_attributes'])) {
                    $variantAttributes = $data['variant_attributes'];
                }
                Log::info('Parsed variant attributes count: ' . count($variantAttributes));
            } catch (\Exception $e) {
                Log::error('Failed to parse variant attributes: ' . $e->getMessage());
            }
        }

        // ============ PHOTOS PARSING - UNIVERSAL PARSER ============
        $photosArray = [];

        if (!empty($data['photos'])) {
            $photosArray = $this->parsePhotos($data['photos']);
        }

        $photosJson = !empty($photosArray) ? json_encode($photosArray) : null;
        Log::info('Final photos array: ' . ($photosJson ?? 'empty'));
        // ============ END PHOTOS PARSING ============

        // Process thumbnail image
        $thumbnailId = $this->getUploadId($data['thumbnail_img'] ?? null, auth()->id());

        // Prepare product data discount
        $baseSlug = !empty($data['slug']) ? $data['slug'] : ($data['name'] ? Str::slug($data['name']) : null);
        $productData = [
            'name' => $data['name'] ?? null,
            'slug' => $baseSlug . '-' . time() . '-' . rand(1000, 9999),
            'added_by' => auth()->id(),
            'user_id' => auth()->id(),
            'vendor_id' => null,
            'brand_id' => !empty($data['brand_id']) && is_numeric($data['brand_id']) ? (int)$data['brand_id'] : null,
            'category_id' => !empty($data['category_id']) && is_numeric($data['category_id']) ? (int)$data['category_id'] : null,
            'subcategory_id' => !empty($data['subcategory_id']) && is_numeric($data['subcategory_id']) ? (int)$data['subcategory_id'] : null,
            'thumbnail' => $thumbnailId,
            'photos' => $photosJson,
            'tags' => !empty($data['tags']) ? json_encode(explode(',', $data['tags'])) : null,
            'description' => $data['description'] ?? null,
            'short_description' => $data['short_description'] ?? null,
            'status' => isset($data['status']) ? (int)$data['status'] : 1,
            'is_published' => isset($data['is_published']) ? (int)$data['is_published'] : 1,
            'is_featured' => isset($data['is_featured']) ? (int)$data['is_featured'] : 0,
            'best_selling' => isset($data['best_selling']) ? (int)$data['best_selling'] : 0,
            'is_new_arrival' => isset($data['is_new_arrival']) ? (int)$data['is_new_arrival'] : 0,
            'todays_deal' => isset($data['todays_deal']) ? (int)$data['todays_deal'] : 0,
            'is_variant' => $isVariant,
            'unit' => !empty($data['unit']) ? $data['unit'] : 'Pc',
            'barcode' => $data['barcode'] ?? null,
            'video_link' => $data['video_link'] ?? null,
            'badge_name' => $data['badge_name'] ?? null,
            'batch_no' => !empty($data['batch_no']) ? (int)$data['batch_no'] : 1,
            'position' => !empty($data['position']) ? (int)$data['position'] : 0,
            'is_cat' => false,
            'num_of_sale' => 0,
            'stock_request' => 0,
        ];

        // Validate required fields
        if (empty($productData['name'])) {
            return ['success' => false, 'error' => 'Product name is required'];
        }
        if (empty($productData['category_id'])) {
            return ['success' => false, 'error' => 'Category ID is required'];
        }
        if (empty($data['regular_price']) || !is_numeric($data['regular_price'])) {
            return ['success' => false, 'error' => 'Valid regular price is required'];
        }
        if (!isset($data['stock']) || !is_numeric($data['stock'])) {
            return ['success' => false, 'error' => 'Stock quantity is required and must be numeric'];
        }
        if (empty($thumbnailId)) {
            // If thumbnail download failed, try to use first photo as thumbnail
            if (!empty($photosArray)) {
                $thumbnailId = $photosArray[0];
                $productData['thumbnail'] = $thumbnailId;
                Log::info('Using first photo as thumbnail: ' . $thumbnailId);
            } else {
                return ['success' => false, 'error' => 'Thumbnail image is required'];
            }
        }

        $action = 'insert';

        try {
            if ($existingProduct && $importMode == 'update') {
                $product = $existingProduct;
                $product->update($productData);
                $action = 'update';
            } else {
                $product = Product::create($productData);
                $action = 'insert';
            }

            // Generate unique SKU
            $mainSku = !empty($data['sku']) ? $data['sku'] : $this->generateUniqueSku($product->name, $product->id);
            $skuExists = ProductInventory::where('sku', $mainSku)->exists();
            if ($skuExists) {
                $mainSku = $this->generateUniqueSku($product->name, $product->id);
            }

            // Inventory
            ProductInventory::updateOrCreate(
                ['product_id' => $product->id],
                [
                    'sku' => $mainSku,
                    'barcode' => $data['barcode'] ?? null,
                    'stock' => (int)($data['stock'] ?? 0),
                    'low_stock_qty' => !empty($data['low_stock_qty']) ? (int)$data['low_stock_qty'] : 1,
                    'track_inventory' => 1,
                ]
            );

            // Price
            $regularPrice = (float)($data['regular_price'] ?? 0);
            $discount = (float)($data['discount'] ?? 0);
            $discountType = !empty($data['discount_type']) && in_array($data['discount_type'], ['flat', 'percent'])
                ? $data['discount_type'] : 'percent';

            $salePrice = $discountType === 'flat'
                ? max(0, $regularPrice - $discount)
                : max(0, $regularPrice - (($regularPrice * $discount) / 100));

            ProductPrice::updateOrCreate(
                ['product_id' => $product->id],
                [
                    'purchase_price' => !empty($data['purchase_price']) ? (float)$data['purchase_price'] : 0,
                    'regular_price' => (int)round($regularPrice),
                    'sale_price' => (int)round($salePrice),
                    'discount_type' => $discountType,
                    'discount' => $discount,
                    'wholesale_price' => !empty($data['wholesale_price']) ? (float)$data['wholesale_price'] : 0,
                    'currency' => 'BDT',
                ]
            );

            // Shipping
            ProductShipping::updateOrCreate(
                ['product_id' => $product->id],
                [
                    'shipping_type' => $data['shipping_type'] ?? 'flat_rate',
                    'shipping_cost' => $data['shipping_cost'] ?? 0,
                    'weight' => $data['weight'] ?? 0,
                    'length' => $data['length'] ?? 0,
                    'width' => $data['width'] ?? 0,
                    'height' => $data['height'] ?? 0,
                ]
            );

            // SEO
            $metaImageId = $this->getUploadId($data['meta_img'] ?? null, auth()->id());
            if (!empty($data['meta_title']) || !empty($data['meta_description']) || !empty($metaImageId)) {
                ProductSeo::updateOrCreate(
                    ['product_id' => $product->id],
                    [
                        'meta_title' => $data['meta_title'] ?? null,
                        'meta_description' => $data['meta_description'] ?? null,
                        'meta_image' => $metaImageId,
                    ]
                );
            }

            // ============ HANDLE VARIANTS - UPDATED ============
            if ($isVariant == 1) {
                if (!empty($variantAttributes) && is_array($variantAttributes)) {
                    if ($action == 'update') {
                        ProductVarient::where('product_id', $product->id)->delete();
                    }

                    $variantCount = 0;
                    foreach ($variantAttributes as $variant) {
                        // Generate SKU if empty or not provided
                        $variantSku = !empty($variant['sku']) ? $variant['sku'] : $this->generateVariantSku($product->name, $product->id, $variantCount + 1);

                        // Check if variant SKU already exists
                        $variantSkuExists = ProductVarient::where('sku', $variantSku)->exists();
                        if ($variantSkuExists) {
                            $variantSku = $this->generateVariantSku($product->name, $product->id, $variantCount + 1, true);
                        }

                        $variantPrice = isset($variant['price']) ? (float)$variant['price'] : (int)round($salePrice);
                        $variantWholesalePrice = isset($variant['wholesale_price']) ? (float)$variant['wholesale_price'] : (float)($data['wholesale_price'] ?? 0);


                        $variantQuantity = isset($variant['quantity']) && !empty($variant['quantity']) ? (int)$variant['quantity'] : (int)($data['stock'] ?? 0);

                        // ============ PROCESS VARIANT IMAGE ============
                        $variantImageId = $thumbnailId; // Default to product thumbnail

                        if (!empty($variant['image'])) {
                            $variantImageId = $this->processVariantImage($variant['image'], $thumbnailId, auth()->id());
                        }
                        // ============ END VARIANT IMAGE PROCESSING ============

                        $variantAttributesValue = isset($variant['attributes']) ? $variant['attributes'] : 'Default';

                        ProductVarient::create([
                            'product_id' => $product->id,
                            'attribute_value' => $variantAttributesValue,
                            'sku' => $variantSku,
                            'price' => (int)round($variantPrice),
                            'quantity' => $variantQuantity,
                            'image' => $variantImageId,
                            'material' => null,
                            'discount_type' => $discountType,
                            'discount' => $discount,
                            'wholesale_price' => $variantWholesalePrice ?? 0,
                        ]);
                        $variantCount++;

                        Log::info("Variant {$variantCount} created with SKU: {$variantSku}, Image ID: {$variantImageId}");
                    }
                    Log::info("Total {$variantCount} variants created for product ID {$product->id}");
                } else {
                    // Create default variant
                    $defaultVariantSku = $mainSku . '-VAR';
                    $defaultSkuExists = ProductVarient::where('sku', $defaultVariantSku)->exists();
                    if ($defaultSkuExists) {
                        $defaultVariantSku = $this->generateVariantSku($product->name, $product->id, 1, true);
                    }

                    ProductVarient::create([
                        'product_id' => $product->id,
                        'attribute_value' => 'Default Variant',
                        'sku' => $defaultVariantSku,
                        'price' => (int)round($salePrice),
                        'quantity' => (int)($data['stock'] ?? 0),
                        'image' => $thumbnailId,
                        'material' => null,
                        'discount_type' => $discountType,
                        'discount' => $discount,
                    ]);
                    Log::info("Default variant created for product ID {$product->id} with SKU: {$defaultVariantSku}");
                }
            }
            // ============ END VARIANTS HANDLING ============

            Log::info('Product imported successfully. Photos saved: ' . $photosJson);

            return ['success' => true, 'action' => $action, 'product_id' => $product->id];
        } catch (\Exception $e) {
            Log::error('Error importing product: ' . $e->getMessage(), [
                'data' => $data,
                'trace' => $e->getTraceAsString()
            ]);
            return ['success' => false, 'error' => 'Database error: ' . $e->getMessage()];
        }
    }


    private function importSingleProductFromRow111111($data, $importMode)
    {
        DB::beginTransaction();

        try {
            $existingProduct = null;

            // ---------- FIND EXISTING PRODUCT (if update mode) ----------
            if ($importMode === 'update') {
                // 1. Use explicit ID if provided
                if (!empty($data['id']) && is_numeric($data['id'])) {
                    $existingProduct = Product::find((int) $data['id']);
                }

                // 2. Fallback to SKU (via inventory relation)
                if (!$existingProduct && !empty($data['sku'])) {
                    $existingProduct = Product::whereHas('inventory', function ($q) use ($data) {
                        $q->where('sku', $data['sku']);
                    })->first();
                }

                // 3. Fallback to slug
                if (!$existingProduct && !empty($data['slug'])) {
                    $existingProduct = Product::where('slug', $data['slug'])->first();
                }
            }

            // ---------- BASIC VALIDATION ----------
            if (empty($data['name'])) {
                throw new \Exception('Product name is required');
            }
            if (empty($data['category_id']) || !is_numeric($data['category_id'])) {
                throw new \Exception('Category ID is required');
            }
            if (empty($data['regular_price']) || !is_numeric($data['regular_price'])) {
                throw new \Exception('Valid regular price is required');
            }
            if (!isset($data['stock']) || !is_numeric($data['stock'])) {
                throw new \Exception('Stock quantity is required and must be numeric');
            }

            // ---------- PARSE VARIANT FLAG AND ATTRIBUTES ----------
            $isVariant = isset($data['is_variant']) ? (int) $data['is_variant'] : 0;

            $variantAttributes = [];
            if ($isVariant == 1 && !empty($data['variant_attributes'])) {
                try {
                    if (is_string($data['variant_attributes'])) {
                        $parsed = json_decode($data['variant_attributes'], true);
                        if (isset($parsed['variant_attributes']) && is_array($parsed['variant_attributes'])) {
                            $variantAttributes = $parsed['variant_attributes'];
                        } elseif (is_array($parsed)) {
                            $variantAttributes = $parsed;
                        }
                    } elseif (is_array($data['variant_attributes'])) {
                        $variantAttributes = $data['variant_attributes'];
                    }
                    Log::info('Parsed variant attributes count: ' . count($variantAttributes));
                } catch (\Exception $e) {
                    Log::error('Failed to parse variant attributes: ' . $e->getMessage());
                }
            }

            // ---------- PHOTOS PARSING ----------
            $photosArray = [];
            if (!empty($data['photos'])) {
                $photosArray = $this->parsePhotos($data['photos']);
            }
            $photosJson = !empty($photosArray) ? json_encode($photosArray) : null;
            Log::info('Final photos array: ' . ($photosJson ?? 'empty'));

            // ---------- THUMBNAIL ----------
            $thumbnailId = $this->getUploadId($data['thumbnail_img'] ?? null, auth()->id());
            if (empty($thumbnailId) && !empty($photosArray)) {
                $thumbnailId = $photosArray[0];
                Log::info('Using first photo as thumbnail: ' . $thumbnailId);
            }
            if (empty($thumbnailId)) {
                throw new \Exception('Thumbnail image is required');
            }

            // ---------- SLUG GENERATION (fixed) ----------
            if ($existingProduct) {
                $slug = !empty($data['slug']) ? $data['slug'] : $existingProduct->slug;
            } else {
                $slug = !empty($data['slug']) ? $data['slug'] : Str::slug($data['name']);
            }
            $slugQuery = Product::where('slug', $slug);
            if ($existingProduct) {
                $slugQuery->where('id', '!=', $existingProduct->id);
            }
            if ($slugQuery->exists()) {
                $slug = $slug . '-' . time() . '-' . rand(1000, 9999);
            }

            // ---------- BUILD PRODUCT DATA (NOW INCLUDES 'id') ----------
            $productData = [
                'id'                => $data['id'] ?? null,   // <--- ADDED
                'name'              => $data['name'],
                'slug'              => $slug,
                'added_by'          => auth()->id(),
                'user_id'           => auth()->id(),
                'vendor_id'         => null,
                'brand_id'          => !empty($data['brand_id']) && is_numeric($data['brand_id']) ? (int) $data['brand_id'] : null,
                'category_id'       => (int) $data['category_id'],
                'subcategory_id'    => !empty($data['subcategory_id']) && is_numeric($data['subcategory_id']) ? (int) $data['subcategory_id'] : null,
                'thumbnail'         => $thumbnailId,
                'photos'            => $photosJson,
                'tags'              => !empty($data['tags']) ? json_encode(explode(',', $data['tags'])) : null,
                'description'       => $data['description'] ?? null,
                'short_description' => $data['short_description'] ?? null,
                'status'            => isset($data['status']) ? (int) $data['status'] : 1,
                'is_published'      => isset($data['is_published']) ? (int) $data['is_published'] : 1,
                'is_featured'       => isset($data['is_featured']) ? (int) $data['is_featured'] : 0,
                'best_selling'      => isset($data['best_selling']) ? (int) $data['best_selling'] : 0,
                'is_new_arrival'    => isset($data['is_new_arrival']) ? (int) $data['is_new_arrival'] : 0,
                'todays_deal'       => isset($data['todays_deal']) ? (int) $data['todays_deal'] : 0,
                'is_variant'        => $isVariant,
                'unit'              => !empty($data['unit']) ? $data['unit'] : 'Pc',
                'barcode'           => $data['barcode'] ?? null,
                'video_link'        => $data['video_link'] ?? null,
                'badge_name'        => $data['badge_name'] ?? null,
                'batch_no'          => !empty($data['batch_no']) ? (int) $data['batch_no'] : 1,
                'position'          => !empty($data['position']) ? (int) $data['position'] : 0,
                'is_cat'            => false,
                'num_of_sale'       => 0,
                'stock_request'     => 0,
            ];

            // ---------- CREATE OR UPDATE PRODUCT ----------
            if ($existingProduct && $importMode === 'update') {
                $product = $existingProduct;
                // Do NOT update the 'id' field during update
                unset($productData['id']);
                $product->update($productData);
                $action = 'update';
            } else {
                // Insert new product
                // If 'id' is provided and not null, we want to set it explicitly
                $product = new Product($productData);
                if (!empty($productData['id']) && !Product::where('id', $productData['id'])->exists()) {
                    $product->id = $productData['id'];
                } else {
                    // If id is provided but already exists, we treat as update? 
                    // But we already checked existence earlier; if it existed, we would have found it.
                    // So this should not happen, but fallback to auto-increment if conflict.
                    unset($productData['id']);
                    $product = new Product($productData);
                }
                $product->save();
                $action = 'insert';
            }

            // ---------- INVENTORY ----------
            $mainSku = !empty($data['sku']) ? $data['sku'] : $this->generateUniqueSku($product->name, $product->id);
            if (ProductInventory::where('sku', $mainSku)->exists()) {
                $mainSku = $this->generateUniqueSku($product->name, $product->id);
            }
            ProductInventory::updateOrCreate(
                ['product_id' => $product->id],
                [
                    'sku'            => $mainSku,
                    'barcode'        => $data['barcode'] ?? null,
                    'stock'          => (int) ($data['stock'] ?? 0),
                    'low_stock_qty'  => !empty($data['low_stock_qty']) ? (int) $data['low_stock_qty'] : 1,
                    'track_inventory' => 1,
                ]
            );

            // ---------- PRICE ----------
            $regularPrice = (float) ($data['regular_price'] ?? 0);
            $discount = (float) ($data['discount'] ?? 0);
            $discountType = !empty($data['discount_type']) && in_array($data['discount_type'], ['flat', 'percent'])
                ? $data['discount_type'] : 'percent';

            $salePrice = $discountType === 'flat'
                ? max(0, $regularPrice - $discount)
                : max(0, $regularPrice - (($regularPrice * $discount) / 100));

            ProductPrice::updateOrCreate(
                ['product_id' => $product->id],
                [
                    'purchase_price'   => !empty($data['purchase_price']) ? (float) $data['purchase_price'] : 0,
                    'regular_price'    => (int) round($regularPrice),
                    'sale_price'       => (int) round($salePrice),
                    'discount_type'    => $discountType,
                    'discount'         => $discount,
                    'wholesale_price'  => !empty($data['wholesale_price']) ? (float) $data['wholesale_price'] : 0,
                    'currency'         => 'BDT',
                ]
            );

            // ---------- SHIPPING ----------
            ProductShipping::updateOrCreate(
                ['product_id' => $product->id],
                [
                    'shipping_type' => $data['shipping_type'] ?? 'flat_rate',
                    'shipping_cost' => $data['shipping_cost'] ?? 0,
                    'weight'        => $data['weight'] ?? 0,
                    'length'        => $data['length'] ?? 0,
                    'width'         => $data['width'] ?? 0,
                    'height'        => $data['height'] ?? 0,
                ]
            );

            // ---------- SEO ----------
            $metaImageId = $this->getUploadId($data['meta_img'] ?? null, auth()->id());
            if (!empty($data['meta_title']) || !empty($data['meta_description']) || !empty($metaImageId)) {
                ProductSeo::updateOrCreate(
                    ['product_id' => $product->id],
                    [
                        'meta_title'       => $data['meta_title'] ?? null,
                        'meta_description' => $data['meta_description'] ?? null,
                        'meta_image'       => $metaImageId,
                    ]
                );
            }

            // ---------- HANDLE VARIANTS ----------
            if ($isVariant == 1) {
                if (!empty($variantAttributes) && is_array($variantAttributes)) {
                    if ($action === 'update') {
                        ProductVarient::where('product_id', $product->id)->delete();
                    }

                    $variantCount = 0;
                    foreach ($variantAttributes as $variant) {
                        $variantSku = !empty($variant['sku']) ? $variant['sku']
                            : $this->generateVariantSku($product->name, $product->id, $variantCount + 1);

                        if (ProductVarient::where('sku', $variantSku)->exists()) {
                            $variantSku = $this->generateVariantSku($product->name, $product->id, $variantCount + 1, true);
                        }

                        $variantPrice = isset($variant['price']) ? (float) $variant['price'] : (int) round($salePrice);
                        $variantWholesalePrice = isset($variant['wholesale_price'])
                            ? (float) $variant['wholesale_price']
                            : (float) ($data['wholesale_price'] ?? 0);

                        $variantQuantity = isset($variant['quantity']) && !empty($variant['quantity'])
                            ? (int) $variant['quantity']
                            : (int) ($data['stock'] ?? 0);

                        $variantImageId = $thumbnailId;
                        if (!empty($variant['image'])) {
                            $variantImageId = $this->processVariantImage($variant['image'], $thumbnailId, auth()->id());
                        }

                        $variantAttributesValue = isset($variant['attributes']) ? $variant['attributes'] : 'Default';

                        ProductVarient::create([
                            'product_id'       => $product->id,
                            'attribute_value'  => $variantAttributesValue,
                            'sku'              => $variantSku,
                            'price'            => (int) round($variantPrice),
                            'quantity'         => $variantQuantity,
                            'image'            => $variantImageId,
                            'material'         => null,
                            'discount_type'    => $discountType,
                            'discount'         => $discount,
                            'wholesale_price'  => $variantWholesalePrice,
                        ]);
                        $variantCount++;
                        Log::info("Variant {$variantCount} created with SKU: {$variantSku}, Image ID: {$variantImageId}");
                    }
                    Log::info("Total {$variantCount} variants created for product ID {$product->id}");
                } else {
                    // Default variant
                    $defaultVariantSku = $mainSku . '-VAR';
                    if (ProductVarient::where('sku', $defaultVariantSku)->exists()) {
                        $defaultVariantSku = $this->generateVariantSku($product->name, $product->id, 1, true);
                    }
                    ProductVarient::create([
                        'product_id'       => $product->id,
                        'attribute_value'  => 'Default Variant',
                        'sku'              => $defaultVariantSku,
                        'price'            => (int) round($salePrice),
                        'quantity'         => (int) ($data['stock'] ?? 0),
                        'image'            => $thumbnailId,
                        'material'         => null,
                        'discount_type'    => $discountType,
                        'discount'         => $discount,
                    ]);
                    Log::info("Default variant created for product ID {$product->id} with SKU: {$defaultVariantSku}");
                }
            }

            // ---------- COMMIT ----------
            DB::commit();
            Log::info('Product imported successfully. Photos saved: ' . $photosJson);

            return ['success' => true, 'action' => $action, 'product_id' => $product->id];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error importing product: ' . $e->getMessage(), [
                'data'  => $data,
                'trace' => $e->getTraceAsString()
            ]);
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Generate unique SKU for main product
     */
    private function generateUniqueSku($productName, $productId = null)
    {
        $prefix = strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $productName), 0, 3));
        if (empty($prefix)) {
            $prefix = 'PRD';
        }

        $uniqueId = $productId ?? rand(10000, 99999);
        $sku = $prefix . '-' . $uniqueId;

        $counter = 1;
        while (ProductInventory::where('sku', $sku)->exists()) {
            $sku = $prefix . '-' . $uniqueId . '-' . $counter;
            $counter++;
        }

        return $sku;
    }

    /**
     * Send import notification email
     */
    private function sendImportNotification($email, $result)
    {
        try {
            Mail::send('emails.import-complete', [
                'result' => $result['summary'],
                'date' => now()->format('Y-m-d H:i:s')
            ], function ($message) use ($email) {
                $message->to($email)->subject('Product Import Completed');
            });
        } catch (\Exception $e) {
            Log::warning('Failed to send import notification: ' . $e->getMessage());
        }
    }

    public function preview()
    {
        return view('backend.product.products.preview');
    }

    public function cancelImport(Request $request)
    {
        $importId = session('import_id');
        if ($importId) {
            Cache::forget('import_progress_' . $importId);
            session()->forget('import_id');
        }

        return response()->json(['success' => true]);
    }


    private function importSingleProductFromRow($data, $importMode)
    {
        DB::beginTransaction();

        try {
            $existingProduct = null;

            // ---------- FIND EXISTING PRODUCT (if update mode) ----------
            if ($importMode === 'update') {
                // 1. Explicit ID
                if (!empty($data['id']) && is_numeric($data['id'])) {
                    $existingProduct = Product::find((int) $data['id']);
                }
                // 2. Fallback to SKU (via inventory)
                if (!$existingProduct && !empty($data['sku'])) {
                    $existingProduct = Product::whereHas('inventory', function ($q) use ($data) {
                        $q->where('sku', $data['sku']);
                    })->first();
                }
                // 3. Fallback to slug
                if (!$existingProduct && !empty($data['slug'])) {
                    $existingProduct = Product::where('slug', $data['slug'])->first();
                }
            }

            // ---------- BASIC VALIDATION ----------
            if (empty($data['name'])) {
                throw new \Exception('Product name is required');
            }
            if (empty($data['category_id']) || !is_numeric($data['category_id'])) {
                throw new \Exception('Category ID is required');
            }
            if (empty($data['regular_price']) || !is_numeric($data['regular_price'])) {
                throw new \Exception('Valid regular price is required');
            }
            if (!isset($data['stock']) || !is_numeric($data['stock'])) {
                throw new \Exception('Stock quantity is required and must be numeric');
            }

            // ---------- PARSE VARIANT FLAG AND ATTRIBUTES ----------
            $isVariant = isset($data['is_variant']) ? (int) $data['is_variant'] : 0;

            $variantAttributes = [];
            if ($isVariant == 1 && !empty($data['variant_attributes'])) {
                try {
                    if (is_string($data['variant_attributes'])) {
                        $parsed = json_decode($data['variant_attributes'], true);
                        if (isset($parsed['variant_attributes']) && is_array($parsed['variant_attributes'])) {
                            $variantAttributes = $parsed['variant_attributes'];
                        } elseif (is_array($parsed)) {
                            $variantAttributes = $parsed;
                        }
                    } elseif (is_array($data['variant_attributes'])) {
                        $variantAttributes = $data['variant_attributes'];
                    }
                    Log::info('Parsed variant attributes count: ' . count($variantAttributes));
                } catch (\Exception $e) {
                    Log::error('Failed to parse variant attributes: ' . $e->getMessage());
                }
            }

            // ---------- PHOTOS PARSING ----------
            $photosArray = [];
            if (!empty($data['photos'])) {
                $photosArray = $this->parsePhotos($data['photos']);
            }
            $photosJson = !empty($photosArray) ? json_encode($photosArray) : null;
            Log::info('Final photos array: ' . ($photosJson ?? 'empty'));

            // ---------- THUMBNAIL ----------
            $thumbnailId = $this->getUploadId($data['thumbnail_img'] ?? null, auth()->id());
            if (empty($thumbnailId) && !empty($photosArray)) {
                $thumbnailId = $photosArray[0];
                Log::info('Using first photo as thumbnail: ' . $thumbnailId);
            }
            if (empty($thumbnailId)) {
                throw new \Exception('Thumbnail image is required');
            }

            // ---------- SLUG GENERATION ----------
            if ($existingProduct) {
                $slug = !empty($data['slug']) ? $data['slug'] : $existingProduct->slug;
            } else {
                $slug = !empty($data['slug']) ? $data['slug'] : Str::slug($data['name']);
            }
            $slugQuery = Product::where('slug', $slug);
            if ($existingProduct) {
                $slugQuery->where('id', '!=', $existingProduct->id);
            }
            if ($slugQuery->exists()) {
                $slug = $slug . '-' . time() . '-' . rand(1000, 9999);
            }

            // ---------- BUILD PRODUCT DATA ----------
            $productData = [
                'name'              => $data['name'],
                'slug'              => $slug,
                'added_by'          => auth()->id(),
                'user_id'           => auth()->id(),
                'vendor_id'         => null,
                'brand_id'          => !empty($data['brand_id']) && is_numeric($data['brand_id']) ? (int) $data['brand_id'] : null,
                'category_id'       => (int) $data['category_id'],
                'subcategory_id'    => !empty($data['subcategory_id']) && is_numeric($data['subcategory_id']) ? (int) $data['subcategory_id'] : null,
                'thumbnail'         => $thumbnailId,
                'photos'            => $photosJson,
                'tags'              => !empty($data['tags']) ? json_encode(explode(',', $data['tags'])) : null,
                'description'       => $data['description'] ?? null,
                'short_description' => $data['short_description'] ?? null,
                'status'            => isset($data['status']) ? (int) $data['status'] : 1,
                'is_published'      => isset($data['is_published']) ? (int) $data['is_published'] : 1,
                'is_featured'       => isset($data['is_featured']) ? (int) $data['is_featured'] : 0,
                'best_selling'      => isset($data['best_selling']) ? (int) $data['best_selling'] : 0,
                'is_new_arrival'    => isset($data['is_new_arrival']) ? (int) $data['is_new_arrival'] : 0,
                'todays_deal'       => isset($data['todays_deal']) ? (int) $data['todays_deal'] : 0,
                'is_variant'        => $isVariant,
                'unit'              => !empty($data['unit']) ? $data['unit'] : 'Pc',
                'barcode'           => $data['barcode'] ?? null,
                'video_link'        => $data['video_link'] ?? null,
                'badge_name'        => $data['badge_name'] ?? null,
                'batch_no'          => !empty($data['batch_no']) ? (int) $data['batch_no'] : 1,
                'position'          => !empty($data['position']) ? (int) $data['position'] : 0,
                'is_cat'            => false,
                'num_of_sale'       => 0,
                'stock_request'     => 0,
            ];

            // ---------- CREATE OR UPDATE PRODUCT ----------
            if ($existingProduct && $importMode === 'update') {
                $product = $existingProduct;
                unset($productData['slug']); // keep existing slug
                $product->update($productData);
                $action = 'update';
            } else {
                // Insert new product: if an ID is provided and not taken, set it
                $product = new Product($productData);
                if (!empty($data['id']) && is_numeric($data['id']) && !Product::where('id', $data['id'])->exists()) {
                    $product->id = (int) $data['id'];
                }
                $product->save();
                $action = 'insert';
            }

            // ---------- INVENTORY ----------
            $mainSku = !empty($data['sku']) ? $data['sku'] : $this->generateUniqueSku($product->name, $product->id);
            if (ProductInventory::where('sku', $mainSku)->exists()) {
                $mainSku = $this->generateUniqueSku($product->name, $product->id);
            }
            ProductInventory::updateOrCreate(
                ['product_id' => $product->id],
                [
                    'sku'            => $mainSku,
                    'barcode'        => $data['barcode'] ?? null,
                    'stock'          => (int) ($data['stock'] ?? 0),
                    'low_stock_qty'  => !empty($data['low_stock_qty']) ? (int) $data['low_stock_qty'] : 1,
                    'track_inventory' => 1,
                ]
            );

            // ---------- PRICE ----------
            $regularPrice = (float) ($data['regular_price'] ?? 0);
            $discount = (float) ($data['discount'] ?? 0);
            $discountType = !empty($data['discount_type']) && in_array($data['discount_type'], ['flat', 'percent'])
                ? $data['discount_type'] : 'percent';

            $salePrice = $discountType === 'flat'
                ? max(0, $regularPrice - $discount)
                : max(0, $regularPrice - (($regularPrice * $discount) / 100));

            ProductPrice::updateOrCreate(
                ['product_id' => $product->id],
                [
                    'purchase_price'   => !empty($data['purchase_price']) ? (float) $data['purchase_price'] : 0,
                    'regular_price'    => (int) round($regularPrice),
                    'sale_price'       => (int) round($salePrice),
                    'discount_type'    => $discountType,
                    'discount'         => $discount,
                    'wholesale_price'  => !empty($data['wholesale_price']) ? (float) $data['wholesale_price'] : 0,
                    'currency'         => 'BDT',
                ]
            );

            // ---------- SHIPPING ----------
            ProductShipping::updateOrCreate(
                ['product_id' => $product->id],
                [
                    'shipping_type' => $data['shipping_type'] ?? 'flat_rate',
                    'shipping_cost' => $data['shipping_cost'] ?? 0,
                    'weight'        => $data['weight'] ?? 0,
                    'length'        => $data['length'] ?? 0,
                    'width'         => $data['width'] ?? 0,
                    'height'        => $data['height'] ?? 0,
                ]
            );

            // ---------- SEO ----------
            $metaImageId = $this->getUploadId($data['meta_img'] ?? null, auth()->id());
            if (!empty($data['meta_title']) || !empty($data['meta_description']) || !empty($metaImageId)) {
                ProductSeo::updateOrCreate(
                    ['product_id' => $product->id],
                    [
                        'meta_title'       => $data['meta_title'] ?? null,
                        'meta_description' => $data['meta_description'] ?? null,
                        'meta_image'       => $metaImageId,
                    ]
                );
            }

            // ---------- HANDLE VARIANTS (FIXED) ----------
            if ($isVariant == 1) {
                if (!empty($variantAttributes) && is_array($variantAttributes)) {
                    if ($action === 'update') {
                        ProductVarient::where('product_id', $product->id)->delete();
                    }

                    $variantCount = 0;
                    foreach ($variantAttributes as $variant) {
                        $variantSku = !empty($variant['sku']) ? $variant['sku']
                            : $this->generateVariantSku($product->name, $product->id, $variantCount + 1);

                        if (ProductVarient::where('sku', $variantSku)->exists()) {
                            $variantSku = $this->generateVariantSku($product->name, $product->id, $variantCount + 1, true);
                        }

                        $variantPrice = isset($variant['price']) ? (float) $variant['price'] : (int) round($salePrice);
                        $variantWholesalePrice = isset($variant['wholesale_price'])
                            ? (float) $variant['wholesale_price']
                            : (float) ($data['wholesale_price'] ?? 0);

                        $variantQuantity = isset($variant['quantity']) && !empty($variant['quantity'])
                            ? (int) $variant['quantity']
                            : (int) ($data['stock'] ?? 0);

                        $variantImageId = $thumbnailId;
                        if (!empty($variant['image'])) {
                            $variantImageId = $this->processVariantImage($variant['image'], $thumbnailId, auth()->id());
                        }

                        // ---------- FIX: Ensure attribute_value is stored as JSON ----------
                        $attributes = $variant['attributes'] ?? null;
                        $variantAttributesValue = null;

                        if (is_array($attributes)) {
                            // Already an array, encode to JSON
                            $variantAttributesValue = json_encode($attributes);
                        } elseif (is_string($attributes)) {
                            // Try to decode as JSON; if valid, keep as is; otherwise wrap as {"value": string}
                            $decoded = json_decode($attributes, true);
                            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                                // It's a valid JSON object already → keep the string
                                $variantAttributesValue = $attributes;
                            } else {
                                // Not JSON → assume it's a single attribute value; wrap with a default key
                                // Or you can use a heuristic: if it contains ':', treat as key:value? For safety, we wrap.
                                // But better to expect the correct JSON from CSV.
                                // We'll store as {"attribute": "value"} – you can adjust key.
                                $variantAttributesValue = json_encode(['attribute' => $attributes]);
                            }
                        } else {
                            // No attributes provided – use a default JSON
                            $variantAttributesValue = json_encode(['Default' => 'Default Variant']);
                        }

                        ProductVarient::create([
                            'product_id'       => $product->id,
                            'attribute_value'  => $variantAttributesValue, // now always valid JSON
                            'sku'              => $variantSku,
                            'price'            => (int) round($variantPrice),
                            'quantity'         => $variantQuantity,
                            'image'            => $variantImageId,
                            'material'         => null,
                            'discount_type'    => $discountType,
                            'discount'         => $discount,
                            'wholesale_price'  => $variantWholesalePrice,
                        ]);
                        $variantCount++;
                        Log::info("Variant {$variantCount} created with SKU: {$variantSku}, attribute_value: {$variantAttributesValue}");
                    }
                    Log::info("Total {$variantCount} variants created for product ID {$product->id}");
                } else {
                    // Default variant (when no variant data provided)
                    $defaultVariantSku = $mainSku . '-VAR';
                    if (ProductVarient::where('sku', $defaultVariantSku)->exists()) {
                        $defaultVariantSku = $this->generateVariantSku($product->name, $product->id, 1, true);
                    }
                    ProductVarient::create([
                        'product_id'       => $product->id,
                        'attribute_value'  => json_encode(['Default' => 'Default Variant']), // JSON
                        'sku'              => $defaultVariantSku,
                        'price'            => (int) round($salePrice),
                        'quantity'         => (int) ($data['stock'] ?? 0),
                        'image'            => $thumbnailId,
                        'material'         => null,
                        'discount_type'    => $discountType,
                        'discount'         => $discount,
                    ]);
                    Log::info("Default variant created for product ID {$product->id} with SKU: {$defaultVariantSku}");
                }
            }

            // ---------- COMMIT ----------
            DB::commit();
            Log::info('Product imported successfully. Photos saved: ' . $photosJson);

            return ['success' => true, 'action' => $action, 'product_id' => $product->id];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error importing product: ' . $e->getMessage(), [
                'data'  => $data,
                'trace' => $e->getTraceAsString()
            ]);
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    private function updateImportProgress($importId, $data)
    {
        $progress = Cache::get('import_progress_' . $importId, []);
        $progress = array_merge($progress, $data);
        Cache::put('import_progress_' . $importId, $progress, 3600);
    }
}
