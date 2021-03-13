<?php

namespace App\Repositories\Backend\Category;

interface CategoryRepositoryInterface{

    public function all(array $conditions = []);
    public function tree($id);

}