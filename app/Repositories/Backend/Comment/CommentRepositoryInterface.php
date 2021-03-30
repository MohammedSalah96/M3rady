<?php

namespace App\Repositories\Backend\Comment;

use Illuminate\Http\Request;

interface CommentRepositoryInterface{
    public function find($id);
    public function delete(Request $request, $id, $comment);
    public function dataTable(Request $request);

}