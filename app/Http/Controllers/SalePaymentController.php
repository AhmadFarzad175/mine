<?php

namespace App\Http\Controllers;

use App\Models\SalePayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\SalePaymentsRequest;
use App\Http\Resources\SalePaymentResource;
use App\Models\Sales;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\DB;

class SalePaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:viewSalePayments')->only(['index', 'show']);
        $this->middleware('can:createSalePayments')->only(['store']);
        $this->middleware('can:editSalePayments')->only(['update']);
        $this->middleware('can:deleteSalePayments')->only('destroy');
    }
    private function authorizeUser(SalePayment $salePayment)
    {
        if ($salePayment->user_id !== auth()->id()) {
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
        $saleId = request()->input('saleId');
        $ePayment = SalePayment::with(['sales'])->search($search, $saleId)->latest();
        $ePayments = $perPage == -1 ? $ePayment->get() : $ePayment->paginate($perPage);
        return SalePaymentResource::collection($ePayments);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(SalePaymentsRequest $request)
    {
        
        $validated = $request->validated();
        $validated['user_id'] = Auth::id() ?? 1;

    
      
    
        $salesPayment = SalePayment::create($validated);
    
        // Fetch the sale using sales_id
        $sale = $salesPayment->sales; 
    
        if (!$sale) {
            throw new \Exception('Sale not found for the given sale_id.');
        }
    
        // Increment the paid amount in the sale by the amount of the payment
        $sale->increment('paid', $salesPayment->amount);
    
        return SalePaymentResource::make($salesPayment);
    }
    

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $salePayment = SalePayment::find($id);
        return SalePaymentResource::make($salePayment);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(SalePaymentsRequest $request, $id)
    {
        // Use transaction to ensure atomicity
        return DB::transaction(function () use ($request, $id) {
            // Find the SalePayment or fail
            $salePayment = SalePayment::findOrFail($id);
            $validated = $request->validated();
    
            // Find the related sale
            $sale = $salePayment->sales;
    
            if (!$sale) {
                throw new \Exception('Sale not found for the given sale_id.');
            }
    
            // Calculate the difference between the old and new payment amounts
            $amountDifference = $validated['amount'] - $salePayment->amount;
    
            // Update the sales' paid field by adjusting the difference
            $sale->increment('paid', $amountDifference);
    
            // Set the created_by field, default to 1 if Auth is not available
            $validated['created_by'] = Auth::id() ?? 1;
    
            // Update the sale payment record
            $salePayment->update($validated);
    
            // Return the updated SalePayment as a resource
            return new SalePaymentResource($salePayment);
        });
    }


    public function destroy($id)
    {
        // Use transaction to ensure atomicity
        return DB::transaction(function () use ($id) {
            // Find the sale payment or return a 404 response
            $salePayment = SalePayment::findOrFail($id);
    
            // Access related sale directly via the relationship (assuming it's defined)
            $sale = $salePayment->sales;
    
            if (!$sale) {
                // If there's no related sale, throw an exception
                throw new \Exception('Sale not found for the given sale_id.');
            }
    
            // Increment the paid amount in the sale by the amount of the payment
            $sale->decrement('paid', $salePayment->amount);
    
            // Delete the sale payment
            $salePayment->delete();
    
            // Return a no-content response
            return response()->noContent();
        });
    }
}
