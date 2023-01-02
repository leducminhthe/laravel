<?php

namespace Modules\Config\Http\Controllers;

use App\Models\Categories\Unit;
use App\Models\Config;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Modules\Config\Entities\ConfigEmail;

class ConfigController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()){
            $search = $request->input('search');

            $sort = $request->input('sort', 'id');
            $order = $request->input('order', 'desc');
            $offset = $request->input('offset', 0);
            $limit = $request->input('limit', 20);
            $query = ConfigEmail::query();
            $query->select('el_unit.name as unit_company','config_email.*');
            $query->join('el_unit','el_unit.id','=','config_email.company');
            $data['total'] = $query->count();
            $data['rows'] = $query->get();
            foreach ($data['rows'] as $index => $row) {
                $row->edit_url = route('backend.config.email.edit',['id'=>$row->id]);
            }
            json_result(['total' => $data['total'], 'rows' => $data['rows']]);
        }
        return view('config::backend.index');
    }

    public function formRefer()
    {
        $grade_refer = Config::where('name','=','grade_refer')->value('value');
        $grade_refered = Config::where('name','=','grade_refered')->value('value');
        $point_course_referer = Config::where('name','=','point_course_referer')->value('value');
        $point_course_referer_finish = Config::where('name','=','point_course_referer_finish')->value('value');
        return view('config::backend.refer',
            [
                'grade_refer'=>$grade_refer,
                'grade_refered'=>$grade_refered,
                'point_course_referer'=>$point_course_referer,
                'point_course_referer_finish'=>$point_course_referer_finish,
            ]
        );
    }

    public function saveRefer(Request $request)
    {
        Config::updateOrCreate([
            'name'=>'grade_refer'
        ],[
            'name'=>'grade_refer',
            'value'=>$request->input('grade_refer')
        ]);

        Config::updateOrCreate([
            'name'=>'grade_refered'
        ],[
            'name'=>'grade_refered',
            'value'=>$request->input('grade_refered')
        ]);

        Config::updateOrCreate([
            'name'=>'point_course_referer'
        ],[
            'name'=>'point_course_referer',
            'value'=>$request->input('point_course_referer')
        ]);

        Config::updateOrCreate([
            'name'=>'point_course_referer_finish'
        ],[
            'name'=>'point_course_referer_finish',
            'value'=>$request->input('point_course_referer_finish')
        ]);

        json_message('Cập nhật thành công','success');
    }

    public function formEmail(Request $request)
    {
        $id = $request->id;
        $email = ConfigEmail::firstOrNew(['id'=>$id]);
        $unit = $email? Unit::find($email->company): null;

        return view('config::backend.form',[
            'unit'=>$unit,
            'email'=>$email
        ]);
    }

    public function saveEmail(Request $request)
    {
        if(!$request->id){
            $check = ConfigEmail::whereCompany($request->company);
            if($check->exists()){
                json_message('Công ty đã thiết lập mail', 'error');
            }
        }

        $model = ConfigEmail::firstOrNew(['id'=>$request->id]);
        $model->fill($request->all());
        $model->send_noty = $request->send_noty ? $request->send_noty : 0;
        if ($model->save()){
            return response()->json([
                'status' => 'success',
                'message' => trans('backend.save_success'),
                'redirect' => route('backend.config.email.index')
            ]);
        }
        json_message(trans('laother.save_error'), 'error');
    }

    public function testSendMail(Request $request) {
        $this->validateRequest([
            'email' => 'required',
        ], $request, [
            'email' => 'Email test',
        ]);
        $company = $request->company;
        $emails = explode(',', $request->post('email'));
        $subject = 'Email gửi từ hệ thống LMS';
        $content = 'Đây là email gửi từ hệ thống LMS. Nếu bạn nhận được email này tức là cấu hình mail đã chính xác.';

        try {
            setMailConfig($company);
            $smtp = 'smtp_'.$company;
            Mail::mailer($smtp)->send('mail.default', [
                'content' => $content
            ], function ($message) use ($emails, $subject) {
                $message->to($emails)->subject($subject);
            });

            if (Mail::mailer($smtp)->failures()) {
                return response()->json([
                    'status' => 'error',
                    'message' => Mail::mailer($smtp)->failures()[0],
                ]);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Gửi mail thành công! Vui lòng kiểm tra mail test.',
            ]);
        }
        catch(\Exception $e){
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function remove(Request $request)
    {
        $ids = $request->input('ids', null);
        ConfigEmail::destroy($ids);
        json_result([
            'status' => 'success',
            'message' => trans('laother.delete_success'),
        ]);
    }
}
