<?php

namespace App\Http\Controllers;

use App\Models\Customers;
use Illuminate\Http\Request;
use App\Http\Requests\CustomersRequest;
use App\Http\Resources\CustomerResource;
use App\Models\Customer;
use App\Traits\ImageManipulation;
use Illuminate\Auth\Access\AuthorizationException;

class CustomerController extends Controller
{
    use ImageManipulation;

    public function __construct()
    {
        $this->middleware('can:viewCustomers')->only(['index','show']);
        $this->middleware('can:createCustomers')->only('store');
        $this->middleware('can:updateCustomers')->only('update');
        $this->middleware('can:deleteCustomers')->only('destroy');
    }
    // private function autorizeUser(Customer $customer)
    // {
    //     if ($customer->user_id !== auth()->id()) {
    //         throw new  AuthorizationException('You are not authorized');
    //     }
    // }


   public function index(Request $request)
    {
        $perPage = $request->input('perPage', 10);
        $search = request()->input('search');
        $customers = Customer::query()->search($search);
        $customers = $perPage ? $customers->latest()->paginate($perPage) : $customers->latest()->get();


        return CustomerResource::collection($customers);
    }

    public function store(CustomersRequest $request)
    {
        
        $validated = $request->validated();
        $validated['user_id']= 2;

        // store the image if exists
        $request->hasFile('image') ? $this->storeImage($request, $validated, "customers", 'image') : null;

        $customer = Customer::create($validated);
        return new CustomerResource($customer);
    }

    public function show($id)
    {
        $customer = Customer::findOrFail($id);
        // dd($customer);
        return CustomerResource::make($customer);
    }


    public function update(CustomersRequest $request, $id)
{
    // Find the customer
    $customer = Customer::findOrFail($id);

    // Validate the request data
    $validated = $request->validated();

    // Handle image update
    $this->updateImage($customer, $request, $validated, 'customers', 'image');

    // Update the customer with validated data
    $customer->update($validated);

    // Return the updated resource
    return new CustomerResource($customer);
}


    public function destroy($id)
    {
        $customer = Customer::findOrFail($id);
        $this->deleteImage($customer, 'customers', 'image');
        $customer->delete();
        return response()->noContent();
    }
}
