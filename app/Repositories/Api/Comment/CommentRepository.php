<?php

namespace App\Repositories\Api\Comment;

use App\Models\Comment;
use Illuminate\Http\Request;
use App\Repositories\Api\BaseRepository;
use App\Repositories\Api\BaseRepositoryInterface;

class CommentRepository extends BaseRepository implements BaseRepositoryInterface, CommentRepositoryInterface
{

    private $comment;
   
    public function __construct(Comment $comment)
    {
        Parent::__construct();
        $this->comment = $comment;
    }

    public function list(Request $request, $postId){
        return $this->getComments($postId)->paginate($this->limit);
    }

    public function find($id)
    {
        return $this->getComments(null,$id)->first();
    }

    public function create(Request $request){
        $comment = new $this->comment;
        $comment->post_id = $request->input('post_id');
        $comment->user_id = $this->authUser()->id;
        $comment->comment = $request->input('comment');
        if ($request->file('image')) {
            $comment->image = $this->comment->upload($request->file('image'), 'comments');
        }
        $comment->save();
        return $comment;
    }

    public function findForAuth($id){
        return $this->comment->where('user_id',$this->authUser()->id)->where('id',$id)->first();
    }

    public function delete($comment){
        $comment->delete();
    }

    private function getComments($postId ,$id = null)
    {
        $user = $this->authUser();
        $columns = [
            'comments.*',
            'company_details.company_id',
            'users.image as user_image',
            'users.name',
            'users.type'
        ];
        if ($user) {
            $columns[] = \DB::raw('(CASE WHEN comments.user_id = '.$user->id.' THEN 1 ELSE 0 END) as is_mine');
        }
        $comments = $this->comment->join('users','comments.user_id','=','users.id')
                                  ->leftJoin('company_details','users.id','=', 'company_details.user_id')
                                  ->where('users.active',true);
                                  if ($postId) {
                                    $comments->where('post_id', $postId);
                                  }
                                  if ($id) {
                                    $comments->where('comments.id',$id);
                                  }

        $comments = $comments->select($columns);
        
        return $comments;
    }
}
