<?php

namespace Modules\SalesKit\Entities;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;

class SalesKit extends BaseModel
{
    protected $table = 'el_sales_kit';
    protected $fillable = [
        'name',
        'image',
        'views',
        'download',
        'status',
        'current_number',
        'description',
        'category_id',
        'created_by',
        'updated_by',
        'attachment',
        'phone_contact',
        'name_author',
    ];
    protected $primaryKey = 'id';

    public static function getAttributeName() {
        return [
            'name'=>'Tên sách',
            'image'=>'Ảnh',
            'views'=>'Lượt xem',
            'download'=>'download',
            'status'=>trans('labutton.enable'),
            'current_number'=>'Số lượng hiện có',
            'description'=>'Mô tả',
            'category_id'=>'Id sách',
            'created_by'=> trans("latraining.created_at"),
            'updated_by'=>'Ngày sửa',
            'phone_contact'=>'Số điện thoại liên hệ',
            'name_author' => 'Tên tác giả',
            'attachment' => 'Tệp tin',
        ];
    }

    public function getLinkDownload() {
        return link_download('uploads/'.$this->attachment);
    }

    public function isFilePdf() {
        if (empty($this->attachment)) {
            return false;
        }

        $extention = pathinfo($this->attachment, PATHINFO_EXTENSION);
        if ($extention == 'pdf' || $extention == 'PDF') {
            return true;
        }

        return false;
    }

    public function getLinkView() {
        if (!$this->isFilePdf()) {
            return false;
        }
        return upload_file($this->attachment);
    }
}
