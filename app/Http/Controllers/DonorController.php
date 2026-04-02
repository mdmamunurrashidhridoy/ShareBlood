<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DonorProfile;
use Carbon\Carbon;

class DonorController extends Controller
{
    public function dashboard(Request $request)
    {
        $donor = DonorProfile::where('user_id', $request->user()->id)->first();

        return view('donor.dashboard', compact('donor'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'is_available' => ['required', 'boolean'],
            'last_donate_date' => ['nullable', 'date'],
        ]);

        $donor = DonorProfile::firstOrNew(['user_id' => $request->user()->id]);
        $donor->is_available = (bool)$validated['is_available'];
        $donor->lat_donate_date = $validated['last_donate_date'] ?? null;

        if(!empty($donor->last_donate_date)) {
            $donor->next_eligible_date = Carbon::parse($donor->last_donate_date)->addDays(90)->toDateString();
        } else {
            $donor->next_eligible_date = null;
        }

        $donor->save();

        return back()->with('status', 'Donor settings updated!');
    }
}
