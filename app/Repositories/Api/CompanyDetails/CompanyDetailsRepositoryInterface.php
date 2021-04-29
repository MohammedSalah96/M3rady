<?php

namespace App\Repositories\Api\CompanyDetails;

use Illuminate\Http\Request;

interface CompanyDetailsRepositoryInterface
{
    public function create(Request $reuqest, $user);
    public function update(Request $reuqest, $user);
    public function increaseFreePosts($userId = null);
}
