<?php

namespace Modules\TrainingUnit\Http\Controllers;

use App\Models\Automail;
use App\Models\Permission;
use App\Models\Categories\UnitManager;
use App\Models\Categories\Unit;
use App\Scopes\DraftScope;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Offline\Entities\OfflineRegister;
use Modules\Offline\Entities\OfflineRegisterApprove;
use Modules\Online\Entities\OnlineCourse;
use Modules\Online\Entities\OnlineRegister;
use Modules\Online\Entities\OnlineRegisterApprove;
use Modules\Online\Entities\OnlineObject;
use App\Models\ProfileView;
use Modules\Quiz\Entities\Quiz;
use Modules\Quiz\Entities\QuizPart;
use Modules\Quiz\Entities\QuizRegister;
use App\Models\Categories\Subject;
use Modules\User\Entities\TrainingProcess;
use Modules\Offline\Entities\OfflineObject;
use Modules\Quiz\Entities\QuizResult;
use Modules\Offline\Entities\OfflineResult;
use Modules\Online\Entities\OnlineResult;
use Modules\TrainingUnit\Imports\RegisterImport;
use App\Events\SaveTrainingProcessRegister;
use Modules\Offline\Entities\OfflineCourseClass;

class RegisterCourseController extends Controller
{
    public function index()
    {
        return view('trainingunit::backend.register.index');
    }

    public function getData(Request $request) {
        $search = $request->search;
        $start_date = $request->start_date;
        $end_date = $request->end_date;

        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $managers = UnitManager::where('user_id', profile()->user_id)->pluck('unit_id')->toArray();
        $query = \DB::query();
        $query->select([
            'course.id',
            'course.course_id',
            'course.name AS course_name',
            'course.code AS course_code',
            'course.start_date',
            'course.end_date',
            'course.course_type',
        ])
            ->from('el_course_view AS course')
            ->where('course.isopen', '=', 1)
            ->where('course.status', '=', 1)
            ->whereNotNull('course.unit_id', '=', 1);

        if (!Permission::isAdmin()){
            $query->where(function ($subquery) use ($managers) {
                foreach ($managers as $key => $manager) {
                    $subquery->orWhere('course.unit_id', 'like', '%'. $manager .'%');
                }
            });
        }

        if ($search) {
            $query->where(function ($subquery) use ($search) {
                $subquery->orWhere('course.code', 'like', '%'. $search .'%');
                $subquery->orWhere('course.name', 'like', '%'. $search .'%');
            });
        }

        if ($start_date) {
            $query->where('course.start_date', '>=', date_convert($start_date));
        }

        if ($end_date) {
            $query->where('course.start_date', '<=', date_convert($end_date));
        }

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row) {
            $row->start_date = get_date($row->start_date, 'd/m/Y');
            $row->end_date = get_date($row->end_date, 'd/m/Y');
            $row->edit_url = route('module.training_unit.register_course.register', ['course_id' => $row->course_id, 'course_type' => $row->course_type]);

            $row->course_type = $row->course_type == 1 ? 'Online' : trans("latraining.offline");
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function register($course_id, $course_type)
    {
        $errors = session()->get('errors');
        \Session::forget('errors');

        return view('trainingunit::backend.register.register',[
            'course_id' => $course_id,
            'course_type' => $course_type,
        ]);
    }

    public function getDataRegister($course_id, $course_type, Request $request) {
        $search = $request->search;
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $managers = UnitManager::getIdUnitManagedByUser(profile()->user_id);
        if ($course_type==1) {
            $query = OnlineRegister::query();
            $table = 'el_online_register';
            $course = 'online';
        } else {
            $query = OfflineRegister::query();
            $table = 'el_offline_register';
            $course = 'offline';
        }

        $query->select([
            "{$table}.id",
            'profile.code',
            'profile.full_name',
            'profile.email',
            'profile.unit_name',
            'profile.title_name',
            "{$table}.status",
            "{$table}.approved_step",
            "{$table}.status",
        ]);
        $query->join("el_{$course}_course AS course", 'course.id', '=', "{$table}.course_id")
            ->join('el_profile_view AS profile', 'profile.user_id', '=', "{$table}.user_id")
            ->where('course.status', '=', 1)
            ->where('course.id', '=', $course_id);

        if (!Permission::isAdmin()){
            $query->whereIn('profile.unit_id', $managers);
        }

        if ($search) {
            $query->where(function ($subquery) use ($search, $dbprefix) {
                $subquery->orWhere('profile.code', 'like', '%'. $search .'%');
                $subquery->orWhere('profile.full_name', 'like', '%'. $search .'%');
            });
        }

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row) {
            $row->unit_status = is_null($row->unit_status) ? 2 : $row->unit_status;
            if (empty($row->parent_name)){
                $row->parent = $row->unit_name;
                $row->unit = '';
            }else{
                $row->parent = $row->parent_name;
                $row->unit = $row->unit_name;
            }
        }
        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function form($course_id, $course_type)
    {
        $max_unit = Unit::getMaxUnitLevel();
        $level_name = function ($level) {
            return Unit::getLevelName($level);
        };
        if ($course_type == 1) {
            $course = OnlineCourse::where('id', '=', $course_id)->first(['id','name']);
        } else {
            $course = OfflineCourse::where('id', '=', $course_id)->first(['id','name']);
        }
        return view('trainingunit::backend.register.form', [
            'course_id' => $course_id,
            'course_type' => $course_type,
            'max_unit' => $max_unit,
            'level_name' => $level_name,
            'course' => $course
        ]);
    }

    public function getDataNotRegister($course_id, $course_type, Request $request){
        $search = $request->input('search');
        $title = $request->input('title');
        $unit = $request->unit;

        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $manager = UnitManager::getIdUnitManagedByUser();

        $query = ProfileView::query();
        $query->select([
            'a.*',
            'user.username',
        ]);
        $query->from('el_profile_view AS a');
        $query->leftJoin('user AS user', 'user.id', '=', 'a.user_id');
        $query->where('a.user_id', '>', 2);
        $query->where('a.type_user', '=', 1);
        $query->whereIn('a.unit_id', $manager);

        if ($course_type == 1) {
            $course_register = OnlineRegister::where('course_id', '=', $course_id)->where('user_type', '=', 1)->pluck('user_id')->toArray();

            if (OnlineObject::where('course_id', $course_id)->exists()) {
                $query->where(function ($sub) use ($course_id){
                    $sub->orWhere(function($sub_query) use ($course_id) {
                        $sub_query->whereIn('a.title_id', function ($sub_query2) use ($course_id){
                            $sub_query2->select(['title_id']);
                            $sub_query2->from('el_online_object');
                            $sub_query2->where('course_id', '=', $course_id);
                        });
                        $sub_query->whereIn('a.unit_id', function ($sub_query3) use ($course_id){
                            $sub_query3->select(['unit_id']);
                            $sub_query3->from('el_online_object');
                            $sub_query3->where('course_id', '=', $course_id);
                        });
                    });
                    $sub->orWhereIn('a.unit_id',function ($sub_query) use ($course_id){
                        $sub_query->select(['unit_id']);
                        $sub_query->from('el_online_object');
                        $sub_query->whereNull('title_id');
                        $sub_query->where('course_id', '=', $course_id);
                    });
                });
            }
        } else {
            $course_register = OfflineRegister::where('course_id', '=', $course_id)->pluck('user_id')->toArray();

            if (OfflineObject::where('course_id', $course_id)->exists()) {
                $query->where(function ($sub) use ($course_id){
                    $sub->orWhere(function($sub_query) use ($course_id) {
                        $sub_query->whereIn('a.title_id', function ($sub_query2) use ($course_id){
                            $sub_query2->select(['title_id']);
                            $sub_query2->from('el_offline_object');
                            $sub_query2->where('course_id', '=', $course_id);
                        });
                        $sub_query->whereIn('a.unit_id', function ($sub_query3) use ($course_id){
                            $sub_query3->select(['unit_id']);
                            $sub_query3->from('el_offline_object');
                            $sub_query3->where('course_id', '=', $course_id);
                        });
                    });
                    $sub->orWhereIn('a.unit_id',function ($sub_query) use ($course_id){
                        $sub_query->select(['unit_id']);
                        $sub_query->from('el_offline_object');
                        $sub_query->whereNull('title_id');
                        $sub_query->where('course_id', '=', $course_id);
                    });
                });
            }
        }
        $query->whereNotIn('a.user_id', $course_register);

        if ($search) {
            $query->where(function ($sub_query) use ($search) {
                $sub_query->orWhere('a.full_name', 'like', '%'. $search .'%');
                $sub_query->orWhere('a.email', 'like', '%'. $search .'%');
                $sub_query->orWhere('a.code', 'like', '%'. $search .'%');
                $sub_query->orWhere('user.username', 'like', '%'. $search .'%');
            });
        }

        if ($title) {
            $query->where('a.title_id', '=', $title);
        }

        if ($unit) {
            $unit = Unit::whereIn('id', explode(';', $unit))->latest('id')->first();
            $unit_id = Unit::getArrayChild($unit->code);

            $query->where(function ($sub_query) use ($unit_id, $unit) {
                $sub_query->orWhereIn('a.unit_id', $unit_id);
                $sub_query->orWhere('a.unit_id', '=', $unit->id);
            });
        }

        $count = $query->count();
        $query->orderBy('a.'.$sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        foreach ($rows as $row){

        }
        json_result(['total' => $count, 'rows' => $rows]);
    }

    // LƯU GHI DANH
    public function save($course_id, $course_type, Request $request) {
        $this->validateRequest([
            'ids' => 'required',
        ], $request, [
            'ids' => 'Id khóa học'
        ]);

        if ($course_type == 1) {
            $course = OnlineCourse::findOrFail($course_id);
        } else {
            $course = OfflineCourse::findOrFail($course_id);

            $class = OfflineCourseClass::whereCourseId($course_id)->where('default', 1)->first();
        }
        $ids = $request->input('ids', null);
        $subject = Subject::findOrFail($course->subject_id);
        foreach($ids as $id){
            if ($course_type == 1) {
                if (OnlineRegister::checkExists($id, $course_id)) {
                    continue;
                }

                $model = new OnlineRegister();
            } else {
                if (OfflineRegister::checkExists($id, $course_id, $class->id)) {
                    continue;
                }

                $model = new OfflineRegister();
            }
            $model->user_id = $id;
            $model->course_id = $course_id;
            if ($model->save()) {
                // update training process
                event(new SaveTrainingProcessRegister($course, $subject, $id, null, $course_type));

                if ($course_type == 1 && $course->auto == 1){
                    $model->status = 1;
                    $quizs = Quiz::where('course_id', '=', $course_id)
                        ->where('status', '=', 1)->get();
                    if ($quizs){
                        foreach ($quizs as $quiz){
                            $quiz_part = QuizPart::where('quiz_id', '=', $quiz->id)->first();
                            if ($quiz_part){
                                QuizRegister::query()
                                    ->updateOrCreate([
                                        'quiz_id' => $quiz->id,
                                        'user_id' => $id,
                                        'type' => 1,
                                    ],[
                                        'quiz_id' => $quiz->id,
                                        'user_id' => $id,
                                        'type' => 1,
                                        'part_id' => $quiz_part->id,
                                    ]);
                            }else{
                                continue;
                            }
                        }
                    }
                    $model->save();
                }
                $users = UnitManager::getManagerOfUser($model->user_id);
                foreach ($users as $user_id){
                    $signature = getMailSignature($user_id);
                    $automail = new Automail();
                    $automail->template_code = 'approve_register_unit';
                    $automail->params = [
                        'signature' => $signature,
                        'code' => $course->code,
                        'name' => $course->name,
                        'start_date' => $course->start_date,
                        'end_date' => $course->end_date,
                        'url' => route('module.training_unit.approve_course.course', ['id' => $course_id, 'type' => $course_type])
                    ];

                    $automail->users = [$user_id];
                    $automail->object_id = $course->id;
                    if ($course_type == 1) {
                        $automail->object_type = 'approve_online_register_unit';
                    } else {
                        $automail->object_type = 'approve_offline_register_unit';
                    }
                    $automail->addToAutomail();
                }
            }
        }

        json_message(trans('laother.successful_save'));
    }

    // XÓA GHI DANH
    public function remove($course_id, $course_type, Request $request) {
        $ids = $request->input('ids', null);
        foreach ($ids as $id){
            if ($course_type == 1) {
                $course_register = OnlineRegister::find($id);
                $result = OnlineResult::where('register_id', '=', $id);
                if ($result->exists()){
                    continue;
                }
                $course = OnlineCourse::find($course_register->course_id);
            } else {
                $course_register = OfflineRegister::find($id);
                $result = OfflineResult::where('register_id', '=', $id);
                if ($result->exists() ){
                    continue;
                }
                $course = OfflineCourse::find($course_register->course_id);
            }

            $quizs = Quiz::query();
            $quizs->select(['a.id', 'b.user_id']);
            $quizs->from('el_quiz as a');
            $quizs->leftJoin('el_quiz_register as b', 'b.quiz_id', '=', 'a.id');
            $quizs->where('a.course_id', '=', $course_register->course_id);
            $quizs->where('b.user_id', '=', $course_register->user_id);
            if ($course_type == 1) {
                $quizs->where('b.type', '=', 1);
            }
            $quizs->get();
            if (count($quizs) > 0){
                $count = 0;
                foreach ($quizs as $quiz){
                    $result = QuizResult::where('quiz_id', '=', $quiz->id)
                        ->where('user_id', '=', $quiz->user_id)
                        ->where('type', '=', 1)
                        ->first();
                    if ($result){
                        $count++;
                        continue;
                    }else{
                        QuizRegister::where('quiz_id', '=', $quiz->id)
                            ->where('user_id', '=', $quiz->user_id)
                            ->where('type', '=', 1)
                            ->delete();
                    }
                }
                if ($count == 0){
                    $course_register->delete();
                }
            } else {
                $course_register->delete();
            }

            TrainingProcess::where(['user_id' => $course_register->user_id,'course_id'=>$course->id, 'course_type'=> $course_type, 'user_type' => 1])->delete();
        }
        json_result([
            'status' => 'success',
            'message' => trans('laother.delete_success'),
        ]);
    }

    // IMPORT GHI DANH
    public function importRegister($course_id, $course_type, Request $request){
        $this->validateRequest([
            'import_file' => 'required|file'
        ], $request, ['import_file' => 'File import']);

        $unit = $request->input('unit');

        $import = new RegisterImport($course_id, $course_type);
        \Excel::import($import, $request->file('import_file'));

        if ($import->errors) {
            session()->put('errors', $import->errors);
            session()->save();
        }

        $redirect = route('module.training_unit.register_course.register', ['course_id' => $course_id, 'course_type' => $course_type]);

        json_result([
            'status' => 'success',
            'message' => 'Import ' . trans('laapi.success'),
            'redirect' => $redirect,
        ]);
    }
}
