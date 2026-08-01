<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\FaqRequest;
use App\Models\Faq;

class AdminFaqController extends Controller
{
    public function index()
    {
        $faqs = Faq::orderBy('sort_order')->paginate(20);
        return view('backend.faqs.index', compact('faqs'));
    }

    public function store(FaqRequest $request)
    {
        $this->authorize('manage', Faq::class);

        Faq::create([
            'question' => $request->question,
            'answer' => $request->answer,
            'category' => $request->category,
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => $request->boolean('is_active'),
        ]);

        return back()->with('success', 'FAQ created successfully!');
    }

    public function update(FaqRequest $request, Faq $faq)
    {
        $this->authorize('manage', $faq);

        $faq->update([
            'question' => $request->question,
            'answer' => $request->answer,
            'category' => $request->category,
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => $request->boolean('is_active'),
        ]);

        return back()->with('success', 'FAQ updated successfully!');
    }

    public function destroy(Faq $faq)
    {
        $this->authorize('manage', $faq);

        $faq->delete();
        return back()->with('success', 'FAQ deleted!');
    }
}
