@extends('backend.layouts.console')
@section('title', 'Posts — '.storeName().' Admin')
@section('page_title', 'Posts')

@section('breadcrumbs')
    @include('backend.partials.breadcrumbs', ['crumbs' => [['label' => 'Content'], ['label' => 'Posts']]])
@endsection

@section('content')
<div class="os-card overflow-hidden">
    <div class="flex flex-wrap items-center justify-between gap-3 border-b border-ink/10 px-5 py-4">
        <div>
            <h3 class="font-display text-sm font-bold text-ink">All Posts</h3>
            <p class="mt-0.5 text-xs text-slate">{{ $posts->count() ?? 0 }} posts</p>
        </div>
        <a href="{{ route('admin.posts.create') }}" class="os-btn os-btn-brand os-btn-sm"><i class="bi bi-plus-lg"></i> New Post</a>
    </div>
    <div class="overflow-x-auto">
        <table class="os-table w-full">
            <thead>
                <tr><th>#</th><th>Title</th><th>Category</th><th>Date</th><th class="w-28">Actions</th></tr>
            </thead>
            <tbody>
                @forelse($posts ?? [] as $p)
                <tr>
                    <td data-label="#" class="text-slate">{{ $loop->iteration }}</td>
                    <td data-label="Title" class="font-semibold text-ink">{{ Str::limit($p->title, 50) }}</td>
                    <td data-label="Category" class="text-slate">{{ $p->PostCategory?->name ?: '—' }}</td>
                    <td data-label="Date" class="text-xs text-slate">{{ $p->created_at->format('M d, Y') }}</td>
                    <td data-label="Actions" class="whitespace-nowrap">
                        <div class="flex gap-2">
                            <a href="{{ url('admin/posts/edit/'.$p->id) }}" class="os-btn os-btn-ghost os-btn-sm" title="Edit"><i class="bi bi-pencil"></i></a>
                            <a href="{{ url('admin/posts/delete/'.$p->id) }}" class="os-btn os-btn-danger os-btn-sm" title="Delete" onclick="return confirm('Delete this post?')"><i class="bi bi-trash"></i></a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-14 text-center">
                        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-mango/15 text-2xl text-mango-deep"><i class="bi bi-journal-text"></i></div>
                        <p class="mt-4 font-semibold text-ink">No posts yet</p>
                        <p class="mt-1 text-sm text-slate">Publish your first post to share updates with customers.</p>
                        <a href="{{ route('admin.posts.create') }}" class="os-btn os-btn-brand os-btn-sm mt-4"><i class="bi bi-plus-lg"></i> New Post</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
