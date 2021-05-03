<?php

namespace App\Repositories\Backend\Notification;

use Illuminate\Http\Request;

interface NotificationRepositoryInterface{

    public function find($id);
    public function create(Request $request);
    public function dataTable(Request $request);
    public function getTranslations($notification);
    

}