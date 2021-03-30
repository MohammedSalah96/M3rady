<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\BackendController;
use App\Repositories\Backend\Post\PostRepositoryInterface;
use App\Repositories\Backend\User\UserRepositoryInterface;
use DB;

class PostsController extends BackendController
{
   
    private $postRepository;
    private $userRepository;
   
    public function __construct(
        PostRepositoryInterface $postRepository,
        UserRepositoryInterface $userRepository
    )
    {
        parent::__construct();
        $this->middleware('CheckPermission:posts,open', ['only' => ['index','show']]);
        $this->middleware('CheckPermission:posts,delete', ['only' => ['delete']]);
        $this->data['tab'] = 'posts';
        $this->postRepository = $postRepository;
        $this->userRepository = $userRepository;
    }

    public function index(Request $request)
    {
        try {
            $this->data['companies'] = $this->userRepository->getByType($this->userRepository->types['company']);
            return $this->_view('posts.index');
        } catch (\Exception $ex) {
            session()->flash('error_message', _lang('app.something_went_wrong'));
            return redirect()->route('posts.index');
        }
       
    }

    public function show($id)
    {
        try {
            $post = $this->postRepository->find($id);
            if (!$post) {
                return _json('error', _lang('app.this_item_doesn\'t_exist'), 404);
            }
            $this->data['post'] = $post;
            return $this->_view('posts.show');
        } catch (\Exception $ex) {
            dd($ex);
            session()->flash('error_message', _lang('app.something_went_wrong'));
            return redirect()->route('posts.index');
        }
    }
    

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        try {
            $post = $this->postRepository->find($id);
            if (!$post) {
                return _json('error', _lang('app.this_item_doesn\'t_exist'), 404);
            }
            DB::beginTransaction();
            $this->postRepository->delete($request, $id, $post);
            DB::commit();
            return _json('success', _lang('app.deleted_successfully'));
        } catch (\Exception $ex) {
            DB::rollback();
            if ($ex->getCode() == 23000) {
                return _json('error', _lang('app.this_record_can_not_be_deleted_for_linking_to_other_records'), 400);
            } else {
                return _json('error', _lang('app.something_went_wrong'), 400);
            }
        }
    }
    
    public function data(Request $request)
    {
        $posts = $this->postRepository->dataTable($request);

        return \DataTables::eloquent($posts)
            ->addColumn('options', function ($item) {

                $back = "";
                $back .= '<a href="'.route('posts.show',$item->id).'" data-toggle="tooltip" title="' . _lang('app.show') . '" class="btn btn-sm btn-clean btn-icon">
                                <span class="svg-icon svg-icon-2x">
                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                            <rect x="0" y="0" width="24" height="24"/>
                                            <path d="M3,12 C3,12 5.45454545,6 12,6 C16.9090909,6 21,12 21,12 C21,12 16.9090909,18 12,18 C5.45454545,18 3,12 3,12 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
                                            <path d="M12,15 C10.3431458,15 9,13.6568542 9,12 C9,10.3431458 10.3431458,9 12,9 C13.6568542,9 15,10.3431458 15,12 C15,13.6568542 13.6568542,15 12,15 Z" fill="#000000" opacity="0.3"/>
                                        </g>
                                    </svg>
                                </span>
                            </a>';
                
                    if (\Permissions::check('posts', 'delete')) {
                        $back .= '<a href="" data-toggle="tooltip" title="'._lang('app.delete').'" onclick="Posts.delete(this);return false;" data-id="' . $item->id . '" class="btn btn-sm btn-clean btn-icon">
                                    <span class="svg-icon svg-icon-md">
                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                                            height="24px" viewBox="0 0 24 24" version="1.1">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <rect x="0" y="0" width="24" height="24"></rect>
                                                <path
                                                    d="M6,8 L6,20.5 C6,21.3284271 6.67157288,22 7.5,22 L16.5,22 C17.3284271,22 18,21.3284271 18,20.5 L18,8 L6,8 Z"
                                                    fill="#000000" fill-rule="nonzero"></path>
                                                <path
                                                    d="M14,4.5 L14,4 C14,3.44771525 13.5522847,3 13,3 L11,3 C10.4477153,3 10,3.44771525 10,4 L10,4.5 L5.5,4.5 C5.22385763,4.5 5,4.72385763 5,5 L5,5.5 C5,5.77614237 5.22385763,6 5.5,6 L18.5,6 C18.7761424,6 19,5.77614237 19,5.5 L19,5 C19,4.72385763 18.7761424,4.5 18.5,4.5 L14,4.5 Z"
                                                    fill="#000000" opacity="0.3"></path>
                                            </g>
                                        </svg>
                                    </span>
                                </a>'; 
                    } 
                
                return $back;
            })
            ->addColumn('image', function ($item) {
                $image = json_decode($item->images,true)[0]; 
                return '<div class="d-flex align-items-center container">
                         <img class="mw-100px w-100px h-100px m-auto" src="' . url('public/uploads/posts/' . $image) . '" alt="photo">
                        </div>';
            })
            ->addColumn('no_of_likes', function ($item) {
                if (\Permissions::check('likes', 'open')) {
                    $back = '<a class="text-primary-50" href="'.route('likes.index',['post' => $item->id]).'" target="_blank">' . $item->no_of_likes . '</a>';
                }else{
                    $back = $item->no_of_likes;
                }
                return $back;
            })
            ->addColumn('no_of_comments', function ($item) {
                if (\Permissions::check('comments', 'open')) {
                    $back = '<a class="text-primary-50" href="'.route('comments.index',['post' => $item->id]).'" target="_blank">' . $item->no_of_comments . '</a>';
                }else{
                    $back = $item->no_of_comments;
                }
                return $back;
            })
            ->addColumn('no_of_abuses', function ($item) {
                if (\Permissions::check('abuses', 'open')) {
                    $back = '<a class="text-primary-50" href="' . route('abuses.index', ['post' => $item->id]) . '" target="_blank">' . $item->no_of_abuses . '</a>';
                } else {
                    $back = $item->no_of_abuses;
                }
                return $back;
            })
            ->filterColumn('no_of_likes', function ($query, $keyword) {
                $query->whereRaw('(select count(*) from likes where post_id = posts.id) like ?', ["%{$keyword}%"]);
            })
            ->filterColumn('no_of_comments', function ($query, $keyword) {
                $query->whereRaw('(select count(*) from comments where post_id = posts.id) like ?', ["%{$keyword}%"]);
            })
            ->filterColumn('no_of_abuses', function ($query, $keyword) {
                $query->whereRaw('(select count(*) from abuses where post_id = posts.id) like ?', ["%{$keyword}%"]);
            })
            ->orderColumn('no_of_likes', function ($query, $order) {
                $query->orderBy(DB::raw("(select count(*) from likes where post_id = posts.id)"), $order);
            })
            ->orderColumn('no_of_comments', function ($query, $order) {
                $query->orderBy(DB::raw("(select count(*) from comments where post_id = posts.id)"), $order);
            })
            ->orderColumn('no_of_abuses', function ($query, $order) {
                $query->orderBy(DB::raw("(select count(*) from abuses where post_id = posts.id)"), $order);
            })
            ->escapeColumns([])
            ->make(true);
    }
    
}
