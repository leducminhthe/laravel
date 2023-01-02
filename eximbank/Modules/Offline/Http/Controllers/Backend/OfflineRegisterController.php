<?php

namespace Modules\Offline\Http\Controllers\Backend;

use App\Models\Automail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Offline\Entities\OfflineRegister;
use Modules\Offline\Entities\OfflineResult;
use Modules\PermissionApproved\Entities\ApprovedModelTracking;
use Modules\User\Entities\TrainingProcess;
use App\Events\SendMailRegister;

class OfflineRegisterController extends Controller
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
            $ids = OfflineRegister::where('user_id', '>', 2)->where('course_id', $request->courseId)->where('class_id', $request->classId)->pluck('id')->toArray();
        } else {
            $ids = $request->input('ids', null);
        }
        foreach ($ids as $id) {
            $result = OfflineResult::where('register_id', '=', $id);
            if ($result->exists() && $status == 0){
                continue;
            }

            (new ApprovedModelTracking())->updateApprovedTracking(OfflineRegister::getModel(), $id, $status, $note);
        }

        $course = OfflineCourse::find($request->courseId);
        $users = OfflineRegister::whereIn('id', $ids)->get();

        $type_send_mail = 1;
        event(new SendMailRegister($users, $course, 2, $type_send_mail, $status));

        if($status == 0) {
            json_message('Đã từ chối','success');
        } else {
            json_message('Duyệt thành công','success');
        }
    }
    public function saveEmailDeniedRegister(array $params,array $user_id,int $register_id)
    {
        $automail = new Automail();
        $automail->template_code = 'declined_enroll';
        $automail->params = $params;
        $automail->users = $user_id;
        $automail->check_exists = true;
        $automail->check_exists_status = 0;
        $automail->object_id = $register_id;
        $automail->object_type = 'declined_enroll_offline';
        $automail->addToAutomail();
    }
}
