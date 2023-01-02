<?php

namespace App\Http\Controllers\Auth;

use App\Jobs\SaveUserLogin;
use App\Models\Automail;
use App\Events\Logged;
use App\Events\LoginSuccess;
use App\Events\Logout;
use App\Http\Controllers\Controller;
use App\Models\LoginHistory;
use App\Models\MailTemplate;
use App\Models\Permission;
use App\Models\Profile;
use App\Models\ProfileView;
use App\Models\SliderOutside;
use App\Models\InfomationCompany;
use App\Models\UserContactOutside;
use App\Models\AdvertisingPhoto;
use App\Models\User;
use App\Models\UserThird;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Jenssegers\Agent\Agent;
use Modules\NewsOutside\Entities\NewsOutsideCategory;
use Modules\NewsOutside\Entities\NewsOutside;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Redirect;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    protected function authenticated()
    {
        \Auth::logoutOtherDevices(request('password'));
    }

    public function login(Request $request)
    {
        $rules = [
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ];
//         if (request()->session()->has('login_attempts')) {
//             $attempts = request()->session()->get('login_attempts');

//             if ($attempts > 5) {
//                 $rules['captcha'] = 'required|captcha';
// //                $rules['g-recaptcha-response'] = 'required|captcha';
//             }
//         }
        $validator = \Validator::make($request->all(), $rules, $messages = [
            'password.required' => 'Mật khẩu không được trống',
            'username.required' => 'Tên đăng nhập không được trống',
        ]);
        if ($validator->fails()) {
            return \redirect(route('login'))->with('message',$validator->errors()->all()[0]);
        }
        $username = $request->post('username');
        $password = $request->post('password');
        $remember = $request->filled('remember');
        $language = $request->post('language');

        session(['username' => $username]);
        session()->save();

        $user = User::whereUsername($username)->where('auth', '!=', 'blocked')->first(['id', 'username', 'auth']);
        if ($user) { //Kiểm tra username tồn tại và không bị khoá
            $profile = ProfileView::whereUserId($user->id)->first();
            if(isset($profile) && $profile->status_id != 0){ // Kiểm tra trạng thái nhân viên chưa nghỉ việc
                if ($user->login($password, $remember)) {
                    $request->session()->put('login_attempts', 0);
                    session()->put('locale_'. $user->id, $language);
                    if(session()->has('locale_'. $user->id)){
                        \App::setLocale($language);
                    }

                    session(['profile' => $profile]);
                    session(['login' => 1]);
                    session(['survey_popup_time' => now()->addSeconds(5)]);
                    session()->save();

                    // $profile_change = ProfileChangedPass::where('user_id', Auth::user()->id);
                    // if(!$profile_change->first()){
                    //     $profile_change = new ProfileChangedPass();
                    //     $profile_change->user_id = Auth::user()->id;
                    //     $profile_change->save();
                    // }
                    $user_social = json_encode([
                        'username' => Auth::user()->username,
                        'password' => Auth::user()->password,
                    ]);
                    $user_social = base64_encode($user_social);
                    session(['user_social' => $user_social]);
                    session()->save();

                    $agent = new Agent();
                    SaveUserLogin::dispatch($user->id,$agent,\Request::userAgent());
                    if (url_mobile()){
                        return \redirect(route('themes.mobile.frontend.home'));
                    }

                    /*if (session()->has('url_previous')){
                        $urlPrevious = session()->get('url_previous');
                        if (strpos($urlPrevious,'/admin-cp/')===false)
                            $url = route('frontend.all_course',['type' => 3]) ;
                        else
                            $url = $urlPrevious;
                    }else{
                        $url = route('frontend.all_course',['type' => 3]);
                    }*/
                    $url = session()->has('url_previous') ? session()->get('url_previous') : route('frontend.home_after_login');
                    return \redirect($url);

                }
            }
        }
        $this->sendFailedLoginResponse($request);
        return \redirect(route('login'))->with('message',trans('auth.login_user'));
    }

    public function logout(Request $request)
    {
        if(url_mobile()) {
            $flag = 1;
        } else {
            $flag = 0;
        }
//        $user = \Auth::user();
        $isLoginAzure = session('autho');
        $user_id = profile()->user_id;
        $model = LoginHistory::where('user_id', '=', $user_id)->orderBy('created_at', 'DESC')->first();
        if ($model) {
            $model->updated_at = time();
            $model->save();
        }
        session()->flush();
        $this->guard()->logout();
        $request->session()->regenerate();
        $request->session()->invalidate();

        if($flag == 1) {
            return  redirect()->route('login');
        }
        session(['logout' => 1]);
        session()->save();
        // event(new Logout($user_id));
//        return  redirect(route('home_outside', ['type' => 0]));
        return  redirect(route('login'));
    }

    public function logoutAzure(Request $request)
    {
        return  redirect('https://login.microsoftonline.com/common/oauth2/logout?post_logout_redirect_uri='.route('returnLogoutAzure'));
//        return  redirect('https://login.microsoftonline.com/c940a8c7-1c8f-44f1-9f2c-de62cdef713d/oauth2/logout?post_logout_redirect_uri='.route('returnLogoutAzure'));
    }
    public function returnLogoutAzure(){
        if(url_mobile()) {
            $flag = 1;
        } else {
            $flag = 0;
        }
        \Session::flush();

        if($flag == 1) {
            return  redirect()->route('mobile');
        }
        return redirect(route('login'));
    }
    public function showLoginForm()
    {

        $ip = \Request()->ip();
        $data = \Location::get($ip);
        if(!empty($data) && $data->countryCode != 'VN') {
            $language = 'en';
        } else {
            $language = 'vi';
        }
        \App::setLocale($language);
        if(session()->get('logout') == 1 || !url()->previous() || url()->previous() == route('login')) {
            session(['url_previous' => '/']);
        }else{
            $domain_previous = parse_url(url()->previous(), PHP_URL_HOST);
            $domain = parse_url(config('app.url'), PHP_URL_HOST);
            session(['url_previous' => $domain_previous!=$domain? url('/'): url()->previous()]);
        }

        if (url_mobile()){
            session(['layout' => 'mobile']);
            return view('themes.mobile.auth.login');
        }
        return view('auth.login',[
            'language' => $language
        ]);
    }

    public function resetPass(Request $request){
        $this->validateRequest([
            'username' => 'required',
            'email' => 'required',
        ], $request, [
            'username' => 'username',
            'email' => 'email',
        ]);
        $username = $request->username;
        $email = $request->email;

        $user = User::where('username', '=', $username)->where('auth', '=', 'manual')->first();
        if ($user && $user->id > 2){
            $profile = Profile::where('user_id', '=', $user->id)->where('email', '=', $email)->first();
            if ($profile){
                $pass_new = Str::random(10);

                $check_template_mail = MailTemplate::where('code', '=', 'reset_pass');
                if (!$check_template_mail->exists()){
                    $mail_template = new MailTemplate();
                    $mail_template->code = 'reset_pass';
                    $mail_template->name = 'Lấy lại mật khẩu khi quên';
                    $mail_template->title = 'Mail lấy lại mật khẩu';
                    $mail_template->content = 'Mật khẩu mới của bạn là: {pass}';
                    $mail_template->note = 'Đối tượng nhận: mọi user';
                    $mail_template->status = 1;
                    $mail_template->save();
                }

                $automail = new Automail();
                $automail->template_code = 'reset_pass';
                $automail->params = [
                    'pass' => $pass_new,
                ];
                $automail->users = [$profile->user_id];
                $automail->object_id = $profile->user_id;
                $automail->object_type = 'reset_pass';
                $automail->addToAutomail();

                $user->password = \Hash::make($pass_new);
                $user->save();

                return response()->json([
                    'status' => 'success',
                    'message' => 'Password đã thay đổi. Mời vào mail bạn lấy thông tin',
                    'redirect' => route('login'),
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Email không đúng',
                    'redirect' => route('login'),
                ]);
            }
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Tên đăng nhập không đúng',
                'redirect' => route('login'),
            ]);
        }
    }

    public function resetPassUserQuestion(Request $request){
        $user_id = decrypt_array($request->user_id);
        $password = $request->password;

        $user = User::find($user_id[0]);
        if($user && $user->id > 2){
            $user->password = password_hash($password, PASSWORD_DEFAULT);
            $user->save();

            return json_message('Password đã thay đổi','success');
        }
        return json_message('Password chưa thay đổi','error');
    }

    public function saveUserThird(Request $request){
        $this->validateRequest([
            'username' => 'required_if:id,==,|unique:user,username',
            'password' => 'nullable|required_if:id,==,',
            'lastname' => 'required',
            'firstname' => 'required',
        ],$request, Profile::getAttributeName());

        $user = User::firstOrNew(['id' => $request->id]);
        $user->auth = 'manual';
        $user->username = $request->username;
        $user->password = password_hash($request->password, PASSWORD_DEFAULT);
        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->save();

        $model = Profile::firstOrNew(['id' => $user->id]);
        $model->fill($request->all());
        $model->code = Str::random(10);
        $model->unit_code = 'DV1';
        $model->title_code = 'CD1';
        $model->type_user = 2;
        $model->id = $user->id;
        $model->user_id = $user->id;

        if ($model->save()) {
            json_result([
                'status' => 'success',
                'message' => trans('app.notify_register_success'),
                'redirect' => route('login')
            ]);
        }

        json_message(trans('laother.can_not_save'), 'error');
    }

    public function homeOutside($type){
        $url = url()->previous();
        $check = '';
        if (in_array('detail-home-outside', explode('/',$url)) || in_array('news-outside', explode('/',$url)) || in_array('admin-cp', explode('/',$url))) {
            $check = 1;
        }
        $get_app_url = env('APP_URL');
        $home_outside = route('home_outside',['type' => 0]);
        $login = route('login');
        if( $url && $check != 1
            && ($get_app_url != $url)
            && ($url != $home_outside)
            && ($url != $login))
        {
            session(['url_previous' => $url]);
            session()->save();
        }

        $sliders = SliderOutside::where('status', '=', 1)->get();
        $get_main_new_hot = NewsOutside::select(['id','title','date_setup_icon','description','image','created_at','views'])->where('hot_public',1)->orderByDesc('created_at')->first();
        $get_hot_news = '';
        if($get_main_new_hot) {
            $get_hot_news = NewsOutside::select(['id','image','description','title','date_setup_icon','created_at','views'])->where('status',1)->where('hot_public',1)->where('id','!=',$get_main_new_hot->id)->get();
        }

        $get_news_parent_cate_left = NewsOutsideCategory::whereNull('parent_id')->where('status',1)->orderBy('stt_sort_parent','asc')->get();

        $get_news_category_sort_right = NewsOutsideCategory::query()
        ->select('a.*')
        ->from('el_news_outside_category as a')
        ->leftJoin('el_news_outside_category as b','b.id','=','a.parent_id')
        ->where('a.sort',2)
        ->orderBy('b.stt_sort_parent', 'asc')
        ->orderBy('a.stt_sort', 'asc')->get();

        $get_infomation_company = InfomationCompany::first();

        $getAdvertisingPhotos = AdvertisingPhoto::where('status',1)->where('type',0)->get();
        if($type == 1) {
            session(['show_home_page' => 1]);
            session()->save();
        }
        return view('frontend.home_outside', [
            'sliders' => $sliders,
            'users_online' => User::countUsersOnline(),
            'get_main_new_hot' => $get_main_new_hot,
            'get_hot_news' => $get_hot_news,
            'get_news_parent_cate_left' => $get_news_parent_cate_left,
            'get_news_category_sort_right' => $get_news_category_sort_right,
            'getAdvertisingPhotos' => $getAdvertisingPhotos,
            'get_infomation_company' => $get_infomation_company,
            'type' => $type,
        ]);
    }

    public function detailHomeOutside($id, $type) {
        $sliders = SliderOutside::where('status', '=', 1)->get();

        $get_news_category_sort_right = NewsOutsideCategory::query()
        ->select('a.*')
        ->from('el_news_outside_category as a')
        ->leftJoin('el_news_outside_category as b','b.id','=','a.parent_id')
        ->where('a.sort',2)
        ->orderBy('b.stt_sort_parent', 'asc')
        ->orderBy('a.stt_sort', 'asc')->get();

        // dd($get_news_category_sort_right);
        $get_new_outside = NewsOutside::find($id);
        $get_new_outside->views = $get_new_outside->views + 1;
        $get_new_outside->save();
        $get_category = NewsOutsideCategory::where('id',$get_new_outside->category_id)->first();
        $get_category_parent = NewsOutsideCategory::where('id',$get_category->parent_id)->first();
        $get_related_news_outside = NewsOutside::select(['image','title','description','id'])->where('category_id',$get_new_outside->category_id)->where('status',1)->where('id','!=',$get_new_outside->id)->paginate(20);
        $getAdvertisingPhotos = AdvertisingPhoto::where('status',1)->where('type',0)->get();
        $get_infomation_company = InfomationCompany::first();
        return view('frontend.detail_home_outside', [
            'sliders' => $sliders,
            'users_online' => \App\Models\User::countUsersOnline(),
            'get_news_category_sort_right' => $get_news_category_sort_right,
            'get_new_outside' => $get_new_outside,
            'get_related_news_outside' => $get_related_news_outside,
            'get_category' => $get_category,
            'get_category_parent' => $get_category_parent,
            'getAdvertisingPhotos' => $getAdvertisingPhotos,
            'get_infomation_company' => $get_infomation_company,
            'type' => $type,
        ]);
    }

    public function hotNewsHomeOutside() {
        $get_hot_news = NewsOutside::select(['image','title','description','id'])->where('hot',1)->where('status',1)->get();
        return view('frontend.hot_news_outside',[
            'get_hot_news' => $get_hot_news,
        ]);
    }

    public function likeNewOutside(Request $request) {
        $check_like = 0;
        $id = $request->id;
        // dd(session()->get('like'));
        if (empty(session()->get('like'))) {
            $sessionLike = session()->put('like', []);
            $like = NewsOutside::find($request->id);
            $like->like_new = $like->like_new + 1;
            $check_like = 1;
            session()->push('like', $id);
            session()->save();
            $like->save();
        } else {
            $sessionLike = session()->get('like');
            if (($key = array_search($request->id, $sessionLike)) !== false) {
                unset($sessionLike[$key]);
                $sessionLike = array_values($sessionLike);
                $like = NewsOutside::find($request->id);
                $like->like_new = $like->like_new - 1;
                $like->save();
                session()->forget('like');
                session()->put('like', $sessionLike);
                session()->save();
            } else {
                $like = NewsOutside::find($request->id);
                $like->like_new = $like->like_new + 1;
                $check_like = 1;
                session()->push('like', $id);
                session()->save();
                $like->save();
            }
        }

        return json_result([
            'view_like'=>$like->like_new,
            'check_like'=>$check_like,
        ]);
    }

    public function ajaxGetRelatedNews(Request $request) {
        $category_id = $request->category_id;
        $date_search = date("Y-m-d", strtotime($request->date_search));
        $new_id = $request->new_id;
        $get_related_news_outside = NewsOutside::where('category_id',$category_id)
        ->select(['image','title','description','id'])
        ->where('status',1)
        ->where('id','!=',$new_id)
        ->whereDate('created_at', '=', $date_search)
        ->get();
        // dd($get_related_news_outside);
        $image_related_new = [];
        if(!$get_related_news_outside->isEmpty()){
            foreach($get_related_news_outside as $item) {
                $image_related_new[] = ['image' => image_file($item->image), 'id' => $item->id, 'title' => $item->title, 'description' => $item->description];
            }
        }
        return json_result([
            'get_related_news_outside'=>$image_related_new,
        ]);
    }

    public function userContact() {
        $model = new InfomationCompany();
        $get_infomation_company = InfomationCompany::first();
        return view('frontend.user_contact_outside',[
            'model' => $model,
            'get_infomation_company' => $get_infomation_company,
        ]);
    }

    public function saveUserContact(Request $request) {
        $this->validateRequest([
            'title' => 'required',
            'content' => 'required',
        ], $request, UserContactOutside::getAttributeName());

        $model = UserContactOutside::firstOrNew(['id' => $request->id]);
        $model->fill($request->all());

        if ($model->save()) {
            json_result([
                'status' => 'success',
                'message' => trans('laother.successful_save'),
                'redirect' => route('user_contact_outside')
            ]);
        }

        json_message(trans('laother.can_not_save'), 'error');

    }

    public function modalResetPass(){
        if (url_mobile()){
            return view('themes.mobile.modal.fogot_password');
        }
        return view('modal.reset_pass');
    }
    public function sendFailedLoginResponse(Request $request)
    {
        $attempts = 0;
        if ($request->session()->has('login_attempts')) {
            $attempts = $request->session()->get('login_attempts');
        }
        $request->session()->put('login_attempts', $attempts + 1);

    }

    public function firstLogin(){
        return view('auth.first_login');
    }

    public function reloadCaptcha()
    {
        return response()->json(['captcha'=> captcha_img()]);
    }
}
