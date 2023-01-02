<?php

namespace App\Http\Controllers\React;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Models\InteractionHistory;
use Modules\FAQ\Entities\FAQs;

class FaqReactController extends Controller
{
    public function index()
    {
        return view('react.faq.index');
    }

    public function getData()
    {
        $faqs = FAQs::get();
        /*foreach ($faqs as $key => $faq) {
            $faq->content = strip_tags($faq->content);
        }*/
        if($faqs->count() > 0){
            /*Lưu lịch sử tương tác của HV*/
            $interaction_history = InteractionHistory::where(['user_id' => profile()->user_id, 'code' => 'help'])->first();
            if($interaction_history){
                $interaction_history->number = ($interaction_history->number + 1);
                $interaction_history->save();
            }else{
                $interaction_history = new InteractionHistory();
                $interaction_history->user_id = profile()->user_id;
                $interaction_history->code = 'help';
                $interaction_history->name = 'Trợ giúp';
                $interaction_history->number = 1;
                $interaction_history->save();
            }
        }

        return response()->json([
            'faqs' => $faqs,
        ]);
    }

}
