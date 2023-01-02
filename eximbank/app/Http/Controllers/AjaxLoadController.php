<?php

namespace App\Http\Controllers;

use App\Models\AreaName;
use App\Models\Categories\Area;
use App\Models\Categories\LevelSubject;
use App\Models\Categories\Position;
use App\Models\Categories\TitleRank;
use App\Models\PermissionType;
use App\Models\Profile;
use App\Models\Categories\Subject;
use App\Models\Categories\Titles;
use App\Models\Categories\TrainingForm;
use App\Models\Categories\TrainingProgram;
use App\Models\Categories\TrainingTeacher;
use App\Models\Categories\Unit;
use App\Models\ProfileStatus;
use App\Models\UnitName;
use App\Scopes\CompanyScope;
use App\Scopes\DraftScope;
use App\Scopes\FrontScope;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Quiz\Entities\Quiz;
use Modules\Quiz\Entities\QuizPart;
use Modules\Quiz\Entities\QuizType;
use App\Models\Categories\TrainingObject;
use App\Models\Categories\TrainingPartner;
use App\Models\Categories\TeacherType;
use App\Models\Categories\TrainingType;
use App\Models\Categories\UnitManager;
use Modules\TableManager\Entities\Table;
use Modules\News\Entities\NewsCategory;
use Modules\NewsOutside\Entities\NewsOutsideCategory;
use Modules\Survey\Entities\Survey;
use function Google\Auth\Cache\get;
use App\Models\CourseView;
use Modules\SalesKit\Entities\SalesKitCategory;

class AjaxLoadController extends Controller
{
    public function loadAjax($func, Request  $request) {
        if (method_exists($this, $func) && Auth::check()) {
            $this->{$func}($request);
            exit();
        }
        json_message('Yêu cầu không hợp lệ', 'error');
    }

    private function loadSalesKitCategory(Request $request) {
        $search = $request->search;
        $not_id = $request->not_id;

        SalesKitCategory::addGlobalScope(new DraftScope());
        $query = SalesKitCategory::select(\DB::raw('id, name AS text'));

        if($not_id){
            $query->where('id', '!=', $not_id);
        }

        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        $result = $query->paginate(10);
        if ($result->nextPageUrl()) {
            $data['pagination'] = ['more' => true];
        }
        $data['results']=$result->getCollection();
        json_result($data);
    }

    private function loadUnitAll(Request $request) {
        $level = $request->level;
        $parent_id = $request->parent_id;
        $search = $request->search;

        Unit::addGlobalScope(new DraftScope());
        $query = Unit::select(\DB::raw('id, CONCAT(code, \' - \', name) AS text'));
        $query->where('status', '=', 1);

        if ($search) {
            $query->where(function ($sub_query) use ($search) {
                $sub_query->orWhere(\DB::raw('CONCAT(code, \' \', name)'), 'like', '%' . $search . '%');
            });
        }

        $result = $query->paginate(10);
        if ($result->nextPageUrl()) {
            $data['pagination'] = ['more' => true];
        }
        $data['results']=$result->getCollection();
        json_result($data);
    }
    private function loadUnitByLevel(Request $request) {
        $level = $request->level;
        $parent_id = $request->parent_id;
        $search = $request->search;

        Unit::addGlobalScope(new DraftScope('level_'.$level));
        $query = Unit::select(\DB::raw('id, CONCAT(code, \' - \', name) AS text'));
        $query->where('status', '=', 1);

        if ($search) {
            $query->where(function ($sub_query) use ($search) {
                $sub_query->orWhere(\DB::raw('CONCAT(code, \' \', name)'), 'like', '%' . $search . '%');
            });
        }

        if ($parent_id){
            $parent_code = \DB::table('el_unit')->where(['id' => $parent_id])->first(['code']);
            $query->where('parent_code', '=', @$parent_code->code);
        }

        if ($level>=0 && !is_null($level)) {
            $query->where('level', '=', $level);
        }

        $result = $query->paginate(10);

        if ($result->nextPageUrl()) {
            $data['pagination'] = ['more' => true];
        }
        $data['results'] = $result->getCollection();
        json_result($data);
    }
    private function loadAreaByLevel(Request $request) {
        $level = $request->level;
        $parent_id = $request->parent_id;
        $search = $request->search;

        $query = Area::select(\DB::raw('id, CONCAT(code, \' - \', name) AS text'));
        $query->where('status', '=', 1);

        if ($search) {
            $query->where(function ($sub_query) use ($search) {
                $sub_query->orWhere(\DB::raw('CONCAT(code, \' \', name)'), 'like', '%' . $search . '%');
            });
        }

        if ($level>=0 && !is_null($level)) {
            $query->where('level', '=', $level);
        }

        if ($parent_id) {
            $parent_code = Area::where(['id' => $parent_id])->first(['code']);
            $query->where('parent_code', '=', @$parent_code->code);
        }

        $result = $query->paginate(10);
        if ($result->nextPageUrl()) {
            $data['pagination'] = ['more' => true];
        }
        $data['results'] = $result->getCollection();
        json_result($data);
    }
	private function loadSurvey(Request $request) {
        $search = $request->search;

        Survey::addGlobalScope(new DraftScope());
        $query = Survey::query();
        $query->where('status', '=', 1);

        if ($search) {
            $query->where('name', 'like', '%'. $search .'%');
        }

        $paginate = $query->paginate(10);
        $data['results'] = $query->select('id', 'name AS text')->get();
        if ($paginate->nextPageUrl()) {
            $data['pagination'] = ['more' => true];
        }

        json_result($data);
    }
    //Lấy khoá học dành cho TĐV
    private function loadCoursesByUnit(Request $request) {
        $search = $request->search;
        $type = $request->type;

        $unit_user = UnitManager::getIdUnitManagedByUser();

        CourseView::addGlobalScope(new CompanyScope());
        $query = CourseView::query();
        $query->select([
            'el_course_view.course_id as id',
            'el_course_view.name',
            'el_course_view.code',
            'el_course_view.start_date',
            'el_course_view.end_date',
        ]);
        $query->where('el_course_view.status', '=', 1);
        $query->where('el_course_view.isopen', '=', 1);
        $query->where('el_course_view.offline', '=', 0);
        $query->where('el_course_view.course_type', '=', $type);
        $query->whereExists(function($sub) use($unit_user) {
            $sub->select(['id'])
            ->from('el_course_register_view')
            ->whereColumn('course_id', '=', 'el_course_view.course_id')
            ->whereColumn('course_type', '=', 'el_course_view.course_type')
            ->where('status', 1)
            ->whereIn('unit_id', $unit_user);
        });

        if ($search) {
            $query->where('el_course_view.name', 'like', '%'. $search .'%');
            $query->orWhere('el_course_view.code', 'like', '%'. $search .'%');
        }
        $query->orderByDesc('el_course_view.start_date');
        $result = $query->paginate(10);
        foreach($result as $row){
            $row->text = '('. $row->code .') '. $row->name .' - '. get_date($row->start_date);
        }

        if ($result->nextPageUrl()) {
            $data['pagination'] = ['more' => true];
        }
        $data['results'] = $result->getCollection();
        json_result($data);
    }
    private function loadCourses(Request $request) {
        $search = $request->search;
        $type = $request->type;

        CourseView::addGlobalScope(new CompanyScope());
        $query = CourseView::query();
        $query->select([
            'course_id as id',
            'name AS text'
        ]);
        $query->where('status', '=', 1);
        $query->where('offline', '=', 0);
        $query->where('course_type', '=', $type);

        if ($search) {
            $query->where('name', 'like', '%'. $search .'%');
            $query->orWhere('code', 'like', '%'. $search .'%');
        }

        $result = $query->paginate(10);
        if ($result->nextPageUrl()) {
            $data['pagination'] = ['more' => true];
        }
        $data['results'] = $result->getCollection();
        json_result($data);
    }
    private function loadTitle(Request $request) {
        $search = $request->search;
        $position_id = $request->position_id;
        $title_rank_id = $request->title_rank_id;

        Titles::addGlobalScope(new DraftScope());
        $query = Titles::query();
        $query->select(\DB::raw('id, CONCAT(code, \' - \', name) AS text'));
        $query->where('status', '=', 1);

        if ($search) {
            $query->where('name', 'like', '%'. $search .'%');
            $query->orWhere('code', 'like', '%'. $search .'%');
        }
        if ($position_id){
            $query->where('position_id', '=', $position_id);
        }
        if ($title_rank_id){
            $query->where('group', '=', $title_rank_id);
        }

        $result = $query->paginate(10);
        if ($result->nextPageUrl()) {
            $data['pagination'] = ['more' => true];
        }
        $data['results'] = $result->getCollection();
        json_result($data);
    }
    private function loadTitleRank(Request $request) {
        $search = $request->search;

        TitleRank::addGlobalScope(new DraftScope());
        $query = TitleRank::query();
        $query->where('status', '=', 1);

        if ($search) {
            $query->where('name', 'like', '%'. $search .'%');
        }

        $paginate = $query->paginate(10);
        $data['results'] = $query->select('id', 'name AS text')->get();;
        if ($paginate->nextPageUrl()) {
            $data['pagination'] = ['more' => true];
        }

        json_result($data);
    }
    private function loadTrainingProgram(Request $request) {
        $search = $request->search;

        TrainingProgram::addGlobalScope(new DraftScope());
        $query = TrainingProgram::query();
        $query->where('status', '=', 1);

        if ($search) {
            $query->where(function ($sub_query) use ($search) {
                $sub_query->orWhere(\DB::raw('CONCAT(code, \' \', name)'), 'like', '%' . $search . '%');
            });
        }

        $query->orderBy('id', 'desc');
        $paginate = $query->paginate(10);
        $data['results'] = $query->selectRaw(\DB::raw('id, CONCAT(code, \' - \', name) AS text'))->get();
        if ($paginate->nextPageUrl()) {
            $data['pagination'] = ['more' => true];
        }

        json_result($data);
    }

	private function loadTrainingObject(Request $request) {
        $search = $request->search;
        $query = TrainingObject::query();
        $query->where('status', '=', 1);

        if ($search) {
            $query->where('name', 'like', '%'. $search .'%');
        }

        $query->orderBy('id', 'desc');
        $paginate = $query->paginate(10);
        $data['results'] = $query->select('id', 'name AS text')->get();;
        if ($paginate->nextPageUrl()) {
            $data['pagination'] = ['more' => true];
        }

        json_result($data);
    }

	private function loadTrainingPartner(Request $request) {
        $search = $request->search;
        $query = TrainingPartner::query();
       // $query->where('status', '=', 1);

        if ($search) {
            $query->where('name', 'like', '%'. $search .'%');
        }

        $query->orderBy('id', 'desc');
        $paginate = $query->paginate(10);
        $data['results'] = $query->select('id', 'name AS text')->get();;
        if ($paginate->nextPageUrl()) {
            $data['pagination'] = ['more' => true];
        }

        json_result($data);
    }

    private function loadLevelSubject(Request $request) {
        $search = $request->search;
        $training_program = (int) $request->training_program;

        $query = LevelSubject::query();
        $query->where('status', '=', 1);

        if ($search) {
            $query->where('name', 'like', '%'. $search .'%');
        }

        if ($training_program) {
            $query->where('training_program_id', '=', $training_program);
        }

        $paginate = $query->paginate(10);
        $data['results'] = $query->select('id', 'name AS text')->get();;
        if ($paginate->nextPageUrl()) {
            $data['pagination'] = ['more' => true];
        }

        json_result($data);
    }

    private function loadSubject(Request $request) {
        $search = $request->search;
        $training_program = (int) $request->training_program;
        $level_subject_id = (int) $request->level_subject_id;
        $course_type = $request->course_type;

        Subject::addGlobalScope(new DraftScope());
        $query = Subject::query();
        $query->selectRaw(\DB::raw('id, CONCAT(code, \' - \', name) AS text'));
        $query->where('status', '=', 1);
        $query->where(function($sub) {
            $sub->where('subsection', '!=', 1);
            $sub->orWhereNull('subsection');
        });

        if ($search) {
            $query->where(function ($sub_query) use ($search) {
                $sub_query->orWhere(\DB::raw('CONCAT(code, \' \', name)'), 'like', '%' . $search . '%');
            });
        }

        if ($training_program) {
            $query->where('training_program_id', '=', $training_program);
        }

        if ($level_subject_id){
            $query->where('level_subject_id', '=', $level_subject_id);
        }

        if ($course_type){
            $query->whereIn('id', function ($sub) use ($course_type){
                $sub->select(['subject_id'])
                    ->from('el_course_view')
                    ->whereIn('course_type', $course_type)
                    ->pluck('subject_id')
                    ->toArray();
            });
        }

        $paginate = $query->paginate(10);

        if ($paginate->nextPageUrl()) {
            $data['pagination'] = ['more' => true];
        }
        $data['results'] = $paginate->getCollection();

        json_result($data);
    }

    private function loadUser(Request $request) {
        $search = $request->search;

        Profile::addGlobalScope(new DraftScope('user_id'));
        $query = Profile::query()
            ->where('status', '=', 1)
            ->where('type_user', '=', 1)
            ->where('user_id', '>', 2);

        if ($search) {
            $query->where(function ($sub_query) use ($search) {
                $sub_query->orWhere('code', 'like', '%'. $search .'%');
                $sub_query->orWhere(\DB::raw('CONCAT(lastname, \' \', firstname)'), 'like', '%' . $search . '%');
            });
        }
        $query->selectRaw(\DB::raw('user_id AS id, CONCAT(code, \' - \', lastname, \' \', firstname) AS text'));
        $result = $query->paginate(10);
        if ($result->nextPageUrl()) {
            $data['pagination'] = ['more' => true];
        }
        $data['results']=$result->getCollection();
        json_result($data);
    }

    private function loadAllUser(Request $request) {
        $search = $request->search;
        if(Auth::user()->isRoleManager())
            Profile::addGlobalScope(new DraftScope('user_id'));
        else
            Profile::addGlobalScope(new FrontScope('user_id'));
        $query = Profile::query()
            ->where('type_user', '=', 1)
            ->where('user_id', '>', 2);

        if ($search) {
            $query->where(function ($sub_query) use ($search) {
                $sub_query->orWhere('code', 'like', '%'. $search .'%');
                $sub_query->orWhere(\DB::raw('CONCAT(lastname, \' \', firstname)'), 'like', '%' . $search . '%');
            });
        }

        $paginate = $query->paginate(10);
        $data['results'] = $query->selectRaw(\DB::raw('user_id AS id, CONCAT(code, \' - \', lastname, \' \', firstname) AS text'))->get();
        if ($paginate->nextPageUrl()) {
            $data['pagination'] = ['more' => true];
        }

        json_result($data);
    }

    private function loadUserOther(Request $request) {
        $search = $request->search;
        $query = Profile::query()
            ->where('status', '=', 1)
            ->where('type_user', '=', 1)
            ->where('user_id', '!=', profile()->user_id)
            ->where('user_id', '>', 2);

        if ($search) {
            $query->where(function ($sub_query) use ($search) {
                $sub_query->orWhere('code', 'like', '%'. $search .'%');
                $sub_query->orWhere(\DB::raw('CONCAT(lastname, \' \', firstname)'), 'like', '%' . $search . '%');
            });
        }
        $query->selectRaw(\DB::raw('user_id AS id, CONCAT(code, \' - \', lastname, \' \', firstname) AS text'));
        $result = $query->paginate(10);
        if ($result->nextPageUrl()) {
            $data['pagination'] = ['more' => true];
        }
        $data['results']=$result->getCollection();
        json_result($data);
    }

    private function loadTeacher(Request $request) {
        $search = $request->search;
        $query = TrainingTeacher::query()
            ->where('status', '=', 1)
            ->where('user_id', '>', 2);

        if ($search) {
            $query->where(function ($sub_query) use ($search) {
                $sub_query->orWhere(\DB::raw('CONCAT(code, \' \', name)'), 'like', '%' . $search . '%');
            });
        }

        $paginate = $query->paginate(10);
        $data['results'] = $query->selectRaw(\DB::raw('id, CONCAT(code, \' - \', name) AS text'))->get();
        if ($paginate->nextPageUrl()) {
            $data['pagination'] = ['more' => true];
        }

        json_result($data);
    }

    private function loadTeacherType1(Request $request) {
        $search = $request->search;
        $query = TrainingTeacher::query()
            ->where('type', 1)
            ->where('status', '=', 1)
            ->where('user_id', '>', 2);

        if ($search) {
            $query->where(function ($sub_query) use ($search) {
                $sub_query->orWhere(\DB::raw('CONCAT(code, \' \', name)'), 'like', '%' . $search . '%');
            });
        }

        $paginate = $query->paginate(10);
        $data['results'] = $query->selectRaw(\DB::raw('user_id as id, CONCAT(code, \' - \', name) AS text'))->get();
        if ($paginate->nextPageUrl()) {
            $data['pagination'] = ['more' => true];
        }

        json_result($data);
    }

	private function loadTeacherType(Request $request) {
        $search = $request->search;
        $query = TeacherType::query()
            ->where('status', '=', 1);

        if ($search) {
            $query->where(function ($sub_query) use ($search) {
                $sub_query->orWhere('code', 'like', '%'. $search .'%');
                $sub_query->orWhere('name', 'like', '%' . $search . '%');
            });
        }

        $paginate = $query->paginate(10);
        $data['results'] = $query->selectRaw(\DB::raw('id, CONCAT(code, \' - \', name) AS text'))->get();
        if ($paginate->nextPageUrl()) {
            $data['pagination'] = ['more' => true];
        }

        json_result($data);
    }

	private function loadTrainingType(Request $request) {
        $search = $request->search;
        $query = TrainingType::query()
            ->where('status', '=', 1);

        if ($search) {
            $query->where(function ($sub_query) use ($search) {
                $sub_query->orWhere('code', 'like', '%'. $search .'%');
                $sub_query->orWhere('name', 'like', '%' . $search . '%');
            });
        }

        $paginate = $query->paginate(10);
        $data['results'] = $query->select('id', 'name AS text')->get();
        if ($paginate->nextPageUrl()) {
            $data['pagination'] = ['more' => true];
        }

        json_result($data);
    }

    //Lấy kỳ thi dành cho TĐV
    private function loadQuizsByUnit(Request $request) {
        $search = $request->search;

        $unit_user = UnitManager::getIdUnitManagedByUser();

        $query = Quiz::query();
        $query->where('status', '=', 1);
        $query->where('is_open', '=', 1);
        $query->whereExists(function($sub) use($unit_user){
            $sub->select(['a.id'])
                ->from('el_quiz_register as a')
                ->leftJoin('el_profile as b', 'b.user_id', '=', 'a.user_id')
                ->whereColumn('a.quiz_id', '=', 'el_quiz.id')
                ->whereIn('b.unit_id', $unit_user);
        });

        if ($search) {
            $query->where(function ($sub_query) use ($search) {
                $sub_query->orWhere('code', 'like', '%'. $search .'%');
                $sub_query->orWhere('name', 'like', '%' . $search . '%');
            });
        }

        $paginate = $query->paginate(10);

        $data['results'] = $query->select('id', 'name AS text')->get();

        if ($paginate->nextPageUrl()) {
            $data['pagination'] = ['more' => true];
        }
        json_result($data);
    }

    private function loadQuizs(Request $request) {
        $search = $request->search;

        Quiz::addGlobalScope(new DraftScope());
        $query = Quiz::query();
        $query->where('status', '=', 1);
        // $query->where('is_open', '=', 1);
        $query->where('course_id', 0);

        if ($search) {
            $query->where(function ($sub_query) use ($search) {
                $sub_query->orWhere('code', 'like', '%'. $search .'%');
                $sub_query->orWhere('name', 'like', '%' . $search . '%');
            });
        }

        $paginate = $query->paginate(10);

        $data['results'] = $query->select('id', 'name AS text')->get();

        if ($paginate->nextPageUrl()) {
            $data['pagination'] = ['more' => true];
        }
        json_result($data);
    }

    private function loadQuizCourse(Request $request) {
        $course_id = $request->course_id;
        $search = $request->search;

        $query = Quiz::query();
        $query->where('status', '=', 1)
            ->where('quiz_type', '=', 2)
            ->where(function($where) use ($course_id){
                $where->orWhereNull('course_id');
                $where->orWhere('course_id', '=', 0);
                $where->orWhere('course_id', '=', $course_id);
            });

        if ($search) {
            $query->where('name', 'like', '%'. $search .'%');
        }

        $paginate = $query->paginate(10);

        $data['results'] = $query->select('id', 'name AS text')->get();

        if ($paginate->nextPageUrl()) {
            $data['pagination'] = ['more' => true];
        }
        json_result($data);
    }

    private function loadQuizCourseOnline(Request $request) {
        $course_id = $request->course_id;
        $search = $request->search;

        $query = Quiz::query();
        $query->where('status', '=', 1)
            ->where('quiz_type', '=', 1)
            ->where('course_id', '=', $course_id);

        if ($search) {
            $query->where('name', 'like', '%'. $search .'%');
        }

        $paginate = $query->paginate(10);
        $data['results'] = $query->select('id', 'name AS text')->get();
        if ($paginate->nextPageUrl()) {
            $data['pagination'] = ['more' => true];
        }
        json_result($data);
    }

    private function loadPartQuizCourseOnline(Request $request) {
        $quiz_id = $request->quiz_id;
        $search = $request->search;

        $query = QuizPart::where('quiz_id', '=', $quiz_id);

        if ($search) {
            $query->where('name', 'like', '%'. $search .'%');
        }

        $paginate = $query->paginate(10);
        $data['results'] = $query->selectRaw(\DB::raw('id, CONCAT(name, \' ( \', DATE_FORMAT(start_date, "%d-%m-%Y %H:%i:%s"), \' - \', DATE_FORMAT(end_date, "%d-%m-%Y %H:%i:%s"), \' ) \') AS text'))->get();
        if ($paginate->nextPageUrl()) {
            $data['pagination'] = ['more' => true];
        }
        json_result($data);
    }

    private function loadTrainingForm(Request $request) {
        $search = $request->search;

        TrainingForm::addGlobalScope(new DraftScope());
        $query = TrainingForm::query();

        if ($search) {
            $query->where('name', 'like', '%'. $search .'%');
        }

        $paginate = $query->paginate(10);
        $data['results'] = $query->select('id', 'name AS text')->get();
        if ($paginate->nextPageUrl()) {
            $data['pagination'] = ['more' => true];
        }

        json_result($data);
    }

    private function loadQuizType(Request $request) {
        $search = $request->search;

        QuizType::addGlobalScope(new CompanyScope());
        $query = QuizType::query();

        if ($search) {
            $query->where('name', 'like', '%'. $search .'%');
        }

        $paginate = $query->paginate(10);
        $data['results'] = $query->select('id', 'name AS text')->get();
        if ($paginate->nextPageUrl()) {
            $data['pagination'] = ['more' => true];
        }

        json_result($data);
    }

    private function loadPosition(Request $request) {
        $search = $request->search;

        $query = Position::query();
        $query->where('status', '=', 1);

        if ($search) {
            $query->where('name', 'like', '%'. $search .'%');
        }

        $paginate = $query->paginate(10);
        $data['results'] = $query->select('id', 'name AS text')->get();
        if ($paginate->nextPageUrl()) {
            $data['pagination'] = ['more' => true];
        }

        json_result($data);
    }

    private function loadCategoryNew(Request $request) {
        $search = $request->search;
        $id_parent = $request->id_parent;

        $query = NewsCategory::query();
        $query->whereNull('parent_id');

        if(!empty($id_parent)) {
            $query->where('id', '!=', $id_parent);
        }

        if ($search) {
            $query->where('name', 'like', '%'. $search .'%');
        }

        $paginate = $query->paginate(10);
        $data['results'] = $query->select('id', 'name AS text')->get();
        if ($paginate->nextPageUrl()) {
            $data['pagination'] = ['more' => true];
        }

        json_result($data);
    }

    private function loadCategoryNewOutside(Request $request) {
        $search = $request->search;

        $query = NewsOutsideCategory::query();
        $query->whereNull('parent_id');

        if ($search) {
            $query->where('name', 'like', '%'. $search .'%');
        }

        $paginate = $query->paginate(10);
        $data['results'] = $query->select('id', 'name AS text')->get();
        if ($paginate->nextPageUrl()) {
            $data['pagination'] = ['more' => true];
        }

        json_result($data);
    }

    private function loadStatusProfile(Request $request){
        $search = $request->search;

        $query = ProfileStatus::query();

        if ($search) {
            $query->where('name', 'like', '%'. $search .'%');
        }

        $paginate = $query->paginate(10);
        $data['results'] = $query->select('id', 'name AS text')->get();
        if ($paginate->nextPageUrl()) {
            $data['pagination'] = ['more' => true];
        }

        json_result($data);
    }
    private function loadGroupPermission(Request $request){
        $search = $request->search;

        $query = PermissionType::where('type', '=', 2);
        if ($search) {
            $query->where('name', 'like', '%'. $search .'%');
        }

        $paginate = $query->paginate(10);
        $data['results'] = $query->select('id', 'name AS text')->get();
        if ($paginate->nextPageUrl()) {
            $data['pagination'] = ['more' => true];
        }

        json_result($data);
    }
    private function loadTable(Request $request){
        $search = $request->search;

        $query =  Table::query();
        if ($search) {
            $query->where('name', 'like', '%'. $search .'%');
        }

        $paginate = $query->paginate(10);
        $data['results'] = $query->select('id', 'name AS text')->get();
        if ($paginate->nextPageUrl()) {
            $data['pagination'] = ['more' => true];
        }

        json_result($data);
    }
    private function loadAreaLevel(Request $request) {
        $search = $request->search;

        $query = AreaName::select(\DB::raw('id, name AS text'));

        if ($search) {
            $query->where('name', 'like', '%'. $search .'%');
        }

        $result = $query->paginate(10);
        if ($result->nextPageUrl()) {
            $data['pagination'] = ['more' => true];
        }
        $data['results'] = $result->getCollection();
        json_result($data);
    }
    private function loadAreaUnitLevel(Request $request) {
        $search = $request->search;
        $query = UnitName::select(\DB::raw(" concat(999,level) as id  ,  name AS text "));
        if ($search) {
            $query->where('name', 'like', '%'. $search .'%');
        }
        $unitName = $query->get();

        $query = AreaName::select(\DB::raw(" concat(888,level) as id"),   'name AS text');
        if ($search) {
            $query->where('name', 'like', '%'. $search .'%');
        }
        $areaName= $query->get();
        $data['results'] = [
            (object)["id"=>1,"text"=>'Loại đơn vị', "children"=>(object)$unitName],
            (object)["id"=>2,"text"=>'Loại khu vực', "children"=>(object)$areaName],
            ];
        json_result($data);
    }
    private function loadUnitByAreaOrUnitLevel(Request $request) {
        $search = $request->search;
        $area_unit_type = $request->area_unit_type;
        $type = substr($area_unit_type,0,3);
        $typeId = substr($area_unit_type,3,strlen($area_unit_type)-3);
        $query = Unit::select(\DB::raw("id  ,  name AS text "));
        if ($search) {
            $query->where('name', 'like', '%'. $search .'%');
        }
        if ($type==888){// tìm theo loại khu vực
            $query->where('area_id',$typeId);
        }elseif($type==999){// tìm theo loại đơn vị
            $query->where('level',$typeId);
        }
        $result = $query->paginate(10);
        if ($result->nextPageUrl()) {
            $data['pagination'] = ['more' => true];
        }
        $data['results'] = $result->getCollection();
        json_result($data);
    }
    private function loadUnitLevel(Request $request) {
        $search = $request->search;
        $query = UnitName::select('level as id','name AS text');
        if ($search) {
            $query->where('name', 'like', '%'. $search .'%');
        }
        $result = $query->paginate(10);
        if ($result->nextPageUrl()) {
            $data['pagination'] = ['more' => true];
        }
        $data['results'] = $result->getCollection();
        json_result($data);
    }
    private function loadAreaAll(Request $request) {
        $records = [];
        $areaName = AreaName::select('id', 'name','level')->get();
        foreach ($areaName as $index => $areaName) {
            $child = collect(Area::where('level',$areaName->level)->select('id', 'name as text')->get());
            $records[] = (object)['id'=>$areaName->id,'text'=>$areaName->name,'children'=>$child];
        }
        $data['results'] = $records;
        json_result($data);
    }
}
