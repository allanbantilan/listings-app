<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $listing = $request->user()->role !== 'suspended' ?
            $request->user()->listings()->latest()->paginate(10) : null;

        return Inertia::render('Dashboard', [
            'listings' => $listing,
            'status' => session('status')
        ]);
    }
}
