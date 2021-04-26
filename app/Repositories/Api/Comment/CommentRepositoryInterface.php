<?php

namespace App\Repositories\Api\Comment;

use Illuminate\Http\Request;


interface CommentRepositoryInterface
{
    public function list(Request $request, $postId);
    public function create(Request $request);
    public function update(Request $request, $comment);
    public function find($id);
    public function findForAuth($id);
    public function delete($comment);
   
}
