<?php
namespace Modules\RegisterTrainingPlan\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ExportTemplateRegister implements WithMultipleSheets
{
    use Exportable;

    public function __construct()
    {
    }

    public function sheets(): array
    {
        $sheet = [];

        $sheet[] = new ExportTemplateRegisterSheet1();
        $sheet[] = new ExportTemplateRegisterSheet2();
        $sheet[] = new ExportTemplateRegisterSheet3();
        $sheet[] = new ExportTemplateRegisterSheet4();

        return $sheet;
    }
}
