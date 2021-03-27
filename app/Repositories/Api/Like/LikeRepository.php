<?php

namespace App\Repositories\Api\Like;

use App\Models\Like;
use App\Repositories\Api\BaseRepository;
use App\Repositories\Api\BaseRepositoryInterface;

class LikeRepository extends BaseRepository implements BaseRepositoryInterface, LikeRepositoryInterface
{

    private $like;
   
    public function __construct(Like $like)
    {
        Parent::__construct();
        $this->like = $like;
    }

    public function createOrDelete($post)
    {
        $liked = $this->like->where('post_id',$post->id)->where('user_id',$this->authUser()->id)->first();
        if ($liked) {
            $liked->delete();
            return false;
        }else{
            $this->like->create([
                'user_id' => $this->authUser()->id,
                'post_id' => $post->id,
            ]);
            return true;
        }
    }
}
