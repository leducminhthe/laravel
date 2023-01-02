<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\FAQ\Entities\FAQs;

class FAQController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request){
        $search = $request->search;

        $faqs = FAQs::query();
        if ($search){
            $faqs->where('name', 'like', '%'.$search.'%');
        }
        $faqs = $faqs->get();

        return view('themes.mobile.frontend.faqs.index', [
            'faqs' => $faqs,
        ]);
    }
}
