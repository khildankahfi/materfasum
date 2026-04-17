<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_laporan' => Report::count(),
            'menunggu'      => Report::where('status', 'menunggu')->count(),
            'diproses'      => Report::where('status', 'diproses')->count(),
            'selesai'       => Report::where('status', 'selesai')->count(),
            'ditolak'       => Report::where('status', 'ditolak')->count(),
            'total_user'    => User::where('role', 'user')->count(),
        ];

        $laporanBaru = Report::with('user')
            ->where('status', 'menunggu')
            ->latest()
            ->take(5)
            ->get();

        $laporanTerbaru = Report::with('user')
            ->latest()
            ->take(8)
            ->get();

        // Data untuk chart per kategori
        $kategoryStat = Report::selectRaw('category, count(*) as total')
            ->groupBy('category')
            ->get()
            ->pluck('total', 'category');

        return view('admin.dashboard', compact('stats', 'laporanBaru', 'laporanTerbaru', 'kategoryStat'));
    }
}
