<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Repositories\Api\Rate\RateRepositoryInterface;
use Validator;

class RatesController extends ApiController {

    private $rules = [
        'company' => 'required',
        'score' => 'required'
    ];

    private $rateRepository;


    public function __construct(RateRepositoryInterface $rateRepository)
    {
        parent::__construct();
        $this->rateRepository = $rateRepository;
    }

    public function index(Request $request)
    {
        try {
            $validator = Validator::make($request->all(),['company' => 'required']);
            if ($validator->fails()) {
                $errors = $validator->errors()->toArray();
                return _api_json([], ['errors' => $errors], 400);
            } 
            $rates = $this->rateRepository->list($request)->transform(function($rate, $key){
                return $rate->transform();
            });
            return _api_json($rates);
        } catch (\Exception $ex) {
            dd($ex);
            $message = _lang('app.something_went_wrong');
            return _api_json([], ['message' => $message], 400);
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), $this->rules);
            if ($validator->fails()) {
                $errors = $validator->errors()->toArray();
                return _api_json(new \stdClass(), ['errors' => $errors], 400);
            } 
            $this->rateRepository->create($request);
            $message = _lang('app.sent_for_revision');
            return _api_json('', ['message' => $message]);
        } catch (\Exception $ex) {
            $message = _lang('app.something_went_wrong');
            return _api_json('', ['message' => $message], 400);
        }
    }

    public function destroy($id)
    {
        try {
            $rate = $this->rateRepository->findForAuth($id);
            if (!$rate) {
                $message = _lang('app.not_found');
                return _api_json('', ['message' => $message], 404);
            }
            $this->rateRepository->delete($rate);
            $message = _lang('app.deleted_successfully');
            return _api_json('', ['message' => $message]);
        } catch (\Exception $ex) {
            $message = _lang('app.something_went_wrong');
            return _api_json('', ['message' => $message], 400);
        }
    }

}
