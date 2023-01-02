<?php

namespace Modules\PlanSuggest\Entities;

use App\Models\BaseModel;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class PlanSuggest extends BaseModel
{
    use Cachable;
    protected $table = 'el_plan_suggest';
    protected $fillable = [
        'intend',
        'subject_name',
        'purpose',
        'duration',
        'title',
        'amount',
        'teacher',
        'attach',
        'attach_report',
        'students',
        'note',
        'unit_code',
        'created_by',
        'approved_by',
        'status',
        'content',
        'type',
        'training_form',
        'start_date',
        'end_date',
        'address',
        'cost',
    ];
    protected $primaryKey = 'id';

    public static function getAttributeName() {
        return [
            'start_date' => 'Thời gian bắt đầu',
            'end_date' => 'Thời gian kết thúc',
            'intend' => 'Thời gian dự kiến',
            'subject_name' => 'Tên học phần',
            'purpose' => 'Mục tiêu đào tạo',
            'duration' => trans('lareport.duration'),
            'title' => 'Đối tượng học',
            'amount' => 'Số lượng học viên',
            'teacher' => trans('lareport.teacher'),
            'attach' => 'File đính kèm',
            'attach_report' => 'File đính kèm báo cáo',
            'students' => 'Danh sách học viên',
            'note' => trans('latraining.note') ,
            'unit_code'=> 'Mã đơn vị',
            'created_by'=> 'Người đề xuất',
            'approved_by'=> trans('latraining.approved_by'),
            'status'=> trans("latraining.status"),
            'content' => trans("latraining.content"),
            'type' => 'Hình thức',
        ];
    }
}
