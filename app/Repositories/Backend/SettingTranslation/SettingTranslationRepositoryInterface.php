<?php

namespace App\Repositories\Backend\SettingTranslation;

use Illuminate\Http\Request;

interface SettingTranslationRepositoryInterface{
    public function all();
    public function update(Request $request);
}