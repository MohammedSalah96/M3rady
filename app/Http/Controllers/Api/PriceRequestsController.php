<?php

namespace App\Http\Controllers\Api;

use DB;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Repositories\Api\Notification\NotificationRepositoryInterface;
use App\Repositories\Api\PriceRequest\PriceRequestRepositoryInterface;

class PriceRequestsController extends ApiController
{
    private $rules = [
        'name' => 'required',
        'email' => 'required',
        'mobile' => 'required',
        'country' => 'required',
        'city' => 'required',
        'request' => 'required',
        'company' => 'required',
        'image' => 'mimes:jpg,png,jpeg'
    ];

    private $listRules = [
        'type' => 'required|in:1,2'
    ];

    private $priceRequestRepository;
    private $notificationRepository;
    

    public function __construct(PriceRequestRepositoryInterface $priceRequestRepository, NotificationRepositoryInterface $notificationRepository) {
        parent::__construct();
        $this->priceRequestRepository = $priceRequestRepository;
        $this->notificationRepository = $notificationRepository;
    }

    public function index(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), $this->listRules);
            if ($validator->fails()) {
                $errors = $validator->errors()->toArray();
                return _api_json(new \stdClass(), ['errors' => $errors], 400);
            } 
            $priceRequests = $this->priceRequestRepository->list($request)->transform(function ($priceRequest, $key) {
                return $priceRequest->transformList();
            });
            return _api_json($priceRequests);
        } catch (\Exception $ex) {
            $message = _lang('app.something_went_wrong');
            return _api_json([], ['message' => $message], 400);
        }
    }

    public function show(Request $request, $id)
    {
        try {
            $priceRequest = $this->priceRequestRepository->find($id);
            if (!$priceRequest) {
                $message = _lang('app.not_found');
                return _api_json(new \stdClass(), ['message' => $message], 404);
            }
            $priceRequest = $priceRequest->transformDetails();
            return _api_json($priceRequest);
        } catch (\Exception $ex) {
            $message = _lang('app.something_went_wrong');
            return _api_json(new \stdClass(), ['message' => $message], 400);
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), $this->rules);
            if ($validator->fails()) {
                $errors = $validator->errors()->toArray();
                return _api_json('', ['errors' => $errors], 400);
            } 
            /*if (!$this->priceRequestRepository->allowedToRequest()) {
                $message = _lang('app.you_cannot_send_another_request_until_24_hours_have_passed_since_the_last_request_sent');
                return _api_json('', ['message' => $message], 400);
            }*/
            DB::beginTransaction();
            $priceRequest = $this->priceRequestRepository->create($request);
            $this->notificationRepository->send($request->input('company'), $this->notificationRepository->types['price_request'], $priceRequest->id);
            DB::commit();
            $message = _lang('app.requested_successfully');
            return _api_json('',['message' => $message]);
        } catch (\Exception $ex) {
            DB::rollback();
            $message = _lang('app.something_went_wrong');
            return _api_json('', ['message' => $message], 400);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), ['reply' => 'required']);
            if ($validator->fails()) {
                $errors = $validator->errors()->toArray();
                return _api_json(new \stdClass(), ['errors' => $errors], 400);
            } 
            $priceRequest = $this->priceRequestRepository->findForCompany($id);
            if (!$priceRequest) {
                $message = _lang('app.not_found');
                return _api_json(new \stdClass(), ['message' => $message], 404);
            }
            DB::beginTransaction();
            $this->priceRequestRepository->update($request, $priceRequest);
            $this->notificationRepository->send($priceRequest->user_id, $this->notificationRepository->types['price_request_reply'], $priceRequest->id);
            DB::commit();
            $message = _lang('app.updated_successfully');
            return _api_json(new \stdClass(), ['message' => $message]);
        } catch (\Exception $ex) {
            DB::rollback();
            $message = _lang('app.something_went_wrong');
            return _api_json(new \stdClass(), ['message' => $message], 400);
        }
    }

    public function destroy($id)
    {
        try {
            $priceRequest = $this->priceRequestRepository->findForAuth($id);
            if (!$priceRequest) {
                $message = _lang('app.not_found');
                return _api_json('', ['message' => $message], 404);
            }
            DB::beginTransaction();
            $this->priceRequestRepository->delete($priceRequest);
            DB::commit();
            $message = _lang('app.deleted_successfully');
            return _api_json('', ['message' => $message]);
        } catch (\Exception $ex) {
            DB::rollback();
            $message = _lang('app.something_went_wrong');
            return _api_json('', ['message' => $message], 400);
        }
    }

    public function deleteImage(Request $request, $id)
    {
        try {
            $priceRequest = $this->priceRequestRepository->findForAuth($id);
            if (!$priceRequest) {
                $message = _lang('app.not_found');
                return _api_json('', ['message' => $message], 404);
            }
            $this->priceRequestRepository->deleteImage($request->input('image'), $priceRequest);
            $message = _lang('app.deleted_successfully');
            return _api_json('', ['message' => $message]);
        } catch (\Exception $ex) {
            $message = _lang('app.something_went_wrong');
            return _api_json('', ['message' => $message], 400);
        }
    }
    

    
}
