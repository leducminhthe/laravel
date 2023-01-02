<?php
namespace Modules\ReportNew\Export;

use App\Models\Config;
use App\Models\LogoModel;
use App\Scopes\CompanyScope;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Modules\ReportNew\Entities\BC38;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use App\Models\CourseView;
use App\Models\CourseRegisterView;

class BC38Export implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize,  WithEvents, WithStartRow, WithDrawings
{
    use Exportable;
    protected $index = 0;
    protected $count = 0;
    protected $column = 1;
    public function __construct($param)
    {
        $allCourse = CourseView::where(['status' => 1, 'isopen' => 1])->get(['course_id', 'course_type', 'name', 'code', 'start_date', 'end_date']);
        $this->allCourse = $allCourse;
        $this->unit_id = $param->unit_id;
        $this->title_id = $param->title_id;
    }

    public function query()
    {
        $query = BC38::sql($this->unit_id, $this->title_id);
        $query->orderBy('profile.id', 'ASC');
        return $query;
    }
    public function map($row): array
    {
        $obj = [];
        $this->index++;
        $obj[] = $this->index;
        $obj[] = $row->code;
        $obj[] = $row->full_name;
        $obj[] = $row->title_name;
        $obj[] = $row->unit_name;

        $model = CourseRegisterView::query();
        $model->select([
            'register.cron_complete',
            'register.course_id',
            'register.course_type',
            'online.score as online_score',
            'online.result as online_result',
            'offline.score as offline_score',
            'offline.result as offline_result',
        ]);
        $model->from('el_course_register_view as register');
        $model->leftJoin('el_online_result as online', function($join) {
            $join->on('register.course_id', '=', 'online.course_id');
            $join->on('register.register_id', '=', 'online.register_id');
            $join->where('register.course_type', '=', 1);
        });
        $model->leftJoin('el_offline_result as offline', function($join2) {
            $join2->on('register.course_id', '=', 'offline.course_id');
            $join2->on('register.register_id', '=', 'offline.register_id');
            $join2->where('register.course_type', '=', 2);
        });
        $model->where('register.user_id', $row->user_id);
        $infoUser = $model->get();
        foreach ($infoUser as $key => $value) {
            $row->{"register_". $value->course_id . "_" . $value->course_type} = 'x';
            if($value->course_type == 1) {
                $row->{"score_". $value->course_id . "_" . $value->course_type} = $value->online_score ? $value->online_score : '-';
                if($value->online_result == 0) {
                    $result = 'Không đạt';
                } else if ($value->online_result == 1) {
                    $result = 'Đạt';
                } else {
                    $result = 'Đang học';
                }
                $row->{"result_". $value->course_id . "_" . $value->course_type} = $result;
            } else {
                $row->{"score_". $value->course_id . "_" . $value->course_type} = $value->offline_score ? $value->offline_score : '-';
                if($value->offline_result == 0) {
                    $result = 'Không đạt';
                } else if ($value->offline_result == 1) {
                    $result = 'Đạt';
                } else {
                    $result = 'Đang học';
                }
                $row->{"result_". $value->course_id . "_" . $value->course_type} = $result;
            }
        }

        foreach ($this->allCourse as $key => $course) {
            $obj[] = $row->{"register_". $course->course_id . "_" . $course->course_type} ? $row->{"register_". $course->course_id . "_" . $course->course_type} : '-';
            $obj[] = $row->{"score_". $course->course_id . "_" . $course->course_type} ? $row->{"score_". $course->course_id . "_" . $course->course_type} : '-';
            $obj[] = $row->{"result_". $course->course_id . "_" . $course->course_type} ? $row->{"result_". $course->course_id . "_" . $course->course_type} : '-';
        }

        return $obj;
    }

    public function headings(): array
    {
        $colHeader1= [
            trans('latraining.stt'),
            trans('lasetting.employee_code'),
            trans('lasetting.employee_name'),
            trans('lacategory.title'),
            trans('lamenu.unit'),
        ];

        foreach ($this->allCourse as $key => $course) {
            array_push($colHeader1, '('. $course->code .') '. $course->name .PHP_EOL. get_date($course->start_date) . ($course->end_date ? ' => '. get_date($course->end_date) : '') . PHP_EOL.PHP_EOL);
            array_push($colHeader1, '');
            array_push($colHeader1, '');
        }

        $colHeader= [
            '',
            '',
            '',
            '',
            '',
        ];

        $this->column = 5;
        foreach ($this->allCourse as $key => $course) {
            array_push($colHeader, trans('labutton.register'));
            array_push($colHeader, trans('latraining.score'));
            array_push($colHeader, trans('laprofile.result'));
            $this->column += 3;
        }
        return [
            [],
            [],
            [],
            [],
            [],
            ['THỐNG KÊ TẤT CẢ NHÂN VIÊN THEO KHÓA HỌC'],
            [],
            $colHeader1,
            $colHeader
        ];
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $title = [
                    'font' => [
                        'size'      =>  12,
                        'name' => 'Arial',
                        'bold'      =>  true,
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                    ],
                ];
                $columnName = $event->sheet->getDelegate()->getColumnDimensionByColumn($this->column);
                $event->sheet->getDelegate()->mergeCells('A6:G6')
                ->getStyle('A6')
                ->applyFromArray($title);

                // header
                $event->sheet->getDelegate()->getStyle('A8:'.$columnName->getColumnIndex().'9')
                    ->applyFromArray($title)
                    ->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('FFFF00');
                $event->sheet->getDelegate()->mergeCells('A8:A9');
                $event->sheet->getDelegate()->mergeCells('B8:B9');
                $event->sheet->getDelegate()->mergeCells('C8:C9');
                $event->sheet->getDelegate()->mergeCells('D8:D9');
                $event->sheet->getDelegate()->mergeCells('E8:E9');

                $colStart = 6;

                foreach ($this->allCourse as $key => $course) {
                    $columnMergeFrom = $event->sheet->getDelegate()->getColumnDimensionByColumn($colStart);
                    $columnMergeTo = $event->sheet->getDelegate()->getColumnDimensionByColumn($colStart + 2);
                    $event->sheet->getDelegate()->mergeCells($columnMergeFrom->getColumnIndex(). '8:' . $columnMergeTo->getColumnIndex() . '8');
                    $colStart += 3;
                }

                // detail item
                $event->sheet->getDelegate()->getStyle('A8:'.$columnName->getColumnIndex().(9 + $this->index))
                    ->applyFromArray([
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                            ],
                        ],
                        'alignment' => [
                            'horizontal' => Alignment::HORIZONTAL_CENTER,
                        ],
                    ])->getAlignment();
            },

        ];
    }
    public function startRow(): int
    {
        return 10;
    }

    public function drawings()
    {
        $storage = \Storage::disk('upload');

        LogoModel::addGlobalScope(new CompanyScope());
        $logo = LogoModel::where('status',1)->first();
        $checkLogo = upload_file($logo->image);
        if ($logo && $checkLogo) {
            $path = $storage->path($logo->image);
        }else{
            $path = './images/logo_topleaning.png';
        }

        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('This is my logo');
        $drawing->setPath($path);
        $drawing->setHeight(100);
        $drawing->setCoordinates('A1');

        return $drawing;
    }
}
