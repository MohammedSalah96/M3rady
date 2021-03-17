<?php

namespace App\Repositories\Api\Package;

use App\Models\Package;
use App\Repositories\Api\BaseRepository;
use App\Repositories\Api\BaseRepositoryInterface;

class PackageRepository extends BaseRepository implements BaseRepositoryInterface, PackageRepositoryInterface
{

    private $package;
   
    public function __construct(Package $package)
    {
        Parent::__construct();
        $this->package = $package;
    }
    public function list(){
        return $this->getPackages()->get();
    }
    public function find($id){
        return $this->getPackages($id)->first();
    }

    private function getPackages($id = null){
        $packages  = $this->package->join('package_translations', function ($query) {
                        $query->on('packages.id', '=', 'package_translations.package_id')
                            ->where('package_translations.locale', $this->langCode);
                    })
            ->where('packages.active', true)
            ->orderBy('packages.position');
            if ($id) {
               $packages->where('packages.id',$id);
            }
        $packages = $packages->select('packages.*', 'package_translations.name','package_translations.description');
        return $packages;
    }
   
}
