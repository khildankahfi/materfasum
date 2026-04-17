<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\ReportPhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $query = Report::where('user_id', Auth::id())->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title',    'like', "%{$search}%")
                ->orWhere('location', 'like', "%{$search}%");
            });
        }

        $reports    = $query->with('photos')->paginate(10);
        $categories = Report::categories();

        return view('user.reports.index', compact('reports', 'categories'));
    }

    public function create()
    {
        $categories = Report::categories();
        return view('user.reports.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'min:20'],
            'location'    => ['required', 'string', 'max:500'],
            'category'    => ['required', 'in:' . implode(',', array_keys(Report::categories()))],
            'photos'      => ['nullable', 'array', 'max:5'],
            'photos.*'    => ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'latitude'    => ['nullable', 'numeric'],
            'longitude'   => ['nullable', 'numeric'],
        ], [
            'title.required'       => 'Judul laporan wajib diisi.',
            'description.required' => 'Deskripsi wajib diisi.',
            'description.min'      => 'Deskripsi minimal 20 karakter.',
            'location.required'    => 'Lokasi wajib diisi.',
            'category.required'    => 'Kategori wajib dipilih.',
            'photos.max'           => 'Maksimal 5 foto.',
            'photos.*.image'       => 'Setiap file harus berupa gambar.',
            'photos.*.max'         => 'Ukuran setiap foto maksimal 5MB.',
        ]);

        $report = Report::create([
            'user_id'     => Auth::id(),
            'title'       => $validated['title'],
            'description' => $validated['description'],
            'location'    => $validated['location'],
            'category'    => $validated['category'],
            'latitude'    => $validated['latitude'] ?? null,
            'longitude'   => $validated['longitude'] ?? null,
            'status'      => 'menunggu',
        ]);

        // Simpan foto-foto
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $i => $file) {
                $path = $file->store('reports', 'public');
                ReportPhoto::create([
                    'report_id' => $report->id,
                    'path'      => $path,
                    'order'     => $i,
                ]);
            }
        }

        return redirect()->route('user.reports.index')
            ->with('success', 'Laporan berhasil dikirim! Kami akan segera memproses laporan Anda.');
    }

    public function show(Report $report)
    {
        if ($report->user_id !== Auth::id()) abort(403);
        $report->load('updates.admin', 'photos');
        return view('user.reports.show', compact('report'));
    }

    public function edit(Report $report)
    {
        if ($report->user_id !== Auth::id()) abort(403);

        if ($report->status !== 'menunggu') {
            return redirect()->route('user.reports.show', $report)
                ->with('error', 'Laporan yang sudah diproses tidak dapat diedit.');
        }

        $categories = Report::categories();
        $report->load('photos');
        return view('user.reports.edit', compact('report', 'categories'));
    }

    public function update(Request $request, Report $report)
    {
        if ($report->user_id !== Auth::id()) abort(403);

        if ($report->status !== 'menunggu') {
            return redirect()->route('user.reports.show', $report)
                ->with('error', 'Laporan yang sudah diproses tidak dapat diedit.');
        }

        $validated = $request->validate([
            'title'           => ['required', 'string', 'max:255'],
            'description'     => ['required', 'string', 'min:20'],
            'location'        => ['required', 'string', 'max:500'],
            'category'        => ['required', 'in:' . implode(',', array_keys(Report::categories()))],
            'photos'          => ['nullable', 'array', 'max:5'],
            'photos.*'        => ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'remove_photos'   => ['nullable', 'array'],
            'remove_photos.*' => ['integer'],
            'latitude'        => ['nullable', 'numeric'],
            'longitude'       => ['nullable', 'numeric'],
        ], [
            'title.required'       => 'Judul laporan wajib diisi.',
            'description.required' => 'Deskripsi wajib diisi.',
            'description.min'      => 'Deskripsi minimal 20 karakter.',
            'location.required'    => 'Lokasi wajib diisi.',
            'category.required'    => 'Kategori wajib dipilih.',
            'photos.max'           => 'Maksimal 5 foto.',
            'photos.*.image'       => 'Setiap file harus berupa gambar.',
            'photos.*.max'         => 'Ukuran setiap foto maksimal 5MB.',
        ]);

        // Hapus foto yang dipilih user
        if ($request->filled('remove_photos')) {
            $toDelete = ReportPhoto::whereIn('id', $request->remove_photos)
                ->where('report_id', $report->id)
                ->get();
            foreach ($toDelete as $p) {
                Storage::disk('public')->delete($p->path);
                $p->delete();
            }
        }

        // Tambah foto baru
        if ($request->hasFile('photos')) {
            $existingCount = $report->photos()->count();
            foreach ($request->file('photos') as $i => $file) {
                if ($existingCount + $i >= 5) break; // max 5
                $path = $file->store('reports', 'public');
                ReportPhoto::create([
                    'report_id' => $report->id,
                    'path'      => $path,
                    'order'     => $existingCount + $i,
                ]);
            }
        }

        $report->update([
            'title'       => $validated['title'],
            'description' => $validated['description'],
            'location'    => $validated['location'],
            'category'    => $validated['category'],
            'latitude'    => $validated['latitude'] ?? null,
            'longitude'   => $validated['longitude'] ?? null,
        ]);

        return redirect()->route('user.reports.show', $report)
            ->with('success', 'Laporan berhasil diperbarui.');
    }

    public function destroy(Report $report)
    {
        if ($report->user_id !== Auth::id()) abort(403);

        if ($report->status !== 'menunggu') {
            return back()->with('error', 'Laporan yang sedang diproses tidak dapat dihapus.');
        }

        // Hapus semua foto
        foreach ($report->photos as $p) {
            Storage::disk('public')->delete($p->path);
        }
        if ($report->photo) {
            Storage::disk('public')->delete($report->photo);
        }

        $report->delete();

        return redirect()->route('user.reports.index')
            ->with('success', 'Laporan berhasil dihapus.');
    }
}