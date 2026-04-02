<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\BloodRequest;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'total_admins' => User::where('role', 'admin')->count(),
            'total_donors' => User::where('role', 'user')
                ->whereNotNull('blood_group')
                ->count(),
            'blocked_users' => User::where('is_blocked', true)->count(),
            'verified_users' => User::where('is_verified', true)->count(),
            'available_donors' => User::where('role', 'user')
                ->whereHas('donorProfile', function ($query) {
                    $query->where('is_available', true);
                })->count(),
            'total_requests' => BloodRequest::count(),
            'pending_requests' => BloodRequest::where('status', 'pending')->count(),
            'accepted_requests' => BloodRequest::where('status', 'accepted')->count(),
            'completed_requests' => BloodRequest::where('status', 'completed')->count(),
            'cancelled_requests' => BloodRequest::where('status', 'cancelled')->count(),
            'expired_requests' => BloodRequest::where('status', 'expired')->count(),
            'emergency_requests' => BloodRequest::where('is_emergency', true)->count(),
        ];
        $recentUsers = User::with([
            'division',
            'district',
            'upazilla',
            'cityCorporation',
            'cityArea',
            'donorProfile',
        ])
            ->latest()
            ->take(8)
            ->get();

        $recentRequests = BloodRequest::with([
            'requester',
            'division',
            'district',
            'upazilla',
            'cityCorporation',
            'cityArea',
        ])
            ->latest()
            ->take(8)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentUsers', 'recentRequests'));
    }
}
