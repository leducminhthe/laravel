<?php

namespace App\Http\Middleware;

use App\Models\Categories\Unit;
use App\Models\Permission;
use Closure;
use Modules\User\Entities\ProfileChangedPass;
use Modules\User\Entities\User;
use TorMorten\Eventy\Facades\Events;
use Illuminate\Http\Request;
class Authenticate
{
    public function handle(Request $request, Closure $next)
    {
//        Events::action('auth.middleware_handle', $request);

        if (!\Auth::check()) {
            if ($request->ajax()){
                return response()->json(["message", "Authentication Required!"],419);
            }

            if (url_mobile()){
                return redirect()->route('login');
            }

            //return redirect()->route('home_outside',['type' => 0]);
            return redirect()->route('login');
        }
         if (\Auth::check()){
             $session_role = \session()->get('user_role');
             $currentRole = $session_role;
             if (!$session_role ) {
                 $roles = User::getRoles();
                 // check vai trò có quyền vào url
                 $check= false;
                 if($request->segment(1)=='admin-cp') {
                     $role = 'manager';
                     $check=true;
                 }
                 elseif ($request->segment(1)=='leader-cp') {
                     $role = 'unit_manager';
                     $check=true;
                 }
                 elseif ($request->segment(1)=='teacher-cp') {
                     $role = 'teacher';
                     $check=true;
                 }
                //  if($role !=$roles[0]->role && $check==true)
                //      return abort('403', 'Permission denied !');
                if (count($roles) == 1) {
                    \session()->put('user_role', $roles[0]->role);
                    \session()->save();
                }
             }
             else{
                 if($request->segment(1)=='leader-cp' && Permission::isUnitManager())
                 {
                     $switchRole = 'unit_manager';
                     \session()->put('user_role', 'unit_manager');
                     \session()->save();
                 }
                 elseif($request->segment(1)=='teacher-cp' && Permission::isTeacher()){
                     $switchRole = 'teacher';
                     \session()->put('user_role', 'teacher');
                     \session()->save();
                 }
                 elseif($request->segment(1)=='admin-cp' && \Auth::user()->existsRole())
                 {
                     $switchRole = 'manager';
                     \session()->put('user_role', 'manager');
                     \session()->save();
                 }
             }
             /**user unit**/
             $session_unit = session()->get('user_unit');
             if (!$session_unit || ($switchRole && $currentRole!=$switchRole)) { // chưa tồn tại || chuyển vai trò
                 $userRole = \session()->get('user_role');
                 $unit = User::getUnitFirstByRole($userRole);
                 if ($unit) {
                     \session()->put(['user_unit'=> $unit->id,'user_unit_info'=>$unit]);
                     \session()->save();
                 }
             }elseif ($request->input('unit_select') && $request->input('unit_select')!=$session_unit){ // chọn đơn vị khi có quản lý nhiều đơn vị
                 $unitSelect = $request->input('unit_select');
                 $unit = Unit::find($unitSelect,['id','code','name']);
                 \session()->put(['user_unit'=> $unitSelect,'user_unit_info'=>$unit]);
                 \session()->save();
             }
         }
        // if (\Auth::check()){
        //     if (request()->getRequestUri() ===  '/user/change-pass-first'){
        //         return $next($request);
        //     }else{
        //         $changed_pass = ProfileChangedPass::checkChangedPass(profile()->user_id);
        //         if (!$changed_pass && profile()->user_id > 2){
        //             return redirect()->route('first_login');
        //         }
        //     }
        // }

        return $next($request);
    }
}
