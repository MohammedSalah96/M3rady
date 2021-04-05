<?php

namespace App\Repositories\Backend\Rate;

use Illuminate\Http\Request;

interface RateRepositoryInterface{
    public function find($id);
    public function update(Request $request, $id, $rate);
    public function delete(Request $request, $id, $rate);
    public function dataTable(Request $request);

}