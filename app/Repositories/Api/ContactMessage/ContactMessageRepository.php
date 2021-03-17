<?php

namespace App\Repositories\Api\ContactMessage;

use Illuminate\Http\Request;
use App\Models\ContactMessage;
use App\Repositories\Api\BaseRepository;
use App\Repositories\Api\BaseRepositoryInterface;

class ContactMessageRepository extends BaseRepository implements BaseRepositoryInterface, ContactMessageRepositoryInterface
{

    private $contactMessage;

    public function __construct(ContactMessage $contactMessage)
    {
        Parent::__construct();
        $this->contactMessage = $contactMessage;
    }

    public function create(Request $request)
    {
        $contactMessage = new $this->contactMessage;
        $contactMessage->name = $request->input('name');
        $contactMessage->mobile = $request->input('mobile');
        $contactMessage->message = $request->input('message');
        $contactMessage->save();
    }
}
