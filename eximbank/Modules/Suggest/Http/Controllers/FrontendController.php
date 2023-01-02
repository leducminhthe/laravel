<?php

namespace Modules\Suggest\Http\Controllers;

use App\Models\Profile;
use App\Scopes\CompanyScope;
use App\Scopes\DraftScope;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Suggest\Entities\Suggest;
use Modules\Suggest\Entities\SuggestComment;
use Modules\UserPoint\Entities\UserPointResult;
use Modules\UserPoint\Entities\UserPointItem;
use Modules\Promotion\Entities\PromotionUserPoint;
use Modules\Promotion\Entities\PromotionLevel;
use Modules\UserPoint\Entities\UserPointSettings;
use Modules\Notify\Entities\Notify;
use Modules\AppNotification\Helpers\AppNotification;

class FrontendController extends Controller
{
    public function save(Request $request) {
        $this->validateRequest([
            'name' => 'required',
            'content' => 'required',
        ], $request, Suggest::getAttributeName());

        $name = $request->input('name');
        $content = $request->input('content');

        $model = new Suggest();
        $model->name = $name;
        $model->content = $content;
        $model->user_id = profile()->user_id;
        
        if ($model->save()) {
            $get_point = UserPointItem::where('ikey','suggest_create')->first();
            if ((int)$get_point->default_value > 0) {
                $subject = 'Điểm thưởng khi tạo góp ý';
                $content = 'Nhận điểm thưởng khi tạo góp ý: '. $name;

                $save_point_reward = new UserPointResult();
                $save_point_reward->user_id = profile()->user_id;
                $save_point_reward->content = $content;
                $save_point_reward->setting_id = 1;
                $save_point_reward->point = $get_point->default_value;
                $save_point_reward->item_id = $model->id;
                $save_point_reward->type = 10;
                $save_point_reward->type_promotion = 1;
                $save_point_reward->save();

                $user_point = PromotionUserPoint::firstOrNew(['user_id' => profile()->user_id]);
                $user_point->point = (int)$user_point->point + (int)$get_point->default_value;
                $user_point->level_id = PromotionLevel::levelUp($user_point->point, profile()->user_id);
                $user_point->save();

                $query = new Notify();
                $query->user_id = profile()->user_id;
                $query->subject = $subject;
                $query->content = $content;
                $query->url = '';
                $query->created_by = 0;
                $query->save();

                $content = \Str::words(html_entity_decode(strip_tags($query->content)), 10);
                $redirect_url = route('module.notify.view', [
                    'id' => $query->id,
                    'type' => 1
                ]);

                $notification = new AppNotification();
                $notification->setTitle($query->subject);
                $notification->setMessage($content);
                $notification->setUrl($redirect_url);
                $notification->add(profile()->user_id);
                $notification->save();
            } 

            if (url_mobile()){
                return redirect()->route('suggest_react');
            }
            
            json_result([
                'status' => 'success',
                'message' => trans('laother.successful_save'),
                'redirect' => route('suggest_react')
            ]);
        }

        json_message(trans('laother.save_error'), 'error');
    }

    public function modalComment($suggest_id){
        $suggest = Suggest::find($suggest_id);
        $profile = Profile::find($suggest->user_id);
        $comments = SuggestComment::where('suggest_id', '=', $suggest->id)->where('user_id',profile()->user_id)->get();

        if (url_mobile()){
            return view('themes.mobile.frontend.suggest.comment',[
                'suggest' => $suggest,
                'comments' => $comments,
                'profile' => $profile,
            ]);
        }

        return view('suggest::modal.comment', [
            'suggest' => $suggest,
            'comments' => $comments,
            'profile' => $profile,
        ]);
    }

    public function saveComment($suggest_id, Request $request) {
        $this->validateRequest([
            'content' => 'required',
        ], $request, SuggestComment::getAttributeName());

        $content = $request->input('content');

        $model = new SuggestComment();
        $model->content = $content;
        $model->suggest_id = $suggest_id;
        $model->user_id = profile()->user_id;
        $profile = profile();
        
        if ($model->save()) {
            json_result([
                'status' => 'success',
                'message' => trans('laother.successful_save'),
                'user' => $profile->lastname .' '. $profile->firstname,
                'created_at2' => get_date($model->created_at, 'H:i:s d/m/Y'),
                'content' => $model->content,
            ]);
        }

        json_message(trans('laother.save_error'), 'error');
    }
}
