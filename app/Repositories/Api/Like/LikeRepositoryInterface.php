<?php

namespace App\Repositories\Api\Like;


interface LikeRepositoryInterface
{
    public function createOrDelete($post);
   
}
