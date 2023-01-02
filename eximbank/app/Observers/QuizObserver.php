<?php

namespace App\Observers;

use Modules\Quiz\Entities\Quiz;
use Modules\Quiz\Entities\QuizQuestion;
use Modules\Quiz\Entities\QuizQuestionCategory;

class QuizObserver extends BaseObserver
{
    /**
     * Handle the quiz "created" event.
     *
     * @param  \App\Quiz  $quiz
     * @return void
     */
    public function created(Quiz $quiz)
    {
        $action = "Thêm kỳ thi ";
        parent::saveHistory($quiz,'Insert',$action);
    }

    /**
     * Handle the quiz "updated" event.
     *
     * @param  \App\Quiz  $quiz
     * @return void
     */
    public function updated(Quiz $quiz)
    {

        $action = "Cập nhật kỳ thi";
        if ($quiz->isDirty('approved_step'))
            $action = "Phê duyệt kỳ thi";
        parent::saveHistory($quiz,'Update',$action);
        if ($quiz->isDirty(['name']))
            $this->updateHasChange($quiz,1);
//        if ($quiz->isDirty('max_score'))
//            $this->updateTemplateQuizQuestionCategory($quiz);
//        dd($quiz->getDirty());
        if ($quiz->isDirty('status') || $quiz->isDirty('max_score') || $quiz->isDirty('shuffle_question')  || $quiz->isDirty('shuffle_answers')
            || $quiz->isDirty('max_attempts') || $quiz->isDirty('grade_methor') || $quiz->isDirty('limit_time'))
            $this->updateFlagQuizTemplate($quiz);
    }

    /**
     * Handle the quiz "deleted" event.
     *
     * @param  \App\Quiz  $quiz
     * @return void
     */
    public function deleted(Quiz $quiz)
    {
        $this->updateHasChange($quiz,2);
        $action = "Xóa kỳ thi ";
        parent::saveHistory($quiz,'Delete',$action);
    }

    /**
     * Handle the quiz "restored" event.
     *
     * @param  \App\Quiz  $quiz
     * @return void
     */
    public function restored(Quiz $quiz)
    {
        //
    }

    /**
     * Handle the quiz "force deleted" event.
     *
     * @param  \App\Quiz  $quiz
     * @return void
     */
    public function forceDeleted(Quiz $quiz)
    {
        //
    }
    private function updateTemplateQuizQuestionCategory(Quiz $quiz){
        QuizQuestionCategory::updateCateTemplate($quiz->id);
    }
    private function updateFlagQuizTemplate(Quiz $quiz){
        if ($quiz->status==1)
            Quiz::where(['id'=>$quiz->id])->update(['flag'=>1]);
//            $quiz->update(['flag'=>1]);
    }
}
