<?php

namespace App\Http\Controllers;

use App\Models\Suppliers;
use Illuminate\Http\Request;
use App\Http\Requests\SuppliersRequest;
use App\Http\Resources\SupplierResource;
use Illuminate\Auth\AuthenticationException;

class SupplierController extends Controller
{

    public function __construct()
    {
        $this->middleware('can:viewSuppliers')->only(['index', 'show']);
        $this->middleware('can:createSupplier')->only(['store']);
        $this->middleware('can:editSupplier')->only(['update']);
        $this->middleware('can:deleteSupplier')->only('destroy');
    }
    private function authorizeUser(Suppliers $suppliers)
    {
        if ($suppliers->user_id !== auth()->id()) {
            throw new AuthenticationException('you must be logged in');
        }
    }

    /**
     * Display a listing of the resource.
     */

     public function index(Request $request)
     {
         $perPage = $request->input('perPage', 10);
         $search = request()->input('search');
         $supplier = Suppliers::query()->search($search);
         $supplier = $perPage ? $supplier->latest()->paginate($perPage) : $supplier->latest()->get();


         return SupplierResource::collection($supplier);
     }

     public function store(SuppliersRequest $request)
     {
         $validated = $request->validated();
         $validated['user_id']= 2;

         // store the image if exists
        //  $request->hasFile('image') ? $this->storeImage($request, $validated, "supplier", 'image') : null;

         $supplier = Suppliers::create($validated);
         return new SupplierResource($supplier);
     }

     public function show(Suppliers $supplier)
     {
         // dd($supplier);
         return SupplierResource::make($supplier);
     }


     public function update(SuppliersRequest $request, $id)
 {
     // Find the supplier
     $supplier = Suppliers::findOrFail($id);

     // Validate the request data
     $validated = $request->validated();
     // Update the supplier with validated data
     $supplier->update($validated);

     // Handle image update
    //  $this->updateImage($supplier, $request, $validated, 'supplier', 'image');


     // Return the updated resource
     return new SupplierResource($supplier);
 }


     public function destroy($id)
     {
         $supplier = Suppliers::findOrFail($id);
         $supplier->delete();
        //  $this->deleteImage($supplier, 'supplier', 'image');
         return response()->noContent();
     }
 }

