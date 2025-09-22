<?php

namespace App\Http\Controllers\Api\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\User\UserResource;
use App\Http\Resources\User\UserCollection;
use App\Http\Resources\User\UserProfileResource;

class UserController extends Controller
{
    /**
     * მომხმარებლების სია
     */
    public function index()
    {
        $users = User::paginate(20);
        return new UserCollection($users);
    }

    /**
     * კონკრეტული მომხმარებელი ID-ით
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        return new UserResource($user);
    }

    /**
     * მომხმარებლის პროფილი
     */
    public function profile()
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['message' => 'მომხმარებელი არ არის ავტორიზებული'], 401);
        }

        return new UserProfileResource($user);
    }

    /**
     * პროფილის განახლება
     */
    public function updateProfile(Request $request): JsonResponse
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['message' => 'მომხმარებელი არ არის ავტორიზებული'], 401);
        }

        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $user->id,
        ]);

        $user->update($request->only(['name', 'email']));

        return response()->json([
            'message' => 'პროფილი წარმატებით განახლდა',
            'user' => new UserProfileResource($user->fresh())
        ]);
    }

    /**
     * ავატარის განახლება
     */
    public function updateAvatar(Request $request): JsonResponse
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['message' => 'მომხმარებელი არ არის ავტორიზებული'], 401);
        }

        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // Delete old avatar if exists
        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        // Store new avatar
        $avatarPath = $request->file('avatar')->store('avatars', 'public');
        
        $user->update(['avatar' => $avatarPath]);

        return response()->json([
            'message' => 'ავატარი წარმატებით განახლდა',
            'avatar_url' => Storage::url($avatarPath)
        ]);
    }
}