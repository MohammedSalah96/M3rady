<?php

namespace App\Models;

class Abuse extends MyModel
{
    protected $table = "abuses";

    protected $casts = [
        
        
    ];

    public function transform()
    {
       $transformer = new \stdClass();
       
       return $transformer;

    }
    
}
