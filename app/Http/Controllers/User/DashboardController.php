<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $totalReports     = Report::where('user_id', $user->id)->count();
        $pendingReports   = Report::where('user_id', $user->id)->where('status', 'menunggu')->count();
        $inProgressReports = Report::where('user_id', $user->id)->where('status', 'diproses')->count();
        $completedReports = Report::where('user_id', $user->id)->where('status', 'selesai')->count();
        $rejectedReports  = Report::where('user_id', $user->id)->where('status', 'ditolak')->count();

        // Recent activity: user's own reports, sorted by latest update
        $recentActivity = Report::where('user_id', $user->id)
            ->latest('updated_at')
            ->take(4)
            ->get();

        return view('user.dashboard', compact(
            'totalReports',
            'pendingReports',
            'inProgressReports',
            'completedReports',
            'rejectedReports',
            'recentActivity'
        ));
    }
}
