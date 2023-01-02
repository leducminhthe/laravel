<?php

namespace Modules\Offline\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;

use Modules\Offline\Entities\OfflineCourse;
use Modules\Offline\Entities\OfflineCourseClass;
use Modules\Offline\Entities\OfflineTeacher;
use App\Models\Categories\TrainingTeacher;

class TeacherController extends Controller
{
    public function index($course_id, $class_id, Request $request) {
        if ($request->ajax()){
            $search = $request->input('search');
            $sort = $request->input('sort', 'id');
            $order = $request->input('order', 'desc');
            $offset = $request->input('offset', 0);
            $limit = $request->input('limit', 20);

            $query = OfflineTeacher::query();
            $query->select(['a.*', 'b.name as teacher_name', 'b.email as teacher_email', 'b.phone as teacher_phone']);
            $query->from('el_offline_course_teachers AS a');
            $query->leftJoin('el_training_teacher AS b', 'b.id', '=', 'a.teacher_id');
            $query->where(['a.course_id'=> $course_id,'a.class_id'=>$class_id]);

            if ($search) {
                $query->where('b.name', 'like', '%'. $search .'%');
            }

            $count = $query->count();
            $query->orderBy('b.'.$sort, $order);
            $query->offset($offset);
            $query->limit($limit);

            $rows = $query->get();

            json_result(['total' => $count, 'rows' => $rows]);
        }else {
            $course = OfflineCourse::find($course_id);
            $page_title = $course->name;
            $teachers = TrainingTeacher::get();
            $class = OfflineCourseClass::findOrFail($class_id);
            return view('offline::backend.teacher.index', [
                'page_title' => $page_title,
                'course' => $course,
                'teachers' => $teachers,
                'class' => $class
            ]);
        }
    }

    /*public function getData($course_id,$class_id, Request $request) {
        $search = $request->input('search');
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = OfflineTeacher::query();
        $query->select(['a.*', 'b.name as teacher_name', 'b.email as teacher_email', 'b.phone as teacher_phone']);
        $query->from('el_offline_course_teachers AS a');
        $query->leftJoin('el_training_teacher AS b', 'b.id', '=', 'a.teacher_id');
        $query->where(['a.course_id'=> $course_id,'a.class_id'=>$class_id]);

        if ($search) {
            $query->where('b.name', 'like', '%'. $search .'%');
        }

        $count = $query->count();
        $query->orderBy('b.'.$sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();

        json_result(['total' => $count, 'rows' => $rows]);
    }*/

    public function save($course_id, $class_id, Request $request) {
        $this->validateRequest([
            'teacher_id' => 'required|exists:el_training_teacher,id',
        ], $request, OfflineTeacher::getAttributeName());

        $is_unit = $request->input('unit');

        $teacher_id = $request->input('teacher_id');
        $teacherClass = OfflineTeacher::where('course_id', $course_id)->where('teacher_id', $teacher_id)->get();
        $classWithId = OfflineCourseClass::find($class_id, ['start_date', 'end_date']);
        foreach($teacherClass as $teacher) {
            $getClassOfTeacher = OfflineCourseClass::find($teacher->class_id, ['name', 'start_date', 'end_date']);
            if(($getClassOfTeacher->start_date < $classWithId->start_date && $getClassOfTeacher->end_date > $classWithId->start_date) ||
               ($getClassOfTeacher->start_date < $classWithId->end_date && $getClassOfTeacher->end_date > $classWithId->end_date)) {
                json_message('Giảng viên đang giảng dạy lớp học: ' . $getClassOfTeacher->name, 'error');
            }
        }
        $model = new OfflineTeacher();
        $model->teacher_id = $teacher_id;
        $model->course_id = $course_id;
        $model->class_id = $class_id;

        if ($model->save()) {
            $redirect = $is_unit > 0 ? route('module.training_unit.offline.teacher', ['id' => $course_id]) : route('module.offline.teacher', ['id' => $course_id,'class_id'=>$class_id]);

            json_result([
                'status' => 'success',
                'message' => trans('laother.successful_save'),
                'redirect' => $redirect,
            ]);
        }

        json_message(trans('laother.save_error'), 'error');
    }

    public function remove($course_id, Request $request) {
        $ids = $request->input('ids', null);
        OfflineTeacher::destroy($ids);
        json_result([
            'status' => 'success',
            'message' => trans('laother.delete_success'),
        ]);
    }
    public function saveNote($course_id, Request $request) {
        $this->validateRequest([
            'note' => 'nullable',
            'off_teacher_id' => 'required',
        ], $request);

        $note = $request->input('note');
        $off_teacher_id = $request->input('off_teacher_id');

        $model = OfflineTeacher::find($off_teacher_id);
        $model->note = $note;
        $model->save();
        json_message('ok');
    }

    public function ajaxGetTeacher(Request $request) {
        $model = TrainingTeacher::query();
        $model->select([
            'a.code',
            'a.name',
            'a.phone',
            'a.email',
            'b.id as course_teacher_id',
            'b.note',
            'b.tnt',
        ])->disableCache();
        $model->from('el_training_teacher as a');
        $model->join('el_offline_course_teachers as b', 'b.teacher_id', '=', 'a.id');
        $model->where('b.course_id', $request->id);
        $teachers = $model->get();

        json_result($teachers);
    }

    public function ajaxSaveTeacherNote(Request $request) {
        $model = OfflineTeacher::find($request->id);
        $model->note = $request->note;
        $model->save();
        
        json_result([
            'status' => 'success',
            'message' => trans('laother.successful_save'),
        ]);
    }

    public function ajaxSaveTeacherTNT(Request $request) {
        $model = OfflineTeacher::find($request->courseTeacherId);
        $model->tnt = $request->check;
        $model->save();

        json_result([
            'status' => 'success',
            'message' => trans('laother.successful_save'),
        ]);
    }
}
