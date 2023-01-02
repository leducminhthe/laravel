<?php

namespace App\Http\Controllers\Mobile;

use App\Models\CourseView;
use App\Models\DonatePoints;
use App\Models\Categories\Titles;
use App\Models\Categories\Unit;
use App\Models\Profile;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserMeta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\CareerRoadmap\Entities\CareerRoadmap;
use Modules\DailyTraining\Entities\DailyTrainingVideo;
use Modules\Promotion\Entities\Promotion;
use Modules\Promotion\Entities\PromotionUserPoint;
use App\Models\Categories\TrainingTeacher;
use App\Models\ProfileView;
use Modules\EmulationBadge\Entities\UserEmulationBadge;
use Modules\EmulationBadge\Entities\EmulationBadge;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Online\Entities\OnlineCourse;
use Modules\Promotion\Entities\PromotionOrders;
use Modules\User\Entities\TrainingProcess;
use Modules\UserPoint\Entities\UserPointItem;
use Modules\UserPoint\Entities\UserPointResult;
use App\Models\MyCertificate;
use Illuminate\Support\Str;


class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $lay = 'profile';
    
        $user = profile();
        $title = Titles::whereCode($user->title_code)->first();
        $unit = Unit::whereCode($user->unit_code)->first();
        $user_name = User::find($user->user_id)->username;

        $info_qrcode = json_encode([
            'user_id' => profile()->user_id,
            'type' => 'profile',
        ]);

        return view('themes.mobile.frontend.profile', [
            'lay' => $lay,
            'user_point' => $this->getUserPoint(),
            'user' => $user,
            'title' => $title,
            'unit' => $unit,
            'user_name' => $user_name,
            'total_user' => $this->getTotalUser(),
            'user_rank' => $this->getRankUser(),
            'info_qrcode' => $info_qrcode,
        ]);
    }

    public function qrCodeUser()
    {
        $info_qrcode = json_encode([
            'user_id' => profile()->user_id,
            'type' => 'profile',
        ]);

        $userPointInfo = PromotionUserPoint::where('user_id', profile()->user_id)->first();
        $promotions = Promotion::where('el_promotion.status',1)
            ->select('el_promotion.*','el_promotion_group.name as group_name')
            ->join('el_promotion_group', 'el_promotion_group.id','promotion_group')
            ->orderBy('period')->get();

        return view('themes.mobile.frontend.qrcode.qrcode_user', [
            'info_qrcode' => $info_qrcode,
            'promotions' => $promotions,
        ]);
    }

    public function trainingProcess()
    {
        return view('themes.mobile.frontend.training_process', [
            'get_history_course' => $this->getTrainingProcess(),
        ]);
    }

    /*Lịch sử học của user*/
    public function getTrainingProcess()
    {
        $query = TrainingProcess::query();
        $query->select([
            'id',
            'course_id',
            'course_code as code',
            'course_name as name',
            'course_type',
            'pass as result',
            'start_date',
            'end_date',
        ]);
        $query->where('user_id','=',profile()->user_id);
        $rows = $query->paginate(20);

        foreach($rows as $item){
            if ($item->course_type == 1){
                $course = OnlineCourse::find($item->course_id);
                $percent = OnlineCourse::percentCompleteCourseByUser($item->course_id, profile()->user_id);
                $type = 'Online';
                $url = route('themes.mobile.frontend.online.detail',['course_id' => $item->course_id]);
            }else{
                $course = OfflineCourse::find($item->course_id);
                $percent = OfflineCourse::percent($item->course_id, profile()->user_id);
                $type = 'Offline';
                $url = route('themes.mobile.frontend.offline.detail',['course_id' => $item->course_id]);
            }

            $item->image_cert = '';
            if (isset($course->cert_code) && $item->result == 1){
                $item->image_cert = route('module.frontend.user.trainingprocess.certificate', ['course_id' => $item->course_id, 'course_type' => $item->course_type, 'user_id' => profile()->user_id]);
            }

            $item->check = false;
            if($course->isopen == 1 && $course->status == 1) {
                $item->check = true;
            }

            $item->percent = $percent;
            $item->type = $type;
            $item->url = $url;
        }

        return $rows;
    }

    /*Lấy điểm của 1 học viên*/
    public function getUserPoint(){
        $user = PromotionUserPoint::where('user_id', profile()->user_id)->first();

        return $user;
    }

    /*Lấy tất cả học sinh có điểm*/
    public function getTotalUser(){
        $training_teacher = TrainingTeacher::whereNotNull('user_id')->pluck('user_id')->toArray();
        $user = Profile::query()
            ->select([
                'profile.user_id',
                'user_point.point',
            ])
            ->from('el_promotion_user_point as user_point')
            ->leftJoin('el_profile as profile', 'user_point.user_id', '=', 'profile.user_id')
            ->where('profile.status', '=', 1)
            //->whereNotIn('profile.user_id', $training_teacher)
            ->orderBy('user_point.point', 'DESC')
            ->get();

        return $user;
    }

    public function getRankUser(){
        $training_teacher = TrainingTeacher::whereNotNull('user_id')->pluck('user_id')->toArray();
        $user = Profile::query()
            ->select([
                'profile.user_id',
            ])
            ->from('el_promotion_user_point as user_point')
            ->leftJoin('el_profile as profile', 'user_point.user_id', '=', 'profile.user_id')
            ->where('profile.status', '=', 1)
            //->whereNotIn('profile.user_id', $training_teacher)
            ->orderBy('user_point.point', 'DESC')
            ->get();

        $user_rank = '';
        foreach ($user as $key => $item){
            if ($item->user_id == profile()->user_id){
                $user_rank = ($key + 1);
            }
        }

        return $user_rank;
    }

    public function changeAvatar(Request $request){
        $posts = [ 'selectavatar' => $request->file('selectavatar') ];
        $rules = [ 'selectavatar' => 'required|image|max:10240' ];
        $message = [
            'selectavatar.required' => 'Chưa chọn hình để upload',
            'selectavatar.image' => 'File hình không hợp lệ',
            'selectavatar.uploaded'  =>'Dung lượng hình không được lớn hơn 10mb'
        ];

        $validator = \Validator::make($posts, $rules,$message);
        if ($validator->fails()){
            return redirect()->back();
        }

        $avatar = $request->file('selectavatar');
        $storage = \Storage::disk(config('app.datafile.upload_disk'));
        $extension = $avatar->getClientOriginalExtension();
        $filename = 'avatar-' . profile()->user_id .'.'. $extension;

        if($storage->putFileAs('profile', $avatar, $filename))
        {
            Profile::where('user_id','=',profile()->user_id)->update(['avatar'=>$filename]);
            ProfileView::where('user_id','=',profile()->user_id)->update(['avatar'=>$filename]);

            return redirect()->back();
        }
        else{
            return redirect()->back();
        }
    }

    public function accumulatedVideo()
    {
        $get_history_video = DailyTrainingVideo::query()
            ->where('created_by', '=', profile()->user_id)
            ->where('status', '=', 1)
            ->where('approve', '=', 1)
            ->paginate(20);

        return view('themes.mobile.frontend.history_point.video', [
            'get_history_video' => $get_history_video,
        ]);
    }

    public function myCourse(){
        $query = CourseView::query()
            ->from('el_course_view as a')
            ->select(['a.*'])->disableCache()
            ->join('el_course_register_view as b',function($join){
                $join->on('a.course_id','=','b.course_id');
                $join->on('a.course_type','=','b.course_type');
            })
            ->where('b.user_id','=', profile()->user_id)
            ->where('a.status', '=', 1)
            ->where('b.status', '=', 1)
            ->where('a.isopen', '=', 1)
            ->where('a.offline', '=', 0)
            ->orderBy('a.id', 'desc');

        return view('themes.mobile.frontend.my_course', [
            'my_course' => $query->paginate(10),
            'total' => $query->count(),
        ]);
    }

    public function myCourseLike(){
        $query = CourseView::query()
            ->from('el_course_view as a')
            ->select(['a.*'])->disableCache()
            ->join('el_course_bookmark as b',function($join){
                $join->on('a.course_id','=','b.course_id');
                $join->on('a.course_type','=','b.type');
            })
            ->where('b.user_id','=', profile()->user_id)
            ->where('a.status', '=', 1)
            ->where('a.isopen', '=', 1)
            ->where('a.offline', '=', 0)
            ->orderBy('a.id', 'desc');

        return view('themes.mobile.frontend.my_course_like', [
            'my_course' => $query->paginate(10),
            'total' => $query->count(),
        ]);
    }

    public function getRank(){
        $lay = 'rank';

        return view('themes.mobile.frontend.rank_user', [
            'rank' => $this->getFiveUserMaxPoint(10),
            'lay' => $lay
        ]);
    }

    // HUY HIỆU THI ĐUA
    public function getDataEmulationBadge() {

        $query = UserEmulationBadge::query();
        $query->select([
            'b.name',
            'b.start_time',
            'b.end_time',
            'c.image',
            'c.type',
            'c.level',
        ]);
        $query->from('user_emulation_badge as a');
        $query->join('emulation_badge as b', 'b.id', '=', 'a.emulation_badge_id');
        $query->join('armorial_emulation_badge as c', 'c.id', '=', 'a.armorial_id');
        $query->where(['a.user_id' => profile()->user_id, 'b.status' => 1]);
        $query->orderBy('b.id', 'asc');

        $rows = $query->get();
        foreach ($rows as $row) {
            $row->image = image_file($row->image);
            $row->start_time = get_date($row->start_time);
            $row->end_time = get_date($row->end_time);
            if($row->type == 1) {
                $row->name_armorial = trans('latraining.fastest_learning_badge');
            } else if ($row->type == 2) {
                $row->name_armorial = trans('latraining.top_score_badge');
            } else {
                $row->name_armorial = trans('latraining.earliest_interactive_badge');
            }
        }

        return $rows;
        //json_result(['total' => $count, 'rows' => $rows]);
    }

    // LỊCH SỬ TÍCH ĐIỂM
    public function historyPoint() {
        $get_history_video = DailyTrainingVideo::query()
            ->where('created_by', '=', profile()->user_id)
            ->where('status', '=', 1)
            ->where('approve', '=', 1)
            ->paginate(20);

        $donate_points = DonatePoints::where('user_id', '=', profile()->user_id)->get();
        return view('themes.mobile.frontend.history_point.history', [
            'get_history_course' => $this->userPointHistory(),
            'get_history_video' => $get_history_video,
            'donate_points' => $donate_points
        ]);
    }
    private function userPointHistory(){
        $query = UserPointResult::where("user_id","=",profile()->user_id);
        $query->select('el_userpoint_result.*', 'el_userpoint_settings.pkey');
        $query->leftJoin('el_userpoint_settings', 'el_userpoint_settings.id', 'el_userpoint_result.setting_id');
        $query->where('el_userpoint_result.point', '>', 0);
        $query->whereNull('el_userpoint_result.type');
        $rows = $query->paginate(20);

        foreach ($rows as $row) {
            if($row->pkey == 'quiz_complete'){
                $row->name = 'Hoàn thành kỳ thi';
            }elseif($row->pkey == 'online_activity_complete'){
                $row->name = 'Hoàn thành hoạt động';
            }else{
                $item= UserPointItem::where("ikey","=",$row->pkey)->first();
                $row->name = $item->name;
            }

            $row->datecreated = get_date($row->created_at, 'H:i:s d/m/Y');
        }

        return $rows;
    }

    // HUY HIỆU THI ĐUA
    public function emulationBadgeList() {
        $emulation_badges = $this->getDataEmulationBadge();
        return view('themes.mobile.frontend.emulation_badge.index', [
            'emulation_badges' => $emulation_badges
        ]);
    }

    //Xoá tài khoản đăng ký
    public function removeAccount(){
        Profile::where('user_id', profile()->user_id)->delete();
        User::where('id', profile()->user_id)->delete();

        json_result([
            'status' => 'success',
            'message' => 'Xoá thành công',
            'redirect' => route('login'),
        ]);
    }

    //Lịch sử quà tặng
    public function myPromotion(){
        $promotion_orders = PromotionOrders::whereUserId(profile()->user_id)
            ->select('el_promotion_orders.*','el_promotion.name','el_promotion.images','el_promotion_group.name as group_name')
            ->join('el_promotion', 'promotion_id', 'el_promotion.id')
            ->join('el_promotion_group', 'el_promotion_group.id','promotion_group')
            ->paginate(8);
        foreach($promotion_orders as $order) {
            if($order->status == 'Quy đổi thành công') {
                $order->status = trans('laother.successful_conversion');
            } else if ($order->status == 'Đang chờ xử lý') {
                $order->status = trans('laother.promotion_pending');
            } else if ($order->status == 'Từ chối') {
                $order->status = trans('latraining.deny');
            } else if ($order->status == 'Hủy') {
                $order->status = trans('lacore.cancel');
            } else if ($order->status == 'Đang sử dụng quà tặng') {
                $order->status = trans('laother.using_gift');
            }
        }
        return view('themes.mobile.frontend.my_promotion', [
            'promotion_orders' => $promotion_orders
        ]);
    }

    //Kết quả thi
    public function quizResult($user_id){
        $query = \DB::query()
            ->select([
                'a.quiz_id',
                'c.id',
                'c.code',
                'c.name',
                'c.limit_time',
                'b.start_date',
                'b.end_date',
                'd.grade',
                'd.result',
                'd.reexamine'
            ])
            ->from('el_quiz_register as a')
            ->join('el_quiz_part as b','b.id','=','a.part_id')
            ->join('el_quiz as c','c.id','=','b.quiz_id')
            ->leftJoin('el_quiz_result as d',function ($join){
                $join->on('d.user_id','=','a.user_id');
                $join->on('d.quiz_id','=','a.quiz_id');
                $join->on('d.part_id','=','a.part_id');
            })
            ->where('a.user_id','=', $user_id)
            ->where('c.quiz_type', 3);
        $rows = $query->paginate(20);
        foreach ($rows as $row) {
            $row->start_date = get_date($row->start_date,'d/m/Y H:i');
            $row->end_date = get_date($row->end_date,'d/m/Y H:i');
            $row->grade = number_format(($row->reexamine ? $row->reexamine : $row->grade),2,',','.');
        }

        return view('themes.mobile.frontend.quiz_result', [
            'rows' => $rows,
        ]);
    }

    // CHỨNG CHỈ BÊN NGOÀI
    public function myCertificate(Request $request){
        $checkUser = isset($request->user_id) ? 1 : 0;
        $userId = $request->user_id ? $request->user_id : profile()->user_id;
        $query = \DB::query()
            ->select([
                '*'
            ])
            ->from('el_my_certificate')
            ->where('user_id', $userId);
        $rows = $query->paginate(20);
        foreach ($rows as $row) {
            $row->time_start = get_date($row->time_start, 'd/m/Y');
            $row->date_license = get_date($row->date_license, 'd/m/Y');
            $row->edit_url = route('themes.mobile.front.my_certificate.edit', ['id' => $row->id]);
            $row->img = image_file($row->certificate);
        }

        return view('themes.mobile.frontend.my_certificate.index', [
            'rows' => $rows,
            'checkUser' => $checkUser
        ]);
    }

    public function myCertificateEdit($id = null) {
        $certificate = MyCertificate::firstOrNew(['id' => $id]);
        return view('themes.mobile.frontend.my_certificate.form', [
            'certificate' => $certificate,
        ]);
    }

    // LƯU CHỨNG CHỈ
    public function saveMyCertificate(Request $request)
    {
        $this->validateRequest([
            'time_start' => 'required',
            'date_license' => 'required',
            'certificate' => 'mimes:jpg,png'
        ],$request, MyCertificate::getAttributeName());

        if ($request->path_old) {
            $new_path = $request->path_old;
        } else {
            if(empty($request->file('certificate'))) {
                json_message('Hình chứng chỉ không được trống', 'error');
            }
            $file = $request->file('certificate');
            $type = 'file';
            $filename = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $new_filename = Str::slug(basename($filename, "." . $extension)) . '-' . time() . '.' . $extension;

            $storage = \Storage::disk('upload');
            $new_path = $storage->putFileAs(date('Y/m/d'), $file, $new_filename);
        }

        $model = MyCertificate::firstOrNew(['id' => $request->id]);
        $model->user_id = profile()->user_id;
        $model->name_certificate = $request->name_certificate;
        $model->name_school = $request->name_school;
        $model->rank = $request->rank;
        $model->time_start = get_date($request->time_start, 'Y-m-d');
        $model->date_license = get_date($request->date_license, 'Y-m-d');
        $model->score = $request->score;
        $model->result = $request->result;
        $model->note = $request->note;
        $model->certificate = $new_path;
        $model->save();

        json_result([
            'status' => 'success',
            'message' => trans('laother.successful_save'),
            'redirect' => route('themes.mobile.front.my_certificate')
        ]);
    }

    // XÓA CHỨNG CHỈ
    public function myCertificateDelete(Request $request)
    {
        $delete = MyCertificate::find($request->id)->delete();

        json_result([
            'status' => 'success',
            'message' => 'Xoá thành công',
            'redirect' => route('themes.mobile.front.my_certificate')
        ]);
    }
}
