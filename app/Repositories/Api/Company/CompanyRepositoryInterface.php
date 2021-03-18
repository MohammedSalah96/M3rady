<?php

namespace App\Repositories\Api\Company;

use Illuminate\Http\Request;

interface CompanyRepositoryInterface
{
    public function list(Request $request);
    public function find($id);
   
}
