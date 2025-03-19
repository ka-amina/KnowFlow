<?php

namespace App\Interfaces;

use App\Models\User;


interface UserInterface
{
    public function register( $data);
    public function login($data);
    public function logout($user);
    public function getProfile($user);
    // public function updateProfile($user,$data);
    public function updateProfile( $data);
}