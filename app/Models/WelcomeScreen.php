<?php

namespace App\Models;

class WelcomeScreen extends MyModel {
 
    protected $table = "welcome_screens";

    protected $casts = [
        'id' => 'integer'
    ];
  
    public function translations() {
        return $this->hasMany(WelcomeScreenTranslation::class, 'welcome_screen_id');
    }

    public function transform()
    {
        $transformer = new \stdClass();
        $transformer->description = $this->description;
        $transformer->image = url("public/uploads/welcome_screens/$this->image");
        return $transformer;
    }

    protected static function boot() {
        parent::boot();

        static::deleting(function(WelcomeScreen $welcomeScreen) {
            foreach ($welcomeScreen->translations as $translation) {
                $translation->delete();
            }
        });
        
        static::deleted(function (WelcomeScreen $welcomeScreen) {
            $welcomeScreen->deleteUploaded('welcome_screens', $welcomeScreen->image);
        });
    }

}
