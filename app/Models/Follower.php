<?php

namespace App\Models;

class Follower extends MyModel
{
    protected $table = "followers";

    protected $casts = [
        
        
    ];

    public function transform()
    {
       $transformer = new \stdClass();
       
       return $transformer;

    }
    
}
