<?php

namespace Modules\User\Console;

use App\Models\ProfileView;
use Illuminate\Console\Command;
use Modules\TrainingRoadmap\Entities\TrainingRoadmap;
use Modules\User\Entities\ProfileProgressRoadmap;
use Modules\User\Entities\UserCompletedSubject;

class ProgressRoadmap extends Command
{

    protected $signature = 'progress_roadmap:update';

    protected $description = 'Cập nhật % hoàn thành chương trình khung theo chức danh của nhân viên chạy vào lúc 1h sáng (0 1 * * * )';
    protected $expression = '0 1 * * *';
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $profiles = ProfileView::where('user_id', '>', 2)->where('type_user', 1)->get(['user_id', 'title_id']);
        foreach($profiles as $profile){
            $query = TrainingRoadmap::query();
            $query->select([
                'a.subject_id',
                'a.training_form'
            ]);
            $query->from('el_trainingroadmap AS a');
            $query->leftJoin('el_subject AS b', 'b.id', '=', 'a.subject_id');
            $query->where('a.title_id', '=', $profile->title_id);
            $rows = $query->get();
            $totalSubjectRoadmap = $query->count();
            $countSubjectRoadmapCompleted = 0;
            foreach ($rows as $row) {
                $trainingForm = json_decode($row->training_form);
                if(!empty($trainingForm) || (in_array(1, $trainingForm) && in_array(2, $trainingForm))) {
                    if(in_array(1, $trainingForm)) {
                        $checkCompleted = UserCompletedSubject::whereUserId($profile->user_id)->where('subject_id', $row->subject_id)->where('course_type', 1)->first();
                        if ($checkCompleted) {
                            $countSubjectRoadmapCompleted += 1;
                        }
                    } else {
                        $checkCompleted = UserCompletedSubject::whereUserId($profile->user_id)->where('subject_id', $row->subject_id)->where('course_type', 2)->first();
                        if ($checkCompleted) {
                            $countSubjectRoadmapCompleted += 1;
                        }
                    }
                } else {
                    $checkCompleted = UserCompletedSubject::whereUserId($profile->user_id)->where('subject_id', $row->subject_id)->first();
                    if(isset($checkCompleted)) {
                        $countSubjectRoadmapCompleted += 1;
                    }
                }
            }
            $progressRoadmap = round(($countSubjectRoadmapCompleted / ($totalSubjectRoadmap > 0 ? $totalSubjectRoadmap : 1))*100);

            ProfileProgressRoadmap::updateOrCreate([
                'user_id' => $profile->user_id,
                'title_id' => $profile->title_id
            ],[
                'percent' => $progressRoadmap
            ]);
        }
    }
}
