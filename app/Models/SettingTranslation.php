<?php

namespace App\Models;

class SettingTranslation extends MyModel {

    protected $table = "settings_translations";
    protected $fillable=['locale','policy','about_us'];
    protected $hidden = array('id','locale','created_at','updated_at');
}
