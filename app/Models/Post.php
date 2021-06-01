<?php

namespace App\Models;

use Carbon\Carbon;

class Post extends MyModel
{
    protected $table = "posts";

    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'description' => 'string',
        'company_id' => 'string',
        'country' => 'string',
        'city' => 'string',
        'number_of_likes' => 'integer',
        'number_of_comments' => 'integer',
        'is_mine' => 'boolean',
        'is_liked' => 'boolean',
        'is_abused' => 'boolean'
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
        $transformer->user_id = $this->user_id;
        if ($this->getLangCode() == 'ar') {
            $transformer->name = $this->name_ar;
        } else {
            $transformer->name = $this->name_en;
        }
        $transformer->company_id = $this->company_id;
        $transformer->image = url("public/uploads/users/$this->company_image");
        $transformer->is_featured = $this->is_featured ? true : false;
        $transformer->country = $this->country;
        $transformer->city = $this->city;
        $transformer->images = preg_filter('/^/', url('public/uploads/posts') . '/', json_decode($this->images));
        $transformer->description = $this->description;
        $transformer->number_of_likes = $this->number_of_likes;
        $transformer->number_of_comments = $this->number_of_comments;
        Carbon::setLocale($this->getLangCode());
        $transformer->date_for_humans = Carbon::parse($this->created_at->setTimezone(request()->header('tz')))->diffForHumans();
        $transformer->date = $this->created_at->setTimezone(request()->header('tz'))->format('Y-m-d h:i a');
        if ($this->auth_user()) {
            $transformer->is_liked = $this->is_liked ? true : false;
            $transformer->is_abused = $this->is_abused ? true : false;
            $transformer->following = $this->following ? true : false;
            
            if (is_bool($this->is_mine)) {
                $transformer->is_mine = $this->is_mine;
            }
        }
       
        
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
