<?php

namespace App\Models;

class Follower extends MyModel
{
    protected $table = "followers";
    protected $fillable = ['follower_id','following_id'];

    protected $casts = [
        'id' => 'integer',
        'country' => 'string',
        'city' => 'string',
        'name' => 'string'
    ];

    public function transform()
    {
       $transformer = new \stdClass();
        $transformer->id = $this->id;
        $transformer->name = $this->name;
        $transformer->image = url("public/uploads/users/$this->image");
        $transformer->country = $this->country;
        $transformer->city = $this->city;
       return $transformer;

    }
    
}
