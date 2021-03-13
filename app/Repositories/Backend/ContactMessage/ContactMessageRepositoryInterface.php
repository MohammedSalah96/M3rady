<?php

namespace App\Repositories\Backend\ContactMessage;

use Illuminate\Http\Request;

interface ContactMessageRepositoryInterface{
    
    public function multipleDelete(Request $request);
}