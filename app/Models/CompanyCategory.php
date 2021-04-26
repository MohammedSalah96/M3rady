<?php

namespace App\Models;

class CompanyCategory extends MyModel {

    protected $table = 'company_categories';

    public function treeTransform()
    {
        $transformer = new \stdClass();
        $transformer->id = $this->id;
        $transformer->name = $this->name;
        if (!$this->parent_id) {
            $transformer->childrens = $this->childrens ?: [];
        }
        return $transformer;
    }


}
