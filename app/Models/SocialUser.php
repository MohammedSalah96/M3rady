<?php

namespace App\Models;

class SocialUser extends MyModel
{
    protected $table = "social_users";

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    
}
