<?php

namespace App\Http\Controllers\V2;

use App\Http\Controllers\Controller;
use App\Interfaces\UserInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected $userRepository;

    public function __construct(UserInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    
    public function register(Request $request)
{
    $request->validate([
        'name' => 'required|string',
        'email' => 'required|string|unique:users',
        'password' => 'required|string',
    ]);

    $user = $this->userRepository->register($request->all());

    $token = $user->createToken('personal access token')->plainTextToken;

    return response()->json([
        'message' => 'Successfully created user!',
        'accessToken' => $token,
    ], 201);
}

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $user = $this->userRepository->login($request->all());

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => 'failed',
                'message' => 'invalid credentials'
            ], 401);
        }

        $data['token'] = $user->createToken($request->name . 'Auth-Token')->plainTextToken;
        $data['user'] = $user;

        return response()->json([
            'status' => 'success',
            'message' => 'user is logged in successfully.',
            'data' => $data,
        ], 200);
    }

    public function logout(Request $request)
    {
        $this->userRepository->logout($request->user());

        return response()->json([
            'status' => 'success',
            'message' => 'User is logged out',
        ], 200);
    }
}
