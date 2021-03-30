<?php

namespace App\Models;

class Notification extends MyModel
{
    protected $table = "notifications";
    protected $casts = [
        'entity' => 'integer'
    ];

    public $types = [
        'follow' => 1,
        'like' => 2,
        'comment' => 3,
        'price_request' => 4,
        'price_request_reply' => 5
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
        $name = $this->company_id ?: $this->name;
        $transformer->type = $this->type;
        $transformer->body = $name . ' ' . $this->{array_search($this->type, $this->types) . "_messages"}[$this->getLangCode()];
        if ($this->entity_id) {
            $transformer->entity_id = $this->entity_id;
        }
        $transformer->date = $this->created_at->format('Y-m-d h:i a');

        return $transformer;
    }
    
}
