<?php

namespace App\Http\Controllers;

use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return response()->json($users);
    }

    public function store(StoreUserRequest $request)
    {
        $validRequest = $request->validated();

        if ($request->hasFile('profile_image')) {
            $imageUrl = Cloudinary::upload($request->file('profile_image')->getRealPath())->getSecurePath();
            $validRequest['profile_image'] = $imageUrl;
        }

        if ($request->hasFile('cover_image')) {
            $imageUrl = Cloudinary::upload($request->file('cover_image')->getRealPath())->getSecurePath();
            $validRequest['cover_image'] = $imageUrl;
        }

        $user = User::create($validRequest);
        return response()->json($user)->setStatusCode(201);
    }

    public function show($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'No user with such id'], 404);
        }
        return response()->json($user);
    }

    public function update(UpdateUserRequest $request)
    {
        $validRequest = $request->validated();
        $user = $request->user();

        if ($request->hasFile('profile_image')) {
            if ($user->profile_image) {
                $imagePublicId = $this->extractImagePublicId($user->profile_image);
                Cloudinary::destroy($imagePublicId);
            }

            $imageUrl = Cloudinary::upload($request->file('profile_image')->getRealPath())->getSecurePath();
            $validRequest['profile_image'] = $imageUrl;
        }

        if ($request->hasFile('cover_image')) {
            if ($user->cover_image) {
                $imagePublicId = $this->extractImagePublicId($user->cover_image);
                Cloudinary::destroy($imagePublicId);
            }

            $imageUrl = Cloudinary::upload($request->file('cover_image')->getRealPath())->getSecurePath();
            $validRequest['cover_image'] = $imageUrl;
        }

        $request->user()->update($validRequest);
        return response()->json($request->user());
    }

    public function destroy($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'No user with such id'], 404);
        }

        if ($user->profile_image) {
            $imagePublicId = $this->extractImagePublicId($user->profile_image);
            Cloudinary::destroy($imagePublicId);
        }

        if ($user->cover_image) {
            $imagePublicId = $this->extractImagePublicId($user->cover_image);
            Cloudinary::destroy($imagePublicId);
        }

        $user->deleteOrFail();
        return response()->json()->setStatusCode(204);
    }

    private function extractImagePublicId($imageUrl)
    {
        $parts = explode('/', $imageUrl);
        $lastPart = array_pop($parts);
        return explode('.', $lastPart)[0];
    }
}
