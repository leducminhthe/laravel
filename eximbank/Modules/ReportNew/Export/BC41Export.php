<?php
namespace Modules\ReportNew\Export;

use App\Models\Config;
use App\Models\LogoModel;

use Carbon\Carbon;
use Modules\Offline\Entities\OfflineAttendance;
use Modules\Offline\Entities\OfflineSchedule;
use Modules\Offline\Entities\OfflineTeamsAttendanceReport;
use Modules\Online\Entities\OnlineCourseTimeUserLearn;
use Modules\Quiz\Entities\Quiz;
use Modules\Quiz\Entities\QuizAttempts;

use App\Scopes\CompanyScope;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Modules\ReportNew\Entities\BC40;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use Modules\Capabilities\Entities\CapabilitiesReview;
use Modules\Capabilities\Entities\CapabilitiesTitle;
use Modules\ReportNew\Entities\BC41;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class BC41Export implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize,  WithEvents, WithStartRow, WithDrawings
{
    use Exportable;
    protected $index = 0;
    protected $count = 0;
    protected $column = 1;
    public function __construct($param)
    {
        $this->title_id = $param->title_id;
    }

    public function query()
    {
        $query = BC41::sql($this->title_id);
        $this->count = $query->count();
        return $query;
    }

    public function map($row): array
    {
        $user_arr = $row->profiles->pluck('user_id')->toArray();

        $total = count($user_arr);
        $num_rating = CapabilitiesReview::whereIn('user_id', $user_arr)->count();

        $percent = CapabilitiesTitle::where('title_id', $row->id)->sum('weight') .' %';
        $score = number_format(CapabilitiesTitle::where('title_id', $row->id)->sum('goal'), 2);

        $num_not_rating = ($total - $num_rating);

        $obj = [];
        $this->index++;

        $obj[] = $this->index;
        $obj[] = $row->name;
        $obj[] = $total;
        $obj[] = $percent;
        $obj[] = $score;
        $obj[] = $num_not_rating;
        $obj[] = rand(1, $total);
        $obj[] = rand(1, $total);
        $obj[] = rand(1, $total);
        $obj[] = rand(1, $total);
        $obj[] = rand(1, $total);
        $obj[] = rand(1, $total);
        $obj[] = rand(1, $total);

        return $obj;
    }

    public function headings(): array
    {
        $colHeader= [
            trans('latraining.stt'),
            trans('latraining.title'),
            'Tổng SL',
            'Trọng số',
            'Điểm chuẩn',
            'SL chưa đánh giá',
            '1-30%',
            '30-50%',
            '50-60%',
            '60-70%',
            '70-80%',
            '80-90%',
            '90-100%',
        ];
        return [
            [],
            [],
            [],
            [],
            [],
            ['BÁO CÁO ĐÁNH GIÁ KHUNG NĂNG LỰC THEO CHỨC DANH'],
            [],
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

                $event->sheet->getDelegate()->mergeCells('A6:M6')
                ->getStyle('A6')
                ->applyFromArray($title);

                $event->sheet->getDelegate()->getStyle('A8:M8')
                    ->applyFromArray($title)
                    ->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('FFFF00');

                $event->sheet->getDelegate()->getStyle('A8:M'.(8 + $this->index))
                    ->applyFromArray([
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                            ],
                        ],
                        'alignment' => [
                            'horizontal' => Alignment::HORIZONTAL_CENTER,
                        ],
                    ])->getAlignment()->setWrapText(true);
            },

        ];
    }
    public function startRow(): int
    {
        return 12;
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
        $drawing->setHeight(90);
        $drawing->setCoordinates('A1');

        return $drawing;
    }
}
