<?php

namespace App\Repositories\Api\Follower;

use App\Models\Follower;
use Illuminate\Http\Request;
use App\Repositories\Api\BaseRepository;
use App\Repositories\Api\BaseRepositoryInterface;

class FollowerRepository extends BaseRepository implements BaseRepositoryInterface, FollowerRepositoryInterface
{

    private $follower;
   
    public function __construct(Follower $follower)
    {
        Parent::__construct();
        $this->follower = $follower;
    }

    public function createOrDelete($id)
    {
        $follower = $this->follower->where('following_id',$id)->where('follower_id',$this->authUser()->id)->first();
        if ($follower) {
            $follower->delete();
        }else{
            $follower = new $this->follower;
            $follower->follower_id = $this->authUser()->id;
            $follower->following_id = $id;
            $follower->save();
        }
    }

    public function getFollowings(Request $request){
        return $this->handleList($type = 'followings')->paginate($this->limit);
    }

    public function getFollowers(Request $request){
        return $this->handleList($type = 'followers')->paginate($this->limit);
    }

    public function handleList($type)
    {
        $columns = [
            'users.id',
            'users.image',
            'country_translations.name as country',
            'city_translations.name as city'
        ];
        
        if ($type == 'followings') {
            $list = $this->follower->join('users', 'followers.following_id', '=', 'users.id')
                                ->join('company_details', 'users.id','=', 'company_details.user_id')
                                ->where('followers.follower_id', $this->authUser()->id);
                                $columns[] = 'company_details.company_id as name';
        }else if($type == 'followers'){
            $list = $this->follower->join('users', 'followers.follower_id', '=', 'users.id')
                                    ->where('followers.following_id', $this->authUser()->id);
                                $columns[] = 'users.name';
        }
        $list->join('location_translations as country_translations',function($query){
                                $query->on('users.country_id','=','country_translations.location_id')
                                ->where('country_translations.locale',$this->langCode);
                            })
                            ->join('location_translations as city_translations',function($query){
                                $query->on('users.city_id','=','city_translations.location_id')
                                ->where('city_translations.locale',$this->langCode);
                            });
        return $list = $list->select($columns);
                
    }

    
}
