<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\BackendController;
use App\Repositories\Backend\Category\CategoryRepositoryInterface;
use App\Repositories\Backend\Location\LocationRepositoryInterface;
use App\Repositories\Backend\PackageSubscription\PackageSubscriptionRepositoryInterface;
use App\Repositories\Backend\Post\PostRepositoryInterface;
use App\Repositories\Backend\User\UserRepositoryInterface;

class AdminController extends BackendController
{
    private $categoryRepository;
    private $userRepository;
    private $postRepository;
    private $packageSubscriptionRepository;
    private $locationRepository;

    public function __construct(
        CategoryRepositoryInterface $categoryRepository,
        UserRepositoryInterface $userRepository,
        PostRepositoryInterface $postRepository,
        PackageSubscriptionRepositoryInterface $packageSubscriptionRepository,
        LocationRepositoryInterface $locationRepository
    )
    {
        parent::__construct();
        $this->data['tab'] = 'dashboard';
        $this->categoryRepository = $categoryRepository;
        $this->userRepository = $userRepository;
        $this->postRepository = $postRepository;
        $this->packageSubscriptionRepository = $packageSubscriptionRepository;
        $this->locationRepository = $locationRepository;
    }
    /**
     * Show the application dashboard.
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $this->data['mainCategoriesCount'] = $this->categoryRepository->statistics();
            $this->data['subCategoriesCount'] = $this->categoryRepository->statistics($main = false);
            $this->data['clientsCount'] = $this->userRepository->statistics($this->userRepository->types['client']);
            $this->data['companiesCount'] = $this->userRepository->statistics($this->userRepository->types['company']);
            $this->data['postsCount'] = $this->postRepository->statistics();
            $this->data['countriesCount'] = $this->locationRepository->statistics();
            $this->data['citiesCount'] = $this->locationRepository->statistics($country = false);
            $this->data['profit'] = $this->packageSubscriptionRepository->statistics();
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
