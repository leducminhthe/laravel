<?php

namespace App\Http\Controllers\Mobile;

use App\Models\Guide;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GuideController extends Controller
{
    public function index()
    {
        $guides = Guide::where('type',1)->get();
        return view('themes.mobile.frontend.guide.index', [
            'guides' => $guides,
        ]);
    }

    public function video()
    {
        $guides = Guide::where('type',2)->paginate(3);
        return view('themes.mobile.frontend.guide.index', [
            'guides' => $guides,
        ]);
    }

    public function posts()
    {
        $guides = Guide::where('type',3)->paginate(5);
        return view('themes.mobile.frontend.guide.index', [
            'guides' => $guides,
        ]);
    }

    public function postDetail($id)
    {
        $guide = Guide::where('id',$id)->first();
        return view('themes.mobile.frontend.guide.index', [
            'guides' => $guide,
        ]);
    }

    public function viewPDF($id){
        $guide = Guide::find($id);
        $path = upload_file($guide->attach);
        $path = convert_url_web_to_app($path);

        return view('themes.mobile.frontend.guide.view_pdf', [
            'path' => $path,
        ]);
    }
}
