<?php

namespace App\Models;

class Device extends MyModel
{
    protected $table = "devices";
    protected $fillable = ['user_id', 'device_id', 'device_type', 'device_token', 'lang'];
    public $types = [
        'android' => 1,
        'ios' => 2
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    
}
