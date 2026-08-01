<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SliderRequest;
use App\Models\Slider;
use Illuminate\Support\Facades\Storage;

class SliderController extends Controller
{
    public function index()
    {
        $sliders = Slider::latest()->get();
        return view('backend.sliders', compact('sliders'));
    }

    public function store(SliderRequest $request)
    {
        $this->authorize('manage', Slider::class);

        $slider = new Slider();

        if ($request->hasFile('slider1')) {
            $slider->slider1 = $request->file('slider1')->store('sliders', 'public');
        }

        if ($request->hasFile('slider2')) {
            $slider->slider2 = $request->file('slider2')->store('sliders', 'public');
        }

        $slider->save();

        return redirect()->back()->with('success', 'Slider created successfully!');
    }

    public function delete($id)
    {
        $slider = Slider::findOrFail($id);
        $this->authorize('manage', $slider);

        if ($slider->slider1 && Storage::disk('public')->exists($slider->slider1)) {
            Storage::disk('public')->delete($slider->slider1);
        }

        if ($slider->slider2 && Storage::disk('public')->exists($slider->slider2)) {
            Storage::disk('public')->delete($slider->slider2);
        }

        $slider->delete();

        return redirect()->back()->with('success', 'Slider deleted successfully!');
    }
}