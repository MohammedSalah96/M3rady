<?php

namespace App\Http\Controllers\Admin\Auth;

use Session;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Repositories\Backend\Admin\AdminRepositoryInterface;

class LoginController extends Controller {

    use AuthenticatesUsers;
    public $redirectTo = '/admin';

    private $adminRepository;
    private $rules = array(
        'email' => 'required|email',
        'password' => 'required'
    );

    public function __construct(AdminRepositoryInterface $adminRepository) {
        $this->middleware('guest:admin', ['except' => ['logout']]);
        $this->adminRepository = $adminRepository;
    }

    public function showLoginForm() {

        return view('main_content.backend.auth.login');
    }

    public function login(Request $request) {
        try {
            $validator = Validator::make($request->all(), $this->rules);
            if ($validator->fails()) {
                $errors = $validator->errors()->toArray();
                return _json('error', $errors);
            }
            $admin = $this->adminRepository->checkAuth($request);
            if ($admin) {
                $this->adminRepository->login($admin);
                if (session()->has('url.intended')) {
                    $route = Session::get('url.intended', $this->redirectPath());
                } else {
                    $route = route('admin.dashboard');
                }
                return _json('success', $route); 
            }
            $message = _lang('messages.invalid_credentials');
            return _json('error', $message); 
        } catch (\Exception $ex) {
            return _json('error', _lang('app.something_went_wrong'), 400);
        }
    }

    public function logout() {
       $this->adminRepository->logout();
        return redirect()->route('admin.login');
    }

}
