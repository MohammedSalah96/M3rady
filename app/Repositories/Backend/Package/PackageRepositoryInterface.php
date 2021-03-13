<?php

namespace App\Repositories\Backend\Package;

interface PackageRepositoryInterface{

    public function all(array $conditions = []);

}