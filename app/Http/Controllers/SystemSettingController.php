<?php

namespace App\Http\Controllers;

use App\Http\Requests\SystemSettingRequest;
use App\Http\Resources\SystemSettingResource;
use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SystemSettingController extends Controller
{
    public function index()
    {
        // Fetch the first system setting record
        $settings = SystemSetting::first(); 
    
        // Check if the settings are found
        if (!$settings) {
            return response()->json(['error' => 'System settings not found'], 404);
        }
    
        // Return the resource if settings are found
        return new SystemSettingResource($settings);
    }

    public function store(SystemSettingRequest $request)
    {
        $validated = $request->validated();

        // Handle image upload
        if ($request->hasFile('image')) { // Changed from logo to image
            $path = $request->file('image')->store('logo', 'public'); // Store in 'logo' folder
            $validated['image'] = $path; // Change logo to image
        }

        // We only want to have one system setting record, so truncate the table
        SystemSetting::truncate();  // Clears out the old settings

        // Create the new setting
        $settings = SystemSetting::create($validated);

        return new SystemSettingResource($settings);
    }

    public function show($id)
    {
        $settings = SystemSetting::find($id);
        return SystemSettingResource::make($settings);
    }

    public function update(SystemSettingRequest $request, $id)
    {
        $settings = SystemSetting::find($id);

        $validated = $request->validated();

        // Handle image upload
        if ($request->hasFile('image')) { // Changed from logo to image
            // Delete the old image if it exists
            Storage::disk('public')->delete($settings->image); // Changed from logo to image

            // Store the new image
            $path = $request->file('image')->store('logo', 'public'); // Store in 'logo' folder
            $validated['image'] = $path; // Change logo to image
        }

        // Update the system setting with the validated data
        $settings->update($validated);

        return new SystemSettingResource($settings);
    }

    public function destroy($id)
    {
        // Find the system setting by ID
        $settings = SystemSetting::findOrFail($id);

        // Delete the image if it exists
        if ($settings->image) { // Changed from logo to image
            Storage::disk('public')->delete($settings->image); // Changed from logo to image
        }

        // Delete the system setting record from the database
        $settings->delete();

        return response()->noContent();
    }
}
