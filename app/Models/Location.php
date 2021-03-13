<?php

namespace App\Models;

class Location extends MyModel {
 
    protected $table = "locations";
  
    public function translations() {
        return $this->hasMany(LocationTranslation::class, 'location_id');
    }

    public function childs()
    {
        return $this->hasMany(Location::class, 'parent_id');
    }


    
    public function treeTransform(){
        $transformer = new \stdClass();
        $transformer->id = $this->id;
        $transformer->name = $this->name;
        $transformer->childrens = $this->childrens ?: [];
        
        return $transformer;
    }

    protected static function boot() {
        parent::boot();

        static::deleting(function(Location $location) {
            foreach ($location->childs as $child) {
                $child->delete();
            }
            foreach ($location->translations as $translation) {
                $translation->delete();
            }
        });
        
        static::deleted(function (Location $location) {
            
        });
    }

}
