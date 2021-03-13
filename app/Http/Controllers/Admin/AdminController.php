<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\BackendController;

class AdminController extends BackendController
{

    public function __construct()
    {
        parent::__construct();
        $this->data['tab'] = 'dashboard';
    }
    /**
     * Show the application dashboard.
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {      
            return $this->_view("index");
        } catch (\Exception $ex) {
            return $this->_view('err404');
        }
        
    }

    public function error()
    {
        return $this->_view('err404');
    }
    

}
