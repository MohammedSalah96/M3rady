<?php

namespace App\Http\Controllers\Admin;

use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\BackendController;
use App\Repositories\Backend\Admin\AdminRepositoryInterface;

class ProfileController extends BackendController
{
    private $adminRepository;
    private $password_rules = [
        'current_password' => 'required',
        'new_password' => 'required',
        'new_password_confirmation' => 'required|same:new_password'
    ];
    
    public function __construct(AdminRepositoryInterface $adminRepository)
    {
        parent::__construct();
        $this->adminRepository = $adminRepository;
    }

    public function index()
    {
        $this->data['tab'] = 'personal_information';
        return $this->_view('profile.personal_information');
    }

    public function showChangePasswordForm()
    {
        $this->data['tab'] = 'change_password';
        return $this->_view('profile.change_password');
    }

    public function update(Request $request)
    {
        try {
            $rules = [
                "name" => "required",
                "email" => "required|email|unique:users,email," . $this->user->id,
                "phone" => "required|numeric",
                "image" => 'mimes:jpg,png,jpeg'
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $errors = $validator->errors()->toArray();
                return _json('error', $errors);
            }
            $this->adminRepository->updateProfile($request);
            return _json('success', _lang('app.updated_successfully'));
        } catch (Exception $ex) {
            return _json('error', _lang('app.something_went_wrong'),400);
        }
    }

    public function updatePassword(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), $this->password_rules);
            if ($validator->fails()) {
                $errors = $validator->errors()->toArray();
                return _json('error', $errors);
            }
            if (!$this->adminRepository->checkCurrentPassword($request)) {
                return _json('error', _lang('app.the_current_password_is_incorrect'),400);
            }
            $this->adminRepository->updatePassword($request);
            return _json('success', _lang('app.your_password_has_been_successfully_updated'));
        } catch (Exception $ex) {
            return _json('error', _lang('app.something_went_wrong'),400);
        }
       
    }
}
