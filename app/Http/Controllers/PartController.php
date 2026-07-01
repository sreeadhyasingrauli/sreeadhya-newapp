<?php

namespace App\Http\Controllers;

use App\Models\Part;
use Illuminate\Http\Request;

class PartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $parts = Part::latest()->paginate(10);
        return view('parts.index', compact('parts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('parts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validated = $request->validate([
            'part_number' => 'required|unique:parts,part_number',
            'part_description' => 'required|string|max:255',
            'make' => 'nullable|string|max:100',
            'uom' => 'required|string|max:15',
            'price' => 'required|numeric|min:0',
            'hsn_code' => 'required|integer|min:0',
            'gst_rate' => 'required|numeric|min:0',
            
        ]);

        Part::create($validated);

        return redirect()->route('parts.index')->with('success', 'Part added successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Part $part)
    {
        //
        return view('parts.show', compact('part'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Part $part)
    {
        //
        return view('parts.edit', compact('part'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Part $part)
    {
        //
        $validated = $request->validate([
            'part_number' => 'required|unique:parts,part_number,' . $part->part_number,
            'part_description' => 'required|string|max:255',
            'make' => 'nullable|string|max:100',
            'uom' => 'nullable|string|max:15',
            'price' => 'required|numeric|min:0',
            'hsn_code' => 'required|integer|min:0',
             'gst_rate' => 'required|numeric|min:0',
            
        ]);

        $part->update($validated);

        return redirect()->route('parts.index')->with('success', 'Part updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Part $part)
    {
        //
        $part->delete();
        return redirect()->route('parts.index')->with('success', 'Part deleted successfully!');
    }
}
