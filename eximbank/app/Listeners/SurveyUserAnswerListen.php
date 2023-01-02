<?php

namespace App\Listeners;

use App\Events\SurveyRealTime;
use Illuminate\Support\Facades\Auth;
use Modules\Survey\Entities\SurveyUserAnswerOnline;
use Modules\Survey\Entities\SurveyUser;
use Modules\Survey\Entities\Survey;

class SurveyUserAnswerListen
{
    public function __construct()
    {
        //
    }

    public function handle(SurveyRealTime $event)
    {
        $answers_id = $event->answer_id;
        $survey_id = $event->survey_id;
        $user_id = $event->user_id;
        
        if(!empty($answers_id)) {
            SurveyUserAnswerOnline::where('user_id', $user_id)->where('survey_id', $survey_id)->delete();
            foreach($answers_id as $answer) {
                $model = new SurveyUserAnswerOnline();
                $model->answer_id = $answer;
                $model->user_id = $user_id;
                $model->survey_id = $survey_id;
                $model->save();
            }
        }
    }
}
