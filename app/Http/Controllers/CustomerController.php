<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Database\Eloquent\Collection;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() : View
    {
        $customers = Customer::paginate(10);
        return view('customers.index', compact('customers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() : View
    {
        return view('customers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCustomerRequest $request) : RedirectResponse
    {
        Customer::create($request->validated());
        return redirect()->route('customers.index')
                ->withSuccess('Customer is created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer) : View
    {
        //
        return view('customers.show', compact('customer'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer) : View
    {
        //
        return view('customers.edit', compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCustomerRequest $request, Customer $customer) : RedirectResponse
    {
        //
        $customer->update($request->validated());
        return redirect()->route('customers.index')
                ->withSuccess('Customer is updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer) : RedirectResponse
    {
        //
        $customer->delete();
        return redirect()->route('customers.index')
                ->withSuccess('Customer is deleted successfully.');
    }
}
