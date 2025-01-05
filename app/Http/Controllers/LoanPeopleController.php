<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoanPeopleRequest;
use App\Http\Resources\LoanPeopleResource;
use App\Models\LoanPeople;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class LoanPeopleController extends Controller
{

    public function __construct()
    {
        $this->middleware('can:loanPeopleCreate')->only(['index', 'show']);
        $this->middleware('can:loanPeopleEdit')->only('store');
        $this->middleware('can:loanPeopleView')->only('update');
        $this->middleware('can:loanPeopleDelete')->only('destroy');
    }
    // List all loan people
    public function index(Request $request)
    {
        $perPage = $request->input('perPage', 10); // Default to 10 items per page
        $loanPeople = LoanPeople::paginate($perPage); // Use paginate instead of all
    
        return LoanPeopleResource::collection($loanPeople);
    }

    // Store a new loan person
    public function store(LoanPeopleRequest $request): JsonResponse
    {
        // The request is already validated by LoanPeopleRequest
        $loanPerson = LoanPeople::create($request->validated());

        return response()->json(new LoanPeopleResource($loanPerson), 201);
    }

    // Show a specific loan person
    public function show($id): JsonResponse
    {
        $loanPerson = LoanPeople::find($id);

        if (!$loanPerson) {
            return response()->json(['message' => 'Loan person not found'], 404);
        }

        return response()->json(new LoanPeopleResource($loanPerson));
    }

    // Update a loan person
    public function update(LoanPeopleRequest $request, $id): JsonResponse
    {
        // Find the LoanPeople entry by ID
        $loanPerson = LoanPeople::findOrFail($id);

        // Update the loanPerson with validated data
        $loanPerson->update($request->validated());

        return response()->json(new LoanPeopleResource($loanPerson), Response::HTTP_OK);
    }

    // Delete a loan person
    public function destroy($id): JsonResponse
    {
        $loanPerson = LoanPeople::find($id);

        if (!$loanPerson) {
            return response()->json(['message' => 'Loan person not found'], 404);
        }

        $loanPerson->delete();

        return response()->json(['message' => 'Loan person deleted successfully']);
    }
}
