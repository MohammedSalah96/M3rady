<?php

namespace App\Models;

class PackageSubscription extends MyModel
{
    protected $table = "package_subscriptions";
    public $types = [

    ];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function package()
    {
        return $this->belongsTo(Package::class,'package_id');
    }

    public function transform()
    {
        $transformer = new \stdClass();
        $transformer->package = $this->package;
        $transformer->start_date = $this->start_date;
        $transformer->end_date = $this->end_date;
        $transformer->type = $this->package_id ? _lang('app.subscription') : _lang('app.trial');
        if ($this->package_id) {
            $duration = $this->duration . ' ' . _lang('app.months');
        } else {
            $start_date = strtotime($this->start_date);
            $end_date = strtotime($this->end_date);
            $datediff = $end_date - $start_date;
            $duration = round($datediff / (60 * 60 * 24));
            $duration = $duration . ' ' . _lang('app.days');
        }
        $transformer->duration = $duration;
        return $transformer;
    }

    
}
