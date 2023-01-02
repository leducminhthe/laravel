<?php

namespace Modules\Quiz\Http\Controllers\Backend;

use App\Models\Automail;
use App\Models\MailSignature;
use App\Models\Profile;
use App\Models\Categories\UnitManager;
use App\Models\Categories\Titles;
use App\Models\Categories\Unit;
use App\Models\Categories\Area;
use App\Models\ProfileView;
use App\Models\User;
use App\Scopes\DraftScope;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Online\Entities\OnlineCourse;
use Modules\Quiz\Entities\QuizRegister;
use Modules\Quiz\Entities\QuizResult;
use Modules\Quiz\Entities\QuizSettingAlert;
use Modules\Quiz\Entities\QuizUserSecondary;
use Modules\Quiz\Entities\Quiz;
use Modules\Quiz\Entities\QuizAttempts;
use Modules\Quiz\Entities\QuizAttemptsAgain;
use Modules\Quiz\Entities\QuizPart;
use Modules\Quiz\Exports\RegisterExport;
use Modules\Quiz\Exports\TemplateRegisterExport;
use Modules\Quiz\Exports\RegisterSecondaryExport;
use Modules\Quiz\Imports\RegisterImport;
use function Clue\StreamFilter\fun;

class RegisterController extends Controller
{
    public function index($quiz_id, Request $request) {
        $max_unit = Unit::getMaxUnitLevel();
        $level_name = function ($level) {
            return Unit::getLevelName($level);
        };

        $errors = session()->get('errors');
        \Session::forget('errors');

        $profile = profile();
        $unit = Unit::where('code', '=', $profile->unit_code)->first();

        $quiz_name = Quiz::findOrFail($quiz_id);
        $quiz_part = QuizPart::where('quiz_id', '=', $quiz_id)->get();

        $count_quiz_register = QuizRegister::where('quiz_id', '=', $quiz_id)->where('user_id', '>', 2)->count();

        $course = '';
        if($quiz_name->course_type == 1){
            $course = OnlineCourse::find($quiz_name->course_id, ['name']);
        }elseif($quiz_name->course_type == 2){
            $course = OfflineCourse::find($quiz_name->course_id, ['name']);
        }
        $course_id = $quiz_name->course_id;

        return view('quiz::backend.register.index', [
            'quiz_name' => $quiz_name,
            'quiz_id' => $quiz_id,
            'quiz_part' => $quiz_part,
            'max_unit'=>$max_unit,
            'level_name'=>$level_name,
            'unit' => $unit,
            'count_quiz_register' => $count_quiz_register,
            'course_id' => $course_id,
            'course' => $course,
        ]);
    }

    public function getData($quiz_id, Request $request) {
        $search = $request->input('search');
        $status = $request->input('status');
        $title = $request->input('title');
        $unit = $request->input('unit_id');
        $part = $request->input('part');

        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $quiz = Quiz::find($quiz_id, ['quiz_not_register', 'max_attempts', 'quiz_type']);
        if($quiz->quiz_not_register != 1) {
            QuizRegister::addGlobalScope(new DraftScope());
        }

        $query = QuizRegister::query();
        $query->select([
            'el_quiz_register.*',
            'el_profile.lastname',
            'el_profile.firstname',
            'el_profile.code',
            'el_profile.email',
            'el_profile.title_code',
            'el_profile.unit_code',
            'c.name AS title_name',
            'd.name AS unit_name',
            'e.name as part_name',
            'e.start_date as part_start_date',
            'e.end_date as part_end_date',
            'f.name AS parent_name',
            'u.username',
        ]);
        $query->join('el_profile', 'el_profile.user_id', '=', 'el_quiz_register.user_id');
        $query->leftJoin('el_titles AS c', 'c.code', '=', 'el_profile.title_code');
        $query->leftJoin('el_unit AS d', 'd.code', '=', 'el_profile.unit_code');
        $query->leftJoin('el_area AS area', 'area.id', '=', 'd.area_id');
        $query->leftJoin('el_quiz_part AS e', 'e.id', '=', 'el_quiz_register.part_id');
        $query->leftJoin('el_unit AS f', 'f.code', '=', 'd.parent_code');
        $query->leftJoin('user AS u', 'u.id', '=', 'el_profile.user_id');
        $query->where('el_quiz_register.quiz_id', '=', $quiz_id);
        $query->where('el_quiz_register.type', '=', 1);
        $query->where('el_quiz_register.user_id', '>', 2);

        if ($search) {
            $query->where(function ($sub_query) use ($search) {
                $sub_query->orWhere(\DB::raw('CONCAT(el_profile.lastname, \' \', el_profile.firstname)'), 'like', '%' . $search . '%');
                $sub_query->orWhere('el_profile.lastname', 'like', '%'. $search .'%');
                $sub_query->orWhere('el_profile.firstname', 'like', '%'. $search .'%');
                $sub_query->orWhere('el_profile.code', 'like', '%'. $search .'%');
                $sub_query->orWhere('el_profile.email', 'like', '%'. $search .'%');
                $sub_query->orWhere('u.username', 'like', '%'. $search .'%');
            });
        }
        if (!is_null($status)) {
            $query->where('el_profile.status', '=', $status);
        }
        if ($title) {
            $title = Titles::where('id', '=', $title)->first();
            $query->where('el_profile.title_code', '=', $title->code);
        }
        if ($unit) {
            $unit = Unit::where('id', '=', $unit)->first();
            $unit_id = Unit::getArrayChild($unit->code);

            $query->where(function ($sub_query) use ($unit_id, $unit) {
                $sub_query->orWhereIn('el_profile.unit_code', $unit_id);
                $sub_query->orWhere('d.id', '=', $unit->id);
            });
        }
        if ($request->area) {
            $area = Area::find($request->area);
            $area_id = Area::getArrayChild($area->code);
            $query->where(function ($sub_query) use ($area, $area_id) {
                $sub_query->orWhereIn('area.id', $area_id);
                $sub_query->orWhere('area.id', '=', $area->id);
            });
        }
        if ($part) {
            $query->where('el_quiz_register.part_id', '=',  $part);
        }

        $count = $query->count();
        $query->orderBy('el_quiz_register.'.$sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();

        foreach ($rows as $row) {
            $count_attempt_again = QuizAttemptsAgain::where('quiz_id', $row->quiz_id)->where('part_id',$row->part_id)->where('user_id',$row->user_id)->sum('attempt');
            $row->count_attempt_again = $count_attempt_again;

            $row->attempts_again = 0;
            $count_attempt = QuizAttempts::where('quiz_id', $row->quiz_id)->where('part_id',$row->part_id)->where('user_id',$row->user_id)->count();

            //Cho phép thi lại khi kỳ thi độc lập có thời gian ca thi vẫn còn, có thiết lập cụ thể số lần làm bài và đã làm hết số lần làm bài đó
            if($row->part_end_date > date('Y-m-d H:i:s') && $count_attempt == ($quiz->max_attempts + $count_attempt_again) && $quiz->max_attempts > 0 && $quiz->quiz_type == 3){
                $row->attempts_again = 1;
            }

            $row->part_start_date = get_date($row->part_start_date, 'H:i d/m/Y');
            $row->part_end_date = get_date($row->part_end_date, 'H:i d/m/Y');
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

    public function getDataNotRegister($quiz_id, Request $request){
        $search = $request->input('search');
        $title = $request->input('title');
        $status = $request->input('status');
        $unit = $request->input('unit_id');

        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $quiz = Quiz::find($quiz_id);
        $course_id = $quiz->course_id;
        Profile::addGlobalScope(new DraftScope('user_id'));
        $query = Profile::query();
        $query->select([
            'el_profile.*',
            'b.name AS title_name',
            'c.name AS unit_name',
            'd.name AS parent_name'
        ])->disableCache();
        $query->leftJoin('el_titles AS b', 'b.code', '=', 'el_profile.title_code');
        $query->leftJoin('el_unit AS c', 'c.code', '=', 'el_profile.unit_code');
        $query->leftJoin('el_unit AS d', 'd.code', '=', 'c.parent_code');

        if ($quiz->course_type == 1){
            $query->whereExists(function ($sub) use($course_id){
                $sub->selectRaw('1')
                    ->from('el_online_register')
                    ->where('status', 1)
                    ->where('course_id', '=', $course_id)
                    ->whereColumn('user_id', '=', 'el_profile.user_id');
            });
        }elseif($quiz->course_type == 2){
            $query->whereExists(function ($sub) use($course_id){
                $sub->selectRaw('1')
                    ->from('el_offline_register')
                    ->where('status', 1)
                    ->where('course_id', '=', $course_id)
                    ->whereColumn('user_id', '=', 'el_profile.user_id');
            });
        }

        $query->where(function($sub) use($quiz_id){
            //loại đã ghi danh
            $sub->whereNotExists(function($sub2) use($quiz_id){
                $sub2->selectRaw('1')
                ->from('el_quiz_register')
                ->whereColumn('user_id', '=', 'el_profile.user_id')
                ->where('quiz_id', $quiz_id);
            });
            // hoặc lấy ghi danh nhưng rớt
            $sub->orWhere(function ($sub2) use($quiz_id){
                // đã ghi danh
                $sub2->whereExists(function($sub3) use($quiz_id){
                    $sub3->selectRaw('1')
                        ->from('el_quiz_register')
                        ->whereColumn('user_id', '=', 'el_profile.user_id')
                        ->where('quiz_id', $quiz_id)
                        ->where('type', '=', 1);
                });
                // rớt
                $sub2->whereExists(function($sub3) use($quiz_id){
                    $sub3->selectRaw('1')
                        ->from('el_quiz_result')
                        ->whereColumn('user_id', '=', 'el_profile.user_id')
                        ->where('quiz_id', $quiz_id)
                        ->where('result', '!=', 1);
                });
                // trừ rớt và đã ghi danh
                $sub2->whereNotExists(function($sub3) use($quiz_id){
                    $sub3->selectRaw('1')
                        ->from('el_quiz_register as a')
                        ->leftJoin('el_quiz_result as b', function($on){
                            $on->on('b.user_id', '=', 'a.user_id')
                                ->on('b.quiz_id', '=', 'a.quiz_id')
                                ->on('b.part_id', '=', 'a.part_id');
                        })
                        ->whereColumn('a.user_id', '=', 'el_profile.user_id')
                        ->where('a.quiz_id', $quiz_id)
                        ->where('a.type', 1)
                        ->whereNull('b.part_id');
                });
            });
        });

        $query->where('el_profile.user_id', '>', 2);
        $query->where('el_profile.type_user', '=', 1);

        if ($search) {
            $query->where(function ($sub_query) use ($search) {
                $sub_query->orWhere(\DB::raw('CONCAT(lastname, \' \', firstname)'), 'like', '%' . $search . '%');
                $sub_query->orWhere('el_profile.firstname', 'like', '%'. $search .'%');
                $sub_query->orWhere('el_profile.lastname', 'like', '%'. $search .'%');
                $sub_query->orWhere('el_profile.email', 'like', '%'. $search .'%');
                $sub_query->orWhere('el_profile.code', 'like', '%'. $search .'%');
            });
        }

        if (!is_null($status)) {
            $query->where('el_profile.status', '=', $status);
        }

        if ($title) {
            $title = Titles::where('id', '=', $title)->first();
            $query->where('el_profile.title_code', '=', $title->code);
        }

        if ($unit) {
            $unit = Unit::where('id', '=', $unit)->first();
            $query->where('el_profile.unit_code', '=', $unit->code);
        }

        $count = $query->count();
        $query->orderBy('el_profile.'.$sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row){
            if (empty($row->parent_name)){
                $row->parent = $row->unit_name;
                $row->unit_name = '';
            }else{
                $row->parent = $row->parent_name;
            }
        }

        return response()->json(['total' => $count, 'rows' => $rows]);
    }

    public function form($quiz_id) {
        $date = date('Y-m-d H:i:s');

        $max_unit = Unit::getMaxUnitLevel();
        $level_name = function ($level) {
            return Unit::getLevelName($level);
        };
        $profile = profile();
        $unit = Unit::where('code', '=', $profile->unit_code)->first();

        $quiz_name = Quiz::findOrFail($quiz_id);
        $quiz_part = QuizPart::where('quiz_id', '=', $quiz_id)->where('end_date', '>', $date)->get();

        $course = '';
        if($quiz_name->course_type == 1){
            $course = OnlineCourse::find($quiz_name->course_id, ['name']);
        }elseif($quiz_name->course_type == 2){
            $course = OfflineCourse::find($quiz_name->course_id, ['name']);
        }
        $course_id = $quiz_name->course_id;

        return view('quiz::backend.register.form', [
            'quiz_id' => $quiz_id,
            'quiz_name' => $quiz_name,
            'quiz_part' => $quiz_part,
            'max_unit'=>$max_unit,
            'level_name'=>$level_name,
            'unit' => $unit,
            'course_id' => $course_id,
            'course' => $course,
        ]);
    }

    public function save($quiz_id, Request $request) {
        $this->validateRequest([
            'ids' => 'required',
            'part_id' => 'required',
        ], $request, QuizRegister::getAttributeName());

        $part_id = $request->input('part_id');
        $ids = $request->input('ids', null);

        $quiz = Quiz::with('type')->find($quiz_id);
        $part = QuizPart::whereQuizId($quiz_id)->where('id', $part_id)->first();

        foreach($ids as $id){
            $model = QuizRegister::firstOrNew(['user_id' => $id, 'quiz_id' => $quiz_id, 'part_id' => $part_id, 'type' => 1]);
            $model->user_id = $id;
            $model->quiz_id = $quiz_id;
            $model->part_id = $part_id;
            $model->type = 1;
            $model->save();

            if ($quiz->status == 1){
                $profile = ProfileView::query()->where('user_id', $id)->first();
                $signature = getMailSignature($profile->user_id);
                $params = [
                    'signature' => $signature,
                    'gender' => ($profile->gender=='1'?'Anh':'Chị'),
                    'full_name' => $profile->full_name,
                    'firstname' => $profile->firstname,
                    'quiz_name' => $quiz->name,
                    'quiz_type' => $quiz->type? $quiz->type->name:'',
                    'quiz_part_name' => $part->name,
                    'start_quiz_part' => get_datetime($part->start_date),
                    'end_quiz_part' => get_datetime($part->end_date),
                    'quiz_time' => $quiz->limit_time,
                    'pass_score' => $quiz->pass_score,
                    'url' => route('module.quiz.doquiz.index', ['quiz_id' => $quiz_id,'part_id'=>$part_id]),
                ];
                $user_id = [$id];
                $this->saveEmailQuizRegister($params,$user_id,$part_id,1);
            }
        }

        json_result([
            'status' => 'success',
            'message' => 'Ghi danh thành công',
        ]);
    }

    public function remove(Request $request) {
        $ids = $request->input('ids', null);

        foreach ($ids as $id){
            $register = QuizRegister::find($id);
            $result = QuizResult::where('quiz_id', '=', $register->quiz_id)
                ->where('part_id', '=', $register->part_id)
                ->where('user_id', '=', $register->user_id)
                ->whereNull('text_quiz')
                ->where('type', '=', 1)
                ->first();

            if ($result){
                $profile_view = ProfileView::whereUserId($register->user_id)->first(['full_name']);
                json_message($profile_view->full_name .' đã có làm bài thi. Không thể xoá', 'error');
            }else{
                $register->delete();
            }
        }
        json_result([
            'status' => 'success',
            'message' => trans('laother.delete_success'),
        ]);
    }

    public function importRegister($quiz_id, Request $request){
        $this->validateRequest([
            'import_file' => 'required|file'
        ], $request, ['import_file' => 'File import']);

        $unit = $request->input('unit_id');

        $import = new RegisterImport($quiz_id,1);
        \Excel::import($import, $request->file('import_file'));

        if ($import->errors) {
            session()->put('errors', $import->errors);
            session()->save();
        }

        $redirect = route('module.quiz.register', ['id' => $quiz_id]);

        json_result([
            'status' => 'success',
            'message' => 'Import ' . trans('laapi.success'),
            'redirect' => $redirect,
        ]);
    }

    public function exportRegister($quiz_id){
        return (new RegisterExport($quiz_id))->download('nhan_vien_noi_bo_dang_ki_ky_thi_'. date('d_m_Y') .'.xlsx');
    }

    public function exportTemplateRegister($quiz_id) {
        return (new TemplateRegisterExport($quiz_id))->download('mau_import_nhan_vien_ghi_danh_ky_thi_'. date('d_m_Y') .'.xlsx');
    }

    public function indexSecondary($quiz_id) {
        $errors = session()->get('errors');
        \Session::forget('errors');

        $profile = profile();
        $unit = Unit::where('code', '=', $profile->unit_code)->first();

        $quiz_name = Quiz::findOrFail($quiz_id);
        $quiz_part = QuizPart::where('quiz_id', '=', $quiz_id)->get();

        return view('quiz::backend.register.secondary_index', [
            'quiz_name' => $quiz_name,
            'quiz_id' => $quiz_id,
            'quiz_part' => $quiz_part,
            'unit' => $unit,
        ]);
    }

    public function getDataSecondary($quiz_id, Request $request) {
        $search = $request->input('search');
        $part = $request->input('part');
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);
        QuizRegister::addGlobalScope(new DraftScope());
        $query = QuizRegister::query();
        $query->select([
            'el_quiz_register.*',
            'b.full_name',
            'b.code',
            'b.dob',
            'b.email',
            'b.identity_card',
            'c.name as part_name',
            'c.start_date as part_start_date',
            'c.end_date as part_end_date'
        ]);
        $query->leftJoin('el_profile_view AS b', 'b.id', '=', 'el_quiz_register.user_id');
        $query->leftJoin('el_quiz_part AS c', 'c.id', '=', 'el_quiz_register.part_id');
        $query->where('el_quiz_register.quiz_id', '=', $quiz_id);
        $query->where('el_quiz_register.type', '=', 2);

        if ($search) {
            $query->where(function ($sub_query) use ($search) {
                $sub_query->orWhere('b.name', 'like', '%'. $search .'%');
                $sub_query->orWhere('b.code', 'like', '%'. $search .'%');
                $sub_query->orWhere('b.email', 'like', '%'. $search .'%');
            });
        }
        if ($part) {
            $query->where('c.id', '=',  $part);
        }

        $count = $query->count();
        $query->orderBy('el_quiz_register.'.$sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();

        foreach ($rows as $row) {
            $row->part_start_date = get_date($row->part_start_date, 'H:i d/m/Y');
            $row->part_end_date = get_date($row->part_end_date, 'H:i d/m/Y');
            $row->dob = get_date($row->dob, 'd/m/Y');
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function getDataNotUserSecondary($quiz_id, Request $request){
        $search = $request->input('search');
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);
        $subQuery = QuizRegister::where(['quiz_id'=>$quiz_id])->pluck('user_id')->toArray();
        ProfileView::addGlobalScope(new DraftScope());
        $query = ProfileView::select(['id','user_id','code', 'full_name','email','dob','identity_card'])
        ->where(['type_user'=>2])
        ->whereNotIn('user_id',$subQuery);
//        $query = ProfileView::whereDoesntHave('quizRegisters',function ($query) use($quiz_id){
//            $query->where('quiz_id', '=',$quiz_id);
//        })->where(['type_user'=>2])->select(['id','user_id','code', 'full_name','email','dob','identity_card']);
        if ($search) {
            $query->where(function ($sub_query) use ($search) {
                $sub_query->orWhere('full_name', 'like', '%'. $search .'%');
                $sub_query->orWhere('code', 'like', '%'. $search .'%');
                $sub_query->orWhere('email', 'like', '%'. $search .'%');
            });
        }

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();
        foreach ($rows as $row) {
            $row->dob = get_date($row->dob, 'd/m/Y');
        }
        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function formSecondary($quiz_id) {
        $date = date('Y-m-d H:i:s');

        $profile = profile();
        $unit = Unit::where('code', '=', $profile->unit_code)->first();

        $quiz_name = Quiz::findOrFail($quiz_id);
        $quiz_part = QuizPart::where('quiz_id', '=', $quiz_id)->where('end_date', '>', $date)->get();

        return view('quiz::backend.register.secondary_form', [
            'quiz_id' => $quiz_id,
            'quiz_name' => $quiz_name,
            'quiz_part' => $quiz_part,
            'unit' => $unit,
        ]);
    }

    public function saveSecondary($quiz_id, Request $request) {
        $this->validateRequest([
            'ids' => 'required',
            'part_id' => 'required',
        ], $request);

        $part_id = $request->input('part_id');
        $ids = $request->input('ids', null);

        $quiz = Quiz::with('type')->find($quiz_id);
        $part = QuizPart::whereQuizId($quiz_id)->where('id', $part_id)->first();
        foreach($ids as $id){
            if (QuizRegister::checkSecondaryExists($id, $quiz_id)) {
                continue;
            }else{
                $model = new QuizRegister();
                $model->user_id = $id;
                $model->quiz_id = $quiz_id;
                $model->part_id = $part_id;
                $model->type = 2;
                $model->save();

                if ($quiz->status == 1){
                    $signature = getMailSignature($id, 2);

                    $profile = ProfileView::find($id);
                    $params = [
                        'gender' => 'Anh/Chị',
                        'full_name' => $profile->full_name,
                        'firstname' => $profile->firstname,
                        'quiz_name' => $quiz->name,
                        'quiz_type' => $quiz->type? $quiz->type->name:'',
                        'quiz_part_name' => $part->name,
                        'start_quiz_part' => get_datetime($part->start_date),
                        'end_quiz_part' => get_datetime($part->end_date),
                        'quiz_time' => $quiz->limit_time,
                        'pass_score' => $quiz->pass_score,
                        'url' => route('module.quiz.doquiz.index', ['quiz_id' => $quiz_id,'part_id'=>$part_id]),
                        'signature' => $signature,
                    ];
                    $user_id = [$id];
                    $this->saveEmailQuizRegister($params,$user_id,$part_id,2);
                }
            }
        }

        json_result([
            'status' => 'success',
            'message' => 'Ghi danh thành công',
        ]);
    }

    public function removeSecondary(Request $request) {
        $ids = $request->input('ids', null);
        foreach ($ids as $id){
            $register = QuizRegister::find($id);

            $result = QuizResult::where('quiz_id', '=', $register->quiz_id)
                ->where('user_id', '=', $register->user_id)
                ->where('type', '=', 2)
                ->whereNull('text_quiz')
                ->first();

            if ($result){
                continue;
            }else{
                $register->delete();
            }
        }
        json_result([
            'status' => 'success',
            'message' => trans('laother.delete_success'),
        ]);
    }

    public function importRegisterSecondary($quiz_id, Request $request){
        $this->validateRequest([
            'import_file' => 'required|file'
        ], $request, ['import_file' => 'File import']);

        $unit = $request->input('unit_id');

        $import = new RegisterImport($quiz_id, 2);
        \Excel::import($import, $request->file('import_file'));

        if ($import->errors) {
            session()->put('errors', $import->errors);
            session()->save();
        }

        $redirect = route('module.quiz.register.user_secondary', ['id' => $quiz_id]);

        json_result([
            'status' => 'success',
            'message' => 'Import ' . trans('laapi.success'),
            'redirect' => $redirect,
        ]);
    }

    public function exportRegisterSecondary($quiz_id){
        return (new RegisterSecondaryExport($quiz_id))->download('thi_sinh_ben_ngoai_dang_ki_ky_thi_'. date('d_m_Y') .'.xlsx');
    }

    public function saveEmailQuizRegister(array $params,array $user_id,int $part_id, int $user_type)
    {
        $automail = new Automail();
        $automail->template_code = 'quiz_registerd';
        $automail->params = $params;
        $automail->users = $user_id;
        $automail->user_type = $user_type;
        $automail->check_exists = true;
        $automail->check_exists_status = 0;
        $automail->object_id = $part_id;
        $automail->object_type = 'approve_quiz';
        $automail->addToAutomail();
    }

    public function createNewSecondary($quiz_id) {
        $quiz_name = Quiz::findOrFail($quiz_id);
        $quiz_part = QuizPart::where('quiz_id', '=', $quiz_id)->get();

        return view('quiz::backend.register.secondary_new', [
            'quiz_id' => $quiz_id,
            'quiz_name' => $quiz_name,
            'quiz_part' => $quiz_part,
        ]);
    }

    public function saveNewSecondary($quiz_id, Request $request) {
        $attribute = [
            'code' => 'Mã',
            'name' => 'Họ tên',
            'username' =>'Tên đăng nhập',
            'password' => 'Mật khẩu',
            'repassword' => 'Xác nhận mật khẩu',
            'identity_card' => 'Số căn cước',
        ];
        $this->validateRequest([
            'code' => 'required|unique:user,code,'. $request->id,
            'name' => 'required',
            'username' => 'required|min:6|max:32|unique:user,username,'. $request->id,
            'password' => 'required|min:8|max:32',
            'repassword' => 'same:password',
            'email' => 'nullable|email',
            'identity_card' => 'required|min:9|max:14',
        ], $request, $attribute);

        $part_id = $request->input('part_id');
        $quiz = Quiz::with('type')->find($quiz_id);
        $part = QuizPart::whereQuizId($quiz_id)->where('id', $part_id)->first();

        if (empty($part)){
            json_message('Chưa chọn ca thi', 'error');
        }


        $parts = explode(" ", $request->name);
        if(count($parts) > 1) {
            $lastname = array_pop($parts);
            $firstname = implode(" ", $parts);
        }
        else
        {
            $firstname = $request->name;
            $lastname = " ";
        }
        $user = User::firstOrNew(['id' => $request->id]);
        $user->fill($request->all());
        $user->password = password_hash($request->input('password'), PASSWORD_DEFAULT);
        $user->firstname = $lastname;
        $user->lastname = $firstname;
        if ($user->save()) {
            $profile = Profile::firstOrNew(['id' => $user->id]);
            $profile->fill($request->all());
            $profile->id = $user->id;
            $profile->user_id = $user->id;
            $profile->firstname = $lastname;
            $profile->lastname = $firstname;
            $profile->type_user = 2;
            if ($request->dob)
                $profile->dob = date_convert($request->dob);
            if ($profile->save()) {
                $quiz_register = new QuizRegister();
                $quiz_register->user_id = $profile->id;
                $quiz_register->quiz_id = $quiz_id;
                $quiz_register->part_id = $part_id;
                $quiz_register->type = 2;
                $quiz_register->save();
                if ($quiz->status == 1){
                    $signature = getMailSignature($profile->id, 2);
                    $params = [
                        'gender' => 'Anh/Chị',
                        'full_name' => $request->name,
                        'firstname' => $profile->firstname,
                        'quiz_name' => $quiz->name,
                        'quiz_type' => $quiz->type? $quiz->type->name:'',
                        'quiz_part_name' => $part->name,
                        'start_quiz_part' => get_datetime($part->start_date),
                        'end_quiz_part' => get_datetime($part->end_date),
                        'quiz_time' => $quiz->limit_time,
                        'pass_score' => $quiz->pass_score,
                        'url' => route('module.quiz.doquiz.index', ['quiz_id' => $quiz_id,'part_id'=>$part_id]),
                        'signature' => $signature,
                    ];
                    $user_id = [$profile->id];
                    $this->saveEmailQuizRegister($params,$user_id,$part_id,2);
                }

                json_result([
                    'status' => 'success',
                    'message' => trans('laother.successful_save'),
                    'redirect' => route('module.quiz.register.user_secondary', ['id' => $quiz_id])
                ]);
            }
        }


        json_message(trans('laother.save_error'), 'error');
    }

    public function sendMailUserRegisted($quiz_id, $type, Request $request){
        $this->validateRequest([
            'ids' => 'required',
        ], $request,[
            'ids' => trans("latraining.student"),
        ]);

        $ids = $request->input('ids', null);

        $quiz = Quiz::with('type')->find($quiz_id);
        $users = QuizRegister::query()->whereIn('id', $ids)->where('type', $type)->get();
        foreach($users as $user) {
            $part = QuizPart::whereQuizId($quiz_id)->where('id', $user->part_id)->first();
            $profile = ProfileView::query()->where('user_id', $user->user_id)->first();

            $signature = getMailSignature($user->user_id, $type);
            $params = [
                'signature' => $signature,
                'gender' => $user->type == 1 ? ($profile->gender=='1'?'Anh':'Chị') : 'Anh/Chị',
                'full_name' => $user->type == 1 ? $profile->full_name : $profile->name,
                'firstname' => $user->type == 1 ? $profile->firstname : $profile->name,
                'quiz_name' => $quiz->name,
                'quiz_type' => $quiz->type ? $quiz->type->name : '',
                'quiz_part_name' => $part->name,
                'start_quiz_part' => get_datetime($part->start_date),
                'end_quiz_part' => get_datetime($part->end_date),
                'quiz_time' => $quiz->limit_time,
                'pass_score' => $quiz->pass_score,
                'url' => route('module.quiz.doquiz.index', ['quiz_id' => $quiz_id, 'part_id' => $part->id]),
            ];
            $user_id = [$user->user_id];
            $this->saveEmailQuizRegister($params, $user_id, $part->id, $type);
        }

        json_message('Gửi mail thành công','success');
    }

    public function attemptsAgain($quiz_id, Request $request){
        $part_id = $request->part_id;
        $user_id = $request->user_id;

        $count_attempt = QuizAttemptsAgain::where('quiz_id',$quiz_id)->where('part_id',$part_id)->where('user_id',$user_id)->sum('attempt');

        $model = QuizAttemptsAgain::firstOrNew(['quiz_id' => $quiz_id, 'part_id' => $part_id, 'user_id' => $user_id]);
        $model->quiz_id = $quiz_id;
        $model->part_id = $part_id;
        $model->user_id = $user_id;
        $model->attempt = $count_attempt + 1;
        $model->save();

        json_result([
            'status' => 'success',
            'message' => 'Cho phép thi lại thành công',
        ]);
    }

    public function blockQuiz($quiz_id, Request $request){
        $ids = $request->ids;
        $block_quiz_note = $request->block_quiz_note;
        $status = $request->status;

        QuizRegister::where('quiz_id', $quiz_id)->whereIn('id', $ids)->update([
            'block_quiz' => $status,
            'block_quiz_note' => $block_quiz_note,
        ]);

        $message = $status == 1 ? 'Cấm thi' : 'Bỏ cấm thi';
        json_result([
            'status' => 'success',
            'message' => $message,
        ]);
    }
}
