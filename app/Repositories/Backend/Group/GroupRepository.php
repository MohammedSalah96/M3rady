<?php

namespace App\Repositories\Backend\Group;

use App\Models\Group;
use Illuminate\Http\Request;
use App\Repositories\Backend\BaseRepository;
use App\Repositories\Backend\BaseRepositoryInterface;

class GroupRepository extends BaseRepository implements BaseRepositoryInterface, GroupRepositoryInterface{

    private $group;

    public function __construct(Group $group)
    {
        parent::__construct();
        $this->group = $group;
    }
   
    public function all()
    {
       return $this->group->where('type',1)->where('created_by', $this->user->id)->get();
    }

    public function find($id, array $conditions = [])
    {
        if (!empty($conditions)) {
            return $this->group->where($conditions)->where('id', $id)->first();
        }
        return $this->group->find($id);
    }

    public function create(Request $request)
    {
        $group = new $this->group;
        $group->name = $request->input('name');
        $group->permissions = json_encode($request->input('group_options'));
        $group->active = $request->input('active');
        $group->created_by = $this->user->id;
        $group->save();

        return $group;
    }

    public function update(Request $request, $id, $group)
    {
        $group->name = $request->input('name');
        $group->permissions = json_encode($request->input('group_options'));
        $group->active = $request->input('active');
        $group->save();

        return $group;
    }

    public function delete(Request $request, $id, $group)
    {
        return $group->delete();
    }

    public function dataTable(Request $request)
    {
        return $this->group->where('type', 1)
                            //->where('created_by', $this->user->id)
                            ->select('*');
    }

}
