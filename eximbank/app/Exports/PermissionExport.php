<?php
namespace App\Exports;

use App\Models\User;
use App\Models\Profile;
use App\Models\Role;
use App\Models\Permission;
use App\Models\PermissionType;
use App\Models\RolePermissionType;


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

class PermissionExport implements FromQuery, WithHeadings, ShouldAutoSize, WithMapping, WithEvents
{
    use Exportable;
    protected $index = 0;
    protected $count = 0;

    public function map($row): array
    {
        $this->index++;

        $permissiotn_type = RolePermissionType::query()
        ->from('el_role_permission_type as a')
        ->join('el_permission_type as b', 'b.id', '=', 'a.permission_type_id')
        ->where(['a.role_id' => $row->id, 'a.permission_id' => $row->permission_id])
        ->first(['b.name']);

        if(!isset($permissiotn_type)) {
            $parent_name = Permission::where('name', $row->parent)->first(['description']);
        }

        return [
            $this->index,
            $row->name,
            $row->role_name,
            $permissiotn_type->name,
            $parent_name->description,
        ];
    }

    public function query()
    {
        $query = Role::query();
        $query->select([
            'a.id',
            'a.name',
            'a.description',
            'c.parent',
            'c.id AS permission_id',
            'c.description AS role_name',
        ]);
        $query->from('el_roles AS a');
        $query->Join('el_role_has_permissions AS b', 'b.role_id', '=', 'a.id');
        $query->Join('el_permissions AS c', 'c.id', '=', 'b.permission_id');
        $query->orderBy('a.id', 'ASC');
        $query->orderBy('b.permission_id', 'ASC');
        $this->count = $query->count();
        return $query;
    }

    public function headings(): array
    {
        return [
            ['Vai trò phân quyền'],
            [
                'STT',
                'Tên vai trò',
                'Tên quyền',
                'Nhóm quyền',
                'Thuộc phân quyền',
            ]
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->getDelegate()->mergeCells('A1:E1');

                $event->sheet->getDelegate()->getStyle('A1:E1')->applyFromArray([
                    'font' => [
                        'size'      =>  13,
                        'bold'     => true,
                    ],
                ])->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('DDDDDD');

                $event->sheet->getDelegate()
                ->getStyle('A1:E'.(2 + $this->count).'')
                ->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                    'font' => [
                        'name'      => 'Arial',
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                    ],
                ]);

            },

        ];
    }

}
