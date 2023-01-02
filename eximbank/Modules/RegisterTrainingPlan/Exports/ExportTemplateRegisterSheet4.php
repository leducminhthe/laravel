<?php
namespace Modules\RegisterTrainingPlan\Exports;

use App\Models\Categories\Area;
use App\Models\Categories\Subject;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExportTemplateRegisterSheet4 implements ShouldAutoSize, FromQuery, WithMapping, WithStartRow, WithTitle, WithEvents
{
    use Exportable;
    protected $index = 0;
    protected $total_percent = 0;
    protected $count = 0;

    public function __construct()
    {
    }

    public function query()
    {
        $query = Area::whereStatus(1)->orderBy('id', 'ASC');

        return $query;
    }

    public function map($report): array
    {
        return [
            $report->name,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->getDelegate()->setSheetState(Worksheet::SHEETSTATE_HIDDEN);
            },
        ];
    }

    public function startRow(): int
    {
        return 1;
    }

    public function title(): string
    {
        return 'Sheet4';
    }
}
