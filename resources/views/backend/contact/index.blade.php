@extends('backend.layouts.console')
@section('title', 'Contacts — '.storeName().' Admin')
@section('page_title', 'Contact Messages')

@section('breadcrumbs')
    @include('backend.partials.breadcrumbs', ['crumbs' => [['label' => 'Content'], ['label' => 'Contacts']]])
@endsection

@section('content')
<div class="os-card overflow-hidden">
    <div class="flex items-center justify-between border-b border-ink/10 px-5 py-4">
        <div>
            <h3 class="font-display text-sm font-bold text-ink"><i class="bi bi-envelope text-brand"></i> Customer Messages</h3>
            <p class="mt-0.5 text-xs text-slate">{{ $contacts->count() ?? 0 }} total messages</p>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="os-table w-full">
            <thead>
                <tr><th>#</th><th>Name</th><th>Email</th><th>Message</th><th>Date</th><th>Time</th></tr>
            </thead>
            <tbody>
                @forelse ($contacts as $contact)
                <tr>
                    <td data-label="#" class="text-slate">{{ $loop->iteration }}</td>
                    <td data-label="Name" class="font-semibold text-ink">{{ $contact->name }}</td>
                    <td data-label="Email" class="text-xs text-slate">{{ $contact->email }}</td>
                    <td data-label="Message">
                        <div x-data="{ open: false }">
                            <button type="button" class="os-btn os-btn-ghost os-btn-sm" @click="open = true"><i class="bi bi-chat-dots text-brand"></i> View</button>

                            {{-- Message modal --}}
                            <div x-cloak x-show="open" class="fixed inset-0 z-50 flex items-center justify-center p-4" @keydown.escape.window="open = false">
                                <div class="absolute inset-0 bg-ink/40" @click="open = false" aria-hidden="true"></div>
                                <div class="relative w-full max-w-lg rounded-2xl bg-white shadow-2xl" role="dialog" aria-modal="true" aria-label="Message from {{ $contact->name }}">
                                    <div class="flex items-center justify-between border-b border-ink/10 px-6 py-4">
                                        <h5 class="font-display text-sm font-bold text-ink"><i class="bi bi-chat-dots mr-2 text-brand"></i> Message from {{ $contact->name }}</h5>
                                        <button type="button" class="rounded-lg p-1.5 text-slate transition-colors hover:bg-ink/5 hover:text-ink" @click="open = false" aria-label="Close">
                                            <i class="bi bi-x-lg"></i>
                                        </button>
                                    </div>
                                    <div class="max-h-[50vh] overflow-y-auto p-6 text-sm leading-relaxed text-slate">{{ $contact->message }}</div>
                                    <div class="flex justify-end border-t border-ink/10 px-6 py-4">
                                        <button type="button" class="os-btn os-btn-ghost os-btn-sm" @click="open = false">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td data-label="Date" class="text-xs text-slate">{{ \Carbon\Carbon::parse($contact->created_at)->timezone(config('app.timezone'))->format('d M Y') }}</td>
                    <td data-label="Time" class="text-xs text-slate">{{ \Carbon\Carbon::parse($contact->created_at)->timezone(config('app.timezone'))->format('h:i A') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="py-14 text-center">
                        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-mango/15 text-2xl text-mango-deep"><i class="bi bi-inbox"></i></div>
                        <p class="mt-4 font-semibold text-ink">No messages yet</p>
                        <p class="mt-1 text-sm text-slate">Customer enquiries from the contact form will appear here.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
