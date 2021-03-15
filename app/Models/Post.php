<?php

namespace App\Models;

class Post extends MyModel
{
    protected $table = "posts";

    protected $casts = [
        'id' => 'integer',
        'description' => 'string'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class, 'post_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'post_id');
    }

    public function abuses()
    {
        return $this->hasMany(Abuse::class, 'post_id');
    }


    public function transform()
    {
        $transformer = new \stdClass();
        $transformer->id = $this->id;
        $transformer->images = preg_filter('/^/', url('public/uploads/posts').'/', json_decode($this->images));
        $transformer->description = $this->description;

        return $transformer;
    }


    protected static function boot()
    {
        parent::boot();

        static::deleting(function (Post $post) {
            $post->likes()->delete();
            $post->abuses()->delete();
            foreach ($post->comments as $comment) {
                $comment->delete();
            }
            
        });

        static::deleted(function (Post $post) {
            foreach (json_decode($post->images, true) as $image) {
               $post->deleteUploaded('posts', $image);
            }
        });
    }

    
}
