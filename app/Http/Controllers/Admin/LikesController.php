<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\BackendController;
use App\Repositories\Backend\Like\LikeRepositoryInterface;
use DB;

class LikesController extends BackendController
{
   
    private $likeRepository;
   
    public function __construct(
        LikeRepositoryInterface $likeRepository
    )
    {
        parent::__construct();
        $this->middleware('CheckPermission:likes,open', ['only' => ['index']]);
        $this->middleware('CheckPermission:likes,delete', ['only' => ['delete']]);
        $this->data['tab'] = 'posts';
        $this->likeRepository = $likeRepository;
    }

    public function index(Request $request)
    {
        try {
            if (!$request->has('post')) {
                session()->flash('error_message', _lang('app.please_select_post'));
                return redirect()->route('posts.index');
            }
            $this->data['post'] = $request->input('post');
            return $this->_view('likes.index');
        } catch (\Exception $ex) {
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
            $like = $this->likeRepository->find($id);
            if (!$like) {
                return _json('error', _lang('app.this_item_doesn\'t_exist'), 404);
            }
            DB::beginTransaction();
            $this->likeRepository->delete($request, $id, $like);
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
        $likes = $this->likeRepository->dataTable($request);

        return \DataTables::eloquent($likes)
            ->addColumn('options', function ($item) {
                $back = "";
                    if (\Permissions::check('likes', 'delete')) {
                        $back .= '<a href="#" data-toggle="tooltip" title="'._lang('app.delete').'" onclick="Likes.delete(this);return false;" data-id="' . $item->id . '" class="btn btn-sm btn-clean btn-icon">
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
            ->editColumn('created_at', function ($item) {
                return $item->created_at->format('Y-m-d h:i a');
            })
            ->addColumn('name', function ($item) {
                return $item->name ?: $item->company_id;
            })
            ->filterColumn('name', function ($query, $keyword) {
                $query->whereRaw('users.name like ? or company_details.company_id like ?', ["%{$keyword}%","%{$keyword}%"]);
            })
            ->escapeColumns([])
            ->make(true);
    }
    
}
