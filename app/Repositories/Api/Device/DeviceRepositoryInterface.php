<?php

namespace App\Repositories\Api\Device;

use Illuminate\Http\Request;

interface DeviceRepositoryInterface
{
    public function createOrUpdate(Request $reuqest, $user);
    public function logout($deviceId);
    public function updateLang($deviceId);
   
}
