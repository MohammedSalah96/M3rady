<?php

namespace App\Repositories\Backend\Comment;

use App\Models\Comment;
use Illuminate\Http\Request;
use App\Repositories\Backend\BaseRepository;

class CommentRepository extends BaseRepository implements  CommentRepositoryInterface{

   private $comment;

   public function __construct(Comment $comment)
   {
       parent::__construct();
       $this->comment =  $comment;
   }

   public function find($id)
   {
      return $this->comment->find($id);
   }

   public function delete(Request $request, $id, $comment)
   {
      return $comment->delete();
   }

   public function dataTable(Request $request)
   {
      $post = $request->input('post');
      return $this->comment->join('users','comments.user_id','=','users.id')
                           ->leftJoin('company_details', 'users.id', '=', 'company_details.user_id')
                           ->where('comments.post_id',$post)
                           ->select('comments.*','users.name','company_details.company_id');
       
   }
   

}