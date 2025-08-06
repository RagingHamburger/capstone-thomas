<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Venue;
use Illuminate\Http\Request;

class VenueController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $venues = Venue::latest()->paginate(10);
        return view('admin.venues.index', compact('venues'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.venues.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:venues',
            'capacity' => 'required|integer|min:1',
            'status' => 'required|in:active,inactive',
            'is_available' => 'boolean',
            'description' => 'nullable|string'
        ]);

        Venue::create([
            'name' => $request->name,
            'capacity' => $request->capacity,
            'status' => $request->status,
            'is_available' => $request->has('is_available'),
            'description' => $request->description
        ]);

        return redirect()->route('admin.venues.index')
            ->with('success', 'Venue created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Venue $venue)
    {
        return view('admin.venues.show', compact('venue'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Venue $venue)
    {
        return view('admin.venues.edit', compact('venue'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Venue $venue)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:venues,name,' . $venue->id,
            'capacity' => 'required|integer|min:1',
            'status' => 'required|in:active,inactive',
            'is_available' => 'boolean',
            'description' => 'nullable|string'
        ]);

        $venue->update([
            'name' => $request->name,
            'capacity' => $request->capacity,
            'status' => $request->status,
            'is_available' => $request->has('is_available'),
            'description' => $request->description
        ]);

        return redirect()->route('admin.venues.index')
            ->with('success', 'Venue updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Venue $venue)
    {
        $venue->delete();
        return redirect()->route('admin.venues.index')
            ->with('success', 'Venue deleted successfully!');
    }
}
