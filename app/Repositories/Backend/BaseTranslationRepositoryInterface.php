<?php

namespace App\Repositories\Backend;

use Illuminate\Http\Request;

interface BaseTranslationRepositoryInterface{

    public function getTranslations($model);
    public function create(Request $request, $model);
    public function update(Request $request, $model);
    public function delete($model);
}