@extends('layouts.admin')
@section('content')
<h2 class="text-xl font-bold mb-6" style="font-family:var(--font-heading); color: var(--muni-red-dark);">Reports & Analytics</h2>

<div class="grid md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-sm shadow-sm p-5 border-t-4" style="border-color: var(--muni-red);">
        <p class="text-xs font-bold uppercase tracking-wider text-gray-500">Total Articles</p>
        <p class="text-2xl font-bold" style="color: var(--muni-red-dark);">{{ $stats['articles'] }}</p>
        <p class="text-xs text-gray-500">{{ $stats['published'] }} published • {{ $stats['drafts'] }} drafts</p>
    </div>
    <div class="bg-white rounded-sm shadow-sm p-5 border-t-4" style="border-color: var(--muni-blue);">
        <p class="text-xs font-bold uppercase tracking-wider text-gray-500">Total Views</p>
        <p class="text-2xl font-bold" style="color: var(--muni-red-dark);">{{ number_format($stats['views']) }}</p>
        <p class="text-xs text-gray-500">Across all articles</p>
    </div>
    <div class="bg-white rounded-sm shadow-sm p-5 border-t-4" style="border-color: var(--muni-gold);">
        <p class="text-xs font-bold uppercase tracking-wider text-gray-500">Comments</p>
        <p class="text-2xl font-bold" style="color: var(--muni-red-dark);">{{ $stats['comments'] }}</p>
        <p class="text-xs text-gray-500">{{ $stats['pending_comments'] }} pending</p>
    </div>
    <div class="bg-white rounded-sm shadow-sm p-5 border-t-4" style="border-color: #00b9f1;">
        <p class="text-xs font-bold uppercase tracking-wider text-gray-500">Subscribers</p>
        <p class="text-2xl font-bold" style="color: var(--muni-red-dark);">{{ $stats['subscribers'] }}</p>
        <p class="text-xs text-gray-500">Newsletter</p>
    </div>
</div>

<div class="grid lg:grid-cols-2 gap-6 mb-6">
    <div class="bg-white rounded-sm shadow-sm border">
        <div class="px-4 py-3 border-b-2 flex items-center gap-2" style="border-color: var(--muni-gold); background: var(--muni-red-dark);">
            <h3 class="font-bold text-white text-sm uppercase">Top Articles by Views</h3>
        </div>
        <div class="divide-y divide-gray-100">
            @forelse($topArticles as $idx=>$art)
            <div class="p-4 flex items-center gap-3">
                <span class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold text-white" style="background: {{ $idx==0 ? 'var(--muni-gold)' : 'var(--muni-red)' }}; color: {{ $idx==0 ? 'var(--muni-red-dark)' : '#fff' }};">{{ $idx+1 }}</span>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold truncate">{{ $art->title }}</p>
                    <p class="text-xs text-gray-500">{{ Str::limit($art->slug,30) }}</p>
                </div>
                <span class="font-bold text-sm" style="color: var(--muni-red-dark);">{{ $art->views }} <i class="fa-solid fa-eye ms-1 text-xs"></i></span>
            </div>
            @empty
            <p class="p-8 text-center text-gray-500">No data.</p>
            @endforelse
        </div>
    </div>

    <div class="bg-white rounded-sm shadow-sm border">
        <div class="px-4 py-3 border-b-2" style="border-color: var(--muni-gold); background: var(--muni-red-dark);">
            <h3 class="font-bold text-white text-sm uppercase">Views by Day (Last 7)</h3>
        </div>
        <div class="p-4 space-y-3">
            @forelse($viewsByDay as $row)
            <div class="flex items-center gap-3">
                <span class="text-xs font-mono w-24">{{ $row->date }}</span>
                <div class="flex-1 bg-gray-100 rounded-sm h-6 overflow-hidden">
                    <div class="h-6 flex items-center justify-end pe-2 text-xs font-bold text-white" style="width: {{ min(100, $row->count * 10) }}%; background: var(--muni-blue);">{{ $row->count }}</div>
                </div>
            </div>
            @empty
            <p class="text-center text-gray-500">No recent views.</p>
            @endforelse
        </div>
    </div>
</div>

<div class="grid lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-sm shadow-sm border">
        <div class="px-4 py-3 border-b-2" style="border-color: var(--muni-gold); background: var(--muni-red-dark);">
            <h3 class="font-bold text-white text-sm uppercase">Articles per Category</h3>
        </div>
        <div class="p-4 space-y-2">
            @foreach($categoryStats as $cs)
            <div class="flex justify-between items-center">
                <span class="text-sm">{{ $cs->name }}</span>
                <span class="px-2 py-1 rounded-sm text-xs font-bold" style="background: var(--color-bg-secondary);">{{ $cs->count }}</span>
            </div>
            @endforeach
        </div>
    </div>

    <div class="bg-white rounded-sm shadow-sm border">
        <div class="px-4 py-3 border-b-2" style="border-color: var(--muni-gold); background: var(--muni-red-dark);">
            <h3 class="font-bold text-white text-sm uppercase">Recent Device Views</h3>
        </div>
        <div class="divide-y divide-gray-100 max-h-80 overflow-y-auto">
            @forelse($recentViews as $rv)
            <div class="p-3 flex justify-between items-center hover:bg-gray-50">
                <div>
                    <p class="text-sm font-semibold truncate max-w-[200px]">{{ $rv->article->title ?? 'Deleted' }}</p>
                    <p class="text-xs text-gray-500">{{ $rv->ip_address }} • {{ Str::limit($rv->device_fingerprint,12) }}</p>
                </div>
                <span class="text-xs text-gray-500">{{ $rv->viewed_at->diffForHumans() }}</span>
            </div>
            @empty
            <p class="p-8 text-center text-gray-500">No views yet.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
