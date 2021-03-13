<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\BackendController;
use App\Repositories\Backend\Product\ProductRepositoryInterface;

class AjaxController extends BackendController {

    private $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        parent::__construct();
        $this->productRepository = $productRepository;
    }

    public function change_lang(Request $request) {
        $lang_code = $request->input('lang_code');
        $long = 7 * 60 * 24;
        return response()->json([
                    'type' => 'success',
                    'message' => $lang_code
                ])->cookie('AdminLang', $lang_code, $long);
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

    public function categoryProducts(Request $request, $category)
    {
        try {
            $products = $this->productRepository->allByCategory([$category], $request);
            $products = $products->count() > 0 ? $products->toArray() : [];
           return _json('success',['products' => $products]);
        } catch (\Exception $ex) {
            return _json('error', _lang('app.something_went_wrong'), 400);
        }
    }
}
