<?php

namespace App\Repositories\Api\Post;

use Illuminate\Http\Request;

interface PostRepositoryInterface
{
    public function list(Request $request);
    public function find($id);
    public function findForAuth($id);
    public function create(Request $request, $user = null);
    public function update(Request $request, $post);
    public function delete($post);
    public function deleteImage($image, $post);
}
