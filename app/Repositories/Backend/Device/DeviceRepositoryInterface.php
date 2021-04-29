<?php

namespace App\Repositories\Backend\Device;

interface DeviceRepositoryInterface{

    public function getTokens($deviceType, $lang, $userType = null);

}