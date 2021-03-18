<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\BackendController;
use App\Repositories\Backend\Banner\BannerRepositoryInterface;
use Validator;
use DB;

class BannersController extends BackendController
{

    private $rules = [
        'active' => 'required',
        'position' => 'required',
        'image' => 'required|mimes:jpg,png,jpeg'
    ];
    private $bannerRepository;

    public function __construct(BannerRepositoryInterface $bannerRepository)
    {
        parent::__construct();
        $this->middleware('CheckPermission:banners,open', ['only' => ['index']]);
        $this->middleware('CheckPermission:banners,add', ['only' => ['store']]);
        $this->middleware('CheckPermission:banners,edit', ['only' => ['show', 'update']]);
        $this->middleware('CheckPermission:banners,delete', ['only' => ['delete']]);

        $this->data['tab'] = 'banners';
        $this->bannerRepository = $bannerRepository;
    }

    public function index(Request $request)
    {
        try {
            return $this->_view('banners.index');
        } catch (\Exception $ex) {
            session()->flash('error_message', _lang('app.something_went_wrong'));
            return redirect()->route('banners.index');
        }
       
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
            DB::beginTransaction();
            $this->bannerRepository->create($request);
            DB::commit();
            return _json('success', _lang('app.added_successfully'));
        } catch (\Exception $ex) {
            DB::rollback();
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
    {
        try {
            $banner = $this->bannerRepository->find($id);
            if (!$banner) {
                return _json('error', _lang('app.this_item_doesn\'t_exist'), 404);
            }
            return _json('success', ['model' => $banner]);
        } catch (\Exception $ex) {
            return _json('error', _lang('app.something_went_wrong'), 400);
        }
    }

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
            $banner = $this->bannerRepository->find($id);
            if (!$banner) {
                return _json('error', _lang('app.this_item_doesn\'t_exist'), 404);
            }
            $this->rules['image'] = 'mimes:jpg,png,jpeg';
            $validator = Validator::make($request->all(), $this->rules);
            if ($validator->fails()) {
                $errors = $validator->errors()->toArray();
                return _json('error', $errors);
            }

            DB::beginTransaction();
            $banner = $this->bannerRepository->update($request, $id, $banner);
            DB::commit();
            return _json('success', _lang('app.updated_successfully'));
        } catch (\Exception $ex) {
            DB::rollback();
            return _json('error', _lang('app.something_went_wrong'), 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        try {
            $banner = $this->bannerRepository->find($id);
            if (!$banner) {
                return _json('error', _lang('app.this_item_doesn\'t_exist'), 404);
            }
            DB::beginTransaction();
            $this->bannerRepository->delete($request, $id, $banner);
            DB::commit();
            return _json('success', _lang('app.deleted_successfully'));
        } catch (\Exception $ex) {
            DB::rollback();
            if ($ex->getCode() == 23000) {
                return _json('error', _lang('app.this_record_can_not_be_deleted_for_linking_to_other_records'), 400);
            } else {
                return _json('error', _lang('app.something_went_wrong'), 400);
            }
        }
    }
    
    public function data(Request $request)
    {
        $banners = $this->bannerRepository->dataTable($request);

        return \DataTables::eloquent($banners)
            ->addColumn('options', function ($item) {

                $back = "";
                if (\Permissions::check('banners', 'edit') || \Permissions::check('banners', 'delete')) {
                    if (\Permissions::check('banners', 'edit')) {
                        $back .= '<a href="" data-toggle="tooltip" title="'._lang('app.edit').'" onclick="Banners.edit(this);return false;" data-id="' . $item->id . '" class="btn btn-sm btn-clean btn-icon mr-2">
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
                    if (\Permissions::check('banners', 'delete')) {
                        $back .= '<a href="" data-toggle="tooltip" title="'._lang('app.delete').'" onclick="Banners.delete(this);return false;" data-id="' . $item->id . '" class="btn btn-sm btn-clean btn-icon">
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
            ->editColumn('image', function ($item) {
                return '<div class="d-flex align-items-center container">
                         <img class="mw-200px w-200px h-100px" src="' . url('public/uploads/banners/' . $item->image) . '" alt="photo">
                        </div>';
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
