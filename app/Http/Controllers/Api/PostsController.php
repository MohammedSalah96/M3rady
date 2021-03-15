<?php

namespace App\Http\Controllers\Api;

use DB;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Repositories\Api\Like\LikeRepositoryInterface;
use App\Repositories\Api\Post\PostRepositoryInterface;
use App\Repositories\Api\User\UserRepositoryInterface;
use App\Repositories\Api\Comment\CommentRepositoryInterface;
use App\Repositories\Api\CompanyDetails\CompanyDetailsRepositoryInterface;

class PostsController extends ApiController
{
    private $rules = [
        'images' => 'required',
        'images.*' => 'mimes:jpg,png,jpeg'
    ];

    private $postRepository;
    private $userRepository;
    private $companyDetailsRepository;
    private $likeRepository;
    private $commentRepository;

    public function __construct(
        PostRepositoryInterface $postRepository,
        UserRepositoryInterface $userRepository,
        CompanyDetailsRepositoryInterface $companyDetailsRepository,
        LikeRepositoryInterface $likeRepository,
        CommentRepositoryInterface $commentRepository
    ) {
        parent::__construct();
        $this->postRepository = $postRepository;
        $this->userRepository = $userRepository;
        $this->companyDetailsRepository = $companyDetailsRepository;
        $this->likeRepository = $likeRepository;
        $this->commentRepository = $commentRepository;
    }

    public function index(Request $request)
    {
        try {
            $posts = $this->postRepository->list($request);
            return _api_json($posts);
        } catch (\Exception $ex) {
            $message = _lang('app.something_went_wrong');
            return _api_json([], ['message' => $message], 400);
        }
    }

    public function show(Request $request, $id)
    {
        try {
            $post = $this->postRepository->find($id);
            if (!$post) {
                $message = _lang('app.not_found');
                return _api_json(new \stdClass(), ['message' => $message], 404);
            }
            $post = $post->transform();
            $comments = $this->commentRepository->list($request, $post->id)->transform(function ($comment, $key) {
                return $comment->transform();
            });
            return _api_json($post, ['comments' => $comments]);
        } catch (\Exception $ex) {
            dd($ex);
            $message = _lang('app.something_went_wrong');
            return _api_json(new \stdClass(), ['message' => $message], 400);
        }
    }

    public function store(Request $request)
    {
        try {
            if (!$this->userRepository->canPost()) {
                $message = _lang('app.your_free_trail_or_subscription_has_ended_subscribe_to_one_of_our_backages_to_be_able_to_post_again');
                return _api_json('', ['message' => $message], 400);
            }
            $validator = Validator::make($request->all(), $this->rules);
            if ($validator->fails()) {
                $errors = $validator->errors()->toArray();
                return _api_json('', ['errors' => $errors], 400);
            } 
            $post = $this->postRepository->create($request);
            $this->companyDetailsRepository->decreaseFreePosts();
            $message = _lang('app.posted_successfully');
            return _api_json('',['post_id' => $post->id,'message' => $message]);
        } catch (\Exception $ex) {
            $message = _lang('app.something_went_wrong');
            return _api_json('', ['message' => $message], 400);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            unset($this->rules['images']);
            $validator = Validator::make($request->all(), $this->rules);
            if ($validator->fails()) {
                $errors = $validator->errors()->toArray();
                return _api_json(new \stdClass(), ['errors' => $errors], 400);
            } 
            $post = $this->postRepository->findForAuth($id);
            if (!$post) {
                $message = _lang('app.not_found');
                return _api_json(new \stdClass(), ['message' => $message], 404);
            }
            $this->postRepository->update($request, $post);
            $message = _lang('app.updated_successfully');
            return _api_json(new \stdClass(), ['message' => $message]);
        } catch (\Exception $ex) {
            $message = _lang('app.something_went_wrong');
            return _api_json(new \stdClass(), ['message' => $message], 400);
        }
    }

    public function destroy($id)
    {
        try {
            $post = $this->postRepository->findForAuth($id);
            if (!$post) {
                $message = _lang('app.not_found');
                return _api_json(new \stdClass(), ['message' => $message], 404);
            }
            DB::beginTransaction();
            $this->postRepository->delete($post);
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
            $post = $this->postRepository->findForAuth($id);
            if (!$post) {
                $message = _lang('app.not_found');
                return _api_json('', ['message' => $message], 404);
            }
            $this->postRepository->deleteImage($request->input('image'), $post);
            $message = _lang('app.deleted_successfully');
            return _api_json('', ['message' => $message]);
        } catch (\Exception $ex) {
            $message = _lang('app.something_went_wrong');
            return _api_json('', ['message' => $message], 400);
        }
    }

    public function handleLike($id)
    {
        try {
            $post = $this->postRepository->find($id);
            if (!$post) {
                $message = _lang('app.not_found');
                return _api_json('', ['message' => $message], 404);
            }
            $this->likeRepository->createOrDelete($post);
            $message = _lang('app.updated_successfully');
            return _api_json('', ['message' => $message]);
        } catch (\Exception $ex) {
            $message = _lang('app.something_went_wrong');
            return _api_json('', ['message' => $message], 400);
        }
    }

    
}
