<?php
namespace Modules\ReportNew\Export;

use App\Models\Config;
use App\Models\LogoModel;
use App\Models\Categories\Absent;
use App\Models\Categories\AbsentReason;
use App\Models\Categories\Discipline;
use App\Models\Categories\LevelSubject;
use App\Models\Categories\Position;
use App\Models\Categories\StudentCost;
use App\Models\Categories\TrainingObject;
use App\Models\Categories\TrainingType;
use App\Models\Categories\UnitManager;
use App\Scopes\CompanyScope;
use Illuminate\Support\Facades\Request;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Modules\Certificate\Entities\Certificate;
use Modules\Offline\Entities\OfflineCourseCost;
use Modules\Offline\Entities\OfflineRegister;
use Modules\Offline\Entities\OfflineSchedule;

use Modules\Offline\Entities\OfflineStudentCost;
use Modules\ReportNew\Entities\BC14;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class BC14Export implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize,  WithEvents, WithStartRow, WithDrawings
{
    use Exportable;
    protected $index = 0;
    protected $count = 0;
    protected $count_title = 0;

    public function __construct($param)
    {
        $this->name_obj = $param->name_obj;
    }

    public function query()
    {
        $query = BC14::{$this->name_obj}()->orderBy('id', 'asc');

        $this->count = $query->count();
        return $query;
    }
    public function map($row): array
    {
        $obj = $this->{'map'.$this->name_obj}($row);

        return $obj;
    }

    public function headings(): array
    {
        $title_arr = $this->{'headings'.$this->name_obj}();

        return [
            [],
            [],
            [],
            [],
            [],
            ['EXPORT DANH MỤC'],
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
        if ($logo) {
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

    public function headingsUnit(){
        $this->count_title = 6;
        return [
            trans('latraining.stt'),
            'Mã đơn vị',
            'Đơn vị',
           trans('lareport.unit_management'),
            'Loại đơn vị',
            'Người quản lý',
        ];
    }
    public function mapUnit($row){
        $this->index++;
        $unit_manager = UnitManager::query()
            ->select([
                \DB::raw('CONCAT(user_code ,\' - \', lastname, \' \', firstname) as fullname')
            ])
            ->from('el_unit_manager as a')
            ->leftJoin('el_profile as b', 'b.code', '=', 'a.user_code')
            ->where('a.unit_code', '=', $row->code)
            ->pluck('fullname')->toArray();

        return [
            $this->index,
            $row->code,
            $row->name,
            $row->parent_name,
            $row->type_name,
            implode('; ', $unit_manager),
        ];
    }

    public function headingsArea(){
        $this->count_title = 4;
        return [
            trans('latraining.stt'),
            'Mã',
            'Tên',
            'Địa điểm quản lý',
        ];
    }
    public function mapArea($row){
        $this->index++;
        return [
            $this->index,
            $row->code,
            $row->name,
            $row->parent_name,
        ];
    }

    public function headingsUnitType(){
        $this->count_title = 2;
        return [
            trans('latraining.stt'),
            'Loại đơn vị'
        ];
    }
    public function mapUnitType($row){
        $this->index++;
        return [
            $this->index,
            $row->name,
        ];
    }

    public function headingsTitles(){
        $this->count_title = 5;
        return [
            trans('latraining.stt'),
            'Mã chức danh',
            'Tên chức danh',
            'Nhóm chức danh',
            trans('lareport.status ')
        ];
    }
    public function mapTitles($row){
        $this->index++;
        switch ($row->group) {
            case 'CH': $row->group = 'Cửa hàng'; break;
            case 'CNT': $row->group = 'Chi nhánh tỉnh'; break;
            case 'VP': $row->group = 'Văn Phòng'; break;
            case 'NM': $row->group = 'Công ty con nhà máy'; break;
            default: $row->group = ''; break;
        }

        return [
            $this->index,
            $row->code,
            $row->name,
            $row->group,
            ($row->status == 1) ? trans('labutton.enable') : trans('labutton.disable'),
        ];
    }

    public function headingsCert(){
        $this->count_title = 3;
        return [
            trans('latraining.stt'),
            'Mã trình độ',
            'Tên trình độ',
        ];
    }
    public function mapCert($row){
        $this->index++;

        return [
            $this->index,
            $row->code,
            $row->name,
        ];
    }

    public function headingsPosition(){
        $this->count_title = 4;
        return [
            trans('latraining.stt'),
            'Mã chức vụ',
            'Tên chức vụ',
           trans('lareport.status ')
        ];
    }
    public function mapPosition($row){
        $this->index++;
        return [
            $this->index,
            $row->code,
            $row->name,
            ($row->status == 1) ? trans('labutton.enable') : trans('labutton.disable'),
        ];
    }

    public function headingsTrainingProgram(){
        $this->count_title = 4;
        return [
            trans('latraining.stt'),
            'Mã chủ đề',
            trans('latraining.training_program'),
            trans('lareport.status ')
        ];
    }
    public function mapTrainingProgram($row){
        $this->index++;
        return [
            $this->index,
            $row->code,
            $row->name,
            ($row->status == 1) ? trans('labutton.enable') : trans('labutton.disable'),
        ];
    }

    public function headingsLevelSubject(){
        $this->count_title = 4;
        return [
            trans('latraining.stt'),
            'Mã',
            'Mảng nghiệp vụ',
            trans('lareport.status ')
        ];
    }
    public function mapLevelSubject($row){
        $this->index++;
        return [
            $this->index,
            $row->code,
            $row->name,
            ($row->status == 1) ? trans('labutton.enable') : trans('labutton.disable'),
        ];
    }

    public function headingsSubject(){
        $this->count_title = 6;
        return [
            trans('latraining.stt'),
            trans('laprofile.subject_code'),
            trans('laprofile.subject_name'),
            'Mảng nghiệp vụ',
            trans('latraining.training_program'),
            trans('lareport.status ')
        ];
    }
    public function mapSubject($row){
        $this->index++;
        return [
            $this->index,
            $row->code,
            $row->name,
            $row->level_subject_name,
            $row->parent_name,
            ($row->status == 1) ? trans('labutton.enable') : trans('labutton.disable'),
        ];
    }

    public function headingsTrainingLocation(){
        $this->count_title = 6;
        return [
            trans('latraining.stt'),
            'Mã địa điểm đào tạo',
            'Tên địa điểm đào tạo',
            'Tỉnh thành',
            'Quận huyện',
            trans('lareport.status ')
        ];
    }
    public function mapTrainingLocation($row){
        $this->index++;
        return [
            $this->index,
            $row->code,
            $row->name,
            $row->province,
            $row->district,
            ($row->status == 1) ? trans('labutton.enable') : trans('labutton.disable'),
        ];
    }

    public function headingsTrainingForm(){
        $this->count_title = 3;
        return [
            trans('latraining.stt'),
            'Mã',
            'Tên',
        ];
    }
    public function mapTrainingForm($row){
        $this->index++;
        return [
            $this->index,
            $row->code,
            $row->name,
        ];
    }

    public function headingsTrainingType(){
        $this->count_title = 4;
        return [
           trans('latraining.stt'),
            'Mã hình thức đào tạo',
            'Tên hình thức đào tạo',
           trans('lareport.status ')
        ];
    }
    public function mapTrainingType($row){
        $this->index++;
        return [
            $this->index,
            $row->code,
            $row->name,
            ($row->status == 1) ? trans('labutton.enable') : trans('labutton.disable'),
        ];
    }

    public function headingsTrainingObject(){
        $this->count_title = 4;
        return [
            trans('latraining.stt'),
            'Mã đối tượng đào tạo',
            'Tên đối tượng đào tạo',
            trans('lareport.status ')
        ];
    }
    public function mapTrainingObject($row){
        $this->index++;
        return [
            $this->index,
            $row->code,
            $row->name,
            ($row->status == 1) ? trans('labutton.enable') : trans('labutton.disable'),
        ];
    }

    public function headingsAbsent(){
        $this->count_title = 4;
        return [
           trans('latraining.stt'),
            'Mã loại nghỉ',
            'Tên loại nghỉ',
            trans('lareport.status ')
        ];
    }
    public function mapAbsent($row){
        $this->index++;
        return [
            $this->index,
            $row->code,
            $row->name,
            ($row->status == 1) ? trans('labutton.enable') : trans('labutton.disable'),
        ];
    }

    public function headingsDiscipline(){
        $this->count_title = 4;
        return [
           trans('latraining.stt'),
            'Mã vi phạm',
            'Tên vi phạm',
           trans('lareport.status ')
        ];
    }
    public function mapDiscipline($row){
        $this->index++;
        return [
            $this->index,
            $row->code,
            $row->name,
            ($row->status == 1) ? trans('labutton.enable') : trans('labutton.disable'),
        ];
    }

    public function headingsAbsentReason(){
        $this->count_title = 4;
        return [
           trans('latraining.stt'),
            'Mã lý do vắng mặt',
            'Tên lý do vắng mặt',
           trans('lareport.status ')
        ];
    }
    public function mapAbsentReason($row){
        $this->index++;
        return [
            $this->index,
            $row->code,
            $row->name,
            ($row->status == 1) ? trans('labutton.enable') : trans('labutton.disable'),
        ];
    }

    public function headingsQuizType(){
        $this->count_title = 2;
        return [
            trans('latraining.stt'),
            'Tên',
        ];
    }
    public function mapQuizType($row){
        $this->index++;
        return [
            $this->index,
            $row->name,
        ];
    }

    public function headingsTrainingCost(){
        $this->count_title = 3;
        return [
            trans('latraining.stt'),
            'Tên chi phí đào tạo',
            'Loại chi phí',
        ];
    }
    public function mapTrainingCost($row){
        $this->index++;
        switch ($row->type){
            case 1: $row->type = 'Chi phí tổ chức'; break;
            case 2: $row->type = 'Chi phí phòng đào tạo'; break;
            case 3: $row->type = 'Chi phí đào tạo bên ngoài'; break;
            case 4: $row->type = 'Chi phí giảng viên'; break;
        }

        return [
            $this->index,
            $row->name,
            $row->type,
        ];
    }

    public function headingsStudentCost(){
        $this->count_title = 3;
        return [
            trans('latraining.stt'),
            'Tên chi phí học viên',
           trans('lareport.status ')
        ];
    }
    public function mapStudentCost($row){
        $this->index++;
        return [
            $this->index,
            $row->name,
            ($row->status == 1) ? trans('labutton.enable') : trans('labutton.disable'),
        ];
    }

    public function headingsCommitMonth(){
        $this->count_title = 4;
        return [
           trans('latraining.stt'),
            'Từ',
            'Đến',
            'Tháng',
        ];
    }
    public function mapCommitMonth($row){
        $this->index++;
        return [
            $this->index,
            $row->min_cost,
            $row->max_cost,
            $row->month,
        ];
    }

    public function headingsTrainingTeacher(){
        $this->count_title = 5;
        return [
           trans('latraining.stt'),
            trans('lareport.lecture_name'),
            'Email giảng viên',
            trans('latraining.phone'),
            trans('lareport.status ')
        ];
    }
    public function mapTrainingTeacher($row){
        $this->index++;
        return [
            $this->index,
            $row->name,
            $row->email,
            $row->phone,
            ($row->status == 1) ? trans('labutton.enable') : trans('labutton.disable'),
        ];
    }

    public function headingsTeacherType(){
        $this->count_title = 3;
        return [
            trans('latraining.stt'),
            'Mã loại giảng viên',
            'Tên loại giảng viên',
        ];
    }
    public function mapTeacherType($row){
        $this->index++;
        return [
            $this->index,
            $row->code,
            $row->name,
        ];
    }

    public function headingsTrainingPartner(){
        $this->count_title = 7;
        return [
            trans('latraining.stt'),
            'Mã',
            'Tên đối tác',
            'Người liên hệ',
            'Địa chỉ',
            'Email',
           trans('latraining.phone'),
        ];
    }
    public function mapTrainingPartner($row){
        $this->index++;
        return [
            $this->index,
            $row->code,
            $row->name,
            $row->people,
            $row->address,
            $row->email,
            $row->phone,
        ];
    }

    public function headingsProvince(){
        $this->count_title = 3;
        return [
            trans('latraining.stt'),
            'Mã',
            'Tên tỉnh thành',
        ];
    }
    public function mapProvince($row){
        $this->index++;
        return [
            $this->index,
            $row->id,
            $row->name,
        ];
    }

    public function headingsDistrict(){
        $this->count_title = 4;
        return [
           trans('latraining.stt'),
            'Mã quận huyện',
            'Tên quận huyện',
            'Tỉnh thành',
        ];
    }
    public function mapDistrict($row){
        $this->index++;
        return [
            $this->index,
            $row->id,
            $row->name,
            $row->province,
        ];
    }
}
