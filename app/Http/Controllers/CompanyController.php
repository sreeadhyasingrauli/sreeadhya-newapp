<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use App\Http\Requests\StoreCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Database\Eloquent\Collection;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() : View
    {
        $fontSize = '12px';
        $companies = Company::paginate(10); 
        return view('companies.index', compact('companies','fontSize'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() : View
    {
        $fontSize = '12px';
        return view('companies.create',compact('fontSize'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCompanyRequest $request) : RedirectResponse
    {
        //
        Company::create($request->validated());

        return redirect()->route('companies.index')
                ->withSuccess('New company is added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Company $company) : View
    {
        //
         $fontSize = '12px';
        return view('companies.show', compact('company','fontSize'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Company $company) : View
    {
        //
        $fontSize = '12px';
        return view('companies.edit', compact('company','fontSize'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCompanyRequest $request, Company $company) : RedirectResponse
    {
        //
        $company->update($request->validated());

        return redirect()->back()
                ->withSuccess('Company is updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Company $company) : RedirectResponse
    {
        //
        $company->delete();

        return redirect()->route('companies.index')
                ->withSuccess('Company is deleted successfully.');
    }
}
