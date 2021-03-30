<?php

namespace App\Repositories\Backend\Like;

use Illuminate\Http\Request;

interface LikeRepositoryInterface{
    public function find($id);
    public function delete(Request $request, $id, $like);
    public function dataTable(Request $request);

}