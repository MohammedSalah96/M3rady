<?php 

namespace App\Repositories\Backend\ContactMessage;

use Illuminate\Http\Request;
use App\Models\ContactMessage;
use App\Repositories\Backend\BaseRepository;
use App\Repositories\Backend\BaseRepositoryInterface;

class ContactMessageRepository extends BaseRepository implements BaseRepositoryInterface,ContactMessageRepositoryInterface
{
    private $contactMessage;

    public function __construct(ContactMessage $contactMessage)
    {
        parent::__construct();
        $this->contactMessage = $contactMessage;
    }

    public function find($id, array $conditions = [])
    {
        return $this->contactMessage->find($id);
    }

    public function create(Request $request)
    {
        # code...
    }

    public function update(Request $request, $id, $contactMessage)
    {
        $contactMessage->reply = $request->input('reply');
        $contactMessage->status = true;
        $contactMessage->save();
        return $contactMessage;
    }

    public function delete(Request $request, $id, $contactMessage)
    {
        $contactMessage->delete();
    }

    public function multipleDelete(Request $request)
    {
        $this->contactMessage->destroy($request->input('ids'));
    }

    public function dataTable(Request $request)
    {
        return $this->contactMessage->select('*');
    }
}
