<?php

namespace App\Models;

class Like extends MyModel
{
    protected $table = "likes";
    protected $fillable = ['user_id','post_id'];

    protected $casts = [
        
        
    ];

    public function transform()
    {
       $transformer = new \stdClass();
       
       return $transformer;

    }

    

    
}
