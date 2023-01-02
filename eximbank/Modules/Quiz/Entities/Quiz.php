<?php

namespace Modules\Quiz\Entities;

use App\Models\BaseModel;
use App\Models\Profile;
use App\Traits\ChangeLogs;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Quiz\Entities\Quiz
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property int|null $unit_id Đơn vị tạo kỳ thi
 * @property int|null $type_id Danh mục Loại kỳ thi
 * @property int $limit_time Thời gian làm bài: phút
 * @property int $view_result 1: được xem kết quả, 0: không được xem kết quả
 * @property int $shuffle_answers Xáo trộn đáp án
 * @property int $shuffle_question Xáo trộn câu hỏi
 * @property int $paper_exam Thi giấy
 * @property int $questions_perpage Số câu hỏi 1 trang
 * @property float $pass_score Điểm chuẩn
 * @property float $max_score Điểm tối đa
 * @property string|null $description
 * @property int $max_attempts Số lần làm bài
 * @property int $grade_methor Cách tính điểm
 * @property int $is_open
 * @property int $status 1: Duyệt, 2: Chưa duyệt, 0:Từ chối
 * @property int|null $course_id ID khóa học
 * @property int|null $course_type Loại khóa học
 * @property int|null $quiz_type Loại kỳ thi, 1:online, 2:tập trung, 3: độc lập
 * @property string|null $img
 * @property int $webcam_require
 * @property int $question_require
 * @property int $times_shooting_webcam
 * @property int $times_shooting_question
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $unit_by
 * @property int|null $quiz_template_id
 * @property int|null $approved_by
 * @property string|null $time_approved
 * @property string|null $quiz_location
 * @property int $show_name
 * @property string|null $approved_step
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Quiz\Entities\QuizAttempts[] $attempts
 * @property-read int|null $attempts_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Quiz\Entities\QuizPart[] $parts
 * @property-read int|null $parts_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Quiz\Entities\QuizQuestion[] $questions
 * @property-read int|null $questions_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Quiz\Entities\QuizTeacher[] $teachers
 * @property-read int|null $teachers_count
 * @property-read \Modules\Quiz\Entities\QuizType|null $type
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Quiz active()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Quiz all($columns = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Quiz avg($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Quiz cache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Quiz cachedValue(array $arguments, string $cacheKey)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Quiz count($columns = '*')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Quiz disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Quiz disableModelCaching()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Quiz flushCache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Quiz getModelCacheCooldown(\Illuminate\Database\Eloquent\Model $instance)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Quiz hasEndPart()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Quiz inRandomOrder($seed = '')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Quiz insert(array $values)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Quiz isCachable()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Quiz max($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Quiz min($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Quiz newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Quiz newQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Quiz query()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Quiz sum($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Quiz truncate()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Quiz whereApprovedBy($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Quiz whereApprovedStep($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Quiz whereCode($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Quiz whereCourseId($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Quiz whereCourseType($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Quiz whereCreatedAt($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Quiz whereCreatedBy($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Quiz whereDescription($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Quiz whereGradeMethor($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Quiz whereId($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Quiz whereImg($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Quiz whereIsOpen($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Quiz whereLimitTime($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Quiz whereMaxAttempts($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Quiz whereMaxScore($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Quiz whereName($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Quiz wherePaperExam($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Quiz wherePassScore($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Quiz whereQuestionRequire($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Quiz whereQuestionsPerpage($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Quiz whereQuizLocation($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Quiz whereQuizTemplateId($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Quiz whereQuizType($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Quiz whereShowName($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Quiz whereShuffleAnswers($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Quiz whereShuffleQuestion($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Quiz whereStatus($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Quiz whereTimeApproved($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Quiz whereTimesShootingQuestion($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Quiz whereTimesShootingWebcam($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Quiz whereTypeId($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Quiz whereUnitBy($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Quiz whereUnitId($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Quiz whereUpdatedAt($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Quiz whereUpdatedBy($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Quiz whereViewResult($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Quiz whereWebcamRequire($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Quiz withCacheCooldownSeconds(?int $seconds = null)
 * @mixin \Eloquent
 * @property int|null $flag
 * @property string|null $start_quiz
 * @property string|null $end_quiz
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Quiz whereEndQuiz($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Quiz whereFlag($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Quiz whereStartQuiz($value)
 */
class Quiz extends BaseModel
{
    use ChangeLogs, Cachable;
    protected $table = 'el_quiz';
    protected $table_name = 'Kỳ thi';
    protected $primaryKey = 'id';
    protected $fillable = [
        'code',
        'name',
        'unit_id',
        'limit_time',
        'view_result',
        'shuffle_answers',
        'shuffle_question',
        'paper_exam',
        'questions_perpage',
        'is_open',
        'status',
        'pass_score',
        'max_score',
        'description',
        'max_attempts',
        'grade_methor',
        'created_by',
        'updated_by',
        'course_id',
        'course_type',
        'quiz_type',
        'type_id',
        'img',
        'webcam_require',
        'question_require',
        'times_shooting_webcam',
        'times_shooting_question',
        'quiz_template_id',
        'quiz_location',
        'show_name',
        'approved_step',
        'flag',
        'start_quiz',
        'end_quiz',
        'status_grading',
        'teacher_grade',
        'full_screen',
        'new_tab',
        'quiz_not_register',
        'unit_create_quiz',
        'quiz_type_by_offline',
    ];

    public function parts() {
        return $this->hasMany(QuizPart::class, 'quiz_id', 'id');
    }

    public function teachers() {
        return $this->hasMany('Modules\Quiz\Entities\QuizTeacher', 'quiz_id', 'id');
    }

    public function attempts() {
        return $this->hasMany('Modules\Quiz\Entities\QuizAttempts', 'quiz_id', 'id');
    }

    public function questions() {
        return $this->hasMany('Modules\Quiz\Entities\QuizQuestion', 'quiz_id', 'id');
    }

    public function type()
    {
        return $this->belongsTo(QuizType::class,'type_id');
    }

    public function user_reviews() {
        return $this->hasMany('Modules\Quiz\Entities\QuizUserReview', 'quiz_id', 'id');
    }
    /**
     * Scope a query to only quiz active.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query) {
        return $query->where('status', '=', 1);
    }

    /**
     * Scope a query to only quiz Finished (Enddate < now()).
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeHasEndPart($query) {
        return $query->whereHas('parts', function ($subquery) {
            $subquery->where(function ($where) {
                    $where->orWhere('end_date', '<', now());
                    $where->orWhereNull('end_date');
                });
        });
    }

    public static function getAttributeName() {
        return [
            'code' => trans('latraining.quiz_code'),
            'name' => trans('latraining.quiz_name'),
            'start_date' => 'Thời gian bắt đầu',
            'end_date' => 'Thời gian kết thúc',
            'limit_time' => 'Thời gian làm bài',
            'view_result' => 'Xem kết quả',
            'shuffle_answers' => 'Xáo trộn đáp án',
            'shuffle_question' => 'Xáo trộn câu hỏi',
            'paper_exam' => 'Thi giấy',
            'questions_perpage' => 'Số câu hỏi 1 trang',
            'is_open' => trans('lacore.open'),
            'status' => trans("latraining.status"),
            'pass_score' => 'Điểm chuẩn',
            'max_score' => 'Điểm tối đa',
            'description' => trans("latraining.content"),
            'max_attempts' => 'Số lần làm bài',
            'grade_methor' => 'Cách tính điểm',
            'created_by' => trans('laother.creator'),
            'updated_by' => trans('laother.editor'),
            'course_id' => trans('lacourse.course_code'),
            'course_type' => 'Loại khóa học',
            'quiz_type' => 'Loại kỳ thi',
        ];
    }

    public static function countQuizUser($user_id = null, $user_type = null) {
        if (empty($user_id)) {
            $user_id = Quiz::getUserId();
        }

        if (empty($user_type)) {
            $user_type = Quiz::getUserType();
        }

        $query = QuizPart::query();
        $query->select([
            'a.id',
        ]);
        $query->from('el_quiz AS a')
            ->join('el_quiz_part AS b', function ($subquery) use ($user_id) {
                $subquery->on('b.quiz_id', '=', 'a.id')
                    ->whereIn('b.id', function ($subquery2) use ($user_id) {
                        $subquery2->select(['part_id'])
                            ->from('el_quiz_register')
                            ->where('user_id', '=', $user_id)
                            ->whereColumn('quiz_id', '=', 'a.id');
                    });
            })
            ->where('a.status', '=', 1)
            ->where('a.is_open', '=', 1)
            ->where(function ($sub){
                $sub->orWhere('a.quiz_type', '=', 3);
                $sub->orWhereIn('a.id', function ($subquery){
                    $subquery->select(['quiz_id'])
                        ->from('el_offline_course')
                        ->whereNotNull('quiz_id')
                        ->where('status', '=', 1)
                        ->where('isopen', '=', 1);
                });
            })
            ->whereIn('a.id', function ($subquery) use ($user_id, $user_type){
                $subquery->select(['quiz_id'])
                    ->from('el_quiz_register')
                    ->where('user_id', '=', $user_id)
                    ->where('type', '=', $user_type);
            });
        return $query->count();
    }

    public static function isQuizFinish($quiz_id) {
        $quiz = Quiz::where('id', '=', $quiz_id)->firstOrFail();

    }

    public static function getUserType() {
        if (\Auth::check()) {
            return 1;
        }

        if (\Auth::guard('secondary')->check()) {
            return 2;
        }

        return null;
    }

    public static function getUserId() {
        if (\Auth::check()) {
            return profile()->user_id;
        }

        if (\Auth::guard('secondary')->check()) {
            return \Auth::guard('secondary')->id();
        }

        return null;
    }

    public static function getStatusUser($quiz_id, $part_id = null) {
        $user_id = Quiz::getUserId();
        $user_type = Quiz::getUserType();
        $query = QuizAttempts::where('quiz_id', '=', $quiz_id)
            ->where('user_id', '=', $user_id)
            ->where('type', '=', $user_type);
        if($part_id){
            $query->where('part_id', $part_id);
        }

        if (!$query->exists()) {
            return 0;
        }

        $finishd = $query->where('timefinish', '>', 0);
        if ($finishd->exists()) {
            return 1;
        }

        $process = $query->where('timefinish', '=', 0);
        if ($process->exists()) {
            return 2;
        }

        return 0;
    }

    public static function getMyQuiz($userId = null)
    {

    }

    public static function countQuiz() {
        return self::where('quiz_type','=',3)->count();
    }

    public static function getLastestQuiz($limit = 5){
        $query = self::query();
        $query->orderBy('created_at', 'DESC');
        $query->limit($limit);
        return $query->get();
    }

}
