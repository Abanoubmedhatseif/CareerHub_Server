<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreIndustryRequest;
use App\Http\Requests\UpdateIndustryRequest;
use App\Models\Industry;

class IndustryController extends Controller
{
    public function index()
    {
        $industries = Industry::all();
        return response()->json($industries);
    }

    public function store(StoreIndustryRequest $request)
    {
        $validatedData = $request->validated();
        $industry = Industry::create($validatedData);
        return response()->json($industry, 201);
    }

    public function show(string $id)
    {
        $industry = Industry::findOrFail($id);
        return response()->json($industry);
    }

    public function update(UpdateIndustryRequest $request, string $id)
    {
        $industry = Industry::findOrFail($id);
        $validatedData = $request->validated();
        $industry->update($validatedData);
        return response()->json($industry);
    }

    public function destroy(string $id)
    {
        $industry = Industry::findOrFail($id);
        $industry->delete();
        return response()->json(['message' => 'Industry deleted successfully'], 204);
    }
}
