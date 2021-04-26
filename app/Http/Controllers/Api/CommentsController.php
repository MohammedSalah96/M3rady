<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Repositories\Api\Comment\CommentRepositoryInterface;
use App\Repositories\Api\Notification\NotificationRepositoryInterface;
use App\Repositories\Api\Post\PostRepositoryInterface;
use Validator;
use DB;

class CommentsController extends ApiController {

    private $rules = [
        'post_id' => 'required',
        'comment' => 'required',
        'image' => 'mimes:jpg,png,jpeg'
    ];

    private $commentRepository;
    private $postRepository;
    private $notificationRepository;


    public function __construct(
        CommentRepositoryInterface $commentRepository,
        PostRepositoryInterface $postRepository,
        NotificationRepositoryInterface $notificationRepository
    )
    {
        parent::__construct();
        $this->commentRepository = $commentRepository;
        $this->postRepository = $postRepository;
        $this->notificationRepository = $notificationRepository;
    }

    public function index(Request $request)
    {
        try {
            $validator = Validator::make($request->all(),['post_id' => 'required']);
            if ($validator->fails()) {
                $errors = $validator->errors()->toArray();
                return _api_json([], ['errors' => $errors], 400);
            } 
            $comments = $this->commentRepository->list($request, $request->input('post_id'))->transform(function($comment, $key){
                return $comment->transform();
            });
            return _api_json($comments);
        } catch (\Exception $ex) {
            $message = _lang('app.something_went_wrong');
            return _api_json([], ['message' => $message], 400);
        }
    }

    public function store(Request $request)
    {
        try {
            $post = $this->postRepository->find($request, $request->input('post_id'));
            if (!$post) {
                $message = _lang('app.not_found');
                return _api_json(new \stdClass(), ['message' => $message], 404);
            }
            $validator = Validator::make($request->all(), $this->rules);
            if ($validator->fails()) {
                $errors = $validator->errors()->toArray();
                return _api_json(new \stdClass(), ['errors' => $errors], 400);
            } 
            DB::beginTransaction();
            $comment = $this->commentRepository->create($request);
            $this->notificationRepository->send($post->user_id, $this->notificationRepository->types['comment'],$request->input('post_id'));
            $comment = $this->commentRepository->find($comment->id)->transform();
            $message = _lang('app.posted_successfully');
            DB::commit();
            return _api_json($comment, ['message' => $message]);
        } catch (\Exception $ex) {
            DB::rollback();
            $message = _lang('app.something_went_wrong');
            return _api_json(new \stdClass(), ['message' => $message], 400);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $comment = $this->commentRepository->findForAuth($id);
            if (!$comment) {
                $message = _lang('app.not_found');
                return _api_json(new \stdClass(), ['message' => $message], 404);
            }
            unset($this->rules['post_id']);
            $validator = Validator::make($request->all(), $this->rules);
            if ($validator->fails()) {
                $errors = $validator->errors()->toArray();
                return _api_json(new \stdClass(), ['errors' => $errors], 400);
            }
            DB::beginTransaction();
            $comment = $this->commentRepository->update($request, $comment);
            $comment = $this->commentRepository->find($comment->id)->transform();
            $message = _lang('app.updated_successfully');
            DB::commit();
            return _api_json($comment, ['message' => $message]);
        } catch (\Exception $ex) {
            DB::rollback();
            $message = _lang('app.something_went_wrong');
            return _api_json(new \stdClass(), ['message' => $message], 400);
        }
    }

    public function destroy($id)
    {
        try {
            $comment = $this->commentRepository->findForAuth($id);
            if (!$comment) {
                $message = _lang('app.not_found');
                return _api_json('', ['message' => $message], 404);
            }
            $this->commentRepository->delete($comment);
            $message = _lang('app.deleted_successfully');
            return _api_json('', ['message' => $message]);
        } catch (\Exception $ex) {
            $message = _lang('app.something_went_wrong');
            return _api_json('', ['message' => $message], 400);
        }
    }

}
