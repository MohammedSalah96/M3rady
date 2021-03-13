<?php

namespace App\Http\Controllers\Admin;

use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\BackendController;
use App\Repositories\Backend\Admin\AdminRepositoryInterface;
use App\Repositories\Backend\Group\GroupRepositoryInterface;

class AdminsController extends BackendController
{
    private $adminRepository;
    private $groupRepository;

    private $rules = array(
        'name' => 'required',
        'password' => 'required',
        'email' => 'required|email|unique:admins,email',
        'phone' => 'required|numeric|unique:admins,phone',
    );

    public function __construct(AdminRepositoryInterface $adminRepository, GroupRepositoryInterface $groupRepository)
    {
        parent::__construct();
        $this->middleware('CheckPermission:admins,open', ['only' => ['index']]);
        $this->middleware('CheckPermission:admins,add', ['only' => ['store']]);
        $this->middleware('CheckPermission:admins,edit', ['only' => ['show', 'update']]);
        $this->middleware('CheckPermission:admins,delete', ['only' => ['delete']]);

        $this->adminRepository = $adminRepository;
        $this->groupRepository = $groupRepository;

        $this->data['parent_tab'] = 'admins_section';
        $this->data['tab'] = 'admins';
    }

    public function index()
    {
        $this->data['groups'] = $this->groupRepository->all();
        return $this->_view('admins.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), $this->rules);
            if ($validator->fails()) {
                $errors = $validator->errors()->toArray();
                return _json('error', $errors);
            }

            $this->adminRepository->create($request);

            return _json('success', _lang('app.added_successfully'));
        } catch (\Exception $ex) {
            return _json('error', _lang('app.something_went_wrong'), 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $admin = $this->adminRepository->find($id);

        if ($admin) {
            return _json('success', $admin);
        } else {
            return _json('error', _lang('app.something_went_wrong'), 400);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    { }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $admin = $this->adminRepository->find($id);
            if (!$admin) {
                return _json('error', _lang('app.not_found'), 404);
            }
            $this->rules['email'] = 'required|email|unique:admins,email,' . $admin->id;
            $this->rules['phone'] = 'required|numeric|unique:admins,phone,' . $admin->id;
            unset($this->rules['password']);

            $validator = Validator::make($request->all(), $this->rules);
            if ($validator->fails()) {
                $errors = $validator->errors()->toArray();
                return _json('error', $errors);
            }

            $this->adminRepository->update($request, $id, $admin);

            return _json('success', _lang('app.updated_successfully'));
        } catch (\Exception $ex) {
            return _json('error', _lang('app.something_went_wrong'), 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {
        try {
            $admin = $this->adminRepository->find($id);
            if (!$admin) {
                return _json('error', _lang('app.not_found'), 404);
            }
            $this->adminRepository->delete($request, $id, $admin);
            return _json('success', _lang('app.deleted_successfully'));
        } catch (\Exception $ex) {
            if ($ex->getCode() == 23000) {
                return _json('error', _lang('app.this_record_can_not_be_deleted_for_linking_to_other_records'), 400);
            }
            return _json('error', _lang('app.something_went_wrong'), 400);
        }
    }

    public function data(Request $request)
    {

        $admins = $this->adminRepository->dataTable($request);

        return \DataTables::eloquent($admins)
            ->addColumn('options', function ($item) {

                $back = "";
                if (\Permissions::check('admins', 'edit') || \Permissions::check('admins', 'delete')) {
                    if (\Permissions::check('admins', 'edit')) {
                        $back .= '<a href="" data-toggle="tooltip" title="'._lang('app.edit').'" onclick = "Admins.edit(this); return false;" data-id= "' . $item->id . '"  class="btn btn-sm btn-clean btn-icon mr-2">
                                    <span class="svg-icon svg-icon-md"> 
                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <rect x="0" y="0" width="24" height="24"></rect>
                                                <path d="M8,17.9148182 L8,5.96685884 C8,5.56391781 8.16211443,5.17792052 8.44982609,4.89581508 L10.965708,2.42895648 C11.5426798,1.86322723 12.4640974,1.85620921 13.0496196,2.41308426 L15.5337377,4.77566479 C15.8314604,5.0588212 16,5.45170806 16,5.86258077 L16,17.9148182 C16,18.7432453 15.3284271,19.4148182 14.5,19.4148182 L9.5,19.4148182 C8.67157288,19.4148182 8,18.7432453 8,17.9148182 Z"
                                                    fill="#000000" fill-rule="nonzero"
                                                    transform="translate(12.000000, 10.707409) rotate(-135.000000) translate(-12.000000, -10.707409) ">
                                                </path>
                                                <rect fill="#000000" opacity="0.3" x="5" y="20" width="15" height="2" rx="1"></rect>
                                            </g>
                                        </svg> 
                                    </span>
                                </a>';
                    }
                    if (\Permissions::check('admins', 'delete')) {
                        $back .= '<a href="" data-toggle="tooltip" title="'._lang('app.delete').'" onclick="Admins.delete(this);return false;" data-id="' . $item->id . '" class="btn btn-sm btn-clean btn-icon">
                                    <span class="svg-icon svg-icon-md">
                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                                            height="24px" viewBox="0 0 24 24" version="1.1">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <rect x="0" y="0" width="24" height="24"></rect>
                                                <path
                                                    d="M6,8 L6,20.5 C6,21.3284271 6.67157288,22 7.5,22 L16.5,22 C17.3284271,22 18,21.3284271 18,20.5 L18,8 L6,8 Z"
                                                    fill="#000000" fill-rule="nonzero"></path>
                                                <path
                                                    d="M14,4.5 L14,4 C14,3.44771525 13.5522847,3 13,3 L11,3 C10.4477153,3 10,3.44771525 10,4 L10,4.5 L5.5,4.5 C5.22385763,4.5 5,4.72385763 5,5 L5,5.5 C5,5.77614237 5.22385763,6 5.5,6 L18.5,6 C18.7761424,6 19,5.77614237 19,5.5 L19,5 C19,4.72385763 18.7761424,4.5 18.5,4.5 L14,4.5 Z"
                                                    fill="#000000" opacity="0.3"></path>
                                            </g>
                                        </svg>
                                    </span>
                                </a>'; 
                    } 
                }
                return $back;
            })
            ->editColumn('name',function ($item){
                return '<div class="d-flex align-items-center" style="width:200px">
                            <div class="symbol symbol-50 flex-shrink-0">
                                <img src="'.url('public/uploads/admins/'.$item->image).'" alt="photo">
                            </div>
                            <div class="ml-3">
                                <span class="text-dark-75 font-weight-bold line-height-sm d-block pb-2">'.$item->name.'</span>
                            </div>
                        </div>';
            })
            ->editColumn('email',function($item){
                return '<a class="text-dark-50 text-hover-primary" href="mailto:'.$item->email.'">'.$item->email.'</a>';
            })
            ->editColumn('active', function ($item) {
                if ($item->active == true) {
                    $message = _lang('app.active');
                    $class = 'label-light-success';
                } else {
                    $message = _lang('app.not_active');
                    $class = 'label-light-danger';
                }
                $back = '<span class="label label-lg font-weight-bold label-inline ' . $class . '">' . $message . '</span>';
                return $back;
            })
            ->escapeColumns([])
            ->make(true);
    }
}
