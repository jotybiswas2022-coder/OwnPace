<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    // List all categories
    public function index()
    {
        $categories = Category::all();
        return view('backend.category.index', compact('categories'));
    }

    // Show create form
    public function create()
    {
        return view('backend.category.create');
    }

    // Show edit form (dedicated page — replaces the old Bootstrap modal)
    public function edit(Category $category)
    {
        return view('backend.category.edit', compact('category'));
    }

    // Store new category
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        Category::create(['name' => $request->name]);

        return redirect('admin/category')->with('success', 'Category added successfully!');
    }

    // Delete category
    public function delete($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return redirect('/admin/category')->with('success', 'Category deleted successfully!');
    }

    // Update category
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $category = Category::findOrFail($id);
        $category->name = $request->name;
        $category->save();

        return redirect('/admin/category')->with('success', 'Category updated successfully!');
    }
}