@extends('layouts.admin')
@section('content')
<h2 class="text-xl font-bold mb-6" style="font-family:var(--font-heading); color: var(--muni-red-dark);">Edit Section Pattern</h2>

@if($errors->any())
<div class="bg-red-50 border-l-4 p-4 mb-4 rounded-sm" style="border-color: var(--muni-red);">
    <ul class="text-sm text-red-800 list-disc ms-4">
        @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
    </ul>
</div>
@endif

<form method="POST" action="{{ route('admin.patterns.update', $pattern) }}" enctype="multipart/form-data">
@csrf @method('PUT')
<div class="max-w-3xl bg-white rounded-sm shadow-sm p-6 border space-y-5">
    <div>
        <label class="block text-sm font-bold mb-1">Section *</label>
        <select name="section_slug" id="section_slug" required class="w-full border-2 rounded-sm px-3 py-2" style="min-height:44px;">
            <option value="">Select a section…</option>
            @foreach($sectionOptions as $slug => $name)
                <option value="{{ $slug }}" {{ old('section_slug', $pattern->section_slug) === $slug ? 'selected' : '' }}>{{ $name }}</option>
            @endforeach
        </select>
        @error('section_slug')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
        <p class="text-xs text-gray-500 mt-2">Where on the site the pattern will appear. One setting per section.</p>
    </div>

    <div>
        <label class="block text-sm font-bold mb-1">Section Name</label>
        <input type="text" name="section_name" id="section_name" value="{{ old('section_name', $pattern->section_name) }}" class="w-full border-2 rounded-sm px-3 py-2" style="min-height:44px;">
        @error('section_name')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="block text-sm font-bold mb-1">Pattern Image</label>
        @if($pattern->image_path)
            <div class="w-28 h-28 rounded-sm overflow-hidden border mb-2" style="background:#8B0000;">
                <img src="{{ asset('storage/' . $pattern->image_path) }}" alt="{{ $pattern->section_name }}" class="w-full h-full object-cover">
            </div>
        @endif
        <input type="file" name="image" accept="image/png,image/jpeg,image/webp" class="w-full text-sm">
        <p class="text-xs text-gray-500 mt-2">Recommended: 800x800px seamless PNG with transparent background, or a high-res geometric JPG. Max 5MB. Leave empty to keep the current image.</p>
        @error('image')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="block text-sm font-bold mb-1">Opacity: <span id="opacityValue" class="text-gray-600">{{ number_format($pattern->opacity, 2) }}</span></label>
        <input type="range" name="opacity" id="opacity" min="0" max="1" step="0.05" value="{{ old('opacity', $pattern->opacity) }}" class="w-full" style="accent-color: var(--muni-red);">
        <p class="text-xs text-gray-500 mt-1">0.00 (invisible) to 1.00 (full strength). Lower values blend more subtly.</p>
        @error('opacity')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="block text-sm font-bold mb-1">Blend Mode</label>
        <select name="blend_mode" class="w-full border-2 rounded-sm px-3 py-2" style="min-height:44px;">
            @foreach(['multiply', 'overlay', 'screen', 'normal'] as $mode)
                <option value="{{ $mode }}" {{ old('blend_mode', $pattern->blend_mode) === $mode ? 'selected' : '' }}>{{ ucfirst($mode) }}</option>
            @endforeach
        </select>
        <p class="text-xs text-gray-500 mt-2">Multiply darkens with the section color and works best on light patterns; Overlay adds contrast; Screen lightens.</p>
        @error('blend_mode')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="flex items-center gap-2">
            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $pattern->is_active) ? 'checked' : '' }} class="rounded">
            <span class="text-sm font-bold">Active (visible on the site)</span>
        </label>
    </div>

    <div class="flex gap-3 pt-2 border-t border-gray-100">
        <button type="submit" class="btn-muni">Update Pattern</button>
        <a href="{{ route('admin.patterns.index') }}" class="btn btn-outline">Cancel</a>
    </div>
</div>
</form>

<script>
    const slider = document.getElementById('opacity');
    const output = document.getElementById('opacityValue');
    slider.addEventListener('input', () => { output.textContent = parseFloat(slider.value).toFixed(2); });
    const slug = document.getElementById('section_slug');
    const name = document.getElementById('section_name');
    const fallbackNames = {
        newsletter_cta: 'Newsletter CTA Section',
        hero: 'Hero Section',
        footer: 'Footer',
        about_header: 'About Page Header',
    };
    function autofillName() {
        if (!name.value.trim()) {
            name.value = fallbackNames[slug.value] ?? '';
        }
    }
    slug.addEventListener('change', autofillName);
</script>
@endsection