<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PostRequest;
use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    // ================= INDEX =================
    public function index()
    {
        // সব workers দেখাও — user_id আছে বা নেই উভয়ই
        $posts      = Post::with('user')->latest()->get();
        $categories = Category::all();

        return view('backend.posts.index', compact('posts', 'categories'));
    }

    // ================= CREATE =================
    public function create()
    {
        $categories = Category::all();
        return view('backend.posts.create', compact('categories'));
    }

    // ================= STORE =================
    public function store(PostRequest $request)
    {
        $this->authorize('manage', Post::class);

        $filePath = null;

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $mime = $file->getMimeType();

            if (!in_array($mime, ['video/mp4', 'image/jpeg', 'image/png', 'image/gif', 'image/webp'])) {
                return back()->with('error', 'Only Image and MP4 video allowed!');
            }

            $filePath = $file->store('posts', 'public');
        }

        Post::create([
            'title'          => $request->title,
            'category_id'    => $request->category_id,
            'contact_number' => $request->contact_number,
            'division'       => $request->division,
            'status'         => (int) ($request->status ?? 0),
            'file'           => $filePath,
        ]);

        return redirect('/admin/workers')->with('success', 'Worker added successfully!');
    }

    // ================= UPDATE =================
    public function update(PostRequest $request, $id)
    {
        $post = Post::findOrFail($id);
        $this->authorize('manage', $post);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $mime = $file->getMimeType();

            if (!in_array($mime, ['video/mp4', 'image/jpeg', 'image/png', 'image/gif', 'image/webp'])) {
                return response()->json(['status' => false, 'message' => 'Invalid file type']);
            }

            if ($post->file && Storage::disk('public')->exists($post->file)) {
                Storage::disk('public')->delete($post->file);
            }

            $post->file = $file->store('posts', 'public');
        }

        $post->update([
            'title'          => $request->title,
            'category_id'    => $request->category_id,
            'contact_number' => $request->contact_number,
            'division'       => $request->division,
            'status'         => (int) ($request->status ?? 0),
        ]);

        return redirect('/admin/workers')->with('success', 'Worker updated successfully!');
    }

    // ================= DELETE =================
    public function delete($id)
    {
        $post = Post::findOrFail($id);
        $this->authorize('manage', $post);

        if ($post->file && Storage::disk('public')->exists($post->file)) {
            Storage::disk('public')->delete($post->file);
        }

        $post->delete();

        return redirect('/admin/workers')->with('success', 'Worker deleted successfully!');
    }

    // ================= VIEW FILE =================
    public function viewFile($id)
    {
        $post = Post::findOrFail($id);

        if (!$post->file) {
            abort(404);
        }

        $filePath = storage_path('app/public/' . $post->file);

        if (!file_exists($filePath)) {
            abort(404);
        }

        return response()->file($filePath);
    }
}
