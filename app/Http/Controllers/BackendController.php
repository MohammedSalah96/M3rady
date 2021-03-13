<?php

namespace App\Http\Controllers;

use App\Traits\Basic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Encryption\DecryptException;

class BackendController extends Controller
{

    use Basic;
    protected $languages;
    protected $langCode = 'en';
    protected $user;
    protected $data = array();

    public function __construct() {
        $this->middleware('auth:admin');
        $this->user = Auth::guard('admin')->user();
        $this->languages = \Config::get('app.locales');
        $this->data['user'] = $this->user;
        $this->data['languages'] = $this->languages;
        $this->getCookieLangAndSetLocale();
      
    }
    

    protected function getCookieLangAndSetLocale()
    {
        if (\Cookie::get('AdminLang') !== null) {
            try {
                $this->langCode = \Crypt::decrypt(\Cookie::get('AdminLang'));
            } catch (DecryptException $ex) {
                $this->langCode = 'en';
            }
        } else {
            $this->langCode = 'en';
        }
        $this->data['lang_code'] = $this->langCode;
        app()->setLocale($this->langCode);
    }

    protected function buildTree($elements, $transformer = 'treeTransform', $parentId = 0)
    {
        $branches = array();
        foreach ($elements as $element) {
            if ($element->parent_id == $parentId) {
                $childrens = array();
                $childrens = $this->buildTree($elements, $transformer, $element->id);
                if ($childrens) {
                    $element['childrens'] = $childrens;
                }
                $branches[] = $element->{$transformer}();
            }
        }
        return $branches;
    }
    

    protected function _view($main_content, $type = 'backend') {
        $main_content = "main_content/$type/$main_content";
        return view($main_content, $this->data);
    }

    public function err404()
    {
        return $this->_view('err404');
    }


}
