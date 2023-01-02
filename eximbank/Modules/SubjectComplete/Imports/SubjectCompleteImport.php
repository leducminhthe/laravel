<?php
namespace Modules\SubjectComplete\Imports;
use App\Models\Categories\Subject;
use App\Models\Categories\Unit;

use App\Models\Profile;
use App\Models\ProfileView;
use App\Models\User;
use App\Models\UnitView;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\Importable;
use App\Notifications\ImportUnitHasFailed;
use Modules\SubjectComplete\Jobs\Import;
use Modules\User\Entities\TrainingProcess;
use Modules\User\Entities\TrainingProcessLogs;


class SubjectCompleteImport implements ToCollection, WithStartRow, ShouldQueue, WithChunkReading
{
    use Importable;
    public $imported_by;
    public function __construct($user, $type_import)
    {
        $this->imported_by = $user;
        $this->type_import = $type_import;
    }
    public function collection(Collection $collections)
    {
        foreach ($collections as $index => $item) {
            if($this->type_import == 1) {
                $name_type = 'Mã nhân viên';
                $profile = ProfileView::where('code', '=', $item[1])->first();
            } else if ($this->type_import == 2) {
                $name_type = 'Username';
                $profile = ProfileView::query()
                ->select(['profile.*'])
                ->from('el_profile_view as profile')
                ->join('user', 'user.id', '=', 'profile.user_id')
                ->where('user.username', '=', $item[1])
                ->first();
            } else {
                $name_type = 'Email';
                $profile = ProfileView::where('email', '=', $item[1])->first();
            }
            
            $subject = Subject::where(['code'=>$item[3]])->first();
            if ($profile && $subject) {
                $model = TrainingProcess::updateOrCreate(
                    [
                        'user_id' => $profile->user_id, 'subject_id' => $subject->id
                    ],
                    [
                        'user_id' => $profile->user_id,
                        'subject_id' => $subject->id,
                        'subject_code' => $subject->code,
                        'subject_name' => $subject->name,
                        'titles_code' => $profile->title_code,
                        'titles_name' => $profile->title_name,
                        'unit_code' => $profile->unit_code,
                        'unit_name' => $profile->unit_name,
                        'start_date' => date('Y-m-d H:i:s'),
                        'end_date' => date('Y-m-d H:i:s'),
                        'pass' => 1,
                        'process_type' => 2,
                        'note' => $item[5]
                    ]
                );
                // save logs
                $action='Thêm hoàn thành chuyên đề mã '.$subject->code.' cho học viên '.$profile->full_name.' ('.$profile->code.')';
                TrainingProcessLogs::saveLogs($model->id,'insert_subject_completion',$action,3);
            }
        }
    }
    public function startRow(): int
    {
        return 2;
    }
    public function chunkSize(): int
    {
        return 200;
    }
//    public function registerEvents(): array
//    {
//        return [
//            ImportFailed::class => function(ImportFailed $event) {
//                $this->imported_by->notify(new ImportUnitHasFailed([$event->getException()->getMessage()]));
//            },
//        ];
//    }
}
