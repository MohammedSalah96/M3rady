<?php

namespace App\Repositories\Api\Device;

use App\Models\Device;
use Illuminate\Http\Request;
use App\Repositories\Api\BaseRepository;
use App\Repositories\Api\BaseRepositoryInterface;

class DeviceRepository extends BaseRepository implements BaseRepositoryInterface, DeviceRepositoryInterface
{

    private $device;
   
    public function __construct(Device $device)
    {
        Parent::__construct();
        $this->device = $device;
    }

    public function createOrUpdate(Request $request, $user)
    {
        $this->device->updateOrCreate(
            ['device_id' => $request->input('device_id'), 'user_id' => $user->id],
            [
                'device_token' => $request->input('device_token'),
                'device_type' => $request->input('device_type'), 'lang' => $this->langCode
            ]
        );
    }

    public function logout($deviceId)
    {
        $this->device->where('user_id', $this->authUser()->id)
            ->where('device_id', $deviceId)
            ->update(['device_token' => '']);
    }

    public function updateLang($deviceId)
    {
        $this->device->where('user_id', $this->authUser()->id)
            ->where('device_id', $deviceId)
            ->update(['lang' => $this->langCode]);
    }
}
