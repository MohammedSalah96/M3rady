<?php

namespace App\Http\Controllers\Api\Auth;

use Validator;
use App\Helpers\SMSGateWay;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Repositories\Api\User\UserRepositoryInterface;

class PasswordController extends ApiController {

    private $userRepository;

    private $resetCodeRules = array(
        'dial_code' => 'required',
        'mobile' => 'required'
    );

    private $resetPasswordRules = array(
        'dial_code' => 'required',
        'mobile' => 'required',
        'password' => 'required'
    );


    public function __construct(UserRepositoryInterface $userRepository) {
        parent::__construct();
        $this->userRepository = $userRepository;
    }

    public function sendResetCode(Request $request) {
        try {
            $validator = Validator::make($request->all(), $this->resetCodeRules);
            if ($validator->fails()) {
                $errors = $validator->errors()->toArray();
                return _api_json('', ['errors' => $errors], 400);
            }
            $user = $this->userRepository->checkUserForResest($request->input('dial_code'),$request->input('mobile'));
            if (!$user) {
                $message = _lang('app.we_can\'t_find_a_user_with_that_mobile_number');
                return _api_json('', ['message' => $message], 400);
            }
            $verificationCode = strval(Random(4));
            //send sms with verification code to the user
            $smsGateWay = new SMSGateWay();
            $result = $smsGateWay->send('+'.$request->input('dial_code').$request->input('mobile'), $verificationCode);
            if ($result['ErrorCode'] != '000') {
                return _api_json('', ['message' => $result['ErrorMessage']], 400);
            }
            return _api_json('', ['code' => $verificationCode]);
        } catch (\Exception $ex) {
            $message = _lang('app.something_went_wrong');
            return _api_json('', ['message' => $message], 400);
        }
    }

    public function resetPassword(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), $this->resetPasswordRules);
            if ($validator->fails()) {
                $errors = $validator->errors()->toArray();
                return _api_json('', ['errors' => $errors], 400);
            }
            $user = $this->userRepository->checkUserForResest($request->input('dial_code'), $request->input('mobile'));
            if (!$user) {
                $message = _lang('app.we_can\'t_find_a_user_with_that_mobile_number');
                return _api_json('', ['message' => $message], 400);
            }
           $this->userRepository->updatePassword($user, $request->input('password'));
           $message = _lang('app.your_password_has_been_reset');
            return _api_json('', ['message' => $message]);
        } catch (\Exception $ex) {
            $message = _lang('app.something_went_wrong');
            return _api_json('', ['message' => $message], 400);
        }
    }
}
