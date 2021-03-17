<?php

namespace App\Repositories\Api\Rate;

use Illuminate\Http\Request;

interface RateRepositoryInterface
{
    public function list(Request $request);
    public function create(Request $request);
    public function findForAuth($id);
    public function delete($rate);
    
}
