<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductImportRequest;
use App\Jobs\ImportProductsJob;
use App\Models\Product;
use App\Models\ProductImport;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminProductImportController extends Controller
{
    /**
     * Rows at or below this threshold are imported inline so the admin gets
     * an immediate per-row report; larger files are dispatched to the queue.
     */
    protected int $inlineThreshold = 200;

    public function index()
    {
        $this->authorize('manage', Product::class);

        $imports = ProductImport::latest()->paginate(15);

        return view('backend.imports.index', compact('imports'));
    }

    public function store(ProductImportRequest $request)
    {
        $this->authorize('manage', Product::class);

        $file = $request->file('csv');
        // Unique name prevents collisions when two admins upload the same
        // filename; putFile creates the storage/app/private/imports dir.
        $name = now()->format('Ymd_His').'_'.Str::random(8).'.csv';
        $path = Storage::disk('local')->putFileAs('imports', $file, $name);
        $absolute = storage_path('app/private/'.$path);

        $import = ProductImport::create([
            'user_id' => auth()->id(),
            'file_name' => $file->getClientOriginalName(),
            'status' => 'pending',
        ]);

        // Count rows cheaply (lines minus header) to pick inline vs queued.
        $totalRows = max(0, count(file($absolute, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES)) - 1);

        if ($totalRows <= $this->inlineThreshold) {
            (new ImportProductsJob($import, $absolute))->handle();

            return redirect()->route('admin.products.import.report', $import)
                ->with('success', 'Import complete.');
        }

        ImportProductsJob::dispatch($import, $absolute);

        return redirect()->route('admin.products.import')
            ->with('success', 'Large import queued ('.$totalRows.' rows). It will process in the background.');
    }

    public function report(ProductImport $import)
    {
        $this->authorize('manage', Product::class);

        return view('backend.imports.report', compact('import'));
    }
}
