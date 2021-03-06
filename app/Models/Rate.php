<?php

namespace App\Models;

use Carbon\Carbon;

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

    public $statuses = [
        'pending' => 0,
        'accepted' => 1
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
        Carbon::setLocale($this->getLangCode());
        $transformer->date_for_humans = Carbon::parse($this->created_at->setTimezone(request()->header('tz')))->diffForHumans();
        $transformer->date = $this->created_at->setTimezone(request()->header('tz'))->format('Y-m-d h:i a');
       
        return $transformer;
    }
    
}
