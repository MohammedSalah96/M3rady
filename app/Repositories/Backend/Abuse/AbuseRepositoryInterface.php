<?php

namespace App\Repositories\Backend\Abuse;

use Illuminate\Http\Request;

interface AbuseRepositoryInterface{
    public function find($id);
    public function delete(Request $request, $id, $abuse);
    public function dataTable(Request $request);

}