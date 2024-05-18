<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSkillRequest;
use App\Http\Requests\UpdateSkillRequest;
use App\Models\Skill;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SkillController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        return response()->json(Skill::all());
    }

    public function store(StoreSkillRequest $request): JsonResponse
    {
        $validData = $request->validated();
        $skill = Skill::create($validData);
        return response()->json($skill, 201);
    }

    public function show(Skill $skill): JsonResponse
    {
        return response()->json($skill);
    }

    public function update(UpdateSkillRequest $request, Skill $skill): JsonResponse
    {
        $validData = $request->validated();
        $skill->update($validData);
        return response()->json($skill);
    }

    public function destroy(Request $request, Skill $skill): JsonResponse
    {
        $skill->delete();

        return response()->json()->setStatusCode(204);
    }
}
