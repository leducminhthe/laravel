<?php

namespace Modules\Quiz\Http\Controllers\Backend;

use App\Models\Profile;
use App\Models\ProfileView;
use App\Models\User;
use App\Scopes\DraftScope;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Modules\Quiz\Entities\QuizRegister;
use Modules\Quiz\Entities\QuizResult;
use Modules\Quiz\Entities\QuizSettingAlert;
use Modules\Quiz\Entities\QuizUserSecondary;
use Modules\Quiz\Imports\UserSecondaryImport;
use Modules\User\Entities\LoginFail;

class QuizUserSecondaryController extends Controller
{
    public function index() {
        $errors = session()->get('errors');
        \Session::forget('errors');
        return view('user::backend.user.index2', [
            'errors' => $errors,
        ]);
//        return view('quiz::backend.user_secondary.index', [
//            'errors' => $errors,
//        ]);
    }

    public function form(Request $request) {
        $model = Profile::join('user','el_profile.id','=','user.id')
        ->select(['user.id','el_profile.code','el_profile.firstname','el_profile.lastname','user.username','el_profile.dob','el_profile.email','el_profile.identity_card'])
            ->where('user.id', $request->id)->first();
        $dob = get_date($model->dob);
        json_result([
            'model' => $model,
            'dob' => $dob,
        ]);
    }

    public function getData(Request $request) {
        $search = $request->input('search');
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        //ProfileView::addGlobalScope(new DraftScope('user_id'));
        $query = ProfileView::query();
        $query->select([
            'el_profile_view.id',
            'el_profile_view.user_id',
            'el_profile_view.code',
            'el_profile_view.full_name',
            'el_profile_view.email',
            'u.username',
            'el_profile_view.identity_card',
            'el_profile_view.created_at'
        ]);
        $query->from('el_profile_view');
        $query->leftjoin('user as u','u.id','=','el_profile_view.user_id');
        $query->where('el_profile_view.type_user', '=', 2);

//        QuizUserSecondary::addGlobalScope(new DraftScope());
//        $query = QuizUserSecondary::query();
        if ($search) {
            $query->where(function ($sub_query) use ($search) {
                $sub_query->orWhere('el_profile_view.full_name', 'like', '%' . $search . '%');
                $sub_query->orWhere('el_profile_view.code', 'like', '%'. $search .'%');
                $sub_query->orWhere('el_profile_view.email', 'like', '%'. $search .'%');
                $sub_query->orWhere('u.username', 'like', '%'. $search .'%');
            });
        }

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        foreach ($rows as $row) {
            $row->edit_url = route('module.quiz.user_secondary.edit', ['id' => $row->id]);
            $row->created_at2 = get_date($row->created_at, 'd/m/Y');
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function save(Request $request) {
        $attribute = [
            'code' => 'Mã',
            'name' => 'Họ tên',
            'username' =>'Tên đăng nhập',
            'password' => 'Mật khẩu',
            'repassword' => 'Xác nhận mật khẩu',
            'identity_card' => 'Số căn cước',
        ];
        $this->validateRequest([
            'code' => 'required|unique:el_profile,code,'. $request->id,
            'name' => 'required',
            'username' => 'required_if:id,==,|min:6|max:32',
            'password' => 'nullable|min:8|max:32',
            'repassword' => 'same:password',
            'email' => 'nullable|email',
            'identity_card' => 'required|min:9|max:14',
        ], $request, $attribute);
        if (empty($request->id)){
            if (!$request->password)
                json_message('Mật khẩu không được để trống', 'error');
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
        try {
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
                    json_result([
                        'status' => 'success',
                        'message' => trans('laother.successful_save'),
                    ]);
                }
            }
        }catch (\Exception $e){
            dd($e);
        }
        json_message(trans('laother.save_error'), 'error');
    }

    public function remove(Request $request) {
        $ids = $request->input('ids', null);
        User::destroy($ids);
        Profile::destroy($ids);

        json_result([
            'status' => 'success',
            'message' => trans('laother.delete_success'),
        ]);
    }

    public function importUserSecondary(Request $request){
        $this->validateRequest([
            'import_file' => 'required|file'
        ], $request, ['import_file' => 'File import']);

        $import = new UserSecondaryImport();
        \Excel::import($import, $request->file('import_file'));

        if ($import->errors) {
            session()->put('errors', $import->errors);
            session()->save();
        }
        json_result([
            'status' => 'success',
            'message' => 'Import ' . trans('laapi.success'),
            'redirect' => route('module.quiz.user_secondary'),
        ]);
    }

    public function lockUserSecond(Request $request) {
        $ids = $request->input('ids', null);
        $status = $request->input('status', 0);
        // dd($status);
        if(is_array($ids)) {
            foreach ($ids as $id) {
                $user_second = QuizUserSecondary::find($id);
                if ($status == 1){
                    LoginFail::query()
                        ->updateOrCreate([
                            'user_id' => $id,
                            'user_type' => 2,
                            'username' => str_replace('secondary_', '', $user_second->username)
                        ], [
                            'user_id' => $id,
                            'user_type' => 2,
                            'username' => str_replace('secondary_', '', $user_second->username),
                            'num_fail' => 3
                        ]);
                }else{
                    LoginFail::query()
                        ->updateOrCreate([
                            'user_id' => $id,
                            'user_type' => 2,
                            'username' => str_replace('secondary_', '', $user_second->username)
                        ], [
                            'user_id' => $id,
                            'user_type' => 2,
                            'username' => str_replace('secondary_', '', $user_second->username),
                            'num_fail' => 0
                        ]);
                }
            }
        } else {
            $user_second = QuizUserSecondary::find($ids);
            if ($status == 1){
                LoginFail::query()
                    ->updateOrCreate([
                        'user_id' => $ids,
                        'user_type' => 2,
                        'username' => str_replace('secondary_', '', $user_second->username)
                    ], [
                        'user_id' => $ids,
                        'user_type' => 2,
                        'username' => str_replace('secondary_', '', $user_second->username),
                        'num_fail' => 3
                    ]);
            }else{
                LoginFail::query()
                    ->updateOrCreate([
                        'user_id' => $ids,
                        'user_type' => 2,
                        'username' => str_replace('secondary_', '', $user_second->username)
                    ], [
                        'user_id' => $ids,
                        'user_type' => 2,
                        'username' => str_replace('secondary_', '', $user_second->username),
                        'num_fail' => 0
                    ]);
            }
        }

        json_result([
            'status' => 'success',
            'message' => trans('laother.successful_save')
        ]);
    }
}
