<?php

namespace App\Http\Controllers\Admin;

use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\BackendController;
use App\Repositories\Backend\Rate\RateRepositoryInterface;
use App\Repositories\Backend\User\UserRepositoryInterface;

class RatesController extends BackendController
{
   
    private $rateRepository;
    private $userRepository;
   
    public function __construct(
        RateRepositoryInterface $rateRepository,
        UserRepositoryInterface $userRepository
    )
    {
        parent::__construct();
        $this->middleware('CheckPermission:rates,open', ['only' => ['index']]);
        $this->middleware('CheckPermission:rates,edit', ['only' => ['update']]);
        $this->middleware('CheckPermission:rates,delete', ['only' => ['delete']]);
        $this->data['parent_tab'] = 'rates';
        $this->rateRepository = $rateRepository;
        $this->userRepository = $userRepository;
    }

    public function index(Request $request)
    {
        try {
            $this->data['companies'] = $this->userRepository->getByType($this->userRepository->types['company']);
            $this->data['status'] = $request->input('status') ?: '';
            $this->data['tab'] = $request->input('status') ? $request->input('status').'_rates' : 'rates';
            return $this->_view('rates.index');
        } catch (\Exception $ex) {
            session()->flash('error_message', _lang('app.something_went_wrong'));
            return redirect()->route('rates.index');
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
            $rate = $this->rateRepository->find($id);
            if (!$rate) {
                return _json('error', _lang('app.this_item_doesn\'t_exist'), 404);
            }
            
            $this->rateRepository->update($request, $id, $rate);
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
    public function destroy(Request $request, $id)
    {
        try {
            $rate = $this->rateRepository->find($id);
            if (!$rate) {
                return _json('error', _lang('app.this_item_doesn\'t_exist'), 404);
            }
            DB::beginTransaction();
            $this->rateRepository->delete($request, $id, $rate);
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
        $rates = $this->rateRepository->dataTable($request);

        return \DataTables::eloquent($rates)
            ->addColumn('options', function ($item) {
                $back = "";
                if (\Permissions::check('rates', 'edit') && !$item->status) {
                        $back .= '<a href="#" data-toggle="tooltip" title="'._lang('app.accept').'" onclick="Rates.accept(this);return false;" data-id="' . $item->id . '" class="btn btn-sm btn-clean btn-icon">
                                    <span class="svg-icon svg-icon-md">
                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                                            height="24px" viewBox="0 0 24 24" version="1.1">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <rect x="0" y="0" width="24" height="24"/>
                                                <path d="M4.875,20.75 C4.63541667,20.75 4.39583333,20.6541667 4.20416667,20.4625 L2.2875,18.5458333 C1.90416667,18.1625 1.90416667,17.5875 2.2875,17.2041667 C2.67083333,16.8208333 3.29375,16.8208333 3.62916667,17.2041667 L4.875,18.45 L8.0375,15.2875 C8.42083333,14.9041667 8.99583333,14.9041667 9.37916667,15.2875 C9.7625,15.6708333 9.7625,16.2458333 9.37916667,16.6291667 L5.54583333,20.4625 C5.35416667,20.6541667 5.11458333,20.75 4.875,20.75 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
                                                <path d="M2,11.8650466 L2,6 C2,4.34314575 3.34314575,3 5,3 L19,3 C20.6568542,3 22,4.34314575 22,6 L22,15 C22,15.0032706 21.9999948,15.0065399 21.9999843,15.009808 L22.0249378,15 L22.0249378,19.5857864 C22.0249378,20.1380712 21.5772226,20.5857864 21.0249378,20.5857864 C20.7597213,20.5857864 20.5053674,20.4804296 20.317831,20.2928932 L18.0249378,18 L12.9835977,18 C12.7263047,14.0909841 9.47412135,11 5.5,11 C4.23590829,11 3.04485894,11.3127315 2,11.8650466 Z M6,7 C5.44771525,7 5,7.44771525 5,8 C5,8.55228475 5.44771525,9 6,9 L15,9 C15.5522847,9 16,8.55228475 16,8 C16,7.44771525 15.5522847,7 15,7 L6,7 Z" fill="#000000"/>
                                            </g>
                                        </svg>
                                    </span>
                                </a>'; 
                    } 
                    if (\Permissions::check('rates', 'delete')) {
                        $back .= '<a href="#" data-toggle="tooltip" title="'._lang('app.delete').'" onclick="Rates.delete(this);return false;" data-id="' . $item->id . '" class="btn btn-sm btn-clean btn-icon">
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
                
                return $back;
            })
            ->editColumn('status', function ($item) {
                if ($item->status == true) {
                    $message = _lang('app.accepted');
                    $class = 'label-light-success';
                } else {
                    $message = _lang('app.pending');
                    $class = 'label-light-danger';
                }
                $back = '<span class="label label-lg font-weight-bold label-inline ' . $class . '">' . $message . '</span>';
                return $back;
            })
            ->editColumn('created_at', function ($item) {
                return $item->created_at->format('Y-m-d h:i a');
            })
            ->addColumn('name', function ($item) {
                return $item->name ?: $item->company_id;
            })
            ->filterColumn('name', function ($query, $keyword) {
                $query->whereRaw('users.name like ? or company_details.company_id like ?', ["%{$keyword}%","%{$keyword}%"]);
            })
            ->escapeColumns([])
            ->make(true);
    }
    
}
