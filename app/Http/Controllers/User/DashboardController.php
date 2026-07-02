<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $totalReports      = Report::where('user_id', $user->id)->count();
        $pendingReports    = Report::where('user_id', $user->id)->where('status', 'menunggu')->count();
        $inProgressReports = Report::where('user_id', $user->id)->where('status', 'diproses')->count();
        $completedReports  = Report::where('user_id', $user->id)->where('status', 'selesai')->count();
        $rejectedReports   = Report::where('user_id', $user->id)->where('status', 'ditolak')->count();

        // Recent activity: user's own reports, sorted by latest update
        $recentActivity = Report::where('user_id', $user->id)
            ->latest('updated_at')
            ->take(4)
            ->get();

        // GIS map: recent public reports with coordinates (all users)
        $mapReports = Report::whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->where('status', '!=', 'ditolak')
            ->latest('updated_at')
            ->take(50)
            ->get()
            ->map(fn($r) => [
                'latitude'     => (float) $r->latitude,
                'longitude'    => (float) $r->longitude,
                'title'        => $r->title,
                'status'       => $r->status,
                'status_label' => $r->status_label,
                'category'     => $r->category_label,
                'location'     => $r->location,
                'url'          => route('user.reports.show', $r->id),
            ]);

        return view('user.dashboard', compact(
            'totalReports',
            'pendingReports',
            'inProgressReports',
            'completedReports',
            'rejectedReports',
            'recentActivity',
            'mapReports'
        ));
    }
}
