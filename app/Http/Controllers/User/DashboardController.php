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

        $stats = [
            'total'    => Report::where('user_id', $user->id)->count(),
            'menunggu' => Report::where('user_id', $user->id)->where('status', 'menunggu')->count(),
            'diproses' => Report::where('user_id', $user->id)->where('status', 'diproses')->count(),
            'selesai'  => Report::where('user_id', $user->id)->where('status', 'selesai')->count(),
            'ditolak'  => Report::where('user_id', $user->id)->where('status', 'ditolak')->count(),
        ];

        $latestReports = Report::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        $unreadNotifications = $user->unreadNotifications()->take(5)->get();

        return view('user.dashboard', compact('stats', 'latestReports', 'unreadNotifications'));
    }
}
