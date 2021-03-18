<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Repositories\Api\Comment\CommentRepositoryInterface;
use App\Repositories\Api\Post\PostRepositoryInterface;
use Validator;

class CommentsController extends ApiController {

    private $rules = [
        'post_id' => 'required',
        'comment' => 'required',
        'image' => 'mimes:jpg,png,jpeg'
    ];

    private $commentRepository;
    private $postRepository;


    public function __construct(
        CommentRepositoryInterface $commentRepository,
        PostRepositoryInterface $postRepository
    )
    {
        parent::__construct();
        $this->commentRepository = $commentRepository;
        $this->postRepository = $postRepository;
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
            $validator = Validator::make($request->all(), $this->rules);
            if ($validator->fails()) {
                $errors = $validator->errors()->toArray();
                return _api_json(new \stdClass(), ['errors' => $errors], 400);
            } 
            $comment = $this->commentRepository->create($request);

            $comment = $this->commentRepository->find($comment->id)->transform();
            $message = _lang('app.posted_successfully');
            return _api_json($comment, ['message' => $message]);
        } catch (\Exception $ex) {
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
