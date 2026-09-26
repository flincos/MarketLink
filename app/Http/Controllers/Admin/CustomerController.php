<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Http\RedirectResponse;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = User::where('role', 'customer')
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('admin.customers.index', compact('customers'));
    }

    public function activate(User $user): RedirectResponse
    {
        $user->update(['is_active' => true]);

        return back()->with('status', 'Customer activated.');
    }

    public function deactivate(User $user): RedirectResponse
    {
        $user->update(['is_active' => false]);

        return back()->with('status', 'Customer deactivated.');
    }
}
