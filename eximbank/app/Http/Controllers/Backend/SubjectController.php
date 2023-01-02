<?php
namespace App\Http\Controllers\Backend;

use App\Exports\SubjectExport;
use App\Imports\ImportSubject;
use App\Jobs\NotifyUserOfCompletedImportSubject;
use App\Models\Categories\LevelSubject;
use App\Models\Categories\Unit;
use App\Models\Notifications;
use App\Models\Categories\Subject;
use App\Models\Categories\TrainingProgram;
use App\Models\Categories\Titles;
use App\Models\Profile;
use App\Models\RelatedSubject;
use App\Models\SubjectPrerequisite;
use App\Scopes\DraftScope;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Online\Entities\OnlineCourse;
use App\Models\UserRole;
use App\Models\Permission;

class SubjectController extends Controller
{
    public function index() {
        $notifications = Notifications::where('notifiable_id', '=', profile()->user_id)
            ->where('notifiable_type', '=', 'App\Models\User')
            ->whereNull('read_at')
            ->get();
        \Session::forget('errors');

        $subject = Subject::where('status', 1)->where('subsection', 0)->get(['id','name']);
        return view('backend.category.subject.index', [
            'notifications' => $notifications,
            'subject' => $subject
        ]);
    }

    public function getData(Request $request) {
        $search = $request->input('search');
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);
        $training_program_id = $request->input('training_program_id');
        $level_subject_id = $request->input('level_subject_id');
        Subject::addGlobalScope(new DraftScope());
        $query = Subject::query();
        $query->select([
            'el_subject.code',
            'el_subject.name',
            'el_subject.subsection',
            'el_subject.id',
            'el_subject.created_by',
            'el_subject.updated_by',
            'el_subject.status',
            'b.name AS parent_name',
            'c.name as level_subject_name',
        ]);
        $query->leftJoin('el_training_program AS b', 'b.id', '=', 'el_subject.training_program_id');
        $query->leftJoin('el_level_subject as c', 'c.id', '=', 'el_subject.level_subject_id');

        if ($search) {
            $query->orWhere('el_subject.code', 'like', '%'. $search .'%');
            $query->orWhere('el_subject.name', 'like', '%'. $search .'%');
        }

        if ($training_program_id) {
            $query->where('el_subject.training_program_id', '=', $training_program_id);
        }

        if ($level_subject_id){
            $query->where('el_subject.level_subject_id', '=', $level_subject_id);
        }

        $count = $query->count();
        $query->orderBy('el_subject.'.$sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row) {
            $row->edit_url = route('backend.category.subject.edit', ['id' => $row->id]);
            $created_by = $row->created_by ? $row->created_by : 2;
            $updated_by = $row->updated_by ? $row->updated_by : 2;
            $row->info = route('backend.get_user_info',['created' => $created_by, 'updated' => $updated_by]);

            $row->check_isset = SubjectPrerequisite::where('subject_id', $row->id)->exists();
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function form(Request $request) {
        $model = Subject::findOrFail($request->id);
        $training_programs = TrainingProgram::find($model->training_program_id);
        $level_subject = LevelSubject::find($model->level_subject_id);
        $path_image = $model->image ? image_file($model->image) : null;
        
        json_result([
            'model' => $model,
            'training_programs' => $training_programs,
            'path_image' => $path_image,
            'level_subject' => $level_subject
        ]);
    }

    public function save(Request $request) {
        $this->validateRequest([
            'code' => 'required|unique:el_subject,code,'. $request->id,
            'name' => 'required',
            'status' => 'required|in:0,1',
            'training_program_id' => 'required|exists:el_training_program,id',
            'level_subject_id' => 'required|exists:el_level_subject,id',
        ], $request, Subject::getAttributeName());

        $model = Subject::firstOrNew(['id' => $request->id]);
        $model->fill($request->all());
        $model->created_date = $request->created_date ? date_convert($request->created_date) : null;
        $model->created_by = $model->created_by ? $model->created_by : profile()->user_id;

        if($request->image) {
            $sizes = config('image.sizes.medium');
            $model->image = upload_image($sizes, $request->image);
        }

        if ($request->id){
            $subject_code = $request->code;
            $get_subject_code = Subject::find($request->id);
            $check_subject_code_online = OnlineCourse::where('subject_id',$request->id)->get();
            $check_subject_code_offline = OfflineCourse::where('subject_id',$request->id)->get();
            if( (!$check_subject_code_online->isEmpty() || !$check_subject_code_offline->isEmpty()) && $get_subject_code->code != $subject_code ) {
                json_result([
                    'status' => 'warning',
                    'message' => 'Ko thể lưu vì mã khóa học đã được sử dụng. Vui lòng ko thay đổi mã khóa học',
                ]);
            }
        }

        if ($model->save()) {
            json_result([
                'status' => 'success',
                'message' => trans('laother.successful_save'),

            ]);
        }

        json_message(trans('laother.can_not_save'), 'error');
    }

    public function remove(Request $request) {
        $ids = $request->input('ids', null);
        $check_online = OnlineCourse::whereIn('subject_id', $ids)->first(['name']);
        $check_offline = OfflineCourse::whereIn('subject_id', $ids)->first(['name']);
        if(!empty($check_online)) {
            json_message('Không thể xoá. Có dữ liệu liên quan khóa học online: '. $check_online->name, 'error');
        } else if (!empty($check_offline)) {
            json_message('Không thể xoá. Có dữ liệu liên quan khóa học offline: '. $check_offline->name, 'error');
        }
        Subject::destroy($ids);
        json_result([
            'status' => 'success',
            'message' => trans('laother.delete_success'),
        ]);
    }

    public function import(Request $request) {
        $this->validateRequest([
            'import_file' => 'required|file',
        ], $request, [
            'import_file' => ''
        ]);

        $file = $request->file('import_file');
        // kiểm tra có thuộc đơn vị quản lý hay ko
        if(!Permission::isAdmin()){
            $userUnit = session()->get('user_unit');
            $user_role = UserRole::query()
                ->select(['c.unit_id', 'c.type', 'd.code', 'd.name'])->disableCache()
                ->from('el_user_role as a')
                ->join('el_role_has_permission_type as b', 'b.role_id', '=', 'a.role_id')
                ->join('el_permission_type_unit as c', 'c.permission_type_id', '=', 'b.permission_type_id')
                ->join('el_unit as d', 'd.id', '=', 'c.unit_id')
                ->where('a.user_id', '=', profile()->user_id)
                ->where('c.unit_id', '=', $userUnit)
                ->first();
        }

        $import = new ImportSubject(\Auth::user(), $user_role);
        \Excel::import($import, $request->file('import_file'));

        if ($import->errors) {
            session()->put('errors', $import->errors);
            session()->save();
        } else {
            json_result([
                'status' => 'success',
                'message' => trans('laother.successful_save'),
                'redirect' => route('backend.category.subject')
            ]);
        }

        json_result([
            'status' => 'error',
            'message' => trans('laother.unable_upload'),
            'redirect' => route('backend.category.subject')
        ]);
    }
    public function export()
    {
        return (new SubjectExport())->download('danh_sach_tai_lieu_'. date('d_m_Y') .'.xlsx');
    }

    public function ajaxIsopenPublish(Request $request) {
        $this->validateRequest([
            'ids' => 'required',
            'status' => 'required|in:0,1'
        ], $request, [
            'ids' => trans('laprofile.subject'),
        ]);

        $ids = $request->input('ids', null);
        $status = $request->input('status', 0);
        if(is_array($ids)) {
            foreach ($ids as $id) {
                $model = Subject::findOrFail($id);
                $model->status = $status;
                $model->save();
            }
        } else {
            $model = Subject::findOrFail($ids);
            $model->status = $status;
            $model->save();
        }

        json_result([
            'status' => 'success',
            'message' => trans('laother.successful_save'),
        ]);
    }

    public function formRelated(Request $request) {
        $model = SubjectPrerequisite::where('subject_id', $request->id)->first();
        if($model->title_id) {
            $title = Titles::find($model->title_id, ['name', 'id']);
        }
        json_result([
            'model' => $model,
            'title' => $title
        ]);
    }

    // LƯU ĐIỀU KIỆN TIÊN QUYẾT
    public function saveRelatedSubject(Request $request) {
        if($request->subject_prerequisite) {
            if(!$request->date_finish_prerequisite || !$request->score_prerequisite) {
                json_message('Chưa nhập ngày hoàn thành hoặc điểm', 'error');
            }
        }
        if($request->status_title && !$request->title_id) {
            json_message('Chưa chọn chức danh', 'error');
        }
        
        $status_title = $request->status_title ? 1 : 0;
        $status_date_title_appointment = $request->status_date_title_appointment ? 1 : 0;
        $status_join_company = $request->status_join_company ? 1 : 0;

        $save_related = SubjectPrerequisite::firstOrNew(['subject_id' => $request->id_subject]);
        $save_related->subject_id = $request->id_subject;
        $save_related->subject_prerequisite = $request->subject_prerequisite;
        $save_related->date_finish_prerequisite = $request->date_finish_prerequisite;
        $save_related->finish_and_score = $request->finish_and_score;
        $save_related->score_prerequisite = $request->score_prerequisite;
        $save_related->select_subject_prerequisite = $request->select_subject_prerequisite;
        $save_related->status_title = $status_title;
        $save_related->title_id = $request->title_id;
        $save_related->select_title = $request->select_title;
        $save_related->status_date_title_appointment = $status_date_title_appointment;
        $save_related->date_title_appointment = $request->date_title_appointment; 
        $save_related->select_date_title_appointment = $request->select_date_title_appointment; 
        $save_related->status_join_company = $status_join_company; 
        $save_related->join_company = $request->join_company; 
        $save_related->save();

        json_result([
            'status' => 'success',
            'message' => trans('laother.successful_save'),
        ]);
    }
}
