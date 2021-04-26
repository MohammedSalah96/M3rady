<?php

namespace App\Http\Controllers\Admin;

use Validator;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\BackendController;
use App\Repositories\Backend\ContactMessage\ContactMessageRepositoryInterface;

class ContactMessagesController extends BackendController {

    private $contactMessageRepository;
    
    private $rules = [
        'reply' => 'required'
    ];

    public function __construct(ContactMessageRepositoryInterface $contactMessageRepository) {

        parent::__construct();
        $this->middleware('CheckPermission:contact_messages,open', ['only' => ['index']]);
        $this->middleware('CheckPermission:contact_messages,show', ['only' => ['show']]);
        $this->middleware('CheckPermission:contact_messages,delete', ['only' => ['destroy','destroyMultiple']]);

        $this->data['tab'] = 'contact_messages';
        $this->contactMessageRepository = $contactMessageRepository;

    }

    public function index() {
        return $this->_view('contact_messages.index');
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        try {
            $contactMessage = $this->contactMessageRepository->find($id);
            if (!$contactMessage) {
                return _json('error', _lang('app.not_found'), 404);
            }
            return _json('success', $contactMessage);
        } catch (\Exception $ex) {
            return _json('error', _lang('app.something_went_wrong'),400);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $contactMessage = $this->contactMessageRepository->find($id);
            if (!$contactMessage) {
                return _json('error', _lang('app.not_found'), 404);
            }
            $validator = Validator::make($request->all(), $this->rules);
            if ($validator->fails()) {
                $errors = $validator->errors()->toArray();
                return _json('error', $errors);
            }
            $this->contactMessageRepository->update($request, $id, $contactMessage);
            return _json('success', _lang('app.sent_successfully'));
        } catch (Exception $ex) {
            return _json('error', _lang('app.something_went_wrong'));
        }
    }

    public function destroy(Request $request) {
        try {
            if ($id = $request->input('id')) {
                $contactMessage = $this->contactMessageRepository->find($id);
                if (!$contactMessage) {
                    return _json('error', _lang('app.not_found'), 404);
                }
                $this->contactMessageRepository->delete($request, $id, $contactMessage);
            }
            else if($request->input('ids')){
                $this->contactMessageRepository->multipleDelete($request);
            }
            return _json('success', _lang('app.deleted_successfully'));
        } catch (Exception $ex) {
            return _json('error', _lang('app.something_went_wrong'),400);
        }
    }

    public function data(Request $request) {
        $messages = $this->contactMessageRepository->dataTable($request);
        return \DataTables::eloquent($messages)
                        ->addColumn('options', function ($item) {
                            $back = '';
                            if (\Permissions::check('contact_messages','show')) {
                                 $back .= '<a href="" data-toggle="tooltip" title="'._lang('app.show'). '" onclick="ContactMessages.show(this);return false;" data-id="' . $item->id . '" class="btn btn-sm btn-clean btn-icon">
                                            <span class="svg-icon svg-icon-md">
                                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                                                    height="24px" viewBox="0 0 24 24" version="1.1">
                                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                        <rect x="0" y="0" width="24" height="24"/>
                                                        <path d="M3,12 C3,12 5.45454545,6 12,6 C16.9090909,6 21,12 21,12 C21,12 16.9090909,18 12,18 C5.45454545,18 3,12 3,12 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
                                                        <path d="M12,15 C10.3431458,15 9,13.6568542 9,12 C9,10.3431458 10.3431458,9 12,9 C13.6568542,9 15,10.3431458 15,12 C15,13.6568542 13.6568542,15 12,15 Z" fill="#000000" opacity="0.3"/>
                                                    </g>
                                                </svg>
                                            </span>
                                        </a>';
                            }
                            if (\Permissions::check('contact_messages','delete')) {
                                $back .= '<a href="" data-toggle="tooltip" title="'._lang('app.delete'). '" onclick="ContactMessages.delete(this);return false;" data-id="' . $item->id . '" class="btn btn-sm btn-clean btn-icon">
                                            <span class="svg-icon svg-icon-md">
                                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                                                    height="24px" viewBox="0 0 24 24" version="1.1">
                                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                        <rect x="0" y="0" width="24" height="24"></rect>
                                                        <path d="M6,8 L6,20.5 C6,21.3284271 6.67157288,22 7.5,22 L16.5,22 C17.3284271,22 18,21.3284271 18,20.5 L18,8 L6,8 Z" fill="#000000" fill-rule="nonzero"></path>
                                                        <path d="M14,4.5 L14,4 C14,3.44771525 13.5522847,3 13,3 L11,3 C10.4477153,3 10,3.44771525 10,4 L10,4.5 L5.5,4.5 C5.22385763,4.5 5,4.72385763 5,5 L5,5.5 C5,5.77614237 5.22385763,6 5.5,6 L18.5,6 C18.7761424,6 19,5.77614237 19,5.5 L19,5 C19,4.72385763 18.7761424,4.5 18.5,4.5 L14,4.5 Z" fill="#000000" opacity="0.3"></path>
                                                    </g>
                                                </svg>
                                            </span>
                                        </a>'; 
                            }
                            return $back;
                        })
                        ->addColumn('select', function ($item) {
                            $back  = '<label class="checkbox checkbox-single">';
                            $back .= '<input type="checkbox" data-id="'.$item->id.'" value="" class="checkable">';
                            $back .='<span></span>';
                            $back .= '</label>';
                            return $back;
                        })
                        ->editColumn('message', function ($item) {
                            return Str::limit($item->message, 300, '......'); ;
                        })
                        ->editColumn('created_at', function ($item) {
                            return $item->created_at->format('Y/m/d - h:i a');
                        })
                        ->editColumn('type', function ($item) {
                            if ($item->type == $this->contactMessageRepository->types['managerial']) {
                                $message = _lang('app.managerial');
                                $class = 'label-light-warning';
                            } else {
                                $message = _lang('app.complaint_or_Suggestion');
                                $class = 'label-light-info';
                            }
                            $back = '<span class="label label-lg font-weight-bold label-inline ' . $class . '">' . $message . '</span>';
                            return $back;
                        })
                        ->escapeColumns([])
                        ->make(true);
    }

}
