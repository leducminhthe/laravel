<?php

namespace Modules\Quiz\Entities;

use App\Jobs\SaveQuizAttempt;
use App\Models\BaseModel;
use App\Models\InteractionHistory;
use App\Models\Profile;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Modules\Offline\Entities\OfflineActivityQuiz;
use Modules\Offline\Entities\OfflineCourseActivity;
use Modules\Offline\Entities\OfflineCourseActivityCompletion;
use Modules\Online\Entities\OnlineCourseActivity;
use Modules\Online\Entities\OnlineCourseActivityCompletion;
use Modules\Online\Entities\OnlineCourseSettingPercent;

/**
 * Modules\Quiz\Entities\QuizAttempts
 *
 * @property int $id
 * @property int $quiz_id
 * @property int $part_id
 * @property int $user_id
 * @property int $type 1: Người thi trong, 2: Người thi ngoài
 * @property int $attempt Số lần thử
 * @property string $state Trạng thái
 * @property int|null $end_quiz Thời gian kết thúc kỳ thi
 * @property int $timestart Thời gian bắt đầu
 * @property int $timefinish Thời gian hoàn thành
 * @property string $sumgrades Tổng điểm đạt được
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $unit_by
 * @property int|null $cron_complete 0 chua chay cron, 1 đã chạy cron attemp, 2 đã chạy cron quizComplete
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Quiz\Entities\QuizUserError[] $erroruser
 * @property-read int|null $erroruser_count
 * @property-read \Modules\Quiz\Entities\Quiz|null $quiz
 * @property-read \Modules\Quiz\Entities\QuizAttemptsTemplate|null $template
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|QuizAttempts all($columns = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|QuizAttempts avg($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|QuizAttempts cache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|QuizAttempts cachedValue(array $arguments, string $cacheKey)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|QuizAttempts count($columns = '*')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|QuizAttempts disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|QuizAttempts disableModelCaching()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|QuizAttempts flushCache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|QuizAttempts getModelCacheCooldown(\Illuminate\Database\Eloquent\Model $instance)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|QuizAttempts inRandomOrder($seed = '')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|QuizAttempts insert(array $values)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|QuizAttempts isCachable()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|QuizAttempts max($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|QuizAttempts min($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|QuizAttempts newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|QuizAttempts newQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|QuizAttempts query()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|QuizAttempts sum($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|QuizAttempts truncate()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|QuizAttempts whereAttempt($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|QuizAttempts whereCreatedAt($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|QuizAttempts whereCreatedBy($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|QuizAttempts whereCronComplete($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|QuizAttempts whereEndQuiz($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|QuizAttempts whereId($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|QuizAttempts wherePartId($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|QuizAttempts whereQuizId($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|QuizAttempts whereState($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|QuizAttempts whereSumgrades($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|QuizAttempts whereTimefinish($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|QuizAttempts whereTimestart($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|QuizAttempts whereType($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|QuizAttempts whereUnitBy($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|QuizAttempts whereUpdatedAt($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|QuizAttempts whereUpdatedBy($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|QuizAttempts whereUserId($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|QuizAttempts withCacheCooldownSeconds(?int $seconds = null)
 * @mixin \Eloquent
 */
class QuizAttempts extends BaseModel
{
    use Cachable;
    protected $table = 'el_quiz_attempts';
    protected $table_name = 'Lần thi';
    protected $fillable = [
        'quiz_id',
        'part_id',
        'user_id',
        'type',
        'attempt',
        'state',
        'end_quiz',
        'timestart',
        'timefinish',
        'sumgrades',
        'created_by',
        'updated_by',
        'unit_by',
        'teacher_grade',
        'cron_complete',
    ];

    public function user()
    {
        return $this->belongsTo(Profile::class,'user_id','id');
    }

    public function part()
    {
        return $this->belongsTo(QuizPart::class,'part_id','id');
    }
    public function quiz() {
        return $this->hasOne('Modules\Quiz\Entities\Quiz', 'id', 'quiz_id');
    }

    public function erroruser() {
        return $this->hasMany('Modules\Quiz\Entities\QuizUserError', 'attempt_id', 'id');
    }

    public function template() {
        return $this->hasOne('Modules\Quiz\Entities\QuizAttemptsTemplate', 'attempt_id', 'id');
    }

    public function getTemplateData() {
        $storage = \Storage::disk('local');
        $template = 'quiz/' . $this->quiz_id . '/attempts/attempt-' . $this->id .'.json';

        if ($storage->exists($template)) {
            return json_decode($storage->get($template), true);
        }
        return null;
    }
    public static function getQuizData($attempt_id) {
        $template = Cache::get('attempt-'.$attempt_id);
        if ($template) {
            return json_decode($template, true);
        }else{
            $template = QuizAttemptHistory::whereAttemptId($attempt_id)->value('content');
            \Cache::put('attempt-' . $attempt_id,$template);
            return json_decode($template, true);
        }
        return null;
    }
    public function updateTemplateData($template) {
        $storage = \Storage::disk('local');
        $template_path = 'quiz/' . $this->quiz_id . '/attempts/attempt-' . $this->id .'.json';
        $storage->put($template_path, json_encode($template));
    }
    public static function updateQuizData($attempt_id,$data) {
        \Cache::put('attempt-' . $attempt_id,json_encode($data));
        //SaveQuizAttempt::dispatch($attempt_id,$data);

        QuizAttemptHistory::updateOrCreate([
            'attempt_id' => $attempt_id
        ],[
            'content' => $data
        ]);
    }
    public static function isAttemptFinish($time_start,$time_finish,$limit_time) {
        if ($time_finish > 0) {
            return true;
        }
        if (($time_start + ($limit_time * 60)) < time()) { // thời gian thi đã qua (hết giờ làm bài)
            return true;
        }
        return false;
    }

    public static function countQuizAttempt($quiz_id, $user_id, $part_id = null) {
        $query = self::where('quiz_id', '=', $quiz_id)
        ->where('user_id', '=', $user_id);

        if($part_id){
            $query = $query->where('part_id', $part_id);
        }

        return $query->count();
    }

    public static function updateGradeAttempt($attempt_id) {
//        $attempt = QuizAttempts::where('id', '=', $attempt_id)->first();
        $atemp = QuizAttemptsTemplate::where('attempt_id', '=', $attempt_id)->first();
        $grade = QuizTemplateQuestion::getGradeUser($atemp->template_id);
        return self::where('id', '=', $attempt_id)->update(['sumgrades' => $grade]);
    }

    public static function updateGradeAttemptByTeacher($attempt_id) {
//        $attempt = QuizAttempts::where('id', '=', $attempt_id)->first();
        $atemp = QuizAttemptsTemplate::where('attempt_id', '=', $attempt_id)->first();
        $grade = QuizTemplateQuestion::getGradeUserByTeacher($atemp->template_id);

        return self::where('id', '=', $attempt_id)->update(['sumgrades' => $grade]);
    }

    public static function quizComplete($attemp_id, $quiz_id, $quiz, $attempt)
    {
        $user_id = $attempt['user_id']?$attempt['user_id']:profile()->user_id;
        $user_type = getUserType();
        $score = QuizAttempts::getGrade($attemp_id);
        $grade = 0;

        $quiz_attempt = QuizAttempts::find($attemp_id);
        $quiz_attempt->update([
            'sumgrades' => $score,
            'timefinish' => time(),
            'cron_complete'=>1,
            'state' => 'completed'
        ]);

        if ($quiz['grade_methor'] == 1) { // lần cao nhất
            $sumgrade = QuizAttempts::where('quiz_id', '=', $quiz_id)
                ->where('part_id', $quiz_attempt->part_id)
                ->where('user_id', '=', $user_id)
                ->select(\DB::raw('MAX(sumgrades) AS total_grade'))
                ->first();
            if ($sumgrade) {
                $grade = $sumgrade->total_grade;
            }
        }
        elseif ($quiz['grade_methor'] == 2) {// Điểm trung bình
            $sumgrade = QuizAttempts::where('quiz_id', '=', $quiz_id)
                ->where('part_id', $quiz_attempt->part_id)
                ->where('user_id', '=', $user_id)
                ->select(\DB::raw('AVG(sumgrades) AS total_grade'))
                ->first();
            if ($sumgrade) {
                $grade = $sumgrade->total_grade;
            }
        }
        elseif ($quiz['grade_methor'] == 3) {// Điểm lần đầu
            $sumgrade = QuizAttempts::where('quiz_id', '=', $quiz_id)
                ->where('part_id', $quiz_attempt->part_id)
                ->where('user_id', '=', $user_id)
                ->where('attempt', '=', 1)
                ->first();
            if ($sumgrade) {
                $grade = $sumgrade->sumgrades;
            }
        }
        elseif ($quiz['grade_methor'] == 4) {// Điểm lần cuối
            $grade = $score;
        }

        if(empty($quiz_attempt->text_quiz)) {
            $result = QuizResult::firstOrNew(['quiz_id'=> $quiz_id, 'user_id' => $user_id, 'part_id' => $quiz_attempt->part_id]);
            $result->quiz_id = $quiz_id;
            $result->part_id = $quiz_attempt->part_id;
            $result->user_id = $user_id;
            $result->type = $user_type;
            $result->grade = $grade;
            $result->result = ($grade >= $quiz['pass_score']) ? 1 : 0;
            $result->timecompleted = time();
            $result->save();
        } else {
            return $grade;
        }

        //*** hoàn thành hoạt động online//
        if ($quiz['quiz_type']==1) {
            $activity = OnlineCourseActivity::where(['course_id' => $quiz['course_id'], 'activity_id'=>2,'subject_id'=>$quiz_id])->first('id');
            if($activity){
                $completionActivity = OnlineCourseActivityCompletion::firstOrNew([
                    'user_id' => $user_id,
                    'activity_id' => $activity->id,
                    'course_id'=>$quiz['course_id'],
                ]);
                $completionActivity->user_id = $user_id;
                $completionActivity->activity_id = $activity->id;
                $completionActivity->course_id = $quiz['course_id'];
                $completionActivity->status = ($grade >= $quiz['pass_score']) ? 1 : 0;
                $completionActivity->save();

                OnlineCourseSettingPercent::query()
                ->where('course_id', '=', $quiz['course_id'])
                ->where('course_activity_id', '=', $activity->id)
                ->whereNotNull('percent')
                ->update([
                    'score' => $grade
                ]);

                if($completionActivity->status == 1){
                    \Artisan::call('online:complete '.$user_id .' '.$quiz['course_id']);
                }
            }
        }
        /***Hoàn thành khóa offline */
        if ($quiz['quiz_type'] == 2) {
            $activity_quiz = OfflineActivityQuiz::where('course_id', $quiz['course_id'])->where('quiz_id', $quiz_id)->first();
            $activity = OfflineCourseActivity::where(['course_id' => $quiz['course_id'], 'activity_id' => 7, 'subject_id' => @$activity_quiz->id])->first('id');
            if($activity){
                $completionActivity = OfflineCourseActivityCompletion::firstOrNew([
                    'user_id' => $user_id,
                    'activity_id' => $activity->id,
                    'course_id' => $quiz['course_id'],
                ]);
                $completionActivity->user_id = $user_id;
                $completionActivity->activity_id = $activity->id;
                $completionActivity->course_id = $quiz['course_id'];
                $completionActivity->status = ($grade >= $quiz['pass_score']) ? 1 : 0;
                $completionActivity->save();

                if($completionActivity->status == 1){
                    \Artisan::call('command:offline_complete '.$user_id .' '.$quiz['course_id']);
                }
            }
        }

        \Artisan::call('quiz:complete '.$user_id .' '.$quiz_id);
    }
    public function getQuizRandom($quiz_id) {

        $random = rand(1,10);
        $storage = \Storage::disk('local');
        $template = 'quiz/' . $quiz_id . '/template/' . $random .'.json';

        if ($storage->exists($template)) {
            return $storage->get($template);
        }
        return null;
    }
    public function createTemplate($time_start, $num_attempt, $user_id, $part_id, $quiz_id, $text_quiz = null) {
        $template = json_decode($this->getQuizRandom($quiz_id));
        $quiz=$template->quiz;
        $this->quiz_id = $quiz_id;
        $this->part_id = $part_id;
        $this->user_id = $user_id;
        $this->attempt = $num_attempt;
        $this->state = 'inprogress';
        $this->timestart = $time_start;
        $this->end_quiz = $time_start+$quiz->limit_time*60;
        $this->teacher_grade = $quiz->teacher_grade;
        $this->text_quiz = $text_quiz;
        $this->save();


        $template->attempt=[
            'id'=>$this->id,
            'quiz_id'=>$this->quiz_id,
            'part_id'=>$part_id,
            'user_id'=>$user_id,
            'attempt'=>$num_attempt,
            'timestart'=>$time_start,
            'timefinish'=>0,
        ];
        $template = json_encode($template,true);
        \Cache::put('attempt-' . $this->id,$template,3600);
        //SaveQuizAttempt::dispatch($this->id,$template);

        QuizAttemptHistory::updateOrCreate([
            'attempt_id' => $this->id
        ],[
            'content' => $template
        ]);
    }
    public static function getGrade($attempt_id) {
        $template = QuizAttempts::getQuizData($attempt_id);
        $questions = $template['questions'];
        $grade = 0;
        foreach ($questions as $index => $question) {
            $grade += $question['score'];
        }
        return $grade;
    }
}
