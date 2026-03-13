<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Plat;

class PlatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $plats = Plat::all();
        return response()->json($plats);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'price' => 'required',
            'category_id' => 'required',
        ]);

        $plat = Plat::create($request->all());
        return response()->json($plat, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $plat = Plat::find($id);
        return response()->json($plat);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $plat = Plat::find($id);

        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'price' => 'required',
            'category_id' => 'required',
        ]);
        
        $plat->update($request->all());
        return response()->json($plat);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $plat = Plat::find($id);
        $plat->delete();
        return response()->json(['message' => 'Plat supprimé avec succès'], 200);
    }
}
