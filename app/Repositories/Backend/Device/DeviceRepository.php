<?php

namespace App\Repositories\Backend\Device;

use App\Models\Device;
use Illuminate\Http\Request;
use App\Repositories\Backend\BaseRepository;

class DeviceRepository extends BaseRepository implements  DeviceRepositoryInterface{

   private $device;
   public $types;
   
   public function __construct(Device $device)
   {
      parent::__construct();
      $this->device =  $device;
      $this->types =  $this->device->types;
   }

   public function getTokens($deviceType, $lang, $userType = null)
   {
      $tokens = Device::join('users', 'users.id', '=', 'devices.user_id');
      if ($userType) {
         $tokens->where('users.type', $userType);
      }
      $tokens = $tokens->where('users.active', true)
         ->where('devices.lang', $lang)
         ->where('devices.device_type', $deviceType)
         ->get()
         ->pluck('device_token')
         ->toArray();
      
         return $tokens;
   }
}