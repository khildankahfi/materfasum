<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\ReportUpdate;
use App\Notifications\ReportStatusUpdated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $query = Report::with('user')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title',    'like', "%{$request->search}%")
                  ->orWhere('location', 'like', "%{$request->search}%")
                  ->orWhereHas('user', fn($u) => $u->where('name', 'like', "%{$request->search}%"));
            });
        }

        $reports    = $query->paginate(15);
        $categories = Report::categories();

        return view('admin.reports.index', compact('reports', 'categories'));
    }

    public function show(Report $report)
    {
        $report->load('user', 'updates.admin', 'photos');
        return view('admin.reports.show', compact('report'));
    }

    public function updateStatus(Request $request, Report $report)
    {
        $validated = $request->validate([
            'status'           => ['required', 'in:diproses,selesai,ditolak'],
            'note'             => ['nullable', 'string', 'max:1000'],
            'rejection_reason' => ['required_if:status,ditolak', 'nullable', 'string', 'max:500'],
            'photo_after'      => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ], [
            'status.required'              => 'Status wajib dipilih.',
            'rejection_reason.required_if' => 'Alasan penolakan wajib diisi jika laporan ditolak.',
        ]);

        $photoAfterPath = null;
        if ($request->hasFile('photo_after')) {
            $photoAfterPath = $request->file('photo_after')->store('report_updates', 'public');
        }

        ReportUpdate::create([
            'report_id'   => $report->id,
            'admin_id'    => Auth::id(),
            'status'      => $validated['status'],
            'note'        => $validated['note'] ?? null,
            'photo_after' => $photoAfterPath,
        ]);

        $report->update([
            'status'           => $validated['status'],
            'rejection_reason' => $validated['status'] === 'ditolak' ? $validated['rejection_reason'] : null,
        ]);

        $report->user->notify(new ReportStatusUpdated($report, $validated['note']));

        $label = match ($validated['status']) {
            'diproses' => 'diproses',
            'selesai'  => 'diselesaikan',
            'ditolak'  => 'ditolak',
        };

        return redirect()->route('admin.reports.show', $report)
            ->with('success', "Laporan berhasil {$label}. Notifikasi telah dikirim ke pelapor.");
    }

    public function destroy(Report $report)
    {
        foreach ($report->photos as $p) {
            Storage::disk('public')->delete($p->path);
        }
        if ($report->photo) {
            Storage::disk('public')->delete($report->photo);
        }
        foreach ($report->updates as $update) {
            if ($update->photo_after) {
                Storage::disk('public')->delete($update->photo_after);
            }
        }

        $report->delete();

        return redirect()->route('admin.reports.index')
            ->with('success', 'Laporan berhasil dihapus.');
    }

    // ── Export CSV ──
    public function exportCsv(Request $request)
    {
        $query = Report::with('user')->latest();

        if ($request->filled('status'))   $query->where('status',   $request->status);
        if ($request->filled('category')) $query->where('category', $request->category);

        $reports = $query->get();

        $filename = 'laporan-materfasum-' . now()->format('Ymd-His') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($reports) {
            $file = fopen('php://output', 'w');

            // BOM for Excel UTF-8
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($file, ['#', 'Judul', 'Kategori', 'Lokasi', 'Pelapor', 'Email', 'Status', 'Tanggal']);

            foreach ($reports as $i => $r) {
                fputcsv($file, [
                    $i + 1,
                    $r->title,
                    $r->category_label,
                    $r->location,
                    $r->user->name ?? '-',
                    $r->user->email ?? '-',
                    $r->status_label,
                    $r->created_at->format('d/m/Y H:i'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // ── Export PDF (print view) ──
    public function exportPdf(Request $request)
    {
        $query = Report::with('user')->latest();

        if ($request->filled('status'))   $query->where('status',   $request->status);
        if ($request->filled('category')) $query->where('category', $request->category);

        $reports    = $query->get();
        $categories = Report::categories();

        return view('admin.reports.print', compact('reports', 'categories', 'request'));
    }
}