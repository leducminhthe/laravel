<?php

namespace App\Http\Controllers\React;

use App\Models\CourseView;
use App\Models\CourseRegisterView;
use App\Models\Automail;
use App\Models\Config;
use App\Models\CourseComplete;
use App\Models\Profile;
use App\Events\Online\GoActivity;
use App\Models\Categories\Unit;
use App\Models\Permission;
use App\Models\Categories\Subject;
use App\Models\Categories\TrainingProgram;
use App\Scopes\CompanyScope;
use App\Scopes\DraftScope;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\AppNotification\Helpers\AppNotification;
use Modules\Notify\Entities\Notify;
use Modules\Online\Entities\OnlineCourse;
use Illuminate\Support\Facades\Auth;
use Modules\RefererHist\Entities\RefererRegisterCourse;
use Modules\User\Entities\TrainingProcess;
use App\Models\Categories\Titles;
use App\Models\Categories\LevelSubject;
use Modules\Promotion\Entities\PromotionCourseSetting;
use App\Models\CourseBookmark;

class CourseReactController extends Controller
{
    public function index()
    {
        return view('react.course.index');
    }

    public function dataCourse($type, Request $request) {
        $items = $this->getItems($type, $request);
        return response()->json([
            'items' => $items,
        ]);
    }

    public function getItems($type = 0, Request $request) {
        $search = $request->get('search');
        $fromdate = $request->get('fromdate');
        $todate = $request->get('todate');
        $training_program_id = $request->get('training_program_id');
        $level_subject_id = $request->get('level_subject_id');
        $status = $request->get('search_status') ? $request->get('search_status') : [];
        $course_type = $request->get('course_type') ? $request->get('course_type') : [];
        $user_id = profile()->user_id;

        CourseView::addGlobalScope(new CompanyScope());
        $query = CourseView::query();
        $query->select([
            'el_course_view.course_id',
            'el_course_view.code',
            'el_course_view.name',
            'el_course_view.course_type',
            'el_course_view.content',
            'el_course_view.start_date',
            'el_course_view.end_date',
            'el_course_view.register_deadline',
            'el_course_view.image',
            'el_course_view.min_grades',
            'el_course_view.title_join_id',
            'el_course_view.title_recommend_id',
            'el_course_view.auto',
        ]);
        if(!in_array(5,$status) || in_array(3,$course_type) || in_array(4,$course_type)) {
            $query->leftjoin('el_course_register_view as b',function($join){
                $join->on('el_course_view.course_id','=','b.course_id');
                $join->on('el_course_view.course_type','=','b.course_type');
            });
        }
        $query->where('el_course_view.status', '=', 1);
        $query->where('el_course_view.isopen', '=', 1);

        $get_course_id_register = CourseRegisterView::where('user_id',$user_id)->pluck('course_id')->toArray();
        $get_course_id_complete = CourseComplete::where('user_id',$user_id)->pluck('course_id')->toArray();

        if(in_array(1, $status)) {
            $query->whereNotIn('el_course_view.course_id', $get_course_id_register);
            $query->where(function ($sub){
                $sub->whereNull('el_course_view.end_date');
                $sub->orWhere('el_course_view.end_date', '>', date('Y-m-d'));
            });
        }
        if( in_array(2, $status) || in_array(3, $course_type)) {
            $query->where('b.user_id', $user_id);
            $query->where('b.status',1);
        }
        if( in_array(3, $status) ) {
            $query->where('b.user_id', $user_id);
            $query->where('b.status',2);
        }
        if( in_array(4, $status) ) {
            $query->whereIn('el_course_view.course_id',$get_course_id_complete);
        }
        if( in_array(5, $status) ) {
            $query->where('el_course_view.end_date', '<=', date('Y-m-d'));
        }
        if(in_array(4, $course_type)) {
            $query->where('b.user_id', $user_id);
            $query->where('b.status',1);
            $query->whereNotIn('el_course_view.course_id',$get_course_id_complete);
        }
        if (in_array(5, $course_type)) {
            $query->Join('el_course_bookmark as cb', function($join){
                $join->on('el_course_view.course_id','=','cb.course_id');
                $join->on('el_course_view.course_type','=','cb.type');
            });
        }

        if ($search) {
            $query->where(function ($subquery) use ($search) {
                $subquery->orWhere('el_course_view.code', 'like', '%'. $search .'%');
                $subquery->orWhere('el_course_view.name', 'like', '%'. $search .'%');
            });
        }

        if (in_array(1, $course_type) || in_array(2, $course_type)) {
            $query->whereIn('el_course_view.course_type',$course_type);
        } else if ($type == '1' || $type == '2') {
            $query->where('el_course_view.course_type',$type);
        }

        if ($fromdate) {
            $query->where('el_course_view.start_date', '>=', date_convert($fromdate, '00:00:00'));
        }

        if ($todate) {
            $query->where(function ($sub) use ($todate){
                $sub->whereNull('el_course_view.end_date');
                $sub->orWhere('el_course_view.end_date', '<=', date_convert($todate, '23:59:59'));
            });
        }

        if ($training_program_id) {
            $query->whereIn('el_course_view.training_program_id', $training_program_id);
        }

        if ($level_subject_id){
            $query->whereIn('el_course_view.level_subject_id',$level_subject_id);
        }

        $query->orderByDesc('el_course_view.created_at');
        $items = $query->paginate(12);
        return $items;
    }

    public function ajaxConentCourse(Request $request)
    {
        $course = CourseView::select('content')->where('course_id',$request->id)->where('course_type',$request->type)->first();
        $content_course = html_entity_decode($course->content);
        json_result($content_course);
    }

    public function ajaxObjectCourse(Request $request)
    {
        $item = CourseView::select('title_join_id','title_recommend_id')->where('course_id',$request->id)->where('course_type',$request->type)->first();
        $titles_join = [];
        $titles_recomment = [];
        $get_titles_join = json_decode($item->title_join_id);
        $get_titles_recomment = json_decode($item->title_recommend_id);
        if(!empty($get_titles_join) && !in_array(0,$get_titles_join)) {
            foreach ($get_titles_join as $key => $get_title_join) {
                $get_title = Titles::find($get_title_join);
                $titles_join[] = $get_title->name;
            }
        } elseif (!empty($get_titles_join) && in_array(0,$get_titles_join)) {
            $titles_join[] = 'Tất cả chức danh';
        }

        if(!empty($get_titles_recomment) && !in_array(0,$get_titles_recomment)) {
            foreach ($get_titles_recomment as $key => $get_title_recomment) {
                $title_recomment = Titles::find($get_title_recomment);
                $titles_recomment[] = $title_recomment->name;
            }
        } elseif (!empty($get_titles_recomment) && in_array(0,$get_titles_recomment)) {
            $titles_recomment[] = 'Tất cả chức danh';
        }
        json_result([
            'titles_join' => $titles_join,
            'titles_recomment' => $titles_recomment,
        ]);
    }

    public function ajaxBonusCourse(Request $request)
    {
        $type = $request->type;
        $course_id = $request->id;
        $arr_code = [
            'assessment_after_course' => 'Đánh giá sau khóa học',
            'evaluate_training_effectiveness' => 'Đánh giá hiệu quả đào tạo',
            'rating_star' => 'Đánh giá sao',
            'share_course' => 'Share khóa học'
        ];
        $complete = PromotionCourseSetting::getPromotionCourseSetting($course_id, $request->type, 'complete');
        $landmarks = PromotionCourseSetting::getPromotionCourseSetting($course_id, $request->type, 'landmarks');
        $rating_star = PromotionCourseSetting::getPromotionCourseSetting($course_id, $request->type, 'rating_star');
        $html = '';
        $rhtml = '';
        if ($complete) {
            $html .= '<div class="form-check form-check-inline">
                        <div class="custom-control custom-radio promotion_0_radio">
                            <input type="radio" class="custom-control-input point-type" id="promotion_0_'.$course_id.'_'.$type.'" onclick="checkBoxBonus('.$course_id.','.$type.')" name="method" value="0">
                            <label class="custom-control-label" for="promotion_0_'.$course_id.'_'.$type.'">'. trans('backend.complete_course') .'</label>
                        </div>
                    </div>';
            $rhtml .= '<div class="promotion_0_group_'.$course_id.'_'.$type.'">
                        <div class="form-group row">
                            <div class="col-sm-3 control-label"></div>
                            <div class="col-md-9">
                                <input name="start_date" readonly type="text" class="form-control w-25 d-inline-block datepicker" placeholder="Bắt đầu" autocomplete="off" value="'. ($complete && $complete->start_date ? get_date($complete->start_date) : '') .'">
                                <input name="end_date" readonly type="text" class="form-control w-25 d-inline-block datepicker" placeholder="Kết thúc" autocomplete="off" value="'.($complete && $complete->end_date ? get_date($complete->end_date) : '').'">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3 control-label"></div>
                            <div class="col-md-4">
                                <input name="point_complete" readonly type="text" class="form-control" placeholder="'.trans('backend.bonus_points').'" autocomplete="off" value="'. ($complete ? $complete->point : '') .'">
                            </div>
                        </div>
                    </div>';
        }
        if ($landmarks) {
            $html .= '<div class="form-check form-check-inline promotion_1_radio">
                        <div class="custom-control custom-radio">
                            <input type="radio" class="custom-control-input point-type" id="promotion_1_'.$course_id.'_'.$type.'" onclick="checkBoxBonus('.$course_id.','.$type.')" name="method" value="1">
                            <label class="custom-control-label" for="promotion_1_'.$course_id.'_'.$type.'">'.trans('backend.landmarks').'</label>
                        </div>
                    </div>';
            $rhtml .= '<div class="promotion_1_group_'.$course_id.'_'.$type.'">
                        <div class="row promotion-table">
                            <div class="col-md-12">
                                <table class="tDefault table table-hover bootstrap-table text-nowrap" id="table_setting_'.$course_id.'_'. $type .'">
                                    <thead>
                                        <tr>
                                            <th data-align="center" data-width="3%" data-formatter="stt_formatter_bonus">STT</th>
                                            <th data-field="score" data-align="center">'.trans('backend.landmarks') .'</th>
                                            <th data-field="point" data-align="center">'.trans('backend.bonus_points') .'</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>';
        }
        if ($rating_star) {
            $html .= '<div class="form-check form-check-inline promotion_2_radio">
                        <div class="custom-control custom-radio">
                            <input type="radio" class="custom-control-input point-type" id="promotion_2_'.$course_id.'_'.$type.'" onclick="checkBoxBonus('.$course_id.','.$type.')" name="method" value="2">
                            <label class="custom-control-label" for="promotion_2_'.$course_id.'_'.$type.'">'.trans('backend.other') .'</label>
                        </div>
                    </div>';
            $rhtml .= '';
        }
        $rhtml .= '<div class="promotion_2_group_'.$course_id.'_'.$type.'">';
        foreach($arr_code as $key => $code) {
            $other = PromotionCourseSetting::getPromotionCourseSetting($course_id, $type, $key);
            if ($other){
                $rhtml .= '<div class="form-group row">
                            <div class="col-sm-3 control-label"></div>
                            <div class="col-md-4">
                                '.$code.'
                            </div>
                            <div class="col-md-4">
                                <input name="point[]" readonly type="text" class="form-control" placeholder="'.trans('backend.bonus_points') .'" autocomplete="off" value="'. ($other ? $other->point : '') .'">
                            </div>
                        </div>';
            }
        }
        $rhtml .= '</div>';
        json_result([
            'complete' => $complete,
            'landmarks' => $landmarks,
            'other' => $other,
            'html' => $html,
            'rhtml' => $rhtml,
        ]);
    }

    public function loadData($items) {
        $data = '';
        foreach ($items as $item) {
            $type = $item->course_type;
            $url2 = $item->course_type == 1 ? route('module.online.detail_online', ['id' => $item->course_id]) : route('module.offline.detail', ['id' => $item->course_id]);

            $item->getStatus($item->course_type);
            $get_promotion = PromotionCourseSetting::where('course_id',$item->course_id)->where('type',$type)->first();
            $get_bookmarked = CourseBookmark::where('course_id',$item->course_id)->where('type',$type)->where('user_id',profile()->user_id)->first();
            $check_promotion_course_setting = PromotionCourseSetting::where('course_id',$item->course_id)->exists();

            $check_course_complete = CourseComplete::where('course_id',$item->course_id)->where('course_type',$item->course_type)->where('user_id', profile()->user_id)->first();
            $status = $item->getStatusRegister( $item->course_type );
            $text = status_register_text($status);
            if ($type == 1) {
                $percent = OnlineCourse::percentCompleteCourseByUser($item->course_id, profile()->user_id);
                $img_course = image_online($item->image);
            } else {
                $percent = 0;
                $img_course = image_offline($item->image);
            }
            if($item->end_date) {
                $end_time = trans('app.to') . ' ' . get_date($item->end_date);
            } else {
                $end_time = '';
            }
            $data .= '<div class="col-lg-3 col-md-4 p-1 list_course">
                        <div class="fcrse_1 mb-20">
                        <a href="'. $url2 .'" class="fcrse_img">
                            <img class="picture_course" src="'. $img_course .'" alt="">
                            <div class="course-overlay">';

                            if ( !empty($get_promotion) ) {
                                if ($get_promotion->method == 1) {
                                    $point = $get_promotion->point;
                                } else {
                                    $setting = $get_promotion->methodSetting->sortByDesc('point');
                                    $point = $setting->count() > 0 ? $setting->first()->point : 0;
                                }
                                $data .= '
                                <div class="badge_seller">
                                    '. $point .'
                                    <img class="point ml-1" style="width: 20px;height: 20px" src="'. asset('styles/images/level/point.png') .'" alt="">
                                </div>';
                            }

            $data .= '      <div class="crse_reviews">
                                    <i class="uil uil-star"></i>'.$item->avgRatingStar($type) .'
                                </div>
                            </div>
                        </a>
                        <div class="fcrse_content">
                            <div class="eps_dots more_dropdown check_course">
                                <a href="javascript:void(0)"><i class="uil uil-ellipsis-v"></i></a>
                                <div class="dropdown-content">
                                    <span>
                                        <i class="uil uil-heart-alt"></i>';
                                        if (!empty($get_bookmarked)) {
                                            $data .= '
                                            <a href="'. route("frontend.home.remove_course_bookmark",["course_id"=>$item->course_id,"course_type"=>$type, "my_course"=> 0]) .'" class="item-bookmark">
                                                '. trans('app.unbookmark') .'
                                            </a>';
                                        } else {
                                            $data .= '
                                            <a href="'. route("frontend.home.save_course_bookmark",["course_id"=>$item->course_id,"course_type"=>$type, "my_course" => 0]) .'" class="item-bookmark">
                                                '.trans('app.bookmark').'
                                            </a>';
                                        }
            $data .= '              </span>';
                                    if ($check_promotion_course_setting) {
                                    $data .=
                                    '<span onclick="openModalBonus('.$item->course_id.','.$type.')">
                                        <img class="image_bonus_courses" src="'.asset("images/level/point.png").'" alt="" width="29px" height="15px">
                                        Điểm thưởng
                                    </span>';
                                    }
            $data .= '              <span href="javascript:void(0)" style="cursor: pointer" class="ml-1" onclick="shareCourse('.$item->course_id.','.$type.')">
                                        <i class="fas fa-link mr-2"></i>
                                        Share
                                    </span>
                                </div>
                            </div>
                            <div class="vdtodt">
                                <span class="vdt14"><i class="uil uil-windsock"></i>'. $item->register($item->course_type)->count() .' '.trans('app.joined').'</span>
                                <span class="vdt14"><i class="uil uil-heart '. (!empty($get_bookmarked) ? 'check-heart' : '').'"></i> '.(!empty($get_bookmarked) ? __('app.bookmarked') : __('app.bookmark')).'</span>
                            </div>
                            <div class="course_names">
                                <a href="'.$url2 .'" class="crse14s course_name">'.$item->name.'</a>
                                <span class="hidden_name">'.$item->name.'</span>
                            </div>
                            <div class="vdtodt">
                                <span class="vdt14"><b>Mã khóa học:</b> '.$item->code.'</span>
                            </div>
                            <div class="vdtodt">
                                <span class="vdt14"><b>'.trans("app.time").':</b> '.get_date($item->start_date).' '. $end_time .'</span>
                            </div>
                            <div class="vdtodt description_course" onclick="openModalDescription('.$item->course_id.','.$type.')" style="cursor: pointer">
                                <span class="vdt14"><b>Mô tả:</b> Chi tiết</span>
                            </div>
                            <div class="vdtodt register_deadline">
                                <span class="vdt14"><b>'.trans("app.register_deadline").':</b> '.get_date($item->register_deadline).'</span>
                            </div>
                            <div class="vdtodt passing_score">
                                <span class="vdt14"><b>Điểm đạt:</b> '.$item->min_grades.'</span>
                            </div>
                            <div class="vdtodt course_type_item">
                                <span class="vdt14"><b>Hình thức:</b> '.($type == 1 ? "Online" : "Tập trung") .'</span>
                            </div>
                            <div class="vdtodt course_object" onclick="openModalObject('.$item->course_id.','.$type.')" style="cursor: pointer">
                                <p class="cr1fot import-plan"><b>Đối tượng:</b> <i title="'.$item->getStatus($item->course_type).'">Chi tiết</i></p>
                            </div>
                            <div class="auth1lnkprce">
                                <div class="row">
                                    <div class="col-4 chart">
                                        <div class="chartProgress">
                                            <input type="hidden" name="text" class="canvas_percent" value="'.$item->course_id.','. $type .','.$percent.','.$status.'">';
                                            if ($status == 4 && $type == 1) {
                                                $data.='<canvas id="chartProgress_'.$item->course_id.'_'.$type.'" width="80px" height="80px"></canvas>';
                                            }
            $data.='                    </div>
                                    </div>
                                    <div class="prce142 col-8 button_course">';
                                        if($status == 1 && empty($check_course_complete)) {
                                            $data.='
                                            <div class="mt-2 item item-btn">
                                                <a id="btn_register_'.$item->course_id.'_'.$type.'" class="btn btn_adcart" onclick="submitRegister('.$item->course_id.','.$type.')">'.$text.'</a>
                                            </div>';
                                        } elseif($status == 4 && empty($check_course_complete)) {
                                            $data.='
                                            <div class="mt-2">
                                                <a href="'.$url2.'" class="btn btn_adcart">Vào học</a>
                                            </div>';
                                        } elseif ( !empty($check_course_complete) ){
                                            $data.=`
                                            <div class="mt-2">
                                                <a href="'.$url2.'" class="btn btn_adcart">Hoàn thành</a>
                                            </div>`;
                                        } else {
                                            $data.='
                                            <div class="mt-2">
                                                <a onclick="endCourse('.$item->course_id.','.$type.','.$status.')" type="button" class="btn btn_adcart">'.$text.'</a>
                                            </div>';
                                        }
            $data.='                </div>
                                </div>
                            </div>
                        </div>
                        </div>
                    </div>';

        }
        return $data;
    }

    public function ajaxCourseTrainingProgram(Request $request)
    {
        $type = $request->course_type ? $request->course_type : 0;
        $items = $this->getItems($type, $request);
        $data = '';
        if ($items) {
            $data = $this->loadData($items);
        }
        return $data;
    }
}
