<?php

namespace App\Repositories\Backend\Notification;

use Illuminate\Http\Request;

interface NotificationRepositoryInterface{

    public function create(Request $request);

}