<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\BackendController;
use App\Repositories\Backend\Category\CategoryRepositoryInterface;
use App\Repositories\Backend\Location\LocationRepositoryInterface;

class AjaxController extends BackendController {

    private $locationRepository;
    private $categoryRepository;

    public function __construct(
        LocationRepositoryInterface $locationRepository,
        CategoryRepositoryInterface $categoryRepository
        )
    {
        parent::__construct();
        $this->locationRepository = $locationRepository;
        $this->categoryRepository = $categoryRepository;
    }

    public function change_lang(Request $request) {
        $langCode = $request->input('lang_code');
        
        $long = 7 * 60 * 24;
        return response()->json([
                    'type' => 'success',
                    'message' => $langCode
                ])->cookie('AdminLang', $langCode, $long);
    }

    public function deleteImage(Request $request)
    {
        try {
            $column_name = $request->input('col');

            // for edit form for non uploaded images
            if (!$request->input('model')) {
                return _json('success', _lang('app.updated_successfully'));
            }
            //

            $model = "App\Models\\" . $request->input('model');
            $model::deleteUploaded($request->input('folder'), $request->input('image'));

            $find = $model::find(en_de_crypt($request->input('id'),false));
            if (!json_decode($find->$column_name, true)) {
                $find->$column_name = "";
            } else {
                $images = json_decode($find->$column_name, true);
                $image_index = array_search($request->input('image'), $images);

                if (isset($images[$image_index])) {
                    unset($images[$image_index]);
                }
                if (count($images) == 0) {
                    $find->$column_name = "";
                } else {
                    //$find->$column_name = json_encode(array_values($images));
                    $find->$column_name = json_encode($images, JSON_FORCE_OBJECT);
                }
            }
            $find->save();
            return _json('success', _lang('app.updated_successfully'));
        } catch (\Exception $ex) {
            return _json('error', _lang('app.something_went_wrong'), 400);
        }
    }

    public function getLocations(Request $request, $country)
    {
        try {
            $cities = $this->locationRepository->getByParent($country);
            $cities = $cities->count() > 0 ? $cities->toArray() : [];
           return _json('success', ['cities' => $cities]);
        } catch (\Exception $ex) {
            return _json('error', _lang('app.something_went_wrong'), 400);
        }
    }

    public function getCategories(Request $request, $category)
    {
        try {
            $categories = $this->categoryRepository->getByParent($category);
            $categories = $categories->count() > 0 ? $categories->toArray() : [];
            return _json('success', ['categories' => $categories]);
        } catch (\Exception $ex) {
            return _json('error', _lang('app.something_went_wrong'), 400);
        }
    }


    
}
