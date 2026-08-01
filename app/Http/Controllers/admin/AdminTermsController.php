<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TermsRequest;
use App\Models\TermsAndCondition;

class AdminTermsController extends Controller
{
    public function index()
    {
        $terms = TermsAndCondition::all();
        return view('backend.terms.index', compact('terms'));
    }

    public function update(TermsRequest $request, TermsAndCondition $term)
    {
        $this->authorize('manage', $term);

        $term->update([
            'title' => $request->title,
            'content' => $request->content,
            'is_active' => $request->boolean('is_active'),
        ]);

        return back()->with('success', 'Terms updated successfully!');
    }
}
