<?php
namespace App\Exports;

use App\Models\Languages;
use App\Models\LanguagesGroups;

use App\Models\LanguagesType;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;

class LanguagesExport implements FromQuery, WithHeadings, ShouldAutoSize, WithMapping, WithEvents
{
    use Exportable;
    protected $index = 0;
    protected $count = 0;

    public function map($profile): array
    {
        $result = [];
        $this->index++;

        $result[] = $this->index;
        $result[] = $profile->pkey;
        $result[] = $profile->group_name;
        $result[] = $profile->note;

        $lang_types = LanguagesType::get(['key']);
        foreach ($lang_types as $lang){
            $result[] = $lang->key == 'vi' ? $profile->content : $profile->{'content_'.$lang->key};
        }
        
        return $result;
    }

    public function query()
    {
        $query = Languages::query();
        $query->select([
            'a.*',
            'b.name AS group_name',
        ]);
        $query->from('el_languages AS a');
        $query->leftJoin('el_languages_groups AS b', 'b.id', '=', 'a.groups_id');
        $query->orderBy('a.id', 'DESC');

        $this->count = $query->count();
        return $query;
    }

    public function headings(): array
    {
        $title = [];
        $title[] = 'STT';
        $title[] = 'Từ khóa'.PHP_EOL.'(KHÔNG CHỈNH SỬA)';
        $title[] = 'Nhóm';
        $title[] = 'Ghi chú';

        $lang_types = LanguagesType::get(['name']);
        foreach ($lang_types as $lang){
            $title[] = $lang->name;
        }
        
        return [
            $title,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->getDelegate()->getStyle('A1:E1')->applyFromArray([
                    'font' => [
                        'size'      =>  13,
                        'bold'     => true,
                    ],
                ])->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('DDDDDD');


                $event->sheet->getDelegate()->getStyle('A1:E1')->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                    'font' => [
                        'name'      => 'Arial',
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER
                    ],
                ]);

                $event->sheet->getDelegate()->getColumnDimension('C')->setAutoSize(false)->setWidth(50);
                $event->sheet->getDelegate()->getColumnDimension('D')->setAutoSize(false)->setWidth(50);
                $event->sheet->getDelegate()->getColumnDimension('E')->setAutoSize(false)->setWidth(50);

            },

        ];
    }

}
