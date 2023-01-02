<?php

namespace App\Observers;

use Modules\Quiz\Entities\Quiz;
use Modules\Quiz\Entities\QuizPart;

class QuizPartObserver extends BaseObserver
{
    /**
     * Handle the quiz part "created" event.
     *
     * @param  \App\QuizPart  $quizPart
     * @return void
     */
    public function created(QuizPart $quizPart)
    {
        $this->updateQuiz($quizPart);
        $quiz = Quiz::find($quizPart->quiz_id)->name;
        $action = "Thêm ca thi trong kỳ thi ".$quiz;
        parent::saveHistory($quizPart,'Insert',$action);
    }

    /**
     * Handle the quiz part "updated" event.
     *
     * @param  \App\QuizPart  $quizPart
     * @return void
     */
    public function updated(QuizPart $quizPart)
    {
        $this->updateQuiz($quizPart);
        $quiz = Quiz::find($quizPart->quiz_id)->name;
        $action = "Cập nhật ca thi trong kỳ thi ".$quiz;
        parent::saveHistory($quizPart,'Update',$action);
    }

    /**
     * Handle the quiz part "deleted" event.
     *
     * @param  \App\QuizPart  $quizPart
     * @return void
     */
    public function deleted(QuizPart $quizPart)
    {
        $this->updateQuiz($quizPart);
        $quiz = Quiz::find($quizPart->quiz_id)->name;
        $action = "Xóa ca thi trong kỳ kỳ thi ".$quiz;
        parent::saveHistory($quizPart,'Delete',$action);
    }

    /**
     * Handle the quiz part "restored" event.
     *
     * @param  \App\QuizPart  $quizPart
     * @return void
     */
    public function restored(QuizPart $quizPart)
    {
        //
    }

    /**
     * Handle the quiz part "force deleted" event.
     *
     * @param  \App\QuizPart  $quizPart
     * @return void
     */
    public function forceDeleted(QuizPart $quizPart)
    {
        //
    }
    private function updateQuiz(QuizPart $quizPart){
        if ($quizPart->quiz()->value('status')==1)
            $quizPart->quiz()->update(['flag'=>1]);
        /// update quiz start_date, end_date
        $quizTime = QuizPart::whereQuizId($quizPart->quiz_id)->selectRaw('Min(start_date) as start_date, Max(end_date) as end_date')->first();
        if ($quizTime)
            $quizPart->quiz()->update(['start_quiz'=>$quizTime->start_date,'end_quiz'=>$quizTime->end_date]);
        else
            $quizPart->quiz()->update(['start_quiz'=>null,'end_date'=>null]);
    }
}
