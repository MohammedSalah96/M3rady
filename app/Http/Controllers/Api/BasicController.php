<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Repositories\Api\Category\CategoryRepositoryInterface;
use App\Repositories\Api\Location\LocationRepositoryInterface;
use App\Repositories\Api\ContactMessage\ContactMessageRepositoryInterface;
use App\Repositories\Api\PackageSubscription\PackageSubscriptionRepositoryInterface;
use App\Repositories\Api\Package\PackageRepositoryInterface;
use Validator;

class BasicController extends ApiController {

    private $contactRules = [
        'name' => 'required',
        'mobile' => 'required',
        'message' => 'required'
    ];

    private $subscribeRules = [
        'package' => 'required'
    ];

    private $locationRepository;
    private $categoryRepository;
    private $contactMessage;
    private $packageSubscriptionRepository;
    private $packageRepository;
    

    public function __construct(
        LocationRepositoryInterface $locationRepository,
        CategoryRepositoryInterface $categoryRepository,
        ContactMessageRepositoryInterface $contactMessage,
        PackageSubscriptionRepositoryInterface $packageSubscriptionRepository,
        PackageRepositoryInterface $packageRepository
        
    )
    {
        parent::__construct();
        $this->locationRepository = $locationRepository;
        $this->categoryRepository = $categoryRepository;
        $this->contactMessage = $contactMessage;
        $this->packageSubscriptionRepository = $packageSubscriptionRepository;
        $this->packageRepository = $packageRepository;
    }

    public function getConfig()
    {
        try {
            $locations = $this->locationRepository->getTree();
            $categories = $this->categoryRepository->getTree();
            $packages = $this->packageRepository->list()->transform(function($package, $key){
                return $package->transform();
            });
            return _api_json(['locations' => $locations, 'categories' => $categories, 'packages' => $packages]);
        } catch (\Exception $ex) {
            $message = _lang('app.something_went_wrong');
            return _api_json(new \stdClass(), ['message' => $message], 400);
        }
    }

    public function contactMessage(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), $this->contactRules);
            if ($validator->fails()) {
                $errors = $validator->errors()->toArray();
                return _api_json('', ['errors' => $errors], 400);
            }
            $this->contactMessage->create($request);
            $message = _lang('app.sent_successfully');
            return _api_json('',['message' => $message]);
        } catch (\Exception $ex) {
            $message = _lang('app.something_went_wrong');
            return _api_json('', ['message' => $message], 400);
        }
    }

    public function subscribe(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), $this->subscribeRules);
            if ($validator->fails()) {
                $errors = $validator->errors()->toArray();
                return _api_json('', ['errors' => $errors], 400);
            }
            $package = $this->packageRepository->find($request->input('package'));
            if (!$package) {
                $message = _lang('app.not_found');
                return _api_json('', ['message' => $message], 404);
            }
            $this->packageSubscriptionRepository->subscribe($package);
            $message = _lang('app.subscribed_successfully');
            return _api_json('', ['message' => $message]);
        } catch (\Exception $ex) {
            $message = _lang('app.something_went_wrong');
            return _api_json('', ['message' => $message], 400);
        }
    }

}
