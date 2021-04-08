<?php

namespace App\Repositories\Backend\Location;

interface LocationRepositoryInterface{

    public function all(array $conditions = []);
    public function getByParent($parentId = 0);
    public function tree($id);
    public function statistics($country = false);

}