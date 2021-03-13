<?php

namespace App\Repositories\Backend\Setting;

use Illuminate\Http\Request;

interface SettingRepositoryInterface{
    public function all();
    public function update(Request $request);
}