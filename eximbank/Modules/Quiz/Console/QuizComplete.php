<?php

namespace Modules\Quiz\Console;

use App\Models\InteractionHistory;
use App\Models\ProfileView;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Modules\AppNotification\Helpers\AppNotification;
use Modules\Notify\Entities\Notify;
use Modules\Promotion\Entities\PromotionCourseSetting;
use Modules\Promotion\Entities\PromotionLevel;
use Modules\Promotion\Entities\PromotionUserHistory;
use Modules\Promotion\Entities\PromotionUserPoint;
use Modules\Quiz\Entities\Quiz;
use Modules\Quiz\Entities\QuizAttempts;
use Modules\Quiz\Entities\QuizResult;
use Modules\Quiz\Entities\QuizUpdateAttempts;
use Modules\Quiz\Http\Helpers\AttemptGrade;
use Modules\UserMedal\Entities\UserMedalObject;
use Modules\UserMedal\Entities\UserMedalResult;
use Modules\UserMedal\Entities\UserMedalSettings;
use Modules\UserMedal\Entities\UserMedalSettingsItems;
use Modules\UserPoint\Entities\UserPointResult;
use Modules\UserPoint\Entities\UserPointSettings;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class QuizComplete extends Command
{
    protected $signature = 'quiz:complete {user_id?} {quiz_id?}';

    protected $description = 'Quiz complete (trường hợp chưa nộp bài + điểm thưởng). cron 10 phút/lần';

    protected $expression = "*/10 * * * *";

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $param_user_id = $this->argument('user_id');
        $param_quiz_id = $this->argument('quiz_id');

        $query = QuizAttempts::with('quiz:id,grade_methor,pass_score,quiz_type')
           ->where('cron_complete','=',1);

        if($param_user_id){
            $query->where('user_id', $param_user_id);
        }
        if($param_quiz_id){
            $query->where('quiz_id', $param_quiz_id);
        }

        $rows = $query->get();
        foreach ($rows as $row) {
            $grade = 0;

            if ($row->state != 'completed') {
                if ($row->quiz->grade_methor == 1) { // lần cao nhất
                    $sumgrade = QuizAttempts::where('quiz_id', '=', $row->quiz_id)
                        ->where('part_id', '=', $row->part_id)
                        ->where('user_id', '=', $row->user_id)
                        ->where('type', '=', $row->type)
                        ->select(\DB::raw('MAX(sumgrades) AS total_grade'))
                        ->first();
                    if ($sumgrade) {
                        $grade = $sumgrade->total_grade;
                    }
                }elseif ($row->quiz->grade_methor == 2) {// Điểm trung bình
                    $sumgrade = QuizAttempts::where('quiz_id', '=', $row->quiz_id)
                        ->where('part_id', '=', $row->part_id)
                        ->where('user_id', '=', $row->user_id)
                        ->where('type', '=', $row->type)
                        ->select(\DB::raw('AVG(sumgrades) AS total_grade'))
                        ->first();
                    if ($sumgrade) {
                        $grade = $sumgrade->total_grade;
                    }
                }elseif ($row->quiz->grade_methor == 3) {// Điểm lần đầu
                    $sumgrade = QuizAttempts::where('quiz_id', '=', $row->quiz_id)
                        ->where('part_id', '=', $row->part_id)
                        ->where('user_id', '=', $row->user_id)
                        ->where('type', '=', $row->type)
                        ->where('attempt', '=', 1)
                        ->first();
                    if ($sumgrade) {
                        $grade = $sumgrade->sumgrades;
                    }
                }elseif ($row->quiz->grade_methor == 4) {// Điểm lần cuối
                    $sumgrade = QuizAttempts::where('quiz_id', '=', $row->quiz_id)
                        ->where('part_id', '=', $row->part_id)
                        ->where('user_id', '=', $row->user_id)
                        ->where('type', '=', $row->type)
                        ->where('attempt', '=', function ($subquery) use ($row) {
                            $subquery->select(\DB::raw('MAX(attempt) AS max_attempt'))
                                ->from('el_quiz_attempts')
                                ->where('quiz_id', '=', $row->quiz_id)
                                ->where('user_id', '=', $row->user_id)
                                ->first();
                        })
                        ->first();
                    if ($sumgrade) {
                        $grade = $sumgrade->sumgrades;
                    }
                }

                $result = QuizResult::where('quiz_id', '=', $row->quiz_id)
                    ->where('part_id', '=', $row->part_id)
                    ->where('user_id', '=', $row->user_id)
                    ->where('type', '=', $row->type)
                    ->whereNull('text_quiz')
                    ->first();
                if ($result) {
                    $result->grade = $grade;
                    $result->result = ($grade >= $row->quiz->pass_score) ? 1 : 0;
                    $result->save();
                }else {
                    $result = new QuizResult();
                    $result->quiz_id = $row->quiz_id;
                    $result->part_id = $row->part_id;
                    $result->user_id = $row->user_id;
                    $result->type = $row->type;
                    $result->grade = $grade;
                    $result->result = ($grade >= $row->quiz->pass_score) ? 1 : 0;
                    $result->save();
                }
            }else{
                $result = QuizResult::where('quiz_id', '=', $row->quiz_id)
                    ->where('part_id', '=', $row->part_id)
                    ->where('user_id', '=', $row->user_id)
                    ->where('type', '=', $row->type)
                    ->whereNull('text_quiz')
                    ->first();
            }

            if ($result->result == 1){
                $this->pointPromotionByComplete($result->user_id, $result->quiz_id);
                $this->pointPromotionByAttempt($result->user_id, $result->quiz_id, $row->attempt);
                $this->pointPromotionByScore($result->user_id, $result->quiz_id, $result);
                $this->pointPromotionByTimeComplete($result->user_id, $result->quiz_id, $result);
                $this->pointUserMedal($result->user_id, $result->quiz_id, $result->grade);
            }
            /******* update báo cáo ********/
            $attempt = $row->getQuizData($row->id);
            $attempt_grade = new AttemptGrade($row);
            $score = $attempt_grade->getGrade();
            $update_attempt = QuizUpdateAttempts::where('attempt_id', '=', $row->id)
                ->where('quiz_id', '=', $row->quiz_id)
                ->where('part_id', '=', $row->part_id)
                ->where('user_id', '=', $row->user_id);

            if ($update_attempt->exists()){
                $update_attempt->update([
                    'categories' => json_encode($attempt['categories']),
                    'questions' => json_encode($attempt['questions']),
                    'score' => $score,
                    'status' => 1
                ]);
            }else{
                $update_attempt = new QuizUpdateAttempts();
                $update_attempt->attempt_id = $row->id;
                $update_attempt->quiz_id = $row->quiz_id;
                $update_attempt->part_id = $row->part_id;
                $update_attempt->user_id = $row->user_id;
                $update_attempt->type = $row->type;
                $update_attempt->categories = json_encode($attempt['categories']);
                $update_attempt->questions = json_encode($attempt['questions']);
                $update_attempt->score = $score;
                $update_attempt->status = 1;
                $update_attempt->save();
            }

            /**** update Complete Cron QuizAttemp ****/
            QuizAttempts::where(['id'=>$row->id])->update(['cron_complete'=>2,'state' => 'completed']);

            if($row->quiz->quiz_type != 1){
                /*Lưu lịch sử tương tác của HV*/
                $interaction_history = InteractionHistory::where(['user_id' => $row->user_id, 'code' => 'quiz'])->first();
                if($interaction_history){
                    $interaction_history->number = ($interaction_history->number + 1);
                    $interaction_history->save();
                }else{
                    $interaction_history = new InteractionHistory();
                    $interaction_history->user_id = $row->user_id;
                    $interaction_history->code = 'quiz';
                    $interaction_history->name = 'Kỳ thi';
                    $interaction_history->number = 1;
                    $interaction_history->save();
                }
            }
        }
    }

    private function levelUp($point)
    {
        $level = PromotionLevel::query()->where('point','<=', $point);

        if($level->exists())
            return $level->max('level');
        else
            return 0;
    }

    private function saveHistoryPromotion($user_id, $point, $course_id, $promotion_course_setting_id){
        $history = new PromotionUserHistory();
        $history->user_id = $user_id;
        $history->point = $point;
        $history->type = 3;
        $history->course_id = $course_id;
        $history->promotion_course_setting_id = $promotion_course_setting_id;
        $history->save();

        $quiz_name = Quiz::query()->find($course_id)->name;

        $model = new Notify();
        $model->user_id = $user_id;
        $model->subject = 'Thông báo đạt điểm thưởng kỳ thi.';
        $model->content = 'Bạn đã đạt điểm thưởng là "'. $point .'" điểm của kỳ thi "'. $quiz_name .'"';
        $model->url = null;
        $model->created_by = 0;
        $model->save();

        $content = \Str::words(html_entity_decode(strip_tags($model->content)), 10);
        $redirect_url = route('module.notify.view', [
            'id' => $model->id,
            'type' => 1
        ]);

        $notification = new AppNotification();
        $notification->setTitle($model->subject);
        $notification->setMessage($content);
        $notification->setUrl($redirect_url);
        $notification->add($user_id);
        $notification->save();
    }

    //Điểm thưởng hoàn thành kỳ thi
    private function pointPromotionByComplete($user_id, $quiz_id) {
        $quiz = Quiz::find($quiz_id);

        $setting = UserPointSettings::where("pkey", "=", "quiz_complete")
        ->where("item_id", "=", $quiz_id)
        ->where("item_type", "=", 4)
        ->where('note', 'complete')
        ->first();

        if($setting){
            $note = 'Hoàn thành kỳ thi <b>'. $quiz->name .' ('. $quiz->code .') </b>';

            $exists = UserPointResult::where("setting_id","=",$setting->id)->where("user_id","=",$user_id)->whereNull("type")->first();
            if(!$exists){
                UserPointResult::create([
                    'setting_id' => $setting->id,
                    'user_id' => $user_id,
                    'content' => $note,
                    'point' => $setting->pvalue,
                    'ref' => $quiz_id,
                    'type_promotion' => 0,
                ]);

                $user_point = PromotionUserPoint::firstOrNew(['user_id' => $user_id]);
                $user_point->point = $user_point->point + $setting->pvalue;
                $user_point->level_id = PromotionLevel::levelUp($user_point->point, $user_id);
                $user_point->save();

                $title_noty_point = 'Thông báo đạt điểm thưởng hoàn thành kỳ thi';
                $this->notyPoint($user_id, $title_noty_point, $note);
            }

            $this->info('Added promotion point -- has time');
        }
    }

    //Điểm thưởng theo lần thử
    private function pointPromotionByAttempt($user_id, $quiz_id, $attempt) {
        $quiz = Quiz::find($quiz_id);

        $setting = UserPointSettings::where("pkey","=","quiz_complete")
        ->where("item_id","=",$quiz_id)
        ->where("item_type","=","4")
        ->where('note', 'attempt')
        ->first();

        if($setting){
            if($attempt >= $setting->min_score && $attempt <= $setting->max_score){
                $note = 'Hoàn thành kỳ thi <b>'. $quiz->name .' ('. $quiz->code .') </b> theo lần thử';

                $exists=UserPointResult::where("setting_id","=",$setting->id)->where("user_id","=",$user_id)->whereNull("type")->first();
                if(!$exists){
                    UserPointResult::create([
                        'setting_id' => $setting->id,
                        'user_id' => $user_id,
                        'content' => $note,
                        'point' => $setting->pvalue,
                        'ref' => $quiz_id,
                        'type_promotion' => 0,
                    ]);

                    $user_point = PromotionUserPoint::firstOrNew(['user_id' => $user_id]);
                    $user_point->point = $user_point->point + $setting->pvalue;
                    $user_point->level_id = PromotionLevel::levelUp($user_point->point, $row->user_id);
                    $user_point->save();

                    $title_noty_point = 'Thông báo đạt điểm thưởng hoàn thành kỳ thi theo lần thử';
                    $this->notyPoint($user_id, $title_noty_point, $note);
                }
            }

        }
    }

    //Điểm thưởng theo số điểm
    private function pointPromotionByScore($user_id, $quiz_id, $result) {
        $quiz = Quiz::find($quiz_id);

        $setting = UserPointSettings::where("pkey","=","quiz_complete")
        ->where("item_id","=",$quiz_id)
        ->where("item_type","=","4")
        ->where('note', 'score')
        ->first();

        if($setting){
            if($result->grade >= $setting->min_score && $result->grade <= $setting->max_score){
                $note = 'Hoàn thành kỳ thi <b>'. $quiz->name .' ('. $quiz->code .') </b> theo khoảng điểm';

                $exists=UserPointResult::where("setting_id","=",$setting->id)->where("user_id","=",$user_id)->whereNull("type")->first();
                if(!$exists){
                    UserPointResult::create([
                        'setting_id' => $setting->id,
                        'user_id' => $user_id,
                        'content' => $note,
                        'point' => $setting->pvalue,
                        'ref' => $quiz_id,
                        'type_promotion' => 0,
                    ]);

                    $user_point = PromotionUserPoint::firstOrNew(['user_id' => $user_id]);
                    $user_point->point = $user_point->point + $setting->pvalue;
                    $user_point->level_id = PromotionLevel::levelUp($user_point->point, $row->user_id);
                    $user_point->save();

                    $title_noty_point = 'Thông báo đạt điểm thưởng hoàn thành kỳ thi theo khoảng điểm';
                    $this->notyPoint($user_id, $title_noty_point, $note);
                }
            }
        }
    }

    //Điểm thưởng theo thời gian hoàn thành
    private function pointPromotionByTimeComplete($user_id, $quiz_id, $result) {
        $quiz = Quiz::find($quiz_id);

        $setting = UserPointSettings::where("pkey","=","quiz_complete")
        ->where("item_id","=",$quiz_id)
        ->where("item_type","=","4")
        ->where('note', 'timecompleted')
        ->first();

        if($setting){
            if($result->timecompleted >= $setting->start_date && $result->timecompleted <= $setting->end_date){
                $note = 'Hoàn thành kỳ thi <b>'. $quiz->name .' ('. $quiz->code .') theo khoảng thời gian</b>';

                $exists = UserPointResult::where("setting_id","=",$setting->id)->where("user_id","=",$user_id)->whereNull("type")->first();
                if(!$exists){
                    UserPointResult::create([
                        'setting_id' => $setting->id,
                        'user_id' => $user_id,
                        'content' => $note,
                        'point' => $setting->pvalue,
                        'ref' => $quiz_id,
                        'type_promotion' => 0,
                    ]);

                    $user_point = PromotionUserPoint::firstOrNew(['user_id' => $user_id]);
                    $user_point->point = $user_point->point + $setting->pvalue;
                    $user_point->level_id = PromotionLevel::levelUp($user_point->point, $row->user_id);
                    $user_point->save();

                    $title_noty_point = 'Thông báo đạt điểm thưởng hoàn thành kỳ thi theo khoảng thời gian';
                    $this->notyPoint($user_id, $title_noty_point, $note);
                }
            }

        }
    }

    //Điểm chương trình thi đua
    private function pointUserMedal($user_id, $quiz_id, $point) {
        $quiz = Quiz::find($quiz_id);
        $setting_item = UserMedalSettingsItems::where("item_id","=",$quiz_id)->where("item_type","=","4")->first();
        $time = time();

        $profile = ProfileView::select(['title_id','user_id','unit_id'])->find($user_id);
        $query = new UserMedalObject();
        $query->from('el_usermedal_object')->where('settings_id', '=', $setting_item->setting_id)
            ->where(function ($query)use ($profile) {
                $query->orwhere('user_id', '=', $profile->user_id)
                    ->orWhere('title_id', '=', $profile->title_id)
                    ->orWhere('unit_id', '=', $profile->unit_id);
            });
        $apply = $query->exists();

        if(!empty($setting_item) && $apply){
            $setting = UserMedalSettings::find($setting_item->setting_id);

            if($time >= $setting->start_date && $time <= $setting->end_date){
                $setting_items_point = UserMedalSettingsItems::where("setting_id","=",$setting->id)
                    ->where("item_type", "=", "5")
                    ->where('min_score', '<=', $point)
                    ->where('max_score', '>=', $point)->first();

                if(!empty($setting_items_point)){
                    $note = 'Hoàn thành kỳ thi <b>'. $quiz->name .' ('. $quiz->code .')</b>';

                    $exists = UserMedalResult::where("settings_items_id","=",$setting_item->id)
                        ->where("settings_items_id_got","=",$setting_items_point->id)
                        ->where("user_id","=",$user_id)
                        ->first();

                    if($exists){
                        $model= UserMedalResult::find($exists->id);
                        $model->content =$note;
                        $model->point = $point;
                        $model->ref = $quiz_id;
                        $model->save();
                    }else{
                        UserMedalResult::create([
                            'settings_items_id' => $setting_item->id,
                            'settings_items_id_got' => $setting_items_point->id,
                            'user_id' => $user_id,
                            'content' => $note,
                            'point' => $point,
                            'ref' => $quiz_id
                        ]);
                    }
                }
            }
        }
    }

    public function notyPoint($user_id, $title, $note) {
        $model = new Notify();
        $model->user_id = $user_id;
        $model->subject = $title;
        $model->content = $note;
        $model->url = null;
        $model->created_by = 0;
        $model->save();

        $content = \Str::words(html_entity_decode(strip_tags($model->content)), 10);
        $redirect_url = route('module.notify.view', [
            'id' => $model->id,
            'type' => 1
        ]);

        $notification = new AppNotification();
        $notification->setTitle($model->subject);
        $notification->setMessage($content);
        $notification->setUrl($redirect_url);
        $notification->add($user_id);
        $notification->save();
    }
}
