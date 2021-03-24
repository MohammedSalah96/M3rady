<?php

namespace App\Http\Controllers\Api;

use DB;
use Validator;
use Illuminate\Http\Request;
use App\Helpers\Authorization;
use App\Http\Controllers\ApiController;
use App\Repositories\Api\User\UserRepositoryInterface;
use App\Repositories\Api\Device\DeviceRepositoryInterface;
use App\Repositories\Api\Follower\FollowerRepositoryInterface;
use App\Repositories\Api\CompanyDetails\CompanyDetailsRepositoryInterface;

class UserController extends ApiController {

    private $userRepository;
    private $companyDetailsRepository;
    private $followerRepository;
    private $deviceRepository;
    

    public function __construct(
        UserRepositoryInterface $userRepository,
        CompanyDetailsRepositoryInterface $companyDetailsRepository,
        FollowerRepositoryInterface $followerRepository,
        DeviceRepositoryInterface $deviceRepository) {
        parent::__construct();
        $this->userRepository = $userRepository;
        $this->companyDetailsRepository = $companyDetailsRepository;
        $this->followerRepository = $followerRepository;
        $this->deviceRepository = $deviceRepository;
    }

    public function getToken(Request $request)
    {
        try {
            $oldToken = $request->header('authorization');
            if ($oldToken) {
                $oldToken = Authorization::validateToken($oldToken);
                if ($oldToken) {
                    $newToken = new \stdClass();
                    $user = $this->userRepository->authUserCheck($oldToken->id);
                    if ($user) {
                        $newToken = $this->userRepository->generateToken($user->id);
                        return _api_json('', $newToken);
                    } else {
                        return _api_json('', ['message' => 'user not found'], 401);
                    }
                } else {
                    return _api_json('', ['message' => 'invalid token'], 401);
                }
            } else {
                return _api_json('', ['message' => 'token not provided'], 401);
            }
        } catch (\Exception $ex) {
            $message = _lang('app.something_went_wrong');
            return _api_json('', ['message' => $message], 400);
        }
    }

    protected function updateUser(Request $request) {

        $rules = array();
        $user = $this->userRepository->authUser();
       
        if ($request->input('email')) {
            $rules['email'] = "required|email|unique:users,email,". $user->id;
        }
        
        if ($request->input('mobile')) {
            $rules['step'] = "required|in:1,2";
            $rules['mobile'] =  "required|unique:users,mobile,". $user->id;
        }
        
        if ($request->file('image')) {
            $rules['image'] = 'mimes:jpg,png,jpeg';
        }
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errors = $validator->errors()->toArray();
            return _api_json(new \stdClass(), ['errors' => $errors], 400);
        } 

        try {
            DB::beginTransaction();
            if ($request->input('mobile') && $request->input('mobile') != $user->mobile) {
                if ($request->input('step') == 1) {
                    $verification_code = strval(Random(4));
                    //send sms to the user
                    return _api_json(new \stdClass(), ['code' => $verification_code]);
                }
            }

            $user = $this->userRepository->updateProfile($request);
            if ($user->type == $this->userRepository->types['company']) {
               $this->companyDetailsRepository->update($request, $user);
            }
            DB::commit();
            $message = _lang('app.updated_successfully');
            return _api_json($user->transform(), ['message' => $message ]);
        } catch (\Exception $ex) {
            DB::rollback();
            $message = _lang('app.something_went_wrong');
            return _api_json(new \stdClass(), ['message' => $message], 400);
        }

    }

    public function getUser()
    {
        try {
            $user = $this->userRepository->authUser();
            return _api_json($user->transform());
        } catch (\Exception $ex) {
            $message = _lang('app.something_went_wrong');
            return _api_json(new \stdClass(), ['message' => $message], 400);
        }
    }

    public function handleFollow($id)
    {
        try {
            $this->followerRepository->createOrDelete($id);
            return _api_json('');
        } catch (\Exception $ex) {
            $message = _lang('app.something_went_wrong');
            return _api_json(new \stdClass(), ['message' => $message], 400);
        }
    }

    public function getFollowings(Request $request)
    {
        try {
            $followings = $this->followerRepository->getFollowings($request)->transform(function ($following, $key) {
                return $following->transform();
            });
            return _api_json($followings);
        } catch (\Exception $ex) {
            $message = _lang('app.something_went_wrong');
            return _api_json([], ['message' => $message], 400);
        }
    }

    public function getFollowers(Request $request)
    {
        try {
            $followers = $this->followerRepository->getFollowers($request)->transform(function ($follower, $key) {
                return $follower->transform();
            });
            return _api_json($followers);
        } catch (\Exception $ex) {
            $message = _lang('app.something_went_wrong');
            return _api_json([], ['message' => $message], 400);
        }
    }

    public function logout(Request $request) {
        try {
            $this->deviceRepository->logout($request->input('device_id'));
            return _api_json('');
        } catch (\Exception $ex) {
            $message = _lang('app.something_went_wrong');
            return _api_json('', ['message' => $message], 400);
        }
    }

    public function updateLang(Request $request)
    {
        try {
            $this->deviceRepository->updateLang($request->input('device_id'));
            return _api_json('');
        } catch (\Exception $ex) {
            $message = _lang('app.something_went_wrong');
            return _api_json('', ['message' => $message], 400);
        }
    }

}
