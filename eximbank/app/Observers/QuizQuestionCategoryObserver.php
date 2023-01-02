<?php

namespace App\Observers;


use Modules\Quiz\Entities\Quiz;
use Modules\Quiz\Entities\QuizQuestionCategory;

class QuizQuestionCategoryObserver extends BaseObserver
{
    /**
     * Handle the quiz question category "created" event.
     *
     * @param  \App\QuizQuestionCategory  $quizQuestionCategory
     * @return void
     */
    public function created(QuizQuestionCategory $quizQuestionCategory)
    {
        $this->updateQuiz($quizQuestionCategory);
        $quiz = Quiz::find($quizQuestionCategory->quiz_id)->name;
        $action = "Thêm đề mục câu hỏi vào kỳ thi ".$quiz;
        parent::saveHistory($quizQuestionCategory,'Insert',$action);
    }

    /**
     * Handle the quiz question category "updated" event.
     *
     * @param  \App\QuizQuestionCategory  $quizQuestionCategory
     * @return void
     */
    public function updated(QuizQuestionCategory $quizQuestionCategory)
    {
        $this->updateQuiz($quizQuestionCategory);
        $quiz = Quiz::find($quizQuestionCategory->quiz_id)->name;
        $action = "Cập nhật đề mục câu hỏi vào kỳ thi ".$quiz;
        parent::saveHistory($quizQuestionCategory,'Update',$action);
    }

    /**
     * Handle the quiz question category "deleted" event.
     *
     * @param  \App\QuizQuestionCategory  $quizQuestionCategory
     * @return void
     */
    public function deleted(QuizQuestionCategory $quizQuestionCategory)
    {
        $this->updateQuiz($quizQuestionCategory);
        $quiz = Quiz::find($quizQuestionCategory->quiz_id)->name;
        $action = "Xóa đề mục câu hỏi vào kỳ thi ".$quiz;
        parent::saveHistory($quizQuestionCategory,'Delete',$action);
    }

    /**
     * Handle the quiz question category "restored" event.
     *
     * @param  \App\QuizQuestionCategory  $quizQuestionCategory
     * @return void
     */
    public function restored(QuizQuestionCategory $quizQuestionCategory)
    {
        //
    }

    /**
     * Handle the quiz question category "force deleted" event.
     *
     * @param  \App\QuizQuestionCategory  $quizQuestionCategory
     * @return void
     */
    public function forceDeleted(QuizQuestionCategory $quizQuestionCategory)
    {
        //
    }
    private function updateQuiz(QuizQuestionCategory $quizQuestionCategory){
        if ($quizQuestionCategory->quiz()->value('status')==1)
            $quizQuestionCategory->quiz()->update(['flag'=>1]);
    }
}
