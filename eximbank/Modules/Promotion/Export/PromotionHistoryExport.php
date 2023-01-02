<?php
namespace Modules\Promotion\Export;

use App\Models\Profile;
use Modules\UserPoint\Entities\UserPointResult;
use Modules\UserPoint\Entities\UserPointItem;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class PromotionHistoryExport implements FromQuery, WithHeadings, ShouldAutoSize, WithMapping, WithEvents
{
    use Exportable;
    protected $index = 0;
    protected $count = 0;

    public function __construct($area, $unit, $title, $status, $search)
    {
        $this->status = $status;
        $this->unit = $unit;
        $this->title = $title;
        $this->search = $search;
        $this->area = $area;
    }

    public function map($row): array
    {
        $this->index++;
        if ($row->type == 6) {
            if ($row->setting_id == 1) {
                $row->name = 'Đánh giá sao thư viện';
            } else if ($row->setting_id == 2) {
                $row->name = 'Nhận điểm thưởng xem thư viện';
            } else {
                $row->name = 'Nhận điểm thưởng tải về thư viện';
            }
        } elseif($row->type == 7 && !empty($row->ref)) {
            $row->name = 'Nhận điểm thưởng khi đạt đủ mốc bình luận bài viết diễn đàn';
        } elseif ($row->type == 8 && !empty($row->ref)) {
            if ($row->setting_id == 1) {
                $row->name = 'Nhận điểm thưởng khi đạt đủ lượt xem video học liệu đào tạo';
            } else if ($row->setting_id == 2) {
                $row->name = 'Nhận điểm thưởng khi đạt đủ lượt thích video học liệu đào tạo';
            } else {
                $row->name = 'Nhận điểm thưởng khi đạt đủ lượt bình luận video học liệu đào tạo';
            }
        } elseif ($row->type == 10) {
            if (!empty($row->item_id)) {
                $row->name = 'Nhận điểm thưởng tạo góp ý';
            } else {
                $row->name = 'Nhận điểm thưởng khi đăng nhập';
            }
        } else {
            if($row->pkey == 'quiz_complete'){
                $row->name = 'Hoàn thành kỳ thi';
            }elseif($row->pkey == 'online_activity_complete'){
                $row->name = 'Hoàn thành hoạt động';
            }else{
                $item= UserPointItem::where("ikey","=",$row->pkey)->first();
                $row->name = $item->name;
            }
        }

        $row->datecreated = get_date($row->created_at, 'd/m/Y');

        switch ($row->type_promotion) {
            case 0:
                $row->type_promotion = 'Learn to earn'; break;
            case 1:
                $row->type_promotion = 'Click to earn'; break;
        }

        return [
            $this->index,
            $row->code,
            $row->full_name,
            $row->name,
            $row->content,
            $row->datecreated,
            $row->type_promotion,
            $row->point,
        ];
    }

    public function query(){
        $query = UserPointResult::query();
        $query->select([
            'a.*',
            'b.code',
            'b.full_name',
        ]);
        $query->from('el_userpoint_result AS a');
        $query->join('el_profile_view AS b', 'b.user_id', '=', 'a.user_id');
        // $query->where('b.user_id', '>', 2);
        $query->where('b.type_user', '=', 1);
        $query->where('b.status_id', '=', 1);

        if ($this->search) {
            $query->where(function ($sub_query) {
                $sub_query->orWhere('b.full_name', 'like', '%'. $this->search .'%');
                $sub_query->orWhere('b.code', 'like', '%'. $this->search .'%');
            });
        }

        if (!is_null($this->status)) {
            $query->where('b.status', '=', $this->status);
        }

        if ($this->title) {
            $query->where('b.title_id', '=', $this->title);
        }

        if ($this->unit) {
            $query->where('b.unit_idid', '=',  $this->unit);
        }

        $query->orderBy('b.user_id', 'ASC');
        $this->count = $query->count();
        return $query;
    }

    public function headings(): array
    {
        return [
            ['THỐNG KÊ LỊCH SỬ ĐIỂM'],
            [
                trans('latraining.stt'),
                trans('latraining.employee_code '),
                trans('latraining.fullname'),
                'Hoạt động',
                'Nội dung',
                'Ngày đạt',
                'Loại điểm thưởng',
                'Điểm',
            ]
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->getDelegate()->mergeCells('A1:H1');
                $event->sheet->getDelegate()->getStyle('A1:H1')->applyFromArray([
                    'font' => [
                        'size'      =>  13,
                        'bold'      =>  true,
                    ],
                ])->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor() ->setARGB('DDDDDD');

                $event->sheet->getDelegate()->getStyle('A1:H'.(2 + $this->count).'')->applyFromArray([
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
