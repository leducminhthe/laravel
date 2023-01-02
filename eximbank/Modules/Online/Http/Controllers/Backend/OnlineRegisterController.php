<?php

namespace Modules\Online\Http\Controllers\Backend;

use App\Models\Automail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Modules\Online\Entities\OnlineCourse;
use Modules\Online\Entities\OnlineInviteRegister;
use Modules\Online\Entities\OnlineObject;
use Modules\Online\Entities\OnlineRegister;
use Modules\PermissionApproved\Entities\ApprovedModelTracking;
use App\Events\SendMailRegister;

class OnlineRegisterController extends Controller
{
    public function approve(Request $request)
    {
        $this->validateRequest([
            'ids' => 'required_if:approveAll,==,',
            'status' => 'required|in:0,1',
        ], $request, [
            'ids' => trans("latraining.student"),
            'status' => trans("latraining.status")
        ]);
        
        $status = $request->input('status', null);
        $note = $request->input('note', null);
        $model = $request->input('model');

        if(!empty($request->approveAll)) {
            $ids = OnlineRegister::where('user_id', '>', 2)->where('course_id', $request->courseId)->pluck('id')->toArray();
        } else {
            $ids = $request->input('ids', null);
        }
        foreach ($ids as $id) {
            (new ApprovedModelTracking())->updateApprovedTracking(OnlineRegister::getModel(), $id, $status, $note);
        }

        $course = OnlineCourse::find($request->courseId);
        $users = OnlineRegister::whereIn('id', $ids)->get();

        $type_send_mail = 1;
        event(new SendMailRegister($users, $course, 1, $type_send_mail, $status));

        if($status == 0) {
            json_message('Đã từ chối','success');
        } else {
            json_message('Duyệt thành công','success');
        }
    }
}
