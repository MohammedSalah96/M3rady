<?php

namespace App\Models;

class Setting extends MyModel {

    protected $table = 'settings';
    protected $fillable=['name','value'];
   

}
