<?php

namespace App\Models;

class Banner extends MyModel {
 
    protected $table = "banners";

    protected $casts = [
        'id' => 'integer',
        'image' => 'string'
    ];
   

    
    public function transform(){
        $transformer = new \stdClass();
        $transformer->image = url("public/uploads/banners/$this->image");
        return $transformer;
    }

    protected static function boot() {
        parent::boot();

        static::deleting(function(Banner $banner) {
           
            
        });
        
        static::deleted(function (Banner $banner) {
            $banner->deleteUploaded('banners', $banner->image);
        });
    }

}
