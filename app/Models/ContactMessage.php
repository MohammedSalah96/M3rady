<?php

namespace App\Models;

class ContactMessage extends MyModel {

    protected $table = 'contact_messages';

    public $types = [
        'managerial' => 1,
        'suggestion' => 2
    ];

}
