<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\BackendController;
use App\Repositories\Backend\Package\PackageRepositoryInterface;
use App\Repositories\Backend\PackageSubscription\PackageSubscriptionRepositoryInterface;
use App\Repositories\Backend\User\UserRepositoryInterface;
use Validator;
use DB;

class PackageSubscriptionsController extends BackendController
{
    private $rules = [
        'company' => 'required',
        'type' => 'required|in:trial,subscription'
    ];
    private $packageSubscriptionRepository;
    private $userRepository;
    private $packageRepository;
   
    public function __construct(
        PackageSubscriptionRepositoryInterface $packageSubscriptionRepository,
        UserRepositoryInterface $userRepository,
        PackageRepositoryInterface $packageRepository
    )
    {
        parent::__construct();
        $this->middleware('CheckPermission:package_subscriptions,open', ['only' => ['index']]);
        $this->middleware('CheckPermission:package_subscriptions,add', ['only' => ['store']]);
        $this->middleware('CheckPermission:package_subscriptions,delete', ['only' => ['destroy']]);
        $this->data['tab'] = 'package_subscriptions';
        $this->packageSubscriptionRepository = $packageSubscriptionRepository;
        $this->userRepository = $userRepository;
        $this->packageRepository = $packageRepository;
    }

    public function index(Request $request)
    {
        try {
            $this->data['companies'] = $this->userRepository->getByType($this->userRepository->types['company']);
            $this->data['packages'] = $this->packageRepository->all();
            return $this->_view('package_subscriptions.index');
        } catch (\Exception $ex) {
            session()->flash('error_message', _lang('app.something_went_wrong'));
            return redirect()->route('package_subscriptions.index');
        }
       
    }

    public function store(Request $request)
    {
        try {
            if ($request->input('type') == 'trial') {
                $this->rules['start_date'] = 'required';
                $this->rules['end_date'] = 'required';
            } else if ($request->input('type') == 'subscription') {
                $this->rules['package'] = 'required';
            }
            $validator = Validator::make($request->all(), $this->rules);
            if ($validator->fails()) {
                $errors = $validator->errors()->toArray();
                return _json('error', $errors);
            }
            $this->packageSubscriptionRepository->create($request);
            return _json('success', _lang('app.added_successfully'));
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
            $packageSubscription = $this->packageSubscriptionRepository->find($id);
            if (!$packageSubscription) {
                return _json('error', _lang('app.this_item_doesn\'t_exist'), 404);
            }
            DB::beginTransaction();
            $this->packageSubscriptionRepository->delete($request, $id, $packageSubscription);
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
        $packageSubscriptions = $this->packageSubscriptionRepository->dataTable($request);

        return \DataTables::eloquent($packageSubscriptions)
            ->addColumn('options', function ($item) {
                $back = "";
                if (\Permissions::check('package_subscriptions', 'delete')) {
                    $back .= '<a href="" data-toggle="tooltip" title="' . _lang('app.delete') . '" onclick="PackageSubscriptions.delete(this);return false;" data-id="' . $item->id . '" class="btn btn-sm btn-clean btn-icon">
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
            ->addColumn('type', function ($item) {
                if ($item->package_id) {
                    $message = _lang('app.subscription');
                    $class = 'label-light-success';
                } else {
                    $message = _lang('app.trial');
                    $class = 'label-light-info';
                }
                $back = '<span class="label label-lg font-weight-bold label-inline ' . $class . '">' . $message . '</span>';
                return $back;
            })
            ->editColumn('duration', function ($item) {
                if ($item->package_id) {
                    $back = $item->duration.' '._lang('app.months');
                } else {
                    $start_date = strtotime($item->start_date);
                    $end_date = strtotime($item->end_date);
                    $datediff = $end_date - $start_date;
                    $duration = round($datediff / (60 * 60 * 24));
                    $back = $duration . ' ' . _lang('app.days');
                }
                return $back;
            })
            ->escapeColumns([])
            ->make(true);
    }
    
}
