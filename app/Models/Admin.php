<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\AdminResetPasswordNotification;
use App\Traits\ModelTrait;

class Admin extends Authenticatable {

    use Notifiable;
    use ModelTrait;

    protected $table = "admins";

    public function group() {
        return $this->belongsTo(Group::class, 'group_id');
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new AdminResetPasswordNotification($token));
    }

}
