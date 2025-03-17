<?php

namespace App\Repositories;

use App\Interfaces\UserInterface;
use App\Models\User;
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
}
