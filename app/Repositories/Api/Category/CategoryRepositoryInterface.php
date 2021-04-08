<?php

namespace App\Repositories\Api\Category;

use Illuminate\Http\Request;

interface CategoryRepositoryInterface
{
    public function getTree();
    public function list(Request $request);
}
