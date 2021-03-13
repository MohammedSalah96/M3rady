<?php

namespace App\Models;

class Package extends MyModel {
 
    protected $table = "packages";
  
    public function translations() {
        return $this->hasMany(PackageTranslation::class, 'package_id');
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
