<?php

namespace App\Jobs;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductImport;
use App\Models\Supplier;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

/**
 * Imports products from a CSV file, validating row by row and recording a
 * per-row success/error report on the ProductImport record. Dispatched for
 * large files so the admin request never blocks; small files are processed
 * inline by the controller (reusing this same handle()).
 *
 * Expected CSV columns (header row):
 *   Product Name, Price, Description, Category, Images (URLs),
 *   Supplier Name, Supplier Phone, Supplier Email, Supplier Address
 */
class ImportProductsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public ProductImport $import, public string $path)
    {
        //
    }

    /**
     * Columns that must be present in the CSV header for the import to run.
     * Only Product Name and Price are truly required — Description, Category,
     * Images and all Supplier columns are optional (importRow() skips blanks).
     */
    protected const REQUIRED_COLUMNS = ['Product Name', 'Price'];

    public function handle(): void
    {
        $this->import->update(['status' => 'processing']);

        $report = [];
        $success = 0;
        $errors = 0;

        try {
            $rows = $this->readCsv($this->path);

            // Fail fast on a wrong/missing header instead of an all-errors report.
            $missing = array_values(array_diff(self::REQUIRED_COLUMNS, array_keys($rows[0] ?? [])));
            if ($missing) {
                throw new \RuntimeException(
                    'CSV is missing required columns: '.implode(', ', $missing)
                    .'. Expected at minimum: Product Name, Price. Optional: Description, Category, Images (URLs), Supplier Name, Supplier Phone, Supplier Email, Supplier Address'
                );
            }

            $this->import->update(['total_rows' => count($rows)]);

            foreach ($rows as $index => $row) {
                $lineNumber = $index + 2; // 1 = header
                try {
                    $this->importRow($row);
                    $success++;
                    $report[] = ['row' => $lineNumber, 'status' => 'success', 'message' => 'Imported'];
                } catch (\Throwable $e) {
                    $errors++;
                    $report[] = ['row' => $lineNumber, 'status' => 'error', 'message' => $e->getMessage()];
                }
            }

            $this->import->update([
                'status' => 'completed',
                'success_rows' => $success,
                'error_rows' => $errors,
                'report' => $report,
            ]);
        } catch (\Throwable $e) {
            $this->import->update([
                'status' => 'failed',
                'error' => $e->getMessage(),
            ]);
        } finally {
            if (file_exists($this->path)) {
                @unlink($this->path);
            }
        }
    }

    /**
     * Read a CSV file into an array of associative rows using the header row
     * as keys. Handles BOM, CRLF and quoted fields.
     *
     * @return array<int, array<string, string>>
     */
    protected function readCsv(string $path): array
    {
        $rows = [];
        $header = null;

        $handle = fopen($path, 'r');
        if ($handle === false) {
            throw new \RuntimeException('Could not open uploaded CSV file.');
        }

        try {
            while (($data = fgetcsv($handle)) !== false) {
                if ($data === [null]) {
                    continue; // blank line
                }

                // Strip UTF-8 BOM from the first header cell.
                if ($header === null) {
                    $data[0] = preg_replace('/^\xEF\xBB\xBF/', '', (string) ($data[0] ?? ''));
                    $header = array_map('trim', $data);
                    continue;
                }

                $row = [];
                foreach ($header as $i => $key) {
                    $row[$key] = trim((string) ($data[$i] ?? ''));
                }
                // Only keep rows with at least a product name.
                if (($row['Product Name'] ?? '') !== '') {
                    $rows[] = $row;
                }
            }
        } finally {
            fclose($handle);
        }

        return $rows;
    }

    /**
     * Create (or update) one product from a single CSV row.
     *
     * @param array<string, string> $row
     */
    protected function importRow(array $row): void
    {
        $name = trim($row['Product Name'] ?? '');
        $price = (float) ($row['Price'] ?? 0);

        if ($name === '') {
            throw new \InvalidArgumentException('Product Name is required');
        }
        if ($price <= 0) {
            throw new \InvalidArgumentException('Price must be a positive number');
        }

        // Category — find by name or create.
        $category = null;
        if (trim($row['Category'] ?? '') !== '') {
            $category = Category::firstOrCreate(['name' => trim($row['Category'])]);
        }

        // Supplier — find by name/email/phone or create (admin-only data).
        $supplier = null;
        $supplierName = trim($row['Supplier Name'] ?? '');
        if ($supplierName !== '') {
            $supplier = Supplier::where('name', $supplierName)->first();
            if (! $supplier) {
                $supplier = Supplier::create([
                    'name' => $supplierName,
                    'email' => trim($row['Supplier Email'] ?? '') ?: null,
                    'phone' => trim($row['Supplier Phone'] ?? '') ?: null,
                    'address' => trim($row['Supplier Address'] ?? '') ?: null,
                ]);
            }
        }

        $slug = Str::slug($name);
        $counter = 1;
        while (Product::withTrashed()->where('slug', $slug)->exists()) {
            $slug = Str::slug($name).'-'.$counter++;
        }

        $product = Product::create([
            'name' => $name,
            'slug' => $slug,
            'description' => trim($row['Description'] ?? '') ?: null,
            'category_id' => $category?->id,
            'supplier_id' => $supplier?->id,
            'price' => $price,
            'base_price' => $price,
            'stock_quantity' => 0,
            'status' => 'active',
            'featured' => false,
        ]);

        // Images — pipe- or comma-separated URLs, first becomes primary.
        $imageUrls = array_values(array_filter(array_map('trim', preg_split(
            '/[|,]/',
            (string) ($row['Images (URLs)'] ?? '')
        ))));

        foreach ($imageUrls as $i => $url) {
            if (! preg_match('/^https?:\/\//i', $url)) {
                continue;
            }
            ProductImage::create([
                'product_id' => $product->id,
                'image_path' => $url,
                'is_primary' => $i === 0,
                'sort_order' => $i,
            ]);
        }
    }
}
