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
        # code...
    }

    public function find($id)
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

    

    
}
