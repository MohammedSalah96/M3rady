<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Repositories\Api\Category\CategoryRepositoryInterface;
use App\Repositories\Api\Location\LocationRepositoryInterface;

class BasicController extends ApiController {


    private $locationRepository;
    private $categoryRepository;


    public function __construct(
        LocationRepositoryInterface $locationRepository,
        CategoryRepositoryInterface $categoryRepository
    )
    {
        parent::__construct();
        $this->locationRepository = $locationRepository;
        $this->categoryRepository = $categoryRepository;
    }

    public function getConfig()
    {
        try {
            $locations = $this->locationRepository->getTree();
            $categories = $this->categoryRepository->getTree();
            return _api_json(['locations' => $locations, 'categories' => $categories]);
        } catch (\Exception $ex) {
            $message = _lang('app.something_went_wrong');
            return _api_json(new \stdClass(), ['message' => $message], 400);
        }
    }

}
