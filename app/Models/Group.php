<?php

namespace App\Models;

class Group extends MyModel
{
    protected $table = "groups";
    public function admin() {
        return $this->hasMany(Admin::class);
    }
}
