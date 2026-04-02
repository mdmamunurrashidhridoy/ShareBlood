<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BloodRequest;
use App\Models\DonorProfile;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        if ($request->user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        $user = auth()->user();

        $profile = $user->donorProfile()->first();

        $stats = [
            'total_requests' => BloodRequest::count(),
            'active_donors' => DonorProfile::where('is_available', true)->count(),
            'my_requests' => BloodRequest::where('requester_user_id', $user->id)->count(),
            'matched_requests' => 0,
        ];

        $recentRequests = BloodRequest::with([
            'division',
            'district',
            'upazilla',
            'cityCorporation',
            'cityArea',
        ])
            ->latest()
            ->take(6)
            ->get();

        $matchedRequests = collect();

        if ($user->blood_group && $profile?->is_available) {
            $matchedRequestsQuery = BloodRequest::with([
                'division',
                'district',
                'upazilla',
                'cityCorporation',
                'cityArea',
            ])
                ->where('blood_group', $user->blood_group)
                ->where('status', 'pending');

            if ($user->district_id) {
                $matchedRequestsQuery->where('district_id', $user->district_id);
            }

            if ($profile->next_eligible_date && Carbon::parse($profile->next_eligible_date)->isFuture()) {
                $matchedRequests = collect();
            } else {
                $matchedRequests = $matchedRequestsQuery
                    ->latest()
                    ->take(6)
                    ->get();
            }

            $stats['matched_requests'] = $matchedRequests->count();
        }

        $profileCompletion = 0;

        $fields = [
            $user->phone,
            $user->blood_group,
            $user->division_id,
            $user->district_id,
            $user->address_line,
        ];

        $filled = collect($fields)->filter(fn($value) => !empty($value))->count();
        $profileCompletion = (int) round(($filled / count($fields)) * 100);

        return view('dashboard', compact(
            'profile',
            'stats',
            'recentRequests',
            'matchedRequests',
            'profileCompletion'
        ));
    }
}
