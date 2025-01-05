<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleDetails;
use App\Models\SalesDetail;
use Illuminate\Http\Request;
use App\Http\Requests\salesRequest;
use App\Http\Requests\salessRequest;
use App\Http\Resources\saleResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\salesResource;
use Illuminate\Auth\AuthenticationException;
use App\Http\Resources\Sales_and_SalesDetailsResource;

class SalesController extends Controller
{

    public function __construct()
    {
        $this->middleware('can:viewSale')->only(['index', 'show']);
        $this->middleware('can:createSale')->only(['store']);
        $this->middleware('can:editSale')->only(['update']);
        $this->middleware('can:deleteSale')->only('destroy');
    }
    // private function authorizeUser(sales $sales)
    // {
    //     if ($sales->user_id !== auth()->id()) {
    //         throw new AuthenticationException('you must be logged in');
    //     }
    // }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->input('perPage');
        $search = $request->input('search');

        // Eager load relationships and apply search
        $saless = Sale::with(['salesDetails', 'customer', 'user'])
            ->search($search);

        $saless = $perPage ? $saless->latest()->paginate($perPage) : $saless->latest()->get();

        return saleResource::collection($saless);
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(SalesRequest $request)
    {

        $validated = $request->validated();
        $validated['user_id'] = Auth::id() ?? 3;
        $sales = Sale::create($validated);

        foreach ($request['SaleDetails'] as $detail) {
            SaleDetails::create([
                'sale_id' => $sales->id,
                'sale_products_id' => $detail['sale_products_id'],
                'quantity' => $detail['quantity'],
                'unit_price' => $detail['unit_price'],
                'total' => $detail['quantity'] * $detail['unit_price'],
            ]);
        }

        // Optionally handle salesDetails and salesPayments here

        return response()->json(['status' => 200, 'message' => 'Sales Created Successfully']);
    }

    public function show($id)
    {
        $sales = Sale::findOrFail($id);
        // dd($sales);
        return Sales_and_SalesDetailsResource::make($sales->load(['salesDetails', 'salesPayments', 'user', 'customer']));
    }


    // public function update(SalesRequest $request, $id)
    // {
    //     // dd($request->all());
    //     $sales = Sales::find($id);
    //     // dd($sales);
    //     $validated = $request->validated();
    //     // dd($validated);
    //     $sales->update($validated);

    //     // Update or create sale details
    //     foreach ($validated['SaleDetails'] as $detail) {
    //         // dd($detail['id']);
    //         SaleDetails::updateOrCreate(
    //             [
    //                 'id' => $detail['id'] ?? null,  // If the sale detail has an ID, update it; otherwise, create a new one
    //                 'sales_id' => $sales->id        // Ensure the sale detail is linked to the correct sale
    //             ],
    //             [
    //                 'sale_products_id' => $detail['sale_products_id'],  // Link to the correct product
    //                 'quantity' => $detail['quantity'],                  // Update the quantity
    //                 'unit_price' => $detail['unit_price'],              // Update the unit price
    //                 'total' => $detail['total'],                        // Update the total price
    //             ]
    //         );
    //     }

    //     // Handle deleted sale details
    //     if (!empty($request->deletedIds)) {
    //         SaleDetails::destroy($request->deletedIds);  // Delete sale details by their IDs
    //     }

    //     // Return the updated sale resource
    //     return new SaleResource($sales);
    // }


    public function update(SalesRequest $request, $id)
    {
        // Find the sale
        $sales = Sale::findOrFail($id);

        // Validate the request
        $validated = $request->validated();

        // Update the sale
        $sales->update($validated);

        // Keep track of updated sale detail IDs
        $updatedSaleDetailIds = [];

        // Update or create sale details
        foreach ($validated['SaleDetails'] as $detail) {
            $saleDetail = SaleDetails::updateOrCreate(
                [
                    'id' => $detail['id'] ?? null,  // If the sale detail has an ID, update it; otherwise, create a new one
                    'sales_id' => $sales->id        // Ensure the sale detail is linked to the correct sale
                ],
                [
                    'sale_products_id' => $detail['sale_products_id'],  // Link to the correct product
                    'quantity' => $detail['quantity'],                  // Update the quantity
                    'unit_price' => $detail['unit_price'],              // Update the unit price
                    'total' => $detail['total'],                        // Update the total price
                ]
            );

            // Add the updated or created sale detail ID to the list
            $updatedSaleDetailIds[] = $saleDetail->id;
        }

        // Handle deleted sale details by deleting any sale details not included in the updated list
        SaleDetails::where('sales_id', $sales->id)
            ->whereNotIn('id', $updatedSaleDetailIds)
            ->delete();

        // Return the updated sale resource
        return new SaleResource($sales);
    }

    public function destroy($id)
    {

        $sale = Sale::findOrFail($id);

        // Delete all related sale details using the relationship
        $sale->salesDetails()->delete();

        $sale->salesPayments()->delete();
        // Delete the sale itself
        $sale->delete();

        // Return a 204 No Content response
        return response()->noContent();
    }
}
