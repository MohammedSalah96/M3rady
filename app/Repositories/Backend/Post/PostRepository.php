<?php

namespace App\Repositories\Backend\Post;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Repositories\Backend\BaseRepository;
use App\Repositories\Backend\BaseRepositoryInterface;

class PostRepository extends BaseRepository implements BaseRepositoryInterface, PostRepositoryInterface{

   private $post;

   public function __construct(Post $post)
   {
       parent::__construct();
       $this->post =  $post;
   }

   public function statistics()
   {
      return $this->post->all()->count();
   }

   public function find($id, array $conditions = [])
   {
      
      return $this->post->join('users', 'posts.user_id', '=', 'users.id')
         ->join('company_details', 'users.id', '=', 'company_details.user_id')
         ->select(
            'posts.*',
            'company_details.company_id',
            'users.image as company_image',
            \DB::raw('(select count(*) from likes where post_id = posts.id) as no_of_likes'),
            \DB::raw('(select count(*) from comments where post_id = posts.id) as no_of_comments')
         )
         ->where('posts.id',$id)
         ->first();
      
   }

   public function create(Request $request)
   {
      
   }

   public function update(Request $request, $id, $post)
   {
      $post->active = $request->input('active');
      $post->save(); 
      return $post;
   }

   public function delete(Request $request, $id, $post)
   {
      return $post->delete();
   }

   public function dataTable(Request $request)
   {
      $posts =  $this->post->join('company_details', 'posts.user_id', '=', 'company_details.user_id');
      if ($request->input('company')) {
        $posts->where('posts.user_id',$request->input('company'));
      }
      if ($request->input('from')) {
         $posts->whereDate('posts.created_at', '>=', $request->input('from'));
      }
      if ($request->input('to')) {
         $posts->whereDate('posts.created_at', '<=', $request->input('to'));
      }
      if ($request->input('has_abuses')) {
         $posts->having('no_of_abuses','>',0);
      }
      $posts = $posts->select('posts.*',
                               'company_details.company_id',
                               \DB::raw('(select count(*) from likes where post_id = posts.id) as no_of_likes'),
                               \DB::raw('(select count(*) from comments where post_id = posts.id) as no_of_comments'),
                               \DB::raw('(select count(*) from abuses where post_id = posts.id) as no_of_abuses')
                              )->orderBy('posts.created_at','desc');
      return $posts;
   }
   

}