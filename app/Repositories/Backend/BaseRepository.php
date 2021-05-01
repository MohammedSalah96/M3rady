<?php

namespace App\Repositories\Backend;

use Auth;

class BaseRepository { 
    
    protected $authUser;
    protected $languages;
    protected $langCode;

    public function __construct()
    {
        $this->authUser = Auth::guard('admin')->user();
        $this->languages = array_keys(\Config::get('app.locales'));
        
        if (\Cookie::get('AdminLang') !== null) {
            try {
                $this->langCode = explode("|", \Crypt::decrypt(\Cookie::get('AdminLang')))[1];
            } catch (\DecryptException $ex) {
                $this->langCode = 'en';
            }
        } else {
            $this->langCode = 'en';
        }
       
    }

    protected function buildTree($elements, $transformer = 'treeTransform', $parentId = 0)
    {
        $branches = array();
        foreach ($elements as $key => $element) {
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

    

    

}
