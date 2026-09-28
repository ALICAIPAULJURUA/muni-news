@extends('layouts.admin')
@section('content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-xl font-bold" style="font-family:var(--font-heading); color: var(--muni-red-dark);">Section Patterns</h2>
    <a href="{{ route('admin.patterns.create') }}" class="btn-muni text-sm"><i class="fa-solid fa-plus me-1"></i> New</a>
</div>

<div class="flex items-center gap-2 mb-4 text-xs text-gray-500">
    <i class="fa-solid fa-circle-info"></i>
    <span>Upload a pattern image once, then assign it to a section. The frontend blends it over the section's background color using opacity + blend mode. Toggle Active off to remove it from the site instantly.</span>
</div>

<div class="bg-white rounded-sm shadow-sm border overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-white text-xs uppercase" style="background: var(--muni-red-dark);">
                    <th class="px-4 py-3 text-left" style="border-bottom:3px solid var(--muni-gold);">Preview</th>
                    <th class="px-4 py-3 text-left" style="border-bottom:3px solid var(--muni-gold);">Section</th>
                    <th class="px-4 py-3 text-left" style="border-bottom:3px solid var(--muni-gold);">Opacity</th>
                    <th class="px-4 py-3 text-left" style="border-bottom:3px solid var(--muni-gold);">Blend</th>
                    <th class="px-4 py-3 text-left" style="border-bottom:3px solid var(--muni-gold);">Status</th>
                    <th class="px-4 py-3 text-right" style="border-bottom:3px solid var(--muni-gold);">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($patterns as $pattern)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3">
                        @if($pattern->image_path)
                            <div class="w-16 h-16 rounded-sm overflow-hidden border" style="background:#8B0000;">
                                <img src="{{ asset('storage/' . $pattern->image_path) }}" alt="{{ $pattern->section_name }}" class="w-full h-full object-cover">
                            </div>
                        @else
                            <span class="text-xs text-gray-400">No image</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <span class="font-semibold" style="color: var(--muni-red-dark);">{{ $pattern->section_name }}</span>
                        <div class="text-xs text-gray-500">{{ $pattern->section_slug }}</div>
                    </td>
                    <td class="px-4 py-3">{{ $pattern->opacity }}</td>
                    <td class="px-4 py-3">
                        <span class="text-xs px-2 py-1 rounded-sm border capitalize">{{ $pattern->blend_mode }}</span>
                    </td>
                    <td class="px-4 py-3">
                        @if($pattern->is_active)
                            <span class="text-[10px] uppercase px-2 py-0.5 rounded-full" style="background:#dcfce7; color:#15803d;">Active</span>
                        @else
                            <span class="text-[10px] uppercase px-2 py-0.5 rounded-full" style="background:#fee2e2; color:#b91c1c;">Inactive</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-right">
                        <a href="{{ route('admin.patterns.edit', $pattern) }}" class="text-xs px-2 py-1 rounded-sm" style="background: var(--muni-blue); color:#fff;">Edit</a>
                        <form method="POST" action="{{ route('admin.patterns.destroy', $pattern) }}" class="inline" data-secure-delete>@csrf @method('DELETE') <button type="submit" class="btn-secure-delete text-xs px-2 py-1 rounded-sm bg-red-600 text-white ms-1">Delete</button></form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-4 py-12 text-center text-gray-500">No section patterns yet. <a href="{{ route('admin.patterns.create') }}" class="underline" style="color: var(--muni-blue);">Create the first one</a>.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection