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

use Modules\ReportNew\Entities\BC36;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Modules\TrainingByTitle\Entities\TrainingByTitleDetail;
use Modules\User\Entities\UserCompletedSubject;

class BC36Export implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize,  WithEvents, WithStartRow, WithDrawings
{
    use Exportable;
    protected $index = 0;
    protected $count_title = 8;

    public function __construct($param)
    {
        $this->title = $param->title;
        $this->users = $param->users;
        $this->training_title_category = $param->training_title_category;
    }

    public function query()
    {
        $query = BC36::sql($this->training_title_category, $this->users, $this->title);
        $this->count = $query->count();
        return $query;
    }
    public function map($row): array
    {
        $this->index++;
        $obj[] = $this->index;
        $obj[] = $row->code;
        $obj[] = $row->full_name;
        $obj[] = $row->title_name;
        $obj[] = $row->unit_name;
        $obj[] = $row->email;
        $obj[] = $row->join_company;
        $obj[] = $row->training_category_name;

        $subject_training_detail = TrainingByTitleDetail::where('training_title_category_id', $row->training_category_id)->get('subject_id');
        foreach($subject_training_detail as $subject) {
            $check_complete_subject = UserCompletedSubject::where('subject_id', $subject->subject_id)->latest('id')->first();
            if(isset($check_complete_subject)) {
                $obj[] = get_date($check_complete_subject->date_completed, 'd/m/Y');
            } else {
                $obj[] = 'Chưa học';
            }
        }
        
        return $obj;
    }

    public function headings(): array
    {
        $title_arr = [];
        $title_arr[] = trans('latraining.stt');
        $title_arr[] = trans('latraining.employee_code');
        $title_arr[] = trans('latraining.employee_name');
        $title_arr[] = trans('lacategory.title');
        $title_arr[] = trans('lareport.unit_direct');
        $title_arr[] = 'Email';
        $title_arr[] = trans('latraining.day_work');
        $title_arr[] = trans('latraining.object');

        $subject_training_detail = TrainingByTitleDetail::where('training_title_category_id', $this->training_title_category)->get(['subject_name']);
        foreach ($subject_training_detail as $key => $subject) {
            $title_arr[] = $subject->subject_name;
            $this->count_title += 1;
        }

        return [
            [],
            [],
            [],
            [],
            [],
            ['BÁO CÁO TỈ LỆ HỌC PHẦN THEO LỘ TRÌNH ĐÀO TẠO'],
            [],
            $title_arr
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

                $arr_char = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];
                if ($this->count_title > 26){
                    $num = floor($this->count_title/26);
                    $num_1 = $this->count_title - ($num * 26);

                    $char = $arr_char[($num - 1)] . $arr_char[($num_1 - 1)];
                }else{
                    $char = $arr_char[($this->count_title - 1)];
                }
                
                $event->sheet->getDelegate()->mergeCells('A6:'.$char.'6')
                ->getStyle('A6')
                ->applyFromArray($title);

                $event->sheet->getDelegate()->getStyle('A8:'.$char.'8')
                    ->applyFromArray($title)
                    ->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('FFFF00');

                $event->sheet->getDelegate()->getStyle('A8:'.$char.(8 + $this->index))
                    ->applyFromArray([
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                            ],
                        ],
                        'alignment' => [
                            'horizontal' => Alignment::HORIZONTAL_CENTER,
                        ],
                    ]);
            },
        ];
    }
    public function startRow(): int
    {
        return 9;
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
