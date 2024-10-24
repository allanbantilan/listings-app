<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Http\Middleware\NotSuspended;
use Illuminate\Support\Facades\Storage;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Contracts\Database\Eloquent\Builder;

class ListingController extends Controller implements hasMiddleware
{
    public static function middleware()
    {

        //middlware for checking if the use is suspended and if the user logged in and verified.
        return [
            new Middleware([
                'auth',
                'verified',
                NotSuspended::class
            ], except: [
                'index',
                'show'
            ])
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // dd($request);
        $listings = Listing::whereHas('user', function (Builder $query) {
            $query->where('role', '!=', 'suspended');
        })
            ->with('user')
            ->where('approved', true)
            ->filter(request(['search', 'user_id', 'tag']))
            ->latest()
            ->paginate(6)
            ->withQueryString();

        return Inertia::render('Home', [
            'listings' => $listings,
            'searchTerm' => $request->search
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //add a policy if the use is suspended the user cant add a new listing
        Gate::authorize('create', Listing::class);

        return inertia::render('Listing/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // $newTags = explode(',', $request->tags);
        // $newTags = array_map('trim', $newTags);
        // $newTags = array_filter($newTags);
        // $newTags = array_unique($newTags);
        // $newTags = implode(',', $newTags);

        //add a policy if the use is suspended the user cant add a new listing
        Gate::authorize('create', Listing::class);

        //validating the inputs from the form.
        $fields = $request->validate([
            'title' => ['required', 'max:255'],
            'desc' => ['required'],
            'tags' => ['nullable', 'string'],
            'email' => ['nullable', 'email'],
            'link' => ['nullable', 'url'],
            'image' => ['nullable', 'file', 'max:3072', 'mimes:jpeg,jpg,png,webp'],
        ]);


        //checking if request helper has an image, if it has an image store it to the public/images folder then storing the path back to $fields['image'].
        if ($request->hasFile('image')) {
            $fields['image'] = Storage::disk('public')->put(
                'images/listing',
                $request->image
            );
        }

        // checking if the tags has a whitspace, extra comma to make the tag right and uniform, refer to the code above that is readable.
        $fields['tags'] = implode(',', array_unique(array_filter(array_map('trim', explode(',', $request->tags)))));

        //saving the listing then fetching the user and getting the listing relationship method in the user model the creating the listing.
        $request->user()->listings()->create($fields);

        //redirecting back to dashboard with a text
        return redirect()->route('dashboard')->with('status', 'Listing created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Listing $listing)
    {
        // check if the user owns the listing, if the user owns it the user can delete and edit it.
        Gate::authorize('view', $listing);

        return inertia::render('Listing/Show', [
            'listing' => $listing,
            'user' => $listing->user->only(['name', 'id']),

            // add can modify to only show the edit and delete button to the owner of the listing and hide it the user is not the owner of that listing.
            'canModify' =>
            Auth::user()
                ?  Auth::user()->can('modify', $listing)
                : false
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Listing $listing)
    {
        // check if the user owns the listing, if the user owns it the user can delete and edit it.
        Gate::authorize('modify', $listing);

        return inertia::render('Listing/Edit', [
            'listing' => $listing
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Listing $listing)
    {
        // check if the user owns the listing, if the user owns it the user can delete and edit it.
        Gate::authorize('modify', $listing);

        //validating the inputs from the form.
        $fields = $request->validate([
            'title' => ['required', 'max:255'],
            'desc' => ['required'],
            'tags' => ['nullable', 'string'],
            'email' => ['nullable', 'email'],
            'link' => ['nullable', 'url'],
            'image' => ['nullable', 'file', 'max:3072', 'mimes:jpeg,jpg,png,webp'],
        ]);

        // checking if it has a new image if it has new image delete it and then change it with the new one and if dont have new image it will stay the same.
        if ($request->hasFile('image')) {

            if ($listing->image) {
                Storage::disk('public')->delete($listing->image);
            }
            $fields['image'] = Storage::disk('public')->put(
                'images/listing',
                $request->image
            );
        } else {
            $fields['image'] = $listing->image;
        }

        // checking if the tags has a whitspace, extra comma to make the tags are right and uniform, refer to the code above that is readable.
        $fields['tags'] = implode(',', array_unique(array_filter(array_map('trim', explode(',', $request->tags)))));

        //updating the listing and making the approved to false
        $listing->update([...$fields, 'approved' => false]);

        //redirecting back to dashboard with a text
        return redirect()->route('dashboard')->with('status', 'Listing updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Listing $listing)
    {

        Gate::authorize('modify', $listing);

        // check if the listing has an image, if it has an image delete it also.
        if ($listing->image) {
            Storage::disk('public')->delete($listing->image);
        }
        // delete the listing
        $listing->delete();

        return redirect()->route('dashboard')->with('status', 'Listing deleted successfully');
    }
}
