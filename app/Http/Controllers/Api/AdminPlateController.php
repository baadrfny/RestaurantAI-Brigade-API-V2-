<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Plat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AdminPlateController extends Controller
{
    /**
     * Display a listing of all plates (admin view).
     */
    public function index()
    {
        $plates = Plat::with('category', 'ingredients')->get();
        
        return response()->json([
            'success' => true,
            'data' => $plates
        ]);
    }

    /**
     * Store a newly created plate with image upload.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'ingredients' => 'array',
            'ingredients.*' => 'exists:ingredients,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $request->all();
        
        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('plates', $imageName, 'public');
            $data['image'] = $imagePath;
        }

        $plate = Plat::create($data);

        // Attach ingredients if provided
        if ($request->has('ingredients')) {
            $plate->ingredients()->attach($request->ingredients);
        }

        return response()->json([
            'success' => true,
            'message' => 'Plate created successfully',
            'data' => $plate->load('category', 'ingredients')
        ], 201);
    }

    /**
     * Display the specified plate.
     */
    public function show($id)
    {
        $plate = Plat::with('category', 'ingredients')->find($id);

        if (!$plate) {
            return response()->json([
                'success' => false,
                'message' => 'Plate not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $plate
        ]);
    }

    /**
     * Update the specified plate with image upload.
     */
    public function update(Request $request, $id)
    {
        $plate = Plat::find($id);

        if (!$plate) {
            return response()->json([
                'success' => false,
                'message' => 'Plate not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'price' => 'sometimes|numeric|min:0',
            'category_id' => 'sometimes|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'ingredients' => 'array',
            'ingredients.*' => 'exists:ingredients,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $request->all();

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($plate->image) {
                Storage::disk('public')->delete($plate->image);
            }
            
            $image = $request->file('image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('plates', $imageName, 'public');
            $data['image'] = $imagePath;
        }

        $plate->update($data);

        // Update ingredients if provided
        if ($request->has('ingredients')) {
            $plate->ingredients()->sync($request->ingredients);
        }

        return response()->json([
            'success' => true,
            'message' => 'Plate updated successfully',
            'data' => $plate->load('category', 'ingredients')
        ]);
    }

    /**
     * Remove the specified plate.
     */
    public function destroy($id)
    {
        $plate = Plat::find($id);

        if (!$plate) {
            return response()->json([
                'success' => false,
                'message' => 'Plate not found'
            ], 404);
        }

        // Delete image if exists
        if ($plate->image) {
            Storage::disk('public')->delete($plate->image);
        }

        $plate->delete();

        return response()->json([
            'success' => true,
            'message' => 'Plate deleted successfully'
        ]);
    }
}
