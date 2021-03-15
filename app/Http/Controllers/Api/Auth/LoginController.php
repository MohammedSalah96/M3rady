<?php

namespace App\Http\Controllers\Api\Auth;

use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Repositories\Api\Device\DeviceRepositoryInterface;
use App\Repositories\Api\SocialUser\SocialUserRepositoryInterface;
use App\Repositories\Api\User\UserRepositoryInterface;
use Validator;

class LoginController extends ApiController {

    private $rules = array(
        'username' => 'required',
        'password' => 'required',
        'device_id' => 'required',
        'device_token' => 'required',
        'device_type' => 'required',
    );

    private $social_rules = array(
        'provider' => 'required',
        'access_token' => 'required',
        'device_id' => 'required',
        'device_token' => 'required',
        'device_type' => 'required',
    );

    private $userRepository;
    private $socialUserRepository;
    private $deviceRepository;

    public function __construct(
        UserRepositoryInterface $userRepository,
        SocialUserRepositoryInterface $socialUserRepository,
        DeviceRepositoryInterface $deviceRepository
    )
    {
        parent::__construct();
        $this->userRepository = $userRepository;
        $this->socialUserRepository = $socialUserRepository;
        $this->deviceRepository = $deviceRepository;
    }
   
    public function login(Request $request) {

        try {
            $validator = Validator::make($request->all(), $this->rules);
            if ($validator->fails()) {
                $errors = $validator->errors()->toArray();
                return _api_json(new \stdClass(), ['errors' => $errors], 400);
            }
            $user = $this->userRepository->checkAuth($request->only(['username','password']));
            if ($user) {
               DB::beginTransaction();
               $tokenDetails = $this->userRepository->issueToken($user);
               $this->deviceRepository->createOrUpdate($request, $user);
               DB::commit();
                $message = _lang('app.all_is_cool_welcome_back');
                return _api_json(
                    $user->transform(),
                    [
                        'message' => $message,
                        'token' => $tokenDetails['token'],
                        'expiration' => $tokenDetails['expiration']
                    ]
                );  
            } 
            else {
                $message = _lang('app.invalid_credentials');
                return _api_json(new \stdClass(), ['message' => $message], 400);
            }
        } catch (\Exception $ex) {
            DB::rollback();
            $message = _lang('app.something_went_wrong');
            return _api_json(new \stdClass(), ['message' => $message], 400);
        }
        
    }
    
    public function socialLogin(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), $this->social_rules);
            if ($validator->fails()) {
                $errors = $validator->errors()->toArray();
                return _api_json(new \stdClass(), ['errors' => $errors], 400);
            }
            $socialUserDetails = $this->socialUserRepository->checkSocialUser($request->only(['provider', 'access_token']));
            if (!$socialUserDetails) {
                $message = _lang('app.invalid_provider');
                return _api_json(new \stdClass(),['message' => $message], 400);
            }
            $user = $this->socialUserRepository->get($socialUserDetails);
            DB::beginTransaction();
            if (!$user) {
                $userExist = $this->socialUserRepository->checkEmail($socialUserDetails->getEmail());
                if (!$userExist) {
                    $user = $this->socialUserRepository->create($socialUserDetails, $request->input('provider'));
                }else {
                    if ($userExist->provider_user_id) {
                        $message = _lang('app.this_email_is_already_exist_with_another_social_provider');
                        return _api_json(new \stdClass(), ['message' => $message], 400);
                    } else if (!$userExist->provider_user_id) {
                        $message = _lang('app.this_email_is_already_exist_with_no_social_provider');
                        return _api_json(new \stdClass(), ['message' => $message], 400);
                    }
                }
                
            }
            $tokenDetails = $this->userRepository->issueToken($user);
            $this->deviceRepository->createOrUpdate($request, $user);
            DB::commit();

            $message = _lang('app.all_is_cool_welcome_back');
            return _api_json(
                $user->transform(),
                [
                    'message' => $message,
                    'token' => $tokenDetails['token'],
                    'expiration' => $tokenDetails['expiration']
                ]
            );  
            
        } catch (\Exception $ex) {
            DB::rollback();
            $message = _lang('app.something_went_wrong');
            return _api_json(new \stdClass(), ['message' => $message], 400);
        }
    }

}
