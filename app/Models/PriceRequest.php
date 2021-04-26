<?php

namespace App\Models;

class PriceRequest extends MyModel {
 
    protected $table = "price_requests";

    protected $casts = [
        'id' => 'integer',
        'country_id' => 'integer',
        'city_id' => 'integer',
        'client_id' => 'integer',
        'name' => 'string',
        'email' => 'string',
        'mobile' => 'string',
        'country' => 'string',
        'city' => 'string',
        'request' => 'string',
        'reply' => 'string',
        'is_mine' => 'boolean'
    ];
   
    public function transformList(){
        $transformer = new \stdClass();
        $transformer->id = $this->id;
        if ($this->company_name) {
            $transformer->company_id = $this->company_id;
            $transformer->company_image = url("public/uploads/user/$this->image");
            $transformer->company_name = $this->company_name;
            $transformer->company_country = $this->company_country;
            $transformer->company_city = $this->company_city;
        }else{
            $transformer->user_image = url("public/uploads/user/$this->image");
            $transformer->user_name = $this->user_name;
            $transformer->user_country = $this->user_country;
            $transformer->user_city = $this->user_city;
        }
        if ($this->reply) {
            $transformer->answered = true;
        } else {
            $transformer->answered = false;
        }
        return $transformer;
    }

    public function transformDetails()
    {
        $transformer = new \stdClass();
        $transformer->id = $this->id;
        $transformer->name = $this->name;
        $transformer->email = $this->email;
        $transformer->mobile = $this->mobile;
        $transformer->country = $this->country;
        $transformer->country_id = $this->country_id;
        $transformer->city = $this->city;
        $transformer->city_id = $this->city_id;
        $transformer->request = $this->request;
        if ($this->images) {
            $transformer->images = $this->images ? preg_filter('/^/', url('public/uploads/price_requests') . '/', json_decode($this->images)) : [];
        }
        if ($this->reply) {
            $transformer->reply = $this->reply;
            $transformer->answered = true;
        } else {
            $transformer->answered = false;
        }
        $transformer->is_mine = $this->is_mine;
        return $transformer;
    }

    protected static function boot() {
        parent::boot();

        static::deleting(function(PriceRequest $priceRequest) {
           
            
        });
        
        static::deleted(function (PriceRequest $priceRequest) {
            
        });
    }

}
