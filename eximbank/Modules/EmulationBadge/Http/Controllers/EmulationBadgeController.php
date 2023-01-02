<?php

namespace Modules\EmulationBadge\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Modules\EmulationBadge\Entities\EmulationBadge;
use Modules\EmulationBadge\Entities\ArmorialEmulationBadge;
use Modules\EmulationBadge\Entities\CourseEmulationBadge;
use App\Models\CourseView;
use App\Models\ProfileView;
use App\Models\Profile;
use Modules\EmulationBadge\Entities\UserEmulationBadge;

class EmulationBadgeController extends Controller
{
    public function index()
    {
        return view('emulationbadge::index');
    }

    public function getData(Request $request) {
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $search = $request->input('search');
        $query = EmulationBadge::query();

        if ($search) {
            $query->where('name', 'like', '%'. $search .'%');
        }

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        foreach ($rows as $row) {
            $row->status = $row->status == 1 ? trans('labutton.enable') : trans('labutton.disable');
            $row->createdby = Profile::fullname($row->updated_by);
            $row->createdat = get_date($row->created_at, 'd/m/Y');
            $row->edit_url = route('module.emulation_badge.edit', ["id" => $row->id]);
            $row->result_url = route('module.emulation_badge.result', ["id" => $row->id]);
        }

        return response()->json(['total' => $count, 'rows' => $rows]);
    }

    public function form($id = null)
    {
        $model = EmulationBadge::firstOrNew(["id" => $id]);
        $allCourseOnline = CourseView::where(['course_type' => 1, 'status' => 1, 'isopen' => 1, 'offline' => 0])->get(['course_id', 'name', 'code']);
        $armorial = '';
        $courseOnline = '';
        if($id) {
            $armorial_1 = ArmorialEmulationBadge::where('emulation_badge_id', '=', $id)->where('type', 1)->get();
            $armorial_2 = ArmorialEmulationBadge::where('emulation_badge_id', '=', $id)->where('type', 2)->get();
            $armorial_3 = ArmorialEmulationBadge::where('emulation_badge_id', '=', $id)->where('type', 3)->get();

            $queryOnline = CourseEmulationBadge::query();
            $queryOnline->select([
                'a.*',
                'b.name as course_name',
                'b.code as course_code',
                'b.start_date',
                'b.end_date',
            ]);
            $queryOnline->from('course_emulation_badge as a');
            $queryOnline->join('el_course_view as b', function($sub) {
                $sub->on('b.course_id', '=', 'a.course_id');
                $sub->on('b.course_type', '=', 'a.course_type');
            });
            $queryOnline->where(['a.emulation_badge_id' => $id, 'a.course_type' => 1]);
            $queryOnline->orderBy('created_at');
            $courseOnlines = $queryOnline->get();
        }
    
        return view('emulationbadge::form', [
            'model' => $model,
            'armorials_1' => $armorial_1,
            'armorials_2' => $armorial_2,
            'armorials_3' => $armorial_3,
            'courseOnlines' => $courseOnlines,
            'allCourseOnline' => $allCourseOnline,
        ]);
    }

    public function save(Request $request)
    {
        $this->validateRequest([
            'code' => 'required',
            'name' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
        ], $request, [
            'code' => trans('lacategory.code'),
            'name' => trans('lacategory.name'),
            'start_ime' => trans('laother.start_time'),
            'end_time' => trans('laother.end_time')
        ]);

        $save = EmulationBadge::firstOrNew(['id' => $request->id]);
        $save->code = $request->code;
        $save->name = $request->name;
        $save->start_time = get_date($request->start_time, 'Y-m-d');
        $save->end_time = get_date($request->end_time, 'Y-m-d');
        $save->status = $request->status;
        $save->description = $request->description;
        $save->save();

        return \response()->json([
            'status' => 'success',
            'message' => trans('laother.successful_save'),
            'redirect' => route('module.emulation_badge.edit', $save->id)
        ]);
    }

    public function remove(Request $request)
    {
        $this->validate($request, [
            'ids' => 'required|array',
        ]);
        
        $ids = $request->post('ids');
        EmulationBadge::destroy($ids);
        foreach($ids as $id) {
            ArmorialEmulationBadge::where('emulation_badge_id', $id)->delete();
            CourseEmulationBadge::where('emulation_badge_id', $id)->delete();
        }
        
        return response()->json([
            'status' => 'success'
        ]);
    }

    public function saveArmorial(Request $request) {
        $this->validate($request, [
            'image' => 'required',
            'level' => 'required',
        ],[
            'image' => trans('latraining.picture'),
            'level' => trans('lacategory.rank'),
        ]);
        
        $allArmorial = ArmorialEmulationBadge::where(['emulation_badge_id' => $request->id, 'type' => $request->type])->get();
        foreach ($allArmorial as $key => $armorial) {
            if($request->armorialId == $armorial->id && $armorial->level == $request->level) {
                continue;
            } else if ($armorial->level == $request->level) {
                json_message('Hạng đã tồn tại', 'warning');
            }
        }
        if (empty($request->armorialId) && $allArmorial->count() == '5') {
            json_message('Số lượng huy hiệu chỉ được tối đa 5', 'warning');
        }

        $saveArmorial = ArmorialEmulationBadge::firstOrNew(['id' => $request->armorialId]);
        $saveArmorial->image = upload_image([150, 150], $request->image);
        $saveArmorial->level = $request->level;
        $saveArmorial->emulation_badge_id = $request->id;
        $saveArmorial->type = $request->type;
        $saveArmorial->save();

        $image = image_file($saveArmorial->image);
        json_result([
            'status' => 'success',
            'message' => trans('laother.successful_save'),
            'saveArmorial' => $saveArmorial,
            'image' => $image
        ]);
    }

    public function removeArmorial(Request $request) {
        $this->validate($request, [
            'id' => 'required',
        ],[
            'id' => 'id',
        ]);

        ArmorialEmulationBadge::find($request->id)->delete();

        json_result([
            'status' => 'success',
            'message' => trans('laother.delete_success'),
        ]);
    }

    public function saveCourse(Request $request) {
        $this->validate($request, [
            'courseId' => 'required',
        ],[
            'courseId' => trans('lamenu.course'),
        ]);

        $checkCourse = CourseEmulationBadge::where(['emulation_badge_id' => $request->id, 'course_id' => $request->courseId, 'course_type' => $request->type])->exists();
        if($checkCourse) {
            json_message('Khóa học đã tồn tại', 'warning');
        }

        $saveCourse = new CourseEmulationBadge();
        $saveCourse->course_type = $request->type;
        $saveCourse->emulation_badge_id = $request->id;
        $saveCourse->course_id = $request->courseId;
        $saveCourse->save();

        $course = CourseView::where(['course_id' => $request->courseId, 'course_type' => $request->type])->first(['code', 'name', 'start_date', 'end_date']);
        $course->start_date_format = get_date($course->start_date);
        $course->end_date_format = get_date($course->end_date);

        json_result([
            'status' => 'success',
            'message' => trans('laother.successful_save'),
            'saveCourse' => $saveCourse,
            'course' => $course
        ]);
    }

    public function removeCourse(Request $request) {
        $this->validate($request, [
            'id' => 'required',
        ],[
            'id' => 'id',
        ]);
        CourseEmulationBadge::find($request->id)->delete();
        json_result([
            'status' => 'success',
            'message' => trans('laother.delete_success'),
        ]);
    }

    public function result($id) {
        $model = EmulationBadge::find($id);

        return view('emulationbadge::result', [
            'model' => $model
        ]);
    }

    public function getDataResult($id, Request $request) {
        $search = $request->input('search', '');
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $userGetArmorialArr = UserEmulationBadge::where(['emulation_badge_id' => $id])->groupBy('user_id')->pluck('user_id')->toArray();

        $query = ProfileView::query();
        $query->select([
            'user_id',
            'full_name',
            'code',
        ]);
        $query->whereIn('user_id', $userGetArmorialArr);

        if($search) {
            $query->where(function($sub) use ($search){
                $sub->where('full_name', 'like', '%'. $search .'%');
                $sub->orWhere('code', 'like', '%'. $search .'%');
            });
        }

        $count = $query->count();
        $query->orderBy('id', 'asc');
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row) {
            $userGetArmorials = UserEmulationBadge::select(['*'])
            ->from('user_emulation_badge as a')
            ->join('armorial_emulation_badge as b', 'b.id', '=', 'a.armorial_id')
            ->where(['a.emulation_badge_id' => $id, 'a.user_id' => $row->user_id])
            ->get(); 
            foreach ($userGetArmorials as $key => $armorial) {
                if($armorial->type == 1) {
                    $row->rank_time = $armorial->level;
                    $row->time = image_file($armorial->image);
                } else if($armorial->type == 2) {
                    $row->rank_score = $armorial->level;
                    $row->score = image_file($armorial->image);
                } else {
                    $row->rank_complete = $armorial->level;
                    $row->complete = image_file($armorial->image);
                }
            }
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }
}
