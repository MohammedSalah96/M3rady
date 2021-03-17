<?php

namespace App\Repositories\Api\Post;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Repositories\Api\BaseRepository;
use App\Repositories\Api\BaseRepositoryInterface;

class PostRepository extends BaseRepository implements BaseRepositoryInterface, PostRepositoryInterface
{

    private $post;

    public function __construct(Post $post)
    {
        Parent::__construct();
        $this->post = $post;
    }

    public function list(Request $request)
    {
        return $this->getPosts($request)->paginate($this->limit);
    }

    public function find(Request $request,$id)
    {
        return $this->getPosts($request, $id)->first();
    }
    
    public function findSimple($id)
    {
        return $this->post->find($id);
    }

    public function findForAuth($id)
    {
        return $this->post->where('user_id',$this->authUser()->id)->where('id',$id)->first();
    }

    public function create(Request $request, $user = null)
    {
        $post = new $this->post;
        foreach ($request->file('images') as $image) {
           $images[] = $this->post->upload($image, 'posts');
        }
        $post->images = json_encode($images);
        if ($request->input('description')) {
            $post->description = $request->input('description');
        }
        if ($user) {
            $post->user_id = $user->id;
        }else{
            $post->user_id = $this->authUser()->id;
        }
        
        $post->save();
        return $post;
       
    }

    public function update(Request $request, $post)
    {
        $images = json_decode($post->images, true);
        foreach ($request->file('images') as $image) {
            $images[] = $this->post->upload($image, 'posts');
        }
        $post->images = json_encode($images);
        if ($request->input('description')) {
            $post->description = $request->input('description');
        }
        $post->save();
        return $post;
    }

    public function deleteImage($image, $post)
    {
        $images = json_decode($post->images, true);
        $image = str_replace(url('public/uploads/posts').'/','',$image);
        $this->post->deleteUploaded('posts', $image);
        unset($images[array_search($image, $images)]);
        $post->images = json_encode($images);
        $post->save();
    }

    public function delete($post)
    {
        $post->delete();
    }

    private function getPosts($request, $id = null)
    {
        $user = $this->authUser();
        $columns = [
            'posts.*',
            'company_details.company_id',
            'users.image as company_image',
            'country_translations.name as country',
            'city_translations.name as city',
            'package_subscriptions.id as is_featured',
            \DB::raw('(select count(*) from likes where post_id = posts.id) as number_of_likes'),
            \DB::raw('(select count(*) from comments where post_id = posts.id) as number_of_comments')
        ];
        $posts = $this->post->join('users','posts.user_id','=','users.id')
                            ->join('company_details', 'users.id','=', 'company_details.user_id')
                            ->join('location_translations as country_translations',function($query){
                                $query->on('users.country_id','=','country_translations.location_id')
                                ->where('country_translations.locale',$this->langCode);
                            })
                            ->join('location_translations as city_translations',function($query){
                                $query->on('users.city_id','=','city_translations.location_id')
                                ->where('city_translations.locale',$this->langCode);
                            })->leftJoin('package_subscriptions', function ($query) {
                                $query->on('users.id', '=', 'package_subscriptions.user_id')
                                    ->where('package_subscriptions.id', \DB::raw('(select max(id) from package_subscriptions where user_id = users.id)'))
                                    ->whereDate('package_subscriptions.end_date', '>=', date('Y-m-d'));
                            });
                            if ($request->input('company_id')) {
                                $posts->where('posts.user_id', $request->input('company_id'));
                            }
                            if ($user) {
                              
                                $posts->leftJoin('abuses',function($query) use($user){
                                    $query->on('posts.id','=','abuses.post_id')
                                    ->where('abuses.user_id',$user->id);
                                });
                                if ($request->input('likes')) {
                                    $posts->join('likes',function($query) use($user){
                                        $query->on('posts.id','=','likes.post_id')
                                              ->where('likes.user_id',$user->id);
                                    });
                                }else{
                                    $posts->leftJoin('likes',function($query) use($user){
                                        $query->on('posts.id','=','likes.post_id')
                                            ->where('likes.user_id',$user->id);
                                    });
                                }
                                if ($request->input('mine')) {
                                    $posts->where('posts.user_id',$user->id);
                                }
                                $columns = array_merge($columns, [
                                    'likes.id as is_liked',
                                    'abuses.id as is_abused',
                                   
                                ]);
                                if ($user->type == $user->types['company']) {
                                    $columns[] = \DB::raw('(CASE WHEN posts.user_id = ' . $user->id . ' THEN 1 ELSE 0 END) as is_mine');
                                }
                            }
                            if ($request->input('feed')) {
                                if ($user) {
                                    $followings = $user->followings()->get()->pluck('following_id')->toArray();
                                    $posts->whereIn('posts.user_id', $followings);
                                }
                            }
                            if ($id) {
                                $posts->where('posts.id',$id);
                            }
                            else{
                                if ($request->input('likes')) {
                                    $posts->orderBy('likes.created_at','desc');
                                }else{
                                    if ($request->input('feed')) {
                                        if ($request->input('country')) {
                                            $posts->where('users.country_id', $request->input('country'));
                                        }
                                        if ($request->input('city')) {
                                            $posts->where('users.city_id', $request->input('city'));
                                        }
                                        if ($request->input('order_by')) {
                                            switch ($request->input('order_by')) {
                                                case 1:
                                                $posts->orderBy('posts.created_at','desc');
                                                    break;
                                                case 2:
                                                    $posts->orderBy('posts.created_at','asc');
                                                    break;
                                                
                                                case 3:
                                                $posts->orderBy('package_subscriptions.id')
                                                    ->orderBy('posts.created_at','desc');
                                                    
                                                    break;
                                            }
                                        }else{
                                             $posts->orderBy('posts.created_at','desc');
                                        }
                                        
                                    }else{
                                        $posts->orderBy('posts.created_at','desc');
                                    }
                                }
                            }
                            
        $posts = $posts->select($columns);
        return $posts;
    }

    

    
}
