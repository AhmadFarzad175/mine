<?php
namespace App\Http\Controllers;

use App\Http\Requests\LoanPaymentReceivedRequest;
use App\Models\LoanPaymentReceived;
use App\Http\Resources\LoanPaymentReceivedResource;
use Illuminate\Http\Request;

class LoanPaymentReceivedController extends Controller
{

  
    // List all received payments with pagination
    public function index(Request $request)
    {
        $perPage = $request->input('perPage', 10);
        $payments = LoanPaymentReceived::paginate($perPage);

        return LoanPaymentReceivedResource::collection($payments);
    }

    // Store a new payment received
    public function store(LoanPaymentReceivedRequest $request)
    {
        $validated = $request->validated();
        $paymentReceived = LoanPaymentReceived::create($validated);

        return response()->json(new LoanPaymentReceivedResource($paymentReceived), 201);
    }

    // Show a specific payment received
    public function show($id)
    {
        $paymentReceived = LoanPaymentReceived::find($id);

        if (!$paymentReceived) {
            return response()->json(['message' => 'Payment not found'], 404);
        }

        return new LoanPaymentReceivedResource($paymentReceived);
    }

    // Update a payment received
    public function update(LoanPaymentReceivedRequest $request, $id)
    {
        $paymentReceived = LoanPaymentReceived::find($id);

        if (!$paymentReceived) {
            return response()->json(['message' => 'Payment not found'], 404);
        }

        $validated = $request->validated();
        $paymentReceived->update($validated);

        return new LoanPaymentReceivedResource($paymentReceived);
    }

    // Delete a payment received
    public function destroy($id)
    {
        $paymentReceived = LoanPaymentReceived::find($id);

        if (!$paymentReceived) {
            return response()->json(['message' => 'Payment not found'], 404);
        }

        $paymentReceived->delete();

        return response()->json(['message' => 'Payment deleted successfully']);
    }
}
