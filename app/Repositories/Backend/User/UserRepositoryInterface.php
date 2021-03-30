<?php

namespace App\Repositories\Backend\User;

interface UserRepositoryInterface{

    public function getByType($type);

}