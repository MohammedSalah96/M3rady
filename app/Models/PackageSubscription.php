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
            $duration = $this->duration;
        } else {
            $duration = getDateDifferenceDays($this->start_date, $this->end_date);
        }
        $transformer->duration = $duration.' '._lang('app.days');
        $transformer->remaining = getDateDifferenceDays(date('Y-m-d'), $this->end_date) . ' ' . _lang('app.days');
        $transformer->expired = $transformer->remaining == 0 ? true : false;
        return $transformer;
    }

    
}
