<?php

namespace App\Repositories\Api\CompanyCategory;


interface CompanyCategoryRepositoryInterface
{
    public function create(array $categories, $user);
    public function update(array $categories, $user);
}
