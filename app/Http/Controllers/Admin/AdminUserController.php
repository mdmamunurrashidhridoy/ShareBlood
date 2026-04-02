<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\User;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with([
            'division',
            'district',
            'upazilla',
            'cityCorporation',
            'cityArea',
            'donorProfile',
        ])->latest();

        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('blood_group')) {
            $query->where('blood_group', $request->blood_group);
        }

        if ($request->filled('is_blocked')) {
            $query->where('is_blocked', $request->is_blocked === '1');
        }

        if ($request->filled('is_verified')) {
            $query->where('is_verified', $request->is_verified === '1');
        }

        if ($request->filled('district_id')) {
            $query->where('district_id', $request->district_id);
        }

        if ($request->filled('availability')) {
            if ($request->availability === 'available') {
                $query->whereHas('donorProfile', function ($q) {
                    $q->where('is_available', true);
                });
            }

            if ($request->availability === 'unavailable') {
                $query->whereHas('donorProfile', function ($q) {
                    $q->where('is_available', false);
                });
            }

            if ($request->availability === 'no_profile') {
                $query->whereDoesntHave('donorProfile');
            }
        }

        $users = $query->paginate(12)->withQueryString();

        $districts = District::orderBy('name')->get();

        return view('admin.users.index', compact('users', 'districts'));
    }

    public function show(User $user)
    {
        $user->load([
            'division',
            'district',
            'upazilla',
            'cityCorporation',
            'cityArea',
            'donorProfile',
        ]);

        $recentRequests = $user->bloodRequests()->latest()->take(10)->get();

        return view('admin.users.show', compact('user', 'recentRequests'));
    }

    public function block(User $user)
    {
        if ($user->isAdmin()) {
            return back()->with('error', 'Admin users cannot be blocked.');
        }

        $user->update([
            'is_blocked' => true,
        ]);

        return back()->with('success', 'User blocked successfully.');
    }

    public function unblock(User $user)
    {
        $user->update([
            'is_blocked' => false,
        ]);

        return back()->with('success', 'User unblocked successfully.');
    }

    public function verify(User $user)
    {
        $user->update([
            'is_verified' => true,
        ]);

        return back()->with('success', 'User verified successfully.');
    }

    public function unverify(User $user)
    {
        $user->update([
            'is_verified' => false,
        ]);

        return back()->with('success', 'User verification removed.');
    }

    public function destroy(User $user)
    {
        if ($user->isAdmin()) {
            return back()->with('error', 'Admin users cannot be deleted.');
        }

        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }
}
