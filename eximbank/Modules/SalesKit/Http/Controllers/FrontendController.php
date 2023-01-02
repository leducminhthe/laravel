<?php

namespace Modules\SalesKit\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\ProfileView;
use Modules\SalesKit\Entities\SalesKit;
use Modules\SalesKit\Entities\SalesKitCategory;
use Modules\SalesKit\Entities\SalesKitObject;

class FrontendController extends Controller
{
    public function salekit(){
        $category = SalesKitCategory::whereNull('parent_id')->get();

        $check_child = function($parent_id){
            return SalesKitCategory::where('parent_id', $parent_id)->exists();
        };

        return view('saleskit::frontend.salekit', [
            'category' => $category,
            'check_child' => $check_child,
        ]);
    }

    public function salekitChild($cate_id){
        $category = SalesKitCategory::find($cate_id);
        $parent = SalesKitCategory::find(@$category->parent_id);

        $category_child = SalesKitCategory::where('parent_id', $cate_id)->get();

        $check_child = function($parent_id){
            return SalesKitCategory::where('parent_id', $parent_id)->exists();
        };

        $saleskit = SalesKit::whereCategoryId($cate_id)->get();
        $saleskit_obj = function ($lib_id){
            $profile = profile();
            $object = SalesKitObject::where('saleskit_id', '=', $lib_id);

            if(!$object->exists()){
                return true;
            }else{
                $status = SalesKitObject::where(function($sub) use ($profile){
                    $sub->orWhere('user_id', '=', $profile->user_id)
                    ->orWhere('title_id', '=', $profile->title_id)
                    ->orWhere('unit_id', '=', $profile->unit_id);
                })
                ->where('saleskit_id', '=', $lib_id)
                ->whereIn('status', [1,3]);

                return $status->exists();
            }
        };

        return view('saleskit::frontend.salekit_child', [
            'parent' => $parent,
            'category' => $category,
            'category_child' => $category_child,
            'check_child' => $check_child,
            'saleskit' => $saleskit,
            'saleskit_obj' => $saleskit_obj,
        ]);
    }

    public function salekitDetail($cate_id){
        $category = SalesKitCategory::find($cate_id);
        $parent = SalesKitCategory::find(@$category->parent_id);
        $saleskit = SalesKit::whereCategoryId($cate_id)->get();

        $saleskit_obj = function ($lib_id){
            $profile = profile();
            $object = SalesKitObject::where('saleskit_id', '=', $lib_id);

            if(!$object->exists()){
                return true;
            }else{
                $status = SalesKitObject::where(function($sub) use ($profile){
                    $sub->orWhere('user_id', '=', $profile->user_id)
                    ->orWhere('title_id', '=', $profile->title_id)
                    ->orWhere('unit_id', '=', $profile->unit_id);
                })
                ->where('saleskit_id', '=', $lib_id)
                ->whereIn('status', [1,3]);

                return $status->exists();
            }
        };

        return view('saleskit::frontend.salekit_detail', [
            'parent' => $parent,
            'category' => $category,
            'saleskit' => $saleskit,
            'saleskit_obj' => $saleskit_obj,
        ]);
    }

    public function viewPDF($id){
        $path = SalesKit::find($id)->getLinkView();

        $path = convert_url_web_to_app($path);
        if (url_mobile()){
            return view('themes.mobile.frontend.saleskit.view_pdf', [
                'path' => $path,
            ]);
        }
        return view('saleskit::frontend.view_pdf', [
            'path' => $path,
        ]);
    }
}
