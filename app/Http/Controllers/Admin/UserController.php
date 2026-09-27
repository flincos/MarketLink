<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FarmerProfile;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * All users with optional role filter and search.
     */
    public function index(Request $request)
    {
        $query = User::query();

        if ($role = $request->get('role')) {
            $query->where('role', $role);
        }

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->latest()->paginate(20)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    /**
     * Paginated farmer profiles with optional status filter.
     */
    public function farmers(Request $request)
    {
        $query = FarmerProfile::with('user');

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        $farmers = $query->latest()->paginate(20)->withQueryString();

        return view('admin.farmers.index', compact('farmers'));
    }

    /**
     * Approve a farmer profile.
     */
    public function approve(FarmerProfile $farmer)
    {
        $farmer->update(['status' => 'approved']);

        return back()->with('success', "Farmer \"{$farmer->stall_name}\" has been approved.");
    }

    /**
     * Suspend a farmer profile.
     */
    public function suspend(FarmerProfile $farmer)
    {
        $farmer->update(['status' => 'suspended']);

        return back()->with('success', "Farmer \"{$farmer->stall_name}\" has been suspended.");
    }
}
