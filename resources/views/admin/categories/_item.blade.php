<li class="border rounded-sm" x-data="{ edit:false }" style="margin-left: {{ $depth*16 }}px;">
    <div class="flex items-center justify-between p-3 bg-gray-50">
        <div class="flex items-center gap-3">
            <span class="badge-muni text-xs" style="background: var(--muni-blue);">{{ $category->articles_count ?? 0 }} articles</span>
            <div>
                <p class="font-semibold text-sm" style="color: var(--muni-red-dark);">{{ $category->name }}</p>
                <p class="text-xs text-gray-500">/{{ $category->slug }} • Order: {{ $category->sort_order }}</p>
            </div>
        </div>
        <div class="flex gap-2">
            <button @click="edit=!edit" class="text-xs px-3 py-1 rounded-sm border hover:bg-white" style="min-height:32px;">Edit</button>
            <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" onsubmit="return confirm('Delete?')">
                @csrf @method('DELETE')
                <button class="text-xs px-3 py-1 rounded-sm bg-red-600 text-white">Delete</button>
            </form>
        </div>
    </div>
    <div x-show="edit" class="p-4 bg-white border-t" x-transition>
        <form method="POST" action="{{ route('admin.categories.update', $category) }}" class="space-y-2">
            @csrf @method('PUT')
            <div class="grid grid-cols-2 gap-2">
                <input type="text" name="name" value="{{ $category->name }}" required class="border-2 rounded-sm px-2 py-1 text-sm">
                <input type="text" name="slug" value="{{ $category->slug }}" class="border-2 rounded-sm px-2 py-1 text-sm">
            </div>
            <div class="grid grid-cols-2 gap-2">
                <select name="parent_id" class="border-2 rounded-sm px-2 py-1 text-sm bg-white">
                    <option value="">No parent</option>
                    @foreach($allCategories as $c)
                        @if($c->id !== $category->id)
                        <option value="{{ $c->id }}" {{ $category->parent_id==$c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                        @endif
                    @endforeach
                </select>
                <input type="number" name="sort_order" value="{{ $category->sort_order }}" class="border-2 rounded-sm px-2 py-1 text-sm">
            </div>
            <textarea name="description" rows="2" class="w-full border-2 rounded-sm px-2 py-1 text-sm">{{ $category->description }}</textarea>
            <button type="submit" class="text-xs px-3 py-1 rounded-sm" style="background: var(--muni-red); color:#fff;">Save</button>
        </form>
    </div>
    @if($category->children && $category->children->count())
        <ul class="space-y-2 p-2 bg-white">
            @foreach($category->children as $child)
                @include('admin.categories._item', ['category'=>$child, 'depth'=>$depth+1])
            @endforeach
        </ul>
    @endif
</li>
