@extends('layouts.admin')
@section('content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-xl font-bold" style="font-family:var(--font-heading); color: var(--muni-red-dark);">Subscribers</h2>
    <a href="{{ route('admin.subscribers.export') }}" class="btn-muni text-sm"><i class="fa-solid fa-file-csv me-1"></i> Export CSV</a>
</div>

<div class="bg-white rounded-sm shadow-sm border overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-white text-xs uppercase" style="background: var(--muni-red-dark);">
                    <th class="px-4 py-3 text-left" style="border-bottom:3px solid var(--muni-gold);">Email</th>
                    <th class="px-4 py-3 text-left" style="border-bottom:3px solid var(--muni-gold);">Status</th>
                    <th class="px-4 py-3 text-left" style="border-bottom:3px solid var(--muni-gold);">Subscribed</th>
                    <th class="px-4 py-3 text-right" style="border-bottom:3px solid var(--muni-gold);">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($subscribers as $sub)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 font-semibold">{{ $sub->email }}</td>
                    <td class="px-4 py-3">@if($sub->is_active)<span class="px-2 py-1 rounded-sm text-xs font-bold bg-green-600 text-white">Active</span> @else <span class="px-2 py-1 rounded-sm text-xs font-bold bg-gray-400 text-white">Inactive</span>@endif</td>
                    <td class="px-4 py-3 text-xs">{{ $sub->subscribed_at?->format('M d, Y H:i') ?? $sub->created_at->format('M d, Y') }}</td>
                    <td class="px-4 py-3 text-right">
                        <form method="POST" action="{{ route('admin.subscribers.destroy', $sub) }}" class="inline" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button class="text-xs px-2 py-1 rounded-sm bg-red-600 text-white">Delete</button></form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-4 py-12 text-center text-gray-500">No subscribers.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-4 border-t">{{ $subscribers->links('pagination::bootstrap-5') }}</div>
</div>
@endsection
