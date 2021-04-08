<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Repositories\Api\Category\CategoryRepositoryInterface;
use App\Repositories\Api\Company\CompanyRepositoryInterface;
use App\Repositories\Api\Location\LocationRepositoryInterface;
use App\Repositories\Api\ContactMessage\ContactMessageRepositoryInterface;
use App\Repositories\Api\PackageSubscription\PackageSubscriptionRepositoryInterface;
use App\Repositories\Api\Package\PackageRepositoryInterface;
use App\Repositories\Api\Post\PostRepositoryInterface;
use App\Repositories\Api\Banner\BannerRepositoryInterface;
use App\Repositories\Api\SettingTranslation\SettingTranslationRepositoryInterface;
use App\Repositories\Api\WelcomeScreen\WelcomeScreenRepositoryInterface;

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
    private $companyRepository;
    private $postRepository;
    private $settingTranslationRepository;
    
    

    public function __construct(
        LocationRepositoryInterface $locationRepository,
        CategoryRepositoryInterface $categoryRepository,
        ContactMessageRepositoryInterface $contactMessage,
        PackageSubscriptionRepositoryInterface $packageSubscriptionRepository,
        PackageRepositoryInterface $packageRepository,
        CompanyRepositoryInterface $companyRepository,
        PostRepositoryInterface $postRepository,
        BannerRepositoryInterface $bannerRepository,
        WelcomeScreenRepositoryInterface $welcomeScreenRepository,
        SettingTranslationRepositoryInterface $settingTranslationRepository
        
    )
    {
        parent::__construct();
        $this->locationRepository = $locationRepository;
        $this->categoryRepository = $categoryRepository;
        $this->contactMessage = $contactMessage;
        $this->packageSubscriptionRepository = $packageSubscriptionRepository;
        $this->packageRepository = $packageRepository;
        $this->companyRepository = $companyRepository;
        $this->postRepository = $postRepository;
        $this->bannerRepository = $bannerRepository;
        $this->welcomeScreenRepository = $welcomeScreenRepository;
        $this->settingTranslationRepository = $settingTranslationRepository;
    }

    public function welcomeScreens(Request $request)
    {
        try {
            $welcomeScreens = $this->welcomeScreenRepository->list()->transform(function ($welcomeScreen, $key) {
                return $welcomeScreen->transform();
            });
            return _api_json($welcomeScreens);
        } catch (\Exception $ex) {
            $message = _lang('app.something_went_wrong');
            return _api_json([], ['message' => $message], 400);
        }
    }

    public function home(Request $request)
    {
        try {
            $request->request->add(['feed' => true]);
            $banners = $this->bannerRepository->list();
            $posts = $this->postRepository->list($request)->transform(function ($post, $key) {
                return $post->transform();
            });
            $companies = $this->companyRepository->list($request)->transform(function ($company, $key) {
                return $company->transformCompaniesList();
            });
            return _api_json(['banners' => $banners, 'featured_companies' => $companies , 'posts' => $posts]);
        } catch (\Exception $ex) {
            $message = _lang('app.something_went_wrong');
            return _api_json(new \stdClass(), ['message' => $message], 400);
        }
    }

    public function getConfig()
    {
        try {
            $locations = $this->locationRepository->getTree();
            $categories = $this->categoryRepository->getTree();
            $settings['info'] = $this->settingTranslationRepository->get();
            return _api_json([
                'locations' => $locations,
                'categories' => $categories,
                'settings' => $settings]);
        } catch (\Exception $ex) {
            $message = _lang('app.something_went_wrong');
            return _api_json(new \stdClass(), ['message' => $message], 400);
        }
    }

    public function getCategories(Request $request)
    {
        try {
            $categories = $this->categoryRepository->list($request);
            $categories = $categories->transform(function($category, $key){
                return $category->transformListApi();
            });
            return _api_json($categories);
        } catch (\Exception $ex) {
            $message = _lang('app.something_went_wrong');
            return _api_json([], ['message' => $message], 400);
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

    public function getPackages(Request $request)
    {
        try {
            $packages = $this->packageRepository->list()->transform(function ($package, $key) {
                return $package->transform();
            });
            return _api_json($packages);
        } catch (\Exception $ex) {
            $message = _lang('app.something_went_wrong');
            return _api_json([], ['message' => $message], 400);
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
