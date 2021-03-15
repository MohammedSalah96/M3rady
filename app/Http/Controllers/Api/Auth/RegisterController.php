<?php

namespace App\Http\Controllers\Api\Auth;

use DB;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Repositories\Api\CompanyDetails\CompanyDetailsRepositoryInterface;
use App\Repositories\Api\Device\DeviceRepositoryInterface;
use App\Repositories\Api\Post\PostRepositoryInterface;
use App\Repositories\Api\User\UserRepositoryInterface;

class RegisterController extends ApiController {

    private $step_one_rules = [
        'step' => 'required|in:1',
        'type' => 'required|in:1,2',
        'email' => 'required|email|unique:users,email',
        'mobile' => 'required|unique:users,mobile',
        'password' => 'required',
        'country' => 'required',
        'city' => 'required'
    ];

    private $rules = [
        'step' => 'required|in:1,2',
        'type' => 'required|in:1,2',
        'email' => 'required|email|unique:users,email',
        'mobile' => 'required|unique:users,mobile',
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
        'main_category' => 'required',
        'sub_category' => 'required',
        'lat' => 'required',
        'lng' => 'required'
    ];

    private $userRepository;
    private $deviceRepository;
    private $companyDetailsRepository;
    private $postRepository;

    public function __construct(
        UserRepositoryInterface $userRepository,
        DeviceRepositoryInterface $deviceRepository,
        CompanyDetailsRepositoryInterface $companyDetailsRepository,
        PostRepositoryInterface $postRepository
     ) 
     {
        parent::__construct();
        $this->userRepository = $userRepository;
        $this->deviceRepository = $deviceRepository;
        $this->companyDetailsRepository = $companyDetailsRepository;
        $this->postRepository = $postRepository;

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
            if ($request->input('step') == 1) {
                $verification_code = strval(Random(4));
                //send sms to the user
                return _api_json(new \stdClass(), ['code' => $verification_code]);
            }

            DB::beginTransaction();
            $user = $this->userRepository->register($request);
            $tokenDetails = $this->userRepository->issueToken($user);
            $this->deviceRepository->createOrUpdate($request, $user);
            if ($request->input('type') == $this->userRepository->types['company']) {
            $this->companyDetailsRepository->create($request, $user);
                if ($request->file('images')) {
                   $this->postRepository->create($request, $user);
                   $this->companyDetailsRepository->decreaseFreePosts($user->id);
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
