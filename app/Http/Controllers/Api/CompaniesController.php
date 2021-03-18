<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Repositories\Api\Post\PostRepositoryInterface;
use App\Repositories\Api\Company\CompanyRepositoryInterface;

class CompaniesController extends ApiController {


    private $companyRepository;
    private $postRepository;


    public function __construct(CompanyRepositoryInterface $companyRepository,PostRepositoryInterface $postRepository)
    {
        parent::__construct();
        $this->companyRepository = $companyRepository;
        $this->postRepository = $postRepository;
    }

    public function index(Request $request)
    {
        try {
            $companies = $this->companyRepository->list($request)->transform(function($company, $key){
                return $company->transformCompaniesList();
            });
            return _api_json($companies);
        } catch (\Exception $ex) {
            $message = _lang('app.something_went_wrong');
            return _api_json([], ['message' => $message], 400);
        }
    }

    public function show(Request $request, $id)
    {
        try {
            $company = $this->companyRepository->find($id);
            if (!$company) {
                $message = _lang('app.not_found');
                return _api_json(new \stdClass(), ['message' => $message], 404);
            }
            $company = $company->transformCompanyDetails();
            $request->request->add(['company_id' => $id]);
            $posts = $this->postRepository->list($request)->transform(function ($post, $key) {
                return $post->transform();
            });
            return _api_json($company, ['posts' => $posts]);
        } catch (\Exception $ex) {
            $message = _lang('app.something_went_wrong');
            return _api_json(new \stdClass(), ['message' => $message], 400);
        }
    }

}
