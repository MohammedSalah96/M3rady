<?php

namespace App\Repositories\Api\PriceRequest;

use Illuminate\Http\Request;

interface PriceRequestRepositoryInterface
{
    public function create(Request $request);
    public function update(Request $request, $priceRequest);
    public function delete($priceRequest);
    public function list(Request $request);
    public function find($id);
    public function findForAuth($id);
    public function findForCompany($id);
}
