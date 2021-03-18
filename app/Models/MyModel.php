<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\ModelTrait;



class MyModel extends Model {

    use ModelTrait;
    public function __construct(array $attributes = array()) {
        parent::__construct($attributes);
    }


    protected static function iniDiffLocations($tableName, $lat, $lng)
    {
        $diffLocations = "SQRT(POW(69.1 * ($tableName.lat - {$lat}), 2) + POW(69.1 * ({$lng} - $tableName.lng) * COS($tableName.lat / 57.3), 2)) as distance";
        return $diffLocations;
    }

}
