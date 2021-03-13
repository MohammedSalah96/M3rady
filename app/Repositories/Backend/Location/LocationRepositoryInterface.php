<?php

namespace App\Repositories\Backend\Location;

interface LocationRepositoryInterface{

    public function all(array $conditions = []);
    public function tree($id);

}