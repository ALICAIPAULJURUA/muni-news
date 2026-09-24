<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        $categories = Category::with('parent')->withCount('articles')->orderBy('sort_order')->get();
        // Build tree for display
        $tree = $this->buildTree($categories);
        $allCategories = Category::orderBy('name')->get();
        return view('admin.categories.index', compact('categories','tree','allCategories'));
    }

    private function buildTree($categories, $parentId=null)
    {
        $branch = [];
        foreach($categories as $cat){
            if($cat->parent_id == $parentId){
                $children = $this->buildTree($categories, $cat->id);
                if($children) $cat->children = $children;
                else $cat->children = collect();
                $branch[] = $cat;
            }
        }
        return collect($branch);
    }

    public function store(CategoryRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['slug'] = !empty($data['slug']) ? Str::slug($data['slug']) : Str::slug($data['name']);
        $orig = $data['slug']; $i=1;
        while(Category::where('slug',$data['slug'])->exists()){ $data['slug']=$orig.'-'.$i++; }
        Category::create($data);
        return back()->with('success','Category created.');
    }

    public function update(CategoryRequest $request, Category $category): RedirectResponse
    {
        $data = $request->validated();
        $data['slug'] = !empty($data['slug']) ? Str::slug($data['slug']) : Str::slug($data['name']);
        $orig = $data['slug']; $i=1;
        while(Category::where('slug',$data['slug'])->where('id','!=',$category->id)->exists()){ $data['slug']=$orig.'-'.$i++; }
        $category->update($data);
        return back()->with('success','Category updated.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        if($category->children()->count() || $category->articles()->count()){
            return back()->with('error','Cannot delete category with children or articles.');
        }
        $category->delete();
        return back()->with('success','Category deleted.');
    }
}
