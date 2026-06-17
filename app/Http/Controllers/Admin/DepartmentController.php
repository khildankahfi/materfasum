<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::withCount('reports')->orderBy('name')->paginate(20);
        return view('admin.departments.index', compact('departments'));
    }

    public function create()
    {
        return view('admin.departments.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'    => ['required', 'string', 'max:200', 'unique:departments,name'],
            'code'    => ['nullable', 'string', 'max:20', 'unique:departments,code'],
            'phone'   => ['nullable', 'string', 'max:30'],
            'email'   => ['nullable', 'email', 'max:100'],
            'address' => ['nullable', 'string', 'max:500'],
        ], [
            'name.required' => 'Nama dinas wajib diisi.',
            'name.unique'   => 'Nama dinas sudah terdaftar.',
            'code.unique'   => 'Kode dinas sudah dipakai.',
            'email.email'   => 'Format email tidak valid.',
        ]);

        Department::create($validated);

        return redirect()->route('admin.departments.index')
            ->with('success', "Dinas \"{$validated['name']}\" berhasil ditambahkan.");
    }

    public function edit(Department $department)
    {
        return view('admin.departments.edit', compact('department'));
    }

    public function update(Request $request, Department $department)
    {
        $validated = $request->validate([
            'name'    => ['required', 'string', 'max:200', 'unique:departments,name,' . $department->id],
            'code'    => ['nullable', 'string', 'max:20', 'unique:departments,code,' . $department->id],
            'phone'   => ['nullable', 'string', 'max:30'],
            'email'   => ['nullable', 'email', 'max:100'],
            'address' => ['nullable', 'string', 'max:500'],
        ], [
            'name.required' => 'Nama dinas wajib diisi.',
            'name.unique'   => 'Nama dinas sudah terdaftar.',
            'code.unique'   => 'Kode dinas sudah dipakai.',
            'email.email'   => 'Format email tidak valid.',
        ]);

        $department->update($validated);

        return redirect()->route('admin.departments.index')
            ->with('success', "Dinas \"{$department->name}\" berhasil diperbarui.");
    }

    public function destroy(Department $department)
    {
        $count = $department->reports()->count();
        if ($count > 0) {
            return back()->with('error', "Dinas \"{$department->name}\" tidak dapat dihapus karena masih menangani {$count} laporan aktif.");
        }

        $name = $department->name;
        $department->delete();

        return redirect()->route('admin.departments.index')
            ->with('success', "Dinas \"{$name}\" berhasil dihapus.");
    }
}
