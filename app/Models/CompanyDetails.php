<?php

namespace App\Models;

class CompanyDetails extends MyModel
{
    protected $table = "company_details";

    protected $casts = [
        'company_id' => 'string',
        'name_ar' => 'string',
        'name_en' => 'string',
        'description' => 'string',
        'main_category_id' => 'integer',
        'sub_category_id' => 'integer',
        'allowed_to_rate' => 'boolean',
        'lat' => 'string',
        'lng' => 'string',
        'whatsapp' => 'string',
        'facebook' => 'string',
        'twitter' => 'string',
        'website' => 'string'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transform()
    {
        $transformer = new \stdClass();
        $transformer->company_id = $this->company_id;
        $transformer->name_ar = $this->name_ar;
        $transformer->name_en = $this->name_en;
        $transformer->description = $this->description;
        $transformer->allowed_to_rate = $this->allowed_to_rate;
        $transformer->lat = $this->lat;
        $transformer->lng = $this->lng;
        $transformer->whatsapp = $this->whatsapp;
        $transformer->facebook = $this->facebook;
        $transformer->twitter = $this->twitter;
        $transformer->website = $this->website;
        return $transformer;
    }

    
}
