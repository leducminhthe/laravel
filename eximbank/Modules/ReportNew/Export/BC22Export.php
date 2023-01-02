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
use Modules\ReportNew\Entities\BC22;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class BC22Export implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize,  WithEvents, WithStartRow, WithDrawings
{
    use Exportable;
    protected $index = 0;
    protected $count = 0;
    protected $column = 1;
    public function __construct($param)
    {
        $this->from_date = $param->from_date;
        $this->to_date = $param->to_date;
        $this->type = $param->type;
    }

    public function query()
    {
        $query = BC22::sql($this->type,$this->from_date,$this->to_date);
        $this->count = $query->count();

        return $query;
    }
    public function map($row): array
    {

        $obj = [];
        $this->index++;
        $obj[] = $this->index;
        $obj[]=$row->subject_merge_split_code;
        $obj[]=$row->subject_merge_split_name;
        $obj[]=$row->subject_merges_splits;
        $obj[]=get_date($row->date_action);
        $obj[]=$row->user_code;
        $obj[]=$row->created_user;
        $obj[]=$row->email;
        $obj[]=$row->phone;
        $obj[]=$row->area_name;
//        $obj[]=$row->unit1_code;
        $obj[]=$row->unit1_name;
//        $obj[]=$row->unit2_code;
        $obj[]=$row->unit2_name;
//        $obj[]=$row->unit3_code;
//        $obj[]=$row->unit3_name;
        $obj[]=$row->position;
        $obj[]=$row->title;
        $obj[]=$row->note;
        return $obj;
    }

    public function headings(): array
    {
        if ($this->type=='1'){
            $title='DANH SÁCH GỘP CHUYÊN ĐỀ';
            $nameType='Gộp chuyên đề';
        }
        else{
            $title ='DANH SÁCH TÁCH CHUYÊN ĐỀ';
            $nameType='Tách chuyên đề';
        }

        $colHeader= [
            trans('latraining.stt'),
            'Mã',
            $this->type=='1'?'Tên chuyên đề mới':'Tên chuyên đề cần tách',
            $this->type=='1'?'Chuyên đề cần gộp':'Chuyên đề mới',
            'Ngày gộp',
            trans('latraining.employee_code '),
            trans('latraining.fullname'),
            'Email',
           trans('latraining.phone'),
          trans('lamenu.area'),
            // 'Mã đơn vị 1',
            trans('lareport.unit_direct'),
            // 'Mã đơn vị 2',
            trans('lareport.unit_management'),
            // 'Mã đơn vị 3',
            // 'Đơn vị 3',
            trans('laprofile.position'),
            trans('latraining.title'),
          trans('latraining.note'),
        ];
        return [
            [],
            [],
            [],
            [],
            [],
            [$title],
            [],
            [trans('latraining.from_date').': '.get_date($this->from_date).' - '.get_date($this->to_date)],
            ['Loại: '.$nameType],
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

                $event->sheet->getDelegate()->mergeCells('A6:F6')
                ->getStyle('A6')
                ->applyFromArray($title);

                $event->sheet->getDelegate()->getStyle('A11:L11')
                    ->applyFromArray($title)
                    ->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('FFFF00');

                $event->sheet->getDelegate()->getStyle('A11:L11')
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
        return 11;
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
