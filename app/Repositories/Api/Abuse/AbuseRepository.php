<?php

namespace App\Repositories\Api\Abuse;

use App\Models\Abuse;
use App\Repositories\Api\BaseRepository;
use App\Repositories\Api\BaseRepositoryInterface;

class AbuseRepository extends BaseRepository implements BaseRepositoryInterface, AbuseRepositoryInterface
{

    private $abuse;

    public function __construct(Abuse $abuse)
    {
        Parent::__construct();
        $this->abuse = $abuse;
    }

    public function create($post)
    {
        $user = $this->authUser();
        $abused = $this->abuse->where('post_id', $post->id)->where('user_id', $user->id)->first();
        if (!$abused) {
            $abuse = new $this->abuse;
            $abuse->user_id = $user->id;
            $abuse->post_id = $post->id;
            $abuse->save();
        }
        
    }
}
