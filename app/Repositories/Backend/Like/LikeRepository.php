<?php

namespace App\Repositories\Backend\like;

use App\Models\Like;
use Illuminate\Http\Request;
use App\Repositories\Backend\BaseRepository;

class LikeRepository extends BaseRepository implements  LikeRepositoryInterface{

   private $like;

   public function __construct(Like $like)
   {
       parent::__construct();
       $this->like =  $like;
   }

   public function find($id)
   {
      return $this->like->find($id);
   }

   public function delete(Request $request, $id, $like)
   {
      return $like->delete();
   }

   public function dataTable(Request $request)
   {
      $post = $request->input('post');
      return $this->like->join('users','likes.user_id','=','users.id')
                           ->leftJoin('company_details', 'users.id', '=', 'company_details.user_id')
                           ->where('likes.post_id',$post)
                           ->select('likes.*','users.name','company_details.company_id');
       
   }
   

}