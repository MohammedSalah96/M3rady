<?php

namespace App\Repositories\Api\User;

use Illuminate\Http\Request;

interface UserRepositoryInterface{

    public function register(Request $reuqest);
    public function checkMobileUniqueness(Request $reuqest, $id = null);
    public function checkAuth(array $credentials);
    public function issueToken($user);
    public function canPost();
    public function userProfile($id);

    public function checkUserForResest($dialCode, $mobile);
    public function updatePassword($user, $password);
    
    public function updateProfile(Request $request);
    public function authUserCheck();
    public function generateToken($id = null);
    
}