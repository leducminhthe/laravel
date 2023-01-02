<?php
namespace Modules\Offline\Exports;

use Modules\Offline\Entities\OfflineCourse;
use App\Models\Categories\Absent;
use App\Models\Categories\AbsentReason;
use App\Models\Categories\Discipline;
use App\Models\Categories\TrainingTeacher;
use Modules\Offline\Entities\OfflineSchedule;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use Modules\Offline\Entities\OfflineTeachingOrganizationUser;
use Modules\Offline\Entities\OfflineTeachingOrganizationUserAnswer;
use Modules\Offline\Entities\OfflineTeachingOrganizationUserCategory;
use Modules\Offline\Entities\OfflineTeachingOrganizationUserQuestion;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class TeachingOrganizationExport implements FromQuery, WithHeadings, ShouldAutoSize, WithMapping, WithEvents
{
    use Exportable;
    protected $index = 0;
    protected $count = 0;
    protected $count_title = 8;

    public function __construct($course_id, $request)
    {
        $this->course_id = $course_id;
        $this->search = $request->search;
        $this->class_id = $request->class_id;
    }

    public function map($result): array
    {
        $obj = [];
        $this->index++;

        $questions = OfflineTeachingOrganizationUserQuestion::whereIn('teaching_organization_category_id', function($sub) use($result){
            $sub->select(['id'])
            ->from('el_offline_teaching_organization_user_category')
            ->where('teaching_organization_user_id', '=', $result->id);
        })->get();

        $obj[] = $this->index;
        $obj[] = $result->class_name;
        $obj[] = $result->code;
        $obj[] = $result->full_name;
        $obj[] = $result->unit_name;
        $obj[] = $result->title_name;
        $obj[] = $result->template_name;
        $obj[] = get_date($result->created_at, 'H:i:s d/m/Y');

        foreach($questions as $question){
            if($question->type == 'essay'){
                $obj[] = $question->answer_essay;
            }
            if($question->type == 'rank'){
                $answer = OfflineTeachingOrganizationUserAnswer::where('teaching_organization_question_id', $question->id)
                ->where('answer_id', $question->answer_essay)
                ->first(['answer_code']);

                $obj[] = $answer ? $answer->answer_code : '';
            }
        }

        return $obj;
    }

    public function query(){
        $query = OfflineTeachingOrganizationUser::query()
            ->select([
                'a.id',
                'a.created_at',
                'register.code',
                'register.full_name',
                'register.unit_name',
                'register.title_name',
                'class.name as class_name',
                'template.name as template_name',
            ])
            ->from('el_offline_teaching_organization_user as a')
            ->leftJoin('el_offline_register_view as register', function($sub){
                $sub->on('register.user_id', '=', 'a.user_id');
                $sub->on('register.course_id', '=', 'a.course_id');
            })
            ->leftJoin('offline_course_class as class', 'class.id', '=', 'register.class_id')
            ->leftJoin('el_offline_teaching_organization_template as template', function($sub){
                $sub->on('template.id', '=', 'a.template_id');
                $sub->on('template.course_id', '=', 'a.course_id');
            })
            ->where('a.course_id', '=', $this->course_id);

        if($this->search){
            $query->leftJoin('user', 'user.id', '=', 'a.user_id');
            $query->where(function($sub){
                $sub->orWhere('register.code', 'like', '%'.$this->search.'%');
                $sub->orWhere('register.full_name', 'like', '%'.$this->search.'%');
                $sub->orWhere('register.email', 'like', '%'.$this->search.'%');
                $sub->orWhere('user.username', 'like', '%'.$this->search.'%');
            });
        }

        if($this->class_id){
            $query->where('register.class_id', '=', $this->class_id);
        }

        $query->orderBy('a.id', 'ASC');

        $this->count = $query->count();

        return $query;
    }

    public function headings(): array
    {
        $course = OfflineCourse::find($this->course_id);
        $teaching_organization_user = OfflineTeachingOrganizationUser::where('course_id', $this->course_id)->first();
        $questions = OfflineTeachingOrganizationUserQuestion::whereIn('teaching_organization_category_id', function($sub) use($teaching_organization_user){
            $sub->select(['id'])
            ->from('el_offline_teaching_organization_user_category')
            ->where('teaching_organization_user_id', '=', $teaching_organization_user->id);
        })->get();

        $title = [];
        $title[] = trans('latraining.stt');
        $title[] = trans('latraining.class_name');
        $title[] = trans('latraining.employee_code');
        $title[] = trans('latraining.fullname');
        $title[] = trans('latraining.unit');
        $title[] = trans('latraining.title');
        $title[] = trans('latraining.rating_template');
        $title[] = trans('latraining.time_rating');

        foreach($questions as $question){
            if($question->teacher_id){
                $teacher = TrainingTeacher::find($question->teacher_id);

                $title[] = $question->question_name .PHP_EOL. ($teacher ? '(GV: '. $teacher->name .')' : '');
            }else{
                $title[] = $question->question_name;
            }

            $this->count_title += 1;
        }

        return [
            ['Danh sách đánh giá công tác tổ chức giảng dạy khóa: ' . $course->name],
            $title,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $columnName = $event->sheet->getDelegate()->getColumnDimensionByColumn($this->count_title);
                $char = $columnName->getColumnIndex();

                $event->sheet->mergeCells('A1:I1');
                $event->sheet->getDelegate()->getStyle('A1:'.$char.'1')->applyFromArray([
                    'font' => [
                        'size'      =>  13,
                        'bold'      =>  true,
                    ],
                ])->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('DDDDDD');

                $event->sheet->getDelegate()->getStyle('H2:'.$char.'2')->getAlignment()->setWrapText(true);
                $event->sheet->getDelegate()->getStyle('A2:'.$char.(2 + $this->count))->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                    'font' => [
                        'name' => 'Arial',
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical'   => Alignment::VERTICAL_CENTER,
                    ],
                ]);
            },
        ];
    }
}
