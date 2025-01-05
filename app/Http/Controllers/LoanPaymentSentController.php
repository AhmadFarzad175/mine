<?php

namespace App\Http\Controllers;

use App\Models\LoanPaymentSent;
use App\Http\Requests\LoanPaymentSentRequest;
use App\Http\Resources\LoanPaymentSentResource;
use Illuminate\Http\Request;

class LoanPaymentSentController extends Controller
{
    // List all loan payments sent
    public function index(Request $request)
    {
        $perPage = $request->input('perPage', 10);
        $page = $request->input('page', 1);
    
        $loanPayments = LoanPaymentSent::paginate($perPage);
    
        return LoanPaymentSentResource::collection($loanPayments);
    }

    // Show a specific loan payment sent
    public function show(LoanPaymentSent $loanPaymentSent)
    {
        // Return single resource
        return new LoanPaymentSentResource($loanPaymentSent);
    }

    // Store a new loan payment sent
    public function store(LoanPaymentSentRequest $request)
    {
        $validated = $request->validated();
        $loanPaymentSent = LoanPaymentSent::create($validated);
        
        // Return newly created resource
        return new LoanPaymentSentResource($loanPaymentSent);
    }

    // Update a loan payment sent
    public function update(LoanPaymentSentRequest $request, LoanPaymentSent $loanPaymentSent)
    {
        $validated = $request->validated();
        $loanPaymentSent->update($validated);
        
        // Return updated resource
        return new LoanPaymentSentResource($loanPaymentSent);
    }

    // Delete a loan payment sent
    public function destroy(LoanPaymentSent $loanPaymentSent)
    {
        $loanPaymentSent->delete();
        
        // Optionally, return a deleted resource or a status message
        return response()->json([
            'message' => 'Loan Payment Sent deleted successfully',
        ]);
    }
}
