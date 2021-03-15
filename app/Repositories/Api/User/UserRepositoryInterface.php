<?php

namespace App\Repositories\Api\User;

use Illuminate\Http\Request;

interface UserRepositoryInterface{

    public function register(Request $reuqest);
    public function checkAuth(array $credentials);
    public function issueToken($user);
    public function logout($deviceId);
    public function updateLang($deviceId);
    public function canPost();

    public function checkUserForResest($mobile);
    public function updatePassword($user, $password);
    
    public function updateProfile(Request $request);
    public function authUserCheck();
    public function generateToken($id = null);
    
}