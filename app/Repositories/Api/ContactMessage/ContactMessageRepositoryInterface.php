<?php

namespace App\Repositories\Api\ContactMessage;

use Illuminate\Http\Request;


interface ContactMessageRepositoryInterface
{
    public function create(Request $request);
}
