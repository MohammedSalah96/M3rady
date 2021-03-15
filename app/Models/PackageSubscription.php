<?php

namespace App\Models;

class PackageSubscription extends MyModel
{
    protected $table = "package_subscriptions";

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function package()
    {
        return $this->belongsTo(Package::class,'package_id');
    }

    
}
