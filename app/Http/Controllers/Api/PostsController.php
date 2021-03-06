<?php

namespace App\Http\Controllers\Api;

use DB;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Repositories\Api\Abuse\AbuseRepositoryInterface;
use App\Repositories\Api\Like\LikeRepositoryInterface;
use App\Repositories\Api\Post\PostRepositoryInterface;
use App\Repositories\Api\User\UserRepositoryInterface;
use App\Repositories\Api\Comment\CommentRepositoryInterface;
use App\Repositories\Api\CompanyDetails\CompanyDetailsRepositoryInterface;
use App\Repositories\Api\Notification\NotificationRepositoryInterface;

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
    private $abuseRepository;
    private $notificationRepository;

    public function __construct(
        PostRepositoryInterface $postRepository,
        UserRepositoryInterface $userRepository,
        CompanyDetailsRepositoryInterface $companyDetailsRepository,
        LikeRepositoryInterface $likeRepository,
        CommentRepositoryInterface $commentRepository,
        AbuseRepositoryInterface $abuseRepository,
        NotificationRepositoryInterface $notificationRepository
    ) {
        parent::__construct();
        $this->postRepository = $postRepository;
        $this->userRepository = $userRepository;
        $this->companyDetailsRepository = $companyDetailsRepository;
        $this->likeRepository = $likeRepository;
        $this->commentRepository = $commentRepository;
        $this->abuseRepository = $abuseRepository;
        $this->notificationRepository = $notificationRepository;
    }

    public function index(Request $request)
    {
        try {
            $posts = $this->postRepository->list($request)->transform(function ($post, $key) {
                return $post->transform();
            });
            return _api_json($posts);
        } catch (\Exception $ex) {
            $message = _lang('app.something_went_wrong');
            return _api_json([], ['message' => $message], 400);
        }
    }

    public function show(Request $request, $id)
    {
        try {
            $post = $this->postRepository->find($request, $id);
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
            $message = _lang('app.something_went_wrong');
            return _api_json(new \stdClass(), ['message' => $message], 400);
        }
    }

    public function store(Request $request)
    {
        try {
            if (!$this->userRepository->canPost()) {
                $message = _lang('app.you_have_reached_the_max_limit_for_posting');
                return _api_json('', ['message' => $message], 400);
            }
            $validator = Validator::make($request->all(), $this->rules);
            if ($validator->fails()) {
                $errors = $validator->errors()->toArray();
                return _api_json('', ['errors' => $errors], 400);
            } 
            $post = $this->postRepository->create($request);
            $this->companyDetailsRepository->increaseFreePosts();
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
                return _api_json('', ['message' => $message], 404);
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

    public function handleLike(Request $request,$id)
    {
        try {
            $post = $this->postRepository->findSimple($id);
            if (!$post) {
                $message = _lang('app.not_found');
                return _api_json('', ['message' => $message], 404);
            }
            if ($this->likeRepository->createOrDelete($post) && $this->likeRepository->authUser()->id != $post->user_id) {
                $this->notificationRepository->send($post->user_id, $this->notificationRepository->types['like'], $post->id);
            }
            $message = _lang('app.updated_successfully');
            return _api_json('', ['message' => $message]);
        } catch (\Exception $ex) {
            $message = _lang('app.something_went_wrong');
            return _api_json('', ['message' => $message], 400);
        }
    }

    public function abuse(Request $request,$id)
    {
        try {
            $post = $this->postRepository->findSimple($id);
            if (!$post) {
                $message = _lang('app.not_found');
                return _api_json('', ['message' => $message], 404);
            }
            $this->abuseRepository->create($post);
            $message = _lang('app.reported_successfully');
            return _api_json('', ['message' => $message]);
        } catch (\Exception $ex) {
            $message = _lang('app.something_went_wrong');
            return _api_json('', ['message' => $message], 400);
        }
    }

    
}
