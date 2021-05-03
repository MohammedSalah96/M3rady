<?php

namespace App\Http\Controllers\Admin;

use DB;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\BackendController;
use App\Repositories\Backend\Notification\NotificationRepositoryInterface;

class NotificationsController extends BackendController {

    private $rules = array(
        
    );

    private $notificationRepository;

    public function __construct(NotificationRepositoryInterface $notificationRepository)
    {
        parent::__construct();
        $this->middleware('CheckPermission:notifications,open', ['only' => ['index']]);
        $this->data['tab'] = 'notifications';

        $this->notificationRepository = $notificationRepository;
       
    }
 
    public function index(Request $request) {
        return $this->_view('notifications.index');
    }

    public function store(Request $request) {
        try {
            $columns_arr = array(
                'body' => 'required'
            );
            $lang_rules = $this->lang_rules($columns_arr);
            $this->rules = array_merge($this->rules, $lang_rules);
            $validator = Validator::make($request->all(), $this->rules);
            if ($validator->fails()) {
                $errors = $validator->errors()->toArray();
                return _json('error', $errors);
            }

            DB::beginTransaction();
            $this->notificationRepository->create($request);
            DB::commit();
            return _json('success', _lang('app.sended_successfully'));
        } catch (Exception $ex) {
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
            $notification = $this->notificationRepository->find($id);
            if (!$notification) {
                return _json('error', _lang('app.this_item_doesn\'t_exist'), 404);
            }
            $translations = $this->notificationRepository->getTranslations($notification);
            return _json('success', ['model' => $notification, 'translations' => $translations]);
        } catch (\Exception $ex) {
            return _json('error', _lang('app.something_went_wrong'), 400);
        }
    }

    public function data(Request $request)
    {
        $notifications = $this->notificationRepository->dataTable($request);

        return \DataTables::eloquent($notifications)
            ->addColumn('options', function ($item) {
                $back = "";
                if (\Permissions::check('notifications', 'open')) {
                    $back .= '<a href="#" data-toggle="tooltip" title="' . _lang('app.resend') . '" onclick="Notifications.edit(this);return false;" data-id="' . $item->id . '" class="btn btn-sm btn-clean btn-icon">
                                    <span class="svg-icon svg-icon-md">
                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                                            height="24px" viewBox="0 0 24 24" version="1.1">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <rect x="0" y="0" width="24" height="24"/>
                                                <path d="M8.43296491,7.17429118 L9.40782327,7.85689436 C9.49616631,7.91875282 9.56214077,8.00751728 9.5959027,8.10994332 C9.68235021,8.37220548 9.53982427,8.65489052 9.27756211,8.74133803 L5.89079566,9.85769242 C5.84469033,9.87288977 5.79661753,9.8812917 5.74809064,9.88263369 C5.4720538,9.8902674 5.24209339,9.67268366 5.23445968,9.39664682 L5.13610134,5.83998177 C5.13313425,5.73269078 5.16477113,5.62729274 5.22633424,5.53937151 C5.384723,5.31316892 5.69649589,5.25819495 5.92269848,5.4165837 L6.72910242,5.98123382 C8.16546398,4.72182424 10.0239806,4 12,4 C16.418278,4 20,7.581722 20,12 C20,16.418278 16.418278,20 12,20 C7.581722,20 4,16.418278 4,12 L6,12 C6,15.3137085 8.6862915,18 12,18 C15.3137085,18 18,15.3137085 18,12 C18,8.6862915 15.3137085,6 12,6 C10.6885336,6 9.44767246,6.42282109 8.43296491,7.17429118 Z" fill="#000000" fill-rule="nonzero"/>
                                            </g>
                                        </svg>
                                    </span>
                                </a>';
                }

                return $back;
            })
            ->editColumn('created_at', function ($item) {
                return $item->created_at->format('Y-m-d h:i a');
            })
            ->escapeColumns([])
            ->make(true);
    }

}
