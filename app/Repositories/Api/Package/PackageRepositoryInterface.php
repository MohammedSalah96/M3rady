<?php

namespace App\Repositories\Api\Package;

interface PackageRepositoryInterface
{
    public function list();
    public function find($id);
}
