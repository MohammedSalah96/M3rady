<?php

namespace App\Models;

use Carbon\Carbon;

class Notification extends MyModel
{
    protected $table = "notifications";
    protected $casts = [
        'id' => 'integer',
        'type' => 'integer',
        'entity' => 'integer',
        'user_type' => 'integer'
    ];

    public $types = [
        'follow' => 1,
        'like' => 2,
        'comment' => 3,
        'price_request' => 4,
        'price_request_reply' => 5,
        'general' => 6
    ];

    public $follow_messages = [
        'en' => 'started following you',
        'ar' => 'قام بمتابعتك',
        'tr' => 'seni takip etmeye başladı',
        'ud' => 'اس نے تمہاری پیروی کرنا شروع کردی'
    ];
    public $like_messages = [
        'en' => 'liked your post',
        'ar' => 'اعجب بمنشورك',
        'tr' => 'yayınınızı beğendi',
        'ud' => 'آپ کی پوسٹ کو پسند کیا'
    ];
    public $comment_messages = [
        'en' => 'commented on your post',
        'ar' => 'قام بالتعليق على منشورك',
        'tr' => 'yayınınıza yorum yaptı',
        'ud' => 'آپ کی پوسٹ پر تبصرہ کیا'
    ];
    public $price_request_messages = [
        'en' => 'sent you a price request',
        'ar' => 'ارسل إليك طلب سعر',
        'tr' => 'sana bir fiyat talebi gönderdi',
        'ud' => 'آپ کو قیمت کی درخواست بھیجی'
    ];
    public $price_request_reply_messages = [
        'en' => 'replied on you request',
        'ar' => 'قام بالرد على طلبك',
        'tr' => 'isteğiniz üzerine yanıt verdi',
        'ud' => 'آپ کی درخواست پر جواب دیا'
    ];

    public function transform()
    {
        $transformer = new \stdClass();
        $transformer->id = $this->id;
        $transformer->type = $this->type;
        if ($this->type != $this->types['general']) {
            $name = $this->company_id ? $this->{'name_'.$this->getLangCode()} : $this->name;
            $transformer->body = $name . ' ' . $this->{array_search($this->type, $this->types) . "_messages"}[$this->getLangCode()];
        }else{
            $transformer->body = $this->body;
        }
        if ($this->entity_id) {
            $transformer->entity_id = $this->entity_id;
        }else if($this->type == $this->types['follow']){
            $transformer->user_type = $this->user_type;
            $transformer->entity_id = $this->created_by;
        }
        Carbon::setLocale($this->getLangCode());
        $transformer->date_for_humans = Carbon::parse($this->created_at->setTimezone(request()->header('tz')))->diffForHumans();
        $transformer->date = $this->created_at->setTimezone(request()->header('tz'))->format('Y-m-d h:i a');

        return $transformer;
    }
    
}
