<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Categories\Titles;
use App\Models\CourseRegisterView;
use App\Models\CourseView;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\Request;
use Matrix\Builder;
use Modules\ConvertTitles\Entities\ConvertTitles;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Online\Entities\OnlineCourse;
use Modules\Potential\Entities\Potential;
use Modules\Quiz\Entities\Quiz;

class ChangePassController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        return view('themes.mobile.frontend.change_pass.index');
    }

    public function save(Request $request) {
        $this->validateRequest([
            'old_pass' => 'required',
            'new_pass' => 'required',
            'new_pass_1' => 'required',
        ], $request, [
            'old_pass' => 'Mật khẩu hiện tại',
            'new_pass' => 'Mật khẩu mới',
            'new_pass_1' => 'Nhập lại mật khẩu mới',
        ]);

        if($request->new_pass != $request->new_pass_1) {
            json_message('Nhập lại mật khẩu không đúng', 'error');
        }

        $user = User::find(profile()->user_id);
        if(!\Hash::check($request->old_pass, $user->password)) {
            json_message('Mật khẩu không đúng', 'error');
        } 

        $newPassword = \Hash::make($request->new_pass);
        $user->password = $newPassword;
        $user->save();

        json_result([
            'status' => 'success',
            'message' => trans('laother.successful_save'),
            'redirect' => route('themes.mobile.frontend.profile')
        ]);
    }
}
