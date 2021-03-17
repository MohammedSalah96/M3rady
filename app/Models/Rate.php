<?php

namespace App\Models;

class Rate extends MyModel
{
    protected $table = "rates";

    protected $casts = [
        'id' => 'integer',
        'score' => 'float',
        'comment' => 'string',
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
        $transformer->score = $this->score;
        $transformer->comment = $this->comment;
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
    
}
