<?php

namespace App\Models;

class Rate extends MyModel
{
    protected $table = "rates";

    protected $casts = [
        
        
    ];

    public function transform()
    {
       $transformer = new \stdClass();
       
       return $transformer;

    }
    
}
