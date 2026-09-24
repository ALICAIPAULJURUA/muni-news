@extends('layouts.admin')
@section('content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-xl font-bold" style="font-family:var(--font-heading); color: var(--muni-red-dark);">Downloads</h2>
    <a href="{{ route('admin.downloads.create') }}" class="btn-muni text-sm"><i class="fa-solid fa-plus me-1"></i> New</a>
</div>
<div class="bg-white rounded-sm shadow-sm border overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-white text-xs uppercase" style="background: var(--muni-red-dark);">
                    <th class="px-4 py-3 text-left" style="border-bottom:3px solid var(--muni-gold);">Title</th>
                    <th class="px-4 py-3 text-left" style="border-bottom:3px solid var(--muni-gold);">Category</th>
                    <th class="px-4 py-3 text-left" style="border-bottom:3px solid var(--muni-gold);">Downloads</th>
                    <th class="px-4 py-3 text-right" style="border-bottom:3px solid var(--muni-gold);">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($downloads as $dl)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 font-semibold" style="color: var(--muni-red-dark);">{{ $dl->title }}<div class="text-xs text-gray-500">{{ $dl->slug }}</div></td>
                    <td class="px-4 py-3"><span class="badge-muni text-xs">{{ $dl->category }}</span></td>
                    <td class="px-4 py-3">{{ $dl->download_count }}</td>
                    <td class="px-4 py-3 text-right">
                        <a href="{{ route('admin.downloads.edit', $dl) }}" class="text-xs px-2 py-1 rounded-sm" style="background: var(--muni-blue); color:#fff;">Edit</a>
                        <form method="POST" action="{{ route('admin.downloads.destroy', $dl) }}" class="inline" onsubmit="return confirm('Delete?')">@csrf @method('DELETE') <button class="text-xs px-2 py-1 rounded-sm bg-red-600 text-white">Delete</button></form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-4 py-12 text-center text-gray-500">No downloads.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-4 border-t">{{ $downloads->links('pagination::bootstrap-5') }}</div>
</div>
@endsection
