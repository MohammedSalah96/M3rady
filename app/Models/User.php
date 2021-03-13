<?php

namespace App\Models;

use App\Traits\ModelTrait;
use App\Interfaces\UserInterface;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements UserInterface {
    use Notifiable;
    use ModelTrait;

    public $types = [
        'client' => 1,
        'company' => 2
    ];

    public function devices()
    {
        return $this->hasMany(Device::class, 'user_id');
    }


    public function transform()
    {
        $transformer = new \stdClass();
        return $transformer;
    }

   
    
    protected static function boot() {
        parent::boot();
        static::deleting(function ($user) {
            foreach ($user->devices as $device) {
                $device->delete();
            }
        });
        
        static::deleted(function(User $user) {
            if ($user->image != 'default.png') {
                $user->deleteUploaded('users', $user->image);
            }
        });
    }
   

}
