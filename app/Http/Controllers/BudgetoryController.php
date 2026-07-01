<?php

namespace App\Http\Controllers;

use App\Models\Budgetory;
use App\Http\Requests\StoreBudgetoryRequest;
use App\Http\Requests\UpdateBudgetoryRequest;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;


class BudgetoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() : View
    {
        //
        $budgetories = Budgetory::paginate(10);
        return view('budgetories.index', compact('budgetories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() : View
    {
        //
        return view('budgetories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBudgetoryRequest $request) : RedirectResponse
    {
        //
        $validated = $request->validated();
        Budgetory::create($validated);
        return redirect()->route('budgetories.index')->with('success', 'Budgetory created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Budgetory $budgetory) : View
    {
        //
        return view('budgetories.show', compact('budgetory'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Budgetory $budgetory) : View
    {
        //
        return view('budgetories.edit', compact('budgetory'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBudgetoryRequest $request, Budgetory $budgetory) : RedirectResponse
    {
        //
        $validated = $request->validated();
        $budgetory->update($validated);
        return redirect()->route('budgetories.index')->with('success', 'Budgetory updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Budgetory $budgetory) : RedirectResponse
    {
        //
        $budgetory->delete();
        return redirect()->route('budgetories.index')->with('success', 'Budgetory deleted successfully');
    }
}
