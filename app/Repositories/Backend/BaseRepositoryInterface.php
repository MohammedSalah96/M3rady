<?php

namespace App\Repositories\Backend;

use Illuminate\Http\Request;

interface BaseRepositoryInterface{

    public function find($id, array $conditions = []);
    public function create(Request $request);
    public function update(Request $request, $id, $model);
    public function delete(Request $request, $id, $model);
    public function dataTable(Request $request);
   
    
}