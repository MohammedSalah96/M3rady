<?php

namespace App\Repositories\Backend\Admin;

use Illuminate\Http\Request;

interface AdminRepositoryInterface{
    public function login($admin);
    public function logout();
    public function checkAuth(Request $request);
    public function updateProfile(Request $request);
    public function checkCurrentPassword(Request $request);
    public function updatePassword(Request $request);

}