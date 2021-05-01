<?php

namespace App\Repositories\Backend\CompanyCategory;


interface CompanyCategoryRepositoryInterface
{
    public function getForCompany($company);
    public function create(array $categories, $user);
    public function update(array $categories, $user);
}
