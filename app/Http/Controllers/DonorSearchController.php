<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\DonorSearchRequest;
use App\Models\District;
use App\Models\Division;
use App\Models\CityArea;
use App\Models\CityCorporation;
use App\Models\Upazilla;
use App\Models\User;
use Illuminate\Support\Carbon;

class DonorSearchController extends Controller
{
    public function index(DonorSearchRequest $request)
    {
        $today = Carbon::today()->toDateString();

        $query = User::query()
            ->with([
                'donorProfile',
                'division',
                'district',
                'upazilla',
                'cityCorporation',
                'cityArea',
            ])
            ->where('role', 'user')
            ->where('is_blocked', false)
            ->whereNotNull('blood_group')
            ->whereHas('donorProfile', function ($q) use ($request, $today){
                if ($request->boolean('available_only', true)) {
                    $q->where('is_available', true);
                }

                if($request->boolean('eligible_only', true)) {
                    $q->where(function ($sub) use ($today) {
                        $sub->whereNull('next_eligible_date')
                            ->orWhereDate('next_eligible_date', '<=', $today);
                    });
                }
            });

        $query->when($request->filled('blood_group'), function ($q) use ($request){
            $q->where('blood_group', $request->blood_group);
        });

        $query->when($request->filled('division_id'), function ($q) use ($request){
            $q->where('division_id', $request->division_id);
        });
        $query->when($request->filled('district_id'), function ($q) use ($request) {
            $q->where('district_id', $request->district_id);
        });

        $query->when($request->filled('upazilla_id'), function ($q) use ($request) {
            $q->where('upazilla_id', $request->upazilla_id);
        });

        $query->when($request->filled('city_corporation_id'), function ($q) use ($request) {
            $q->where('city_corporation_id', $request->city_corporation_id);
        });

        $query->when($request->filled('city_area_id'), function ($q) use ($request) {
            $q->where('city_area_id', $request->city_area_id);
        });

        $donors = $query->latest()->paginate(12)->withQueryString();

        $divisions = Division::orderBy('name')->get();
        
        $districts = collect();
        $upazillas = collect();
        $cityCorporations = collect();
        $cityAreas = collect();

        if($request->filled('division_id')) {
            $districts = District::where('division_id', $request->division_id)
                ->orderBy('name')
                ->get(['id', 'name']);
        }
        if ($request->filled('district_id')) {
            $upazillas = Upazilla::where('district_id', $request->district_id)
                ->orderBy('name')
                ->get(['id', 'name']);

            $cityCorporations = CityCorporation::where('district_id', $request->district_id)
                ->orderBy('name')
                ->get(['id', 'name']);
        }

        if ($request->filled('city_corporation_id')) {
            $cityAreas = CityArea::where('city_corporation_id', $request->city_corporation_id)
                ->orderBy('name')
                ->get(['id', 'name']);
        }

        return view('donors.index', compact('donors',
            'divisions',
            'districts',
            'upazillas',
            'cityCorporations',
            'cityAreas'));
    }
}
