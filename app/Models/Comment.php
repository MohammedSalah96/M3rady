<?php

namespace App\Models;

class Comment extends MyModel
{
    protected $table = "comments";

    protected $casts = [
        'id' => 'integer',
        'comment' => 'string',
        'image' => 'string',
        'user_id' => 'integer',
        'user_image' => 'string',
        'company_id' => 'string',
        'name' => 'string',
        'is_mine' => 'boolean'
    ];

    public function transform()
    {
       $transformer = new \stdClass();
       $transformer->id = $this->id;
       $transformer->comment = $this->comment;
       $transformer->image = $this->image ? url("public/uploads/comments/$this->image") : "";
       $transformer->user_id = $this->user_id;
       $transformer->user_type = $this->type;
       $transformer->user_image = url("public/uploads/users/$this->user_image");
       $transformer->username = $this->company_id ?: $this->name;
       if (is_bool($this->is_mine)) {
           $transformer->is_mine = $this->is_mine;
       }
       $transformer->date = $this->created_at->format('Y-m-d h:i a');
       return $transformer;

    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function (Comment $comment) {
           
        });

        static::deleted(function (Comment $comment) {
            if ($comment->image) {
                $comment->deleteUploaded('comments', $comment->image);
            }
        });
    }
    
}
