<?php

namespace Modules\Certificate\Http\Controllers;

use App\Models\Profile;
use App\Scopes\DraftScope;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Models\Categories\SubjectType;
use App\Models\CourseView;
use Modules\Certificate\Entities\Certificate;
use Modules\Certificate\Entities\CertificateSetting;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Online\Entities\OnlineCourse;
use Modules\Certificate\Entities\CertificateDesign;

class CertificateController extends Controller
{
    public function index()
    {
        return view('certificate::backend.certificate.index',[
        ]);
    }
    public function getData(Request $request)
    {
        $search = $request -> input('search');
        $sort = $request ->input('sort','id');
        $order = $request ->input('order','desc');
        $offset =$request ->input('offset',0);
        $limit = $request ->input('limit',20);

        Certificate::addGlobalScope(new DraftScope());
        $query = Certificate::query();

        if($search){
            $query->where(function($sub_query) use ($search){
                $sub_query->orWhere('code','like','%' . $search . '%');
                $sub_query->orWhere('name','like','%' . $search . '%');
            });
        }

        $count = $query->count();
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query ->get();
        foreach ($rows as $row) {
            $created_by = $row->created_by ? $row->created_by : 2;
            $updated_by = $row->updated_by ? $row->updated_by : 2;
            $row->info = route('backend.get_user_info',['created' => $created_by, 'updated' => $updated_by]);
            $row->image = image_file($row->image);

            $row->type = $row->type==1 ? trans('lamenu.course') : trans('latraining.subject_type');
            $row->design = '<a href="'.route('module.certificate.design',["id"=>$row->id]).'"><i class="fa fa-file-image"></i></a>';
        }
        json_result(['total' => $count, 'rows' => $rows]);

    }

    public function form(Request $request) {
        $model = Certificate::where('id', $request->id)->first();
        $path_image = image_file($model->image);
        $logo = image_file($model->logo);
        $signature = image_file($model->signature);

        $check_has_cert = 0;
        $course = CourseView::where('cert_code', $request->id);
        $subject_type = SubjectType::where('certificate_id', $request->id);

        if($course->exists() || $subject_type->exists()){
            $check_has_cert = 1;
        }

        json_result([
            'model' => $model,
            'image' => $path_image,
            'logo' => $logo,
            'signature' => $signature,
            'check_has_cert' => $check_has_cert,
        ]);
    }

    public function save(Request $request) {
        $this->validateRequest ([
            'code' => 'required',
            'name' => 'required',
            'image' => 'required|string',
            // 'user' => 'required',
            // 'position' => 'required',
            // 'signature' => 'required|string',
            // 'location' => 'required',
            // 'logo' => 'required|string',
            'type' => 'required',
        ], $request, Certificate::getAttributeName());

        $model = Certificate::firstOrNew(['id' => $request->id]);
        $model->fill($request->all());
        $model->image = path_upload($request->image);
        // $model->signature = upload_image([],$request->signature);
        // $model->logo = upload_image(config('image.sizes.logo'),$request->logo);

        if ($model->save()) {
            json_result([
                'status' => 'success',
                'message' => trans('laother.successful_save'),
            ]);
        }
        json_message(trans('laother.save_error'), 'error');
    }

    public function remove(Request $request){
        $ids = $request->ids;

        $arr_message = [];
        $arr_id_delete = [];

        foreach($ids as $id){
            $cert = Certificate::find($id);
            $course = CourseView::where('cert_code', $id);
            $subject_type = SubjectType::where('certificate_id', $id);

            if($course->exists()){
                $courses = $course->get(['code', 'name']);
                foreach($courses as $item){
                    $arr_message[] = 'Chứng chỉ "'. $cert->name .'" được thêm trong khoá học: '. $item->name .' ('. $item->code .')';
                }
            }elseif($subject_type->exists()){
                $subject_type = $subject_type->get();
                foreach($subject_type as $item){
                    $arr_message[] = 'Chứng chỉ "'. $cert->name .'" được thêm trong Chương trình đào tạo: '. $item->name .' ('. $item->code .')';
                }
            } else{
                $arr_id_delete[] = $id;

                $arr_message[] = 'Chứng chỉ "'. $cert->name .'" đã được xoá thành công';
            }
        }

        Certificate::destroy($arr_id_delete);

        json_message($arr_message);
    }

    public function design(Request $request)
    {
        $model = Certificate::where('id', $request->id)->first();
        $path_image = image_file($model->image);
        $logo = image_file($model->logo);
        $signature = image_file($model->signature);

        $itemDesigns = CertificateDesign::where("certificate_id",$request->id)->get();
        $arrItems = array();
        // Thông tin: arr = ['left', 'top', 'align', 'font_size', 'status', 'color', 'value']

        $arrItems["fullname"] = [0, 50, 'left', 50 , 1, '#bd8e34', '{HỌ-VÀ-TÊN}'];
        $arrItems["date"] = [0, 200, 'left', 18, 1, '#f90606', '{TP/NGÀY/THÁNG/NĂM}'];
        $arrItems["title"] = [0, 150, 'left', 18, 1, '#f90606', '{CHỨC-DANH}'];
        // $arrItems["user"] = [0, 200, 'left', 18, 1, '#f90606', {USER}];
        // $arrItems["signature"] = [0, 170, 'left', null, 1, $logo, null];
        // $arrItems["logo"] = [0, 50, 'left', null, 1, $signature, null];

        if($model->type == 1) {
            $arrItems["course_name"] = [0, 130, 'left', 18, 1, '#f90606', '{TÊN-KHÓA-HỌC}'];
            $arrItems["course_code"] = [0, 150, 'left', 18, 1, '#f90606', '{MÃ-KHÓA-HỌC}'];
        } else {
            $arrItems["subject_type"] = [0, 100, 'left', 18, 1, '#f90606', '{CHƯƠNG-TRÌNH-ĐÀO-TẠO}'];
        }
        
        foreach ($itemDesigns as $v){
            $arrItems[$v->name] = [$v->pleft, $v->ptop, $v->align, $v->font_size, $v->status, $v->color, $v->value];
        }

        if($model->type == 1) {
            unset($arrItems['subject_type']);
        } else {
            unset($arrItems['course_name']);
            unset($arrItems['course_code']);
        }

        $alignDesgin = array(
            "left"=>"Canh trái",
            "center"=>"Canh giữa",
            "right"=>"Canh phải"
        );

        return view('certificate::backend.certificate.design',[
            'model' => $model,
            'image' => $path_image,
            // 'logo' => $logo,
            // 'signature' => $signature,
            'items' => $arrItems,
            'aligndesign' => $alignDesgin,
        ]);
    }

    public function saveDesign(Request $request) {
        foreach ($request->input_item_pos as $key => $input_item_pos) {
            $pos = explode('-', $input_item_pos);
            $values[] = [
                'certificate_id'  => $request->id_cert,
                'name'  => $request->input_item_name[$key],
                'type' => '2',
                'pleft'      => $pos[0],
                'ptop' => $pos[1],
                'status' => $request->input_item_status[$key],
                'align' => $request->input_item_location[$key],
                'font_size' => $request->input_item_font_szie[$key],
                'color' => $request->input_item_color[$key],
                'value' => $request->input_item_value[$key],
            ];
        }

        CertificateDesign::where("certificate_id",$request->id_cert)->delete();

        \DB::table('el_certificate_design')->insert(
            $values
        );

        json_result([
            'status' => 'success',
            'message' => trans('laother.successful_save'),
            'redirect' => route('module.certificate.design', ['id' => $request->id]),
        ]);
    }

}
