<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class AdminController extends Controller
{
    public function index()
    {
        $user = User::with('listings')
            ->filter(request(['search', 'user_role']))
            ->paginate(5)
            ->withQueryString();

        return Inertia::render('Admin/AdminDashboard', [
            'users' => $user,
            'status' => session('status')
        ]);
    }

    public function show(User $user)
    {
        $user_listings = $user
            ->listings()
            ->filter(request(['search', 'disapproved']))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Admin/UserPage', [
            'user' => $user,
            'listings' => $user_listings,
            'status' => session('status')
        ]);
    }

    public function approve(Listing $listing)
    {
        Gate::authorize('approve', $listing);

        $listing->update(['approved' => !$listing->approved]);

        $msg = $listing->approved ? 'Approved' : 'Disapproved';
        return back()->with('status', "listing {$msg} successfully!");
    }


    public function role(Request $request, User $user)
    {
        Gate::authorize('modifyRole', $user);

        $request->validate(['role' => 'string|required']);

        $user->update(['role' => $request->role]);

        return redirect()->route('admin.index')->with('status', "User role changed to {$request->role} successfully");
    }

}
