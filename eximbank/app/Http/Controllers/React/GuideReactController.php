<?php

namespace App\Http\Controllers\React;

use App\Models\Guide;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\InteractionHistory;
use Illuminate\Support\Facades\Auth;

class GuideReactController extends Controller
{
    public function index()
    {
        return view('react.guide.index');
    }

    public function dataGuide($type)
    {
        if ($type == 1) {
            $guides = Guide::where('type',1)->get();
            foreach ($guides as $key => $guide) {
                $guide->path = upload_file($guide->attach);
                $guide->link_download = link_download('uploads/'.$guide->attach);
            }

        } else if ($type == 2) {
            $guides = Guide::where('type',2)->get();
            foreach ($guides as $key => $guide) {
                $guide->video = image_file($guide->attach);
            }
        } else {
            $guides = Guide::where('type',3)->get();
        }

        if($type != 3){
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
            'guides' => $guides,
        ]);
    }

    public function postDetail($id)
    {
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

        $guide = Guide::where('id',$id)->first();
        return response()->json([
            'guide' => $guide,
        ]);
    }

    public function viewPDF($id){
        if (url_mobile()){
            $path = str_replace(config('app.url'), config('app.mobile_url'), $path);

            return view('themes.mobile.frontend.guide.view_pdf', [
                'path' => $path,
            ]);
        }
    }
}
