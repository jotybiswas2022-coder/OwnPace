<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PlanChangeRequest;
use App\Models\ExchangeRequest;
use App\Models\ProductRequest;
use App\Models\AccountDeletionRequest;

class RequestController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * The account "requests" hub — every request the user has made, with its
     * review status and any admin note, in one place.
     */
    public function index()
    {
        $user = auth()->user();

        $planChanges = PlanChangeRequest::where('user_id', $user->id)
            ->with(['order', 'currentPlan', 'requestedPlan'])
            ->latest()->get();

        $exchanges = ExchangeRequest::where('user_id', $user->id)
            ->with(['order', 'currentProduct', 'requestedProduct'])
            ->latest()->get();

        $productRequests = ProductRequest::where('user_id', $user->id)
            ->latest()->get();

        $deletionRequest = AccountDeletionRequest::where('user_id', $user->id)
            ->latest()->first();

        return view('frontend.requests.index', compact('planChanges', 'exchanges', 'productRequests', 'deletionRequest'));
    }

    public function productForm()
    {
        return view('frontend.requests.product-create');
    }

    /**
     * A simple wishlist-style request: name, description, link and why they
     * want it. Tracked as submitted → under review → approved / rejected.
     */
    public function storeProduct(Request $request)
    {
        $request->validate([
            'product_name' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'product_url' => 'nullable|url|max:500',
            'reason' => 'nullable|string|max:1000',
        ]);

        ProductRequest::create([
            'user_id' => auth()->id(),
            'product_name' => $request->product_name,
            'description' => $request->description,
            'product_url' => $request->product_url,
            'reason' => $request->reason,
            'status' => 'submitted',
        ]);

        return redirect()->route('requests.index')
            ->with('success', "Product request submitted! We'll let you know once it's reviewed.");
    }
}
