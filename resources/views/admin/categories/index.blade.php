@extends('layouts.admin')
@section('content')
<h2 class="text-xl font-bold mb-6" style="font-family:var(--font-heading); color: var(--muni-red-dark);">Categories</h2>

<div class="grid lg:grid-cols-3 gap-6">
    <div class="bg-white rounded-sm shadow-sm p-6 border">
        <h3 class="font-bold mb-3" style="color: var(--muni-red-dark);">Add Category</h3>
        <form method="POST" action="{{ route('admin.categories.store') }}" class="space-y-3">
            @csrf
            <div>
                <label class="text-sm font-bold">Name *</label>
                <input type="text" name="name" required value="{{ old('name') }}" class="w-full border-2 rounded-sm px-3 py-2" style="min-height:44px;" placeholder="e.g. News">
            </div>
            <div>
                <label class="text-sm font-bold">Slug (auto)</label>
                <input type="text" name="slug" value="{{ old('slug') }}" class="w-full border-2 rounded-sm px-3 py-2 text-sm" placeholder="auto">
            </div>
            <div>
                <label class="text-sm font-bold">Parent</label>
                <select name="parent_id" class="w-full border-2 rounded-sm px-3 py-2 bg-white" style="min-height:44px;">
                    <option value="">-- No parent --</option>
                    @foreach($allCategories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-sm font-bold">Description</label>
                <textarea name="description" rows="2" class="w-full border-2 rounded-sm px-3 py-2">{{ old('description') }}</textarea>
            </div>
            <div>
                <label class="text-sm font-bold">Sort Order</label>
                <input type="number" name="sort_order" value="{{ old('sort_order',0) }}" class="w-full border-2 rounded-sm px-3 py-2">
            </div>
            <button type="submit" class="btn-muni w-full">Create</button>
        </form>
    </div>

    <div class="lg:col-span-2 bg-white rounded-sm shadow-sm p-6 border">
        <h3 class="font-bold mb-3" style="color: var(--muni-red-dark);">Category Tree</h3>
        @if($tree->count())
            <ul class="space-y-2">
                @foreach($tree as $cat)
                    @include('admin.categories._item', ['category'=>$cat, 'depth'=>0])
                @endforeach
            </ul>
        @else
            <p class="text-gray-500">No categories.</p>
        @endif
    </div>
</div>
@endsection
