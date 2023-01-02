<?php

namespace Modules\ReportNew\Entities;

use App\Models\CacheModel;
use App\Scopes\CompanyScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\Categories\Titles;
use App\Models\ProfileView;
use Modules\Quiz\Entities\Quiz;
use Modules\Quiz\Entities\QuizAttempts;
use Modules\Quiz\Entities\QuizUpdateAttempts;

class BC37 extends Model
{
    public static function sql($quiz, $quiz_part)
    {
        $quiz_parts = explode(',', $quiz_part);
        
        $data = [];
        $model = QuizAttempts::query();
        $model->select(['user_id', 'part_id']);
        $model->where('quiz_id', $quiz->id);
        if($quiz_part) {
            $model->whereIn('part_id', $quiz_parts);
        }
        $model->groupBy(['user_id', 'part_id']);
        $quizAttempts = $model->get();
        foreach($quizAttempts as $quizAttempt) {
            if($quiz->grade_methor == 1) {
                $attempts = QuizAttempts::where(['quiz_id' => $quiz->id, 'part_id' => $quizAttempt->part_id, 'user_id' => $quizAttempt->user_id])->orderBy('sumgrades', 'desc')->first(['id']);
                $data[] = $attempts->id;
            } else if ($quiz->grade_methor == 3) {
                $attempts = QuizAttempts::where(['quiz_id' => $quiz->id, 'part_id' => $quizAttempt->part_id, 'user_id' => $quizAttempt->user_id])->first(['id']);
                $data[] = $attempts->id;
            } else {
                $attempts = QuizAttempts::where(['quiz_id' => $quiz->id, 'part_id' => $quizAttempt->part_id, 'user_id' => $quizAttempt->user_id])->latest('id')->first(['id']);
                $data[] = $attempts->id;
            }
        }

        $query = QuizUpdateAttempts::query();
        $query->select([
            'profile.full_name',
            'profile.unit_name',
            'profile.title_name',
            'profile.email',
            'profile.code',
            'attempt.questions',
            'attempt.user_id',
            'quiz.name as quiz_name',
            'quiz.unit_create_quiz',
            'quiz.limit_time',
            'part.name as part_name',
            'part.start_date as start_date',
            'part.end_date as end_date',
            'question.question_id',
            'question.random',
            'question.qcategory_id',
            'question.num_order',
        ])->disableCache();
        $query->from('el_quiz_update_attempt as attempt');
        $query->join('el_profile_view as profile', 'profile.user_id', '=', 'attempt.user_id');
        $query->join('el_quiz_part as part', 'part.id', '=', 'attempt.part_id');
        $query->join('el_quiz as quiz', 'quiz.id', '=', 'attempt.quiz_id');
        $query->join('el_quiz_question as question', 'question.quiz_id', '=', 'attempt.quiz_id');
        $query->where('attempt.quiz_id', $quiz->id);
        $query->whereIn('attempt.attempt_id', $data);

        if(isset($quiz_part)) {
            $query->whereIn('attempt.part_id', $quiz_parts);
        }

        $query->orderBy('attempt.attempt_id');
        $query->orderBy('question.num_order', 'asc');
        return $query;
    }
}
