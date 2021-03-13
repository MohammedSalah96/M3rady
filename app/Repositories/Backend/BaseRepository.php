<?php

namespace App\Repositories\Backend;

use Auth;

class BaseRepository { 
    
    protected $user;
    protected $languages;
    protected $langCode;

    public function __construct()
    {
        $this->user = Auth::guard('admin')->user();
        $this->languages = array_keys(\Config::get('app.locales'));
        if (\Cookie::get('AdminLang') !== null) {
            try {
                $this->langCode = \Crypt::decrypt(\Cookie::get('AdminLang'));
            } catch (\DecryptException $ex) {
                $this->langCode = 'en';
            }
        } else {
            $this->langCode = 'en';
        }
       
    }

    

    

}
