<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Modules\Notify\Entities\Notify;
use Modules\AppNotification\Helpers\AppNotification;
use Modules\Notify\Entities\NotifyTemplate;
use App\Models\ProfileView;
use Modules\Quiz\Entities\Quiz;
use Modules\Quiz\Entities\QuizPart;
use Modules\Quiz\Entities\QuizAttempts;
use Modules\Quiz\Entities\QuizResult;
use Modules\UserMedal\Entities\UserMedalSettings;
use Modules\Quiz\Entities\QuizTimeFinishPoint;
use Modules\UserPoint\Entities\UserPointSettings;
use Modules\UserPoint\Entities\UserPointResult;
use Modules\Promotion\Entities\PromotionUserPoint;
use Modules\Promotion\Entities\PromotionLevel;

class CronQuizTimeFinish extends Command
{
    protected $signature = 'command:cron_quiz_time_finish';
    protected $description = 'Kỳ thi kết thúc chạy cron điểm thưởng xếp hạng theo hoàn thành sớm nhất, chạy 10p lần';
    protected $expression = "*/10 * * * *";

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $date = date('Y-m-d H:i:s');

        $query = QuizPart::query();
        $query->select([
            'quiz.name as quiz_name',
            'quiz.grade_methor',
            'part.*',
            'point_setting.id as setting_id',
        ]);
        $query->from('el_quiz_part as part');
        $query->join('el_quiz as quiz', 'quiz.id', '=', 'part.quiz_id');
        $query->join('el_userpoint_settings as point_setting', function($join) {
            $join->on('point_setting.item_id', '=', 'quiz.id');
            $join->where('point_setting.pkey', 'quiz_complete');
            $join->where('point_setting.note', 'timefinish');
        });
        $query->where('part.end_date', '<', $date);
        $query->where('part.userpoint_timefinish', '!=', 1);
        $rows = $query->get();
        
        foreach ($rows as $key => $row) {
            $quizTimeFinishPoint = QuizTimeFinishPoint::where('quiz_id', $row->quiz_id)->get();
            $countQuizTime = count($quizTimeFinishPoint);

            $quizAttempts = QuizAttempts::select('a.user_id')
            ->from('el_quiz_attempts as a')
            ->join('el_quiz_result as b', function ($join) {
                $join->on('a.quiz_id', 'b.quiz_id');
                $join->on('a.user_id', 'b.user_id');
            })
            ->where(['a.part_id' => $row->id, 'a.quiz_id' => $row->quiz_id, 'a.state' => 'completed', 'b.result' => 1])
            ->groupBy('user_id')
            ->get();
            $data = [];
            $listSave = [];

            // ĐIỂM CAO NHẤT
            if($row->grade_methor == 1) {
                $note = 'Hoàn thành kỳ thi '. $row->quiz_name .' sớm nhất theo cách tính điểm cao nhất';

                foreach ($quizAttempts as $key => $quizAttempt) {
                    $userAttempt = QuizAttempts::where([
                        'part_id' => $row->id, 
                        'quiz_id' => $row->quiz_id, 
                        'state' => 'completed', 
                        'user_id' => $quizAttempt->user_id,
                    ])
                    ->orderBy('sumgrades', 'desc')
                    ->first(['timefinish', 'timestart', 'user_id']);
                    $data[] = [
                        'user_id' => $userAttempt->user_id,
                        'value' => (int)$userAttempt->timefinish - (int)$userAttempt->timestart
                    ];
                }   
            } else if($row->grade_methor == 2) { // TRUNG BÌNH
                $note = 'Hoàn thành kỳ thi '. $row->quiz_name .' sớm nhất theo cách tính trung bình';

                foreach ($quizAttempts as $key => $quizAttempt) {
                    $countUserAttempt = QuizAttempts::where(['part_id' => $row->id, 'quiz_id' => $row->quiz_id, 'state' => 'completed', 'user_id' => $quizAttempt->user_id])->count();
                    $timefinish = QuizAttempts::where(['part_id' => $row->id, 'quiz_id' => $row->quiz_id, 'state' => 'completed', 'user_id' => $quizAttempt->user_id])->sum('timefinish');
                    $timestart = QuizAttempts::where(['part_id' => $row->id, 'quiz_id' => $row->quiz_id, 'state' => 'completed', 'user_id' => $quizAttempt->user_id])->sum('timestart');

                    $data[] = [
                        'user_id' => $quizAttempt->user_id,
                        'value' => ((int)$timefinish - (int)$timestart) / $countUserAttempt
                    ];
                }   
            } else if($row->grade_methor == 3) { // LẦN ĐẦU
                $note = 'Hoàn thành kỳ thi '. $row->quiz_name .' sớm nhất theo cách tính lần đầu thi';

                foreach ($quizAttempts as $key => $quizAttempt) {
                    $userAttempt = QuizAttempts::where([
                        'part_id' => $row->id, 
                        'quiz_id' => $row->quiz_id, 
                        'state' => 'completed', 
                        'user_id' => $quizAttempt->user_id
                    ])
                    ->first(['timefinish', 'timestart', 'user_id']);
                    $data[] = [
                        'user_id' => $userAttempt->user_id,
                        'value' => (int)$userAttempt->timefinish - (int)$userAttempt->timestart
                    ];
                }   
            } else if($row->grade_methor == 4) { // LẦN CUỐI
                $note = 'Hoàn thành kỳ thi '. $row->quiz_name .' sớm nhất theo cách tính lần cuối thi';

                foreach ($quizAttempts as $key => $quizAttempt) {
                    $userAttempt = QuizAttempts::where([
                        'part_id' => $row->id, 
                        'quiz_id' => $row->quiz_id, 
                        'state' => 'completed', 
                        'user_id' => $quizAttempt->user_id
                    ])
                    ->latest('id')
                    ->first(['timefinish', 'timestart', 'user_id']);
                    $data[] = [
                        'user_id' => $userAttempt->user_id,
                        'value' => (int)$userAttempt->timefinish - (int)$userAttempt->timestart
                    ];
                }   
            }
            if(!empty($data)) {
                usort($data, function($a, $b) {
                    return $a['value'] - $b['value'];
                });
                for ($i = 1; $i <= $countQuizTime; $i++) { 
                    if($data[$i - 1]) {
                        $listSave[] = $data[$i - 1]['user_id'];
                    }
                }
            }

            foreach($quizTimeFinishPoint as $key => $save) {
                if(!empty($listSave[$key])) {
                    $save_user_point = new UserPointResult();
                    $save_user_point->setting_id = $row->setting_id;
                    $save_user_point->user_id = $listSave[$key];
                    $save_user_point->content = $note;
                    $save_user_point->point = $save->score;
                    $save_user_point->ref = $row->grade_methor;
                    $save_user_point->type_promotion = 0;
                    $save_user_point->save();
                    
                    $user_point = PromotionUserPoint::firstOrNew(['user_id' => $listSave[$key]]);
                    $user_point->point = $user_point->point + $save->score;
                    $user_point->level_id = PromotionLevel::levelUp($user_point->point, $listSave[$key]);
                    $user_point->save();
    
                    $model = new Notify();
                    $model->user_id = $listSave[$key];
                    $model->subject = 'Thông báo đạt điểm thưởng '. $note;
                    $model->content = 'Bạn đã đạt điểm thưởng là "'. $save->score .'" điểm của kỳ thi do hoàn thành sớm nhất và đạt hạng "'. $save->rank .'" của kỳ thi "'. $row->quiz_name .'" ca thi "'. $row->name .'"';
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
                    $notification->add($listSave[$key]);
                    $notification->save();
                }      
            }
            QuizPart::where('id', $row->id)->update(['userpoint_timefinish' => 1]);
        }
    }
}
