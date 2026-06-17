<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('reports')->orderBy('name')->paginate(20);
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:categories,name'],
            'slug' => ['nullable', 'string', 'max:100'],
            'icon' => ['nullable', 'string', 'max:50'],
            'description' => ['nullable', 'string', 'max:500'],
        ], [
            'name.required' => 'Nama kategori wajib diisi.',
            'name.unique'   => 'Nama kategori sudah ada.',
        ]);

        $slug = $validated['slug']
            ? Str::slug($validated['slug'])
            : Str::slug($validated['name']);

        // Pastikan slug unik
        $originalSlug = $slug;
        $counter = 1;
        while (Category::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter++;
        }

        Category::create([
            'name'        => $validated['name'],
            'slug'        => $slug,
            'icon'        => $validated['icon'] ?? 'bi-tag',
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()->route('admin.categories.index')
            ->with('success', "Kategori \"{$validated['name']}\" berhasil ditambahkan.");
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:categories,name,' . $category->id],
            'icon' => ['nullable', 'string', 'max:50'],
            'description' => ['nullable', 'string', 'max:500'],
        ], [
            'name.required' => 'Nama kategori wajib diisi.',
            'name.unique'   => 'Nama kategori sudah ada.',
        ]);

        $category->update([
            'name'        => $validated['name'],
            'icon'        => $validated['icon'] ?? $category->icon,
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()->route('admin.categories.index')
            ->with('success', "Kategori \"{$category->name}\" berhasil diperbarui.");
    }

    public function destroy(Category $category)
    {
        if ($category->reports()->count() > 0) {
            return back()->with('error', "Kategori \"{$category->name}\" tidak dapat dihapus karena masih digunakan oleh {$category->reports()->count()} laporan.");
        }

        $name = $category->name;
        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', "Kategori \"{$name}\" berhasil dihapus.");
    }
}
