<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\BackendController;
use App\Repositories\Backend\Package\PackageRepositoryInterface;
use App\Repositories\Backend\PackageSubscription\PackageSubscriptionRepositoryInterface;
use App\Repositories\Backend\User\UserRepositoryInterface;

class PackageSubscriptionsController extends BackendController
{
   
    private $packageSubscriptionRepository;
    private $userRepository;
    private $packageRepository;
   
    public function __construct(
        PackageSubscriptionRepositoryInterface $packageSubscriptionRepository,
        UserRepositoryInterface $userRepository,
        PackageRepositoryInterface $packageRepository
    )
    {
        parent::__construct();
        $this->middleware('CheckPermission:package_subscriptions,open', ['only' => ['index']]);
        $this->data['tab'] = 'package_subscriptions';
        $this->packageSubscriptionRepository = $packageSubscriptionRepository;
        $this->userRepository = $userRepository;
        $this->packageRepository = $packageRepository;
    }

    public function index(Request $request)
    {
        try {
            $this->data['companies'] = $this->userRepository->getByType($this->userRepository->types['company']);
            $this->data['packages'] = $this->packageRepository->all();
            return $this->_view('package_subscriptions.index');
        } catch (\Exception $ex) {
            session()->flash('error_message', _lang('app.something_went_wrong'));
            return redirect()->route('package_subscriptions.index');
        }
       
    }
    
    public function data(Request $request)
    {
        $packageSubscriptions = $this->packageSubscriptionRepository->dataTable($request);

        return \DataTables::eloquent($packageSubscriptions)
            ->escapeColumns([])
            ->make(true);
    }
    
}
