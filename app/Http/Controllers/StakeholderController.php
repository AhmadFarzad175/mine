<?php

namespace App\Http\Controllers;

use App\Http\Requests\StakeholderRequest;
use App\Http\Resources\StakeholderResource;
use App\Models\Stakeholder;
use App\Traits\ImageManipulation;
use Illuminate\Http\Request;

class StakeholderController extends Controller
{
    use ImageManipulation;
    public function __construct()
    {
        $this->middleware('can:viewStakeholder')->only(['index', 'show']);
        $this->middleware('can:createStakeholder')->only(['store']);
        $this->middleware('can:editStakeholder')->only(['update']);
        $this->middleware('can:deleteStakeholder')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->input('perPage', 10);
        $search = $request->input('search');
        $type = $request->input('type');
    
        // Build the query  
        $query = Stakeholder::query();  
    
        if ($type) {
            // If type is provided, filter by that type
            $query->where('type', $type);
        } else {
            // If no type is provided, exclude the type "Owner"
            $query->where('type', '!=', 'Owner');
        }
    
        if ($search) {
            // Apply search filter if search term is provided
            $query->search($search);  
        }
    
        // Fetch the paginated result  
        $stakeholders = $query->latest()->paginate($perPage);  
    
        return StakeholderResource::collection($stakeholders);
    }
    

    /**
     * Store a newly created resource in storage.
     */
    public function store(StakeholderRequest $request)
    {
        $validated = $request->validated();
        $validated['user_id'] = Auth()->id();

        // store the image if exists
        $request->hasFile('image') ? $this->storeImage($request, $validated, "stakeholders", 'image') : null;

        $stakeholder = Stakeholder::create($validated);
        return new StakeholderResource($stakeholder);
    }

    /**
     * Display the specified resource.
     */
    public function show(Stakeholder $stakeholder)
    {
        return StakeholderResource::make($stakeholder);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(StakeholderRequest $request, Stakeholder $stakeholder)
    {
        // Validate the request data
        $validated = $request->validated();

        // Handle image update
        $this->updateImage($stakeholder, $request, $validated, 'stakeholders', 'image');
        // Update the owner with validated data
        $stakeholder->update($validated);


        // Return the updated resource
        return new StakeholderResource($stakeholder);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Stakeholder $stakeholder)
    {
        $this->deleteImage($stakeholder, 'stakeholders', 'image');
        $stakeholder->delete();
        return response()->noContent();
    }
}
