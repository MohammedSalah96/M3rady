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
            dd($ex);
            return _json('error', _lang('app.something_went_wrong'), 400);
        }
    }

}
