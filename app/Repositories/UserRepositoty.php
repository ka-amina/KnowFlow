<?php

namespace App\Repositories;

use App\Interfaces\UserInterface;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class UserRepositoty implements UserInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function register($data)
    {
        $user = new User([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password'])
        ]);

        $user->save();

        return $user;
    }

    
    public function login($data)
    {
        return User::where('email', $data['email'])->first();
    }

    public function logout($user)
    {
        $user->tokens()->delete();
    }

    public function getProfile($user)
    {
        return $user;
    }

    // public function updateProfile($user, $data)
    // {
    //     $user = User::find($user);

    //     if (isset($data['avatar']) && $data['avatar'] instanceof UploadedFile) {
    //         if ($user->avatar) {
    //             Storage::delete($user->avatar);
    //         }

    //         $avatarPath = $data['avatar']->store('avatars', 'public');
    //         $user->avatar = $avatarPath;
    //     }

    //     $user->name = $data['name'] ?? $user->name;
    //     $user->email = $data['email'] ?? $user->email;
    //     $user->biography = $data['biography'] ?? $user->biography;

    //     $user->save();

    //     return $user;
    // }


    public function updateProfile($data)
    {
        $user = $data->user();

        $data->validate([
            'name' => 'sometimes|string',
            'email' => 'sometimes|string|email|unique:users,email,' . $user->id,
            'avatar' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
            'biography' => 'sometimes|string',
            'badge_count' => 'sometimes|integer',
            'role' => 'sometimes|in:admin,teacher,student',
        ]);

        if ($data->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            } 
            $avatarPath = $data->file('avatar')->store('avatars', 'public');
            $user->avatar = $avatarPath;
        }

        $user->fill($data->except(['avatar']));
        $user->save();

        return $user;
    }

    public function searchMentor($mentor){
        try{
            $mentor=User::where('name','LIKE',"%{$mentor}%")
            ->where('role','teacher')->get();
            return response()->json([
                'success'=>true,
                'data'=>$mentor
            ],200);
        }catch(Exception $e){
            return response()->json(['error' => 'Failed to search courses'], 500);
        }
    }
}
