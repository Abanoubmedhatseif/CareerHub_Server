<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use App\Http\Controllers\Controller;
use App\Models\User;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        $validRequest = $request->validate(
            [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'confirmed', 'min:8'],
                'phone_number' => ['required', 'string', 'regex:/^01[0125][0-9]{8}$/'],
                'profile_image' => 'sometimes|image',
                'cover_image' => 'sometimes|image',
                'role' => [
                    'required',
                    Rule::in(['employer', 'candidate']),
                ]
            ],
            [
                'role.required' => "Must supply a role [employer, candidate]",
                'role.in' => 'Only [employer, candidate] roles are allowed'
            ]
        );

        if ($request->hasFile('profile_image')) {
            $imageUrl = Cloudinary::upload($request->file('profile_image')->getRealPath())->getSecurePath();
            $validRequest['profile_image'] = $imageUrl;
        }

        if ($request->hasFile('cover_image')) {
            $imageUrl = Cloudinary::upload($request->file('cover_image')->getRealPath())->getSecurePath();
            $validRequest['cover_image'] = $imageUrl;
        }

        $user = User::create($validRequest);
        return response()->json($user, 201);
    }
}
