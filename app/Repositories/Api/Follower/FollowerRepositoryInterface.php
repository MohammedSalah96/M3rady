<?php

namespace App\Repositories\Api\Follower;

use Illuminate\Http\Request;


interface FollowerRepositoryInterface
{
    public function createOrDelete($id);
    public function getFollowings(Request $request);
    public function getFollowers(Request $request);
   
}
