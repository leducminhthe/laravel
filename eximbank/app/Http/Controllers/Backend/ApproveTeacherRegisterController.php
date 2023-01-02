<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Scopes\DraftScope;
use App\Models\Categories\TrainingTeacherRegister;
use Modules\Offline\Entities\OfflineSchedule;
use Carbon\Carbon;
use App\Models\Categories\TrainingType;
use App\Models\Categories\TrainingLocation;
use App\Models\Categories\Subject;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Offline\Entities\OfflineRegister;
use App\Models\Profile;
use Modules\ReportNew\Entities\ReportNewExportBC11;
use Modules\Online\Entities\OnlineHistoryEdit;
use Illuminate\Support\Facades\Auth;
use App\Models\Categories\TrainingTeacher;
use Modules\Offline\Entities\OfflineTeacher;
use Modules\Notify\Entities\Notify;
use Modules\AppNotification\Helpers\AppNotification;

class ApproveTeacherRegisterController extends Controller
{
    public function index() {
        return view('backend.approve_teacher_register.index');
    }

    public function getData(Request $request) {
        $search = $request->search;
        $sort = $request->get('sort', 'name');
        $order = $request->get('order', 'desc');
        $offset = $request->get('offset', 0);
        $limit = $request->get('limit', 20);

        $query = TrainingTeacherRegister::query();
        $query->select([
            'a.*',
            'b.name',
            'b.code',
            'c.name as course_name',
            'c.code as course_code',
            'c.start_date',
            'c.end_date',
        ]);
        $query->from('el_training_teacher_register_schedule as a');
        $query->leftJoin('el_training_teacher as b', 'b.id', '=', 'a.teacher_id');
        $query->leftJoin('el_offline_course as c', 'a.course_id', '=', 'c.id');
        $query->where('a.status', 1);

        if($search) {
            $query->where(function($sub) {
                $sub->where('b.code','like', '%'. $search . '%');
                $sub->orWhere('b.name','like', '%'. $search . '%');
                $sub->orWhere('c.name','like', '%'. $search . '%');
            });
        }

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        foreach ($rows as $row) {
            $row->dateRegister = get_date($row->start_date, 'd/m/Y') . ' => ' . get_date($row->end_date, 'd/m/Y');
            $row->note = $row->note ? $row->note : '';
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function remove(Request $request) {
        $ids = $request->post('ids', []);
        foreach($ids as $id) {
            $model = TrainingTeacherRegister::findOrFail($id);
            $model->status = 0;
            $model->approve = 0;
            $model->save();
            OfflineTeacher::where('course_id', $model->course_id)->where('teacher_id', $model->teacher_id)->delete();
        }
        
        json_message(trans('laother.delete_success'));
    }

    public function approve(Request $request) {
        $this->validateRequest([
            'ids' => 'required',
            'status' => 'required|in:0,1'
        ], $request, [
            'ids' => trans('laprofile.rank'),
        ]);

        $ids = $request->input('ids', null);
        $status = $request->input('status', 0);
        $teacherType = $request->teacherType;
        $costTeacher = $request->costTeacher;

        foreach ($ids as $id) {
            $model = TrainingTeacherRegister::findOrFail($id);
            $model->approve = $status;
            $model->save();
            $course = OfflineCourse::find($model->course_id, ['name','code']);

            if($status == 1) {
                $saveTeacherClass = new OfflineTeacher();
                $saveTeacherClass->course_id = $model->course_id;
                $saveTeacherClass->teacher_id = $model->teacher_id;
                $saveTeacherClass->save();
                $subject_notify = 'Thông báo duyệt đăng ký giảng dạy';
                $content_notify = 'Quản trị đã duyệt đăng ký giảng dạy lớp: '. $course->name. ' ('. $course->code .')';
            } else {
                OfflineTeacher::where('course_id', $model->course_id)->where('teacher_id', $model->teacher_id)->delete();
                $subject_notify = 'Thông báo từ chối đăng ký giảng dạy';
                $content_notify = 'Quản trị đã từ chối đăng ký giảng dạy lớp: '. $course->name. ' ('. $course->code .')';
            }

            $noty = new Notify();
            $noty->user_id = $model->user_id;
            $noty->subject = $subject_notify;
            $noty->content = $content_notify;
            $noty->url = '';
            $noty->created_by = 0;
            $noty->save();

            $content = \Str::words(html_entity_decode(strip_tags($content_notify)), 10);
            $redirect_url = route('module.notify.view', [
                'id' => $noty->id,
                'type' => 1
            ]);

            $notification = new AppNotification();
            $notification->setTitle($noty->subject);
            $notification->setMessage($content);
            $notification->setUrl($redirect_url);
            $notification->add($model->user_id);
            $notification->save();
        }

        json_result([
            'status' => 'success',
            'message' => trans('laother.successful_save'),
        ]);
    }

    public function saveNote(Request $request)
    {
        $saveNote = TrainingTeacherRegister::find($request->id);
        $saveNote->note = $request->note;
        $saveNote->save();

        json_result([
            'status' => 'success',
            'message' => trans('laother.successful_save'),
        ]);
    }
}
