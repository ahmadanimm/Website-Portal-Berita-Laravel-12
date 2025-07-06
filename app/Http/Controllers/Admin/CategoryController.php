<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Str;


class CategoryController extends Controller
{

    public function index(Request $request)
    {
        $query = \App\Models\Category::query();

        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $categories = $query->orderBy('id', 'desc')->paginate(10);

        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'nullable|image|mimes:jpg,jpeg,png,svg|max:2048',
        ]);

        $iconPath = null;
        if ($request->hasFile('icon')) {
            $iconPath = $request->file('icon')->store('icons', 'public');
        }

        \App\Models\Category::create([
            'name' => $request->name,
            'slug' => \Str::slug($request->name),
            'icon' => $iconPath,
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function show(string $id)
    {
        
    }

    public function edit($id)
    {
        $category = \App\Models\Category::findOrFail($id);
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $category = \App\Models\Category::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'nullable|image|mimes:jpg,jpeg,png,svg|max:2048',
        ]);

        $data = ['name' => $request->name];

        if ($request->hasFile('icon')) {
            if ($category->icon) {
                \Storage::disk('public')->delete($category->icon);
            }
            $data['icon'] = $request->file('icon')->store('icons', 'public');
        }

        $data['slug'] = \Str::slug($request->name);

        $category->update($data);

        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil diperbarui.');
    }


    public function destroy(Request $request, $id = null)
    {
        $ids = $request->selected_ids ? explode(',', $request->selected_ids) : [$id];

        $categories = \App\Models\Category::whereIn('id', $ids)->get();

        foreach ($categories as $category) {
            if ($category->icon) {
                \Storage::disk('public')->delete($category->icon);
            }
            $category->delete();
        }

        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil dihapus.');
    }


    public function bulkDelete(Request $request)
    {
        $ids = explode(',', $request->selected_ids);

        $categories = \App\Models\Category::whereIn('id', $ids)->get();

        foreach ($categories as $category) {
            if ($category->icon) {
                \Storage::disk('public')->delete($category->icon);
            }
            $category->delete();
        }

        return redirect()->route('admin.categories.index')->with('success', 'Kategori terpilih berhasil dihapus.');
    }


    

}
