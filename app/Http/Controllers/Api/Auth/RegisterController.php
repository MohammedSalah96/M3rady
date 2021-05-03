<?php

namespace App\Http\Controllers\Api\Auth;

use App\Helpers\SMSGateWay;
use DB;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Repositories\Api\Post\PostRepositoryInterface;
use App\Repositories\Api\User\UserRepositoryInterface;
use App\Repositories\Api\Device\DeviceRepositoryInterface;
use App\Repositories\Api\CompanyDetails\CompanyDetailsRepositoryInterface;
use App\Repositories\Api\CompanyCategory\CompanyCategoryRepositoryInterface;

class RegisterController extends ApiController {

    private $step_one_rules = [
        'step' => 'required|in:1',
        'type' => 'required|in:1,2',
        'email' => 'required|email|unique:users,email',
        'dial_code' => 'required',
        'mobile' => 'required',
        'password' => 'required',
        'country' => 'required',
        'city' => 'required'
    ];

    private $rules = [
        'step' => 'required|in:1,2',
        'type' => 'required|in:1,2',
        'email' => 'required|email|unique:users,email',
        'dial_code' => 'required',
        'mobile' => 'required',
        'password' => 'required',
        'country' => 'required',
        'city' => 'required',
        'device_id' => 'required',
        'device_token' => 'required',
        'device_type' => 'required'
    ];

    private $client_rules = [
        'name' => 'required'
    ];

    private $company_rules = [
        'company_id' => 'required|unique:company_details,company_id',
        'name_ar' => 'required',
        'name_en' => 'required',
        'company_description' => 'required',
        'categories' => 'required'
    ];

    private $userRepository;
    private $deviceRepository;
    private $companyDetailsRepository;
    private $postRepository;
    private $companyCategoryRepository;

    public function __construct(
        UserRepositoryInterface $userRepository,
        DeviceRepositoryInterface $deviceRepository,
        CompanyDetailsRepositoryInterface $companyDetailsRepository,
        PostRepositoryInterface $postRepository,
        CompanyCategoryRepositoryInterface $companyCategoryRepository
     ) 
     {
        parent::__construct();
        $this->userRepository = $userRepository;
        $this->deviceRepository = $deviceRepository;
        $this->companyDetailsRepository = $companyDetailsRepository;
        $this->postRepository = $postRepository;
        $this->companyCategoryRepository = $companyCategoryRepository;

    }

    public function register(Request $request) {
        try {
            if ($request->input('step') == 1) {
                if ($request->input('type') == $this->userRepository->types['client']) {
                    $rules= array_merge($this->step_one_rules, $this->client_rules);
                }
                $rules = $this->step_one_rules;
            }else if($request->input('step') == 2){
                if ($request->input('type') == $this->userRepository->types['client']) {
                    $rules = $this->client_rules;
                }else{
                    $rules =  $this->company_rules;
                }
                $rules = array_merge($this->rules, $rules);
            }
            
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $errors = $validator->errors()->toArray();
                return _api_json(new \stdClass(), ['errors' => $errors], 400);
            }
            if ($this->userRepository->checkMobileUniqueness($request)) {
                $errors = ['mobile' => [_lang('app.the_mobile_has_already_been_taken')]];
                return _api_json(new \stdClass(), ['errors' => $errors], 400);
            }
            if ($request->input('step') == 1) {
                $verificationCode = strval(Random(4));
                $smsGateWay = new SMSGateWay();
                $result = $smsGateWay->send('+'.$request->input('dial_code').$request->input('mobile'), $verificationCode);
                if ($result['ErrorCode'] != '000') {
                    return _api_json(new \stdClass(), ['message' => $result['ErrorMessage']], 400);
                }
                return _api_json(new \stdClass(), ['code' => $verificationCode]);
            }

            DB::beginTransaction();
            $user = $this->userRepository->register($request);
            $tokenDetails = $this->userRepository->issueToken($user);
            $this->deviceRepository->createOrUpdate($request, $user);
            if ($request->input('type') == $this->userRepository->types['company']) {
                $this->companyDetailsRepository->create($request, $user);
                $this->companyCategoryRepository->create(json_decode($request->input('categories')), $user);
                if ($request->file('images')) {
                   $this->postRepository->create($request, $user);
                   $this->companyDetailsRepository->increaseFreePosts($user->id);
                }
            }
            DB::commit();

            $message = _lang('app.all_is_cool_welcome');
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
            dd($ex);
            $message = _lang('app.something_went_wrong');
            return _api_json(new \stdClass(), ['message' => $message], 400);
        }
    }
}
