<?php

namespace App\Repositories\Backend\Category;

interface CategoryRepositoryInterface{

    public function all(array $conditions = []);
    public function getByParent($parentId = 0);
    public function tree($id);

}