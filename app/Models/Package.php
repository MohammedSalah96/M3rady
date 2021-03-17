<?php

namespace App\Models;

class Package extends MyModel {
 
    protected $table = "packages";
    protected $casts = [
        'id' => 'integer',
        'price' => 'float',
        'duration' => 'integer'
    ];
  
    public function translations() {
        return $this->hasMany(PackageTranslation::class, 'package_id');
    }

    public function transform()
    {
        $transformer = new \stdClass();
        $transformer->id = $this->id;
        $transformer->name = $this->name;
        $transformer->description = $this->description;
        $transformer->price = $this->price;
        $transformer->duration = $this->duration;
        return $transformer;
    }

    protected static function boot() {
        parent::boot();

        static::deleting(function(Package $location) {
            foreach ($location->translations as $translation) {
                $translation->delete();
            }
        });
        
        static::deleted(function (Package $location) {
            
        });
    }

}
