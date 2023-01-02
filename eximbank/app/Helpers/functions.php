<?php

use App\Helpers\LaravelHooks;
use App\Models\MailSignature;
use App\Models\Profile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Modules\Quiz\Entities\QuizUserSecondary;
use Intervention\Image\ImageManagerStatic;
use App\Models\User;
use App\Models\Permission;
use App\Models\Config;
use App\Models\ProfileView;
use App\Scopes\CompanyScope;
use Illuminate\Support\Facades\Cache;
use Modules\Config\Entities\ConfigEmail;
use Modules\Survey\Entities\Survey;
use Modules\Survey\Entities\SurveyPopup;
use App\Models\Categories\Unit;

function json_result($result_data) {
    header('Content-Type: application/json');
    echo json_encode($result_data);
    exit();
}

function json_message($message, $status = 'success') {
    header('Content-Type: application/json');
    echo json_encode(['status' => $status, 'message' => $message]);
    exit();
}
function json_success() {
    json_message(trans('backend.update_success'));
}
function isUrl($url) {
    if (filter_var($url, FILTER_VALIDATE_URL)) {
        return true;
    }

    return false;
}

function data_file($path, $return_path = true, $disk = 'local') {
    if (empty($path)) {
        return false;
    }

    $storage = \Storage::disk($disk);
    $file_path = $storage->path($path);

    if (file_exists($file_path) && !is_dir($file_path)) {
        if ($return_path) {
            return $file_path;
        }
        return $storage->url($path);
    }

    return null;
}

function upload_file($path) {
    $storage = \Storage::disk(config('app.datafile.upload_disk'));

    if ($storage->exists($path)) {
        return $storage->url($path);
    }

    return null;
}

function image_file($path, $name = null) {
    if (isUrl($path)) {
        return $path;
    }

    $data_file = upload_file($path);
    if ($data_file) {
        return $data_file;
    }

    if ($name == 'forum1') {
        return asset('images/design/icon_forum_1.png');
    }
    if ($name == 'forum2') {
        return asset('images/design/icon_forum_2.png');
    }
    if ($name == 'logo') {
        return asset('images/logo_topleaning.webp');
    }
    if($name == 'avatar'){
        return asset('images/design/user_50_50.png');
    }

    return asset('images/image_default.webp');
}

function image_user($path, $size = 0) {
    if($path){
        $data_file = upload_file('profile/' . $path);
        if ($data_file) {
            return $data_file;
        }

        return image_file($path, 'avatar');
    }

    if($size == 150){
        return asset('images/design/user_150_150.png');
    }

    return asset('images/design/user_50_50.png');
}

function image_library($path) {
    $data_file = upload_file($path);
    if ($data_file) {
        return $data_file;
    }

    return asset('images/design/libraries_default.png');
}

function image_quiz($path) {
    $data_file = upload_file($path);
    if ($data_file) {
        return $data_file;
    }

    return asset('images/design/quiz_default_bg_none.png');
}

function image_course($path) {
    $data_file = upload_file($path);
    if ($data_file) {
        return $data_file;
    }

    return asset('images/design/course_default.png');
}

function image_online($path, $type = null) {
    $data_file = upload_file($path);
    if ($data_file) {
        return $data_file;
    }

    if(url_mobile()) {
        return asset('themes/mobile/img/online_default.png');
    } else {
        if($type == 'detail') {
            return asset('images/design/online_detail_default.png');
        } else {
            return asset('images/design/online_default.png');
        }
    }

}

function image_offline($path, $type = null) {
    $data_file = upload_file($path);
    if ($data_file) {
        return $data_file;
    }

    if(url_mobile()) {
        return asset('themes/mobile/img/offline_default.png');
    } else {
        if($type == 'detail') {
            return asset('images/design/offline_detail_default.png');
        } else {
            return asset('images/design/offline_default.png');
        }
    }
}

function image_daily($path) {
    $data_file = upload_file($path);
    if ($data_file) {
        return $data_file;
    }

    return asset('images/design/dailytraining_default.jpg');
}

function image_topic_situation($path) {
    $data_file = upload_file($path);
    if ($data_file) {
        return $data_file;
    }

    return asset('images/design/topic_situation_default.png');
}

function image_forum_1($path) {
    $data_file = upload_file($path);
    if ($data_file) {
        return $data_file;
    }

    return asset('images/design/icon_forum_1.png');
}

function image_forum_2($path) {
    $data_file = upload_file($path);
    if ($data_file) {
        return $data_file;
    }

    return asset('images/design/icon_forum_2.png');
}

function image_survey($path) {
    $data_file = upload_file($path);
    if ($data_file) {
        return $data_file;
    }

    return asset('images/design/survey_default.png');
}

function image_promotion($path) {
    $data_file = upload_file($path);
    if ($data_file) {
        return $data_file;
    }

    return asset('images/design/quatang.png');
}

function image_chuongtrinhthidua($path) {
    $data_file = upload_file($path);
    if ($data_file) {
        return $data_file;
    }

    return asset('images/design/chuongtrinhthidua.png');
}

function image_app_store($path) {
    $data_file = upload_file($path);
    if ($data_file) {
        return $data_file;
    }

    return asset('images/design/btn_app_store.jpg');
}

function image_google_play($path) {
    $data_file = upload_file($path);
    if ($data_file) {
        return $data_file;
    }

    return asset('images/design/btn_google_play.jpg');
}

function upload_image($sizes, $image, $type = null) {
    $uploadPath = data_file(path_upload($image), true, 'upload');
    $file_path = path_upload($image);
    $storage = \Storage::disk('upload');
    if($type) {
        if (str_contains($image, 'uploads/')) {
            $imageDetail = str_replace("uploads/", "uploads/detail/", $image);
        } else {
            $imageDetail = 'detail/' . $image;
        }
        $uploadPathDetail = data_file(path_upload($imageDetail), true, 'upload');
        $file_path_detail = path_upload($imageDetail);
        resize_image($storage, [516,323], $uploadPathDetail, $file_path_detail);
    }
    $new_folder = date('Y/m/d') . '/';
    if (!$storage->exists($new_folder)) {
        \File::makeDirectory($storage->path($new_folder), 0777, true);
    }

    resize_image($storage, $sizes, $uploadPath, $file_path);
    return $file_path;
}

function resize_image($storage, $sizes, $uploadPath, $file_path) {
    if(!empty($sizes)) {
        list($width, $height) = $sizes;
    }

    $resize_image = ImageManagerStatic::make($uploadPath);
    if (!in_array('gif', explode('.', $file_path))) {
        if(!empty($sizes))
        $resize_image->resize($width, $height);
        $resize_image->encode('webp', 100);
        $resize_image->save($storage->path($file_path), 80, 'webp');
    } else {
        $resize_image->encode('gif',80);
        $resize_image->destroy();
    }
}

function sub_char($string, $limit = 50, $end = '...') {
    return \Illuminate\Support\Str::words($string, $limit, $end);
}

function get_date($date, $format = "d/m/Y"){
    if(empty($date)) {
        return '';
    }
    $date = str_replace('/','-',$date);
    return date($format, strtotime($date));
}
function get_datetime($date, $format = "d/m/Y H:i:s A"){
    if(empty($date)) {
        return '';
    }

    return date($format, strtotime($date));
}
function add_day($days, $format = "d/m/Y"){
    $date = date("Y-m-d");
    return date($format, strtotime($date."+ $days days"));
}

function filesize_formatted($file_size)
{
    $units = array( 'B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
    $power = $file_size > 0 ? floor(log($file_size, 1024)) : 0;
    return number_format($file_size / pow(1024, $power), 2, '.', ',') . ' ' . $units[$power];
}

/* chuyển date từ 'd/m/Y' sang 'Y-m-d H:i:s' */
function date_convert($date, $time = '00:00:00') {
    if($date){
        $date = str_replace('/', '-', $date);
        return date('Y-m-d H:i:s', strtotime($date .' '. $time));
    }

    return null;
}
/* chuyển datetime từ d/m/Y H:i:s sang 'Y-m-d H:i:s' */
function datetime_convert($date_time) {
    if($date_time){
        $date_time = str_replace('/', '-', $date_time);
        return date('Y-m-d H:i:s', strtotime($date_time));
    }

    return null;
}
function unnumber_format($number) {
    $number = str_replace(".", "", $number);
    return (double) $number;
}

function download_template($file) {
    $file_path = data_file('import_template/'. $file);
    if ($file_path) {
        return route('download_file', ['path' => Crypt::encryptString('import_template/'. $file)]);
    }

    return '';
}

function permission($code) {
    if (Permission::isAdmin()) {
        return true;
    }

    $permission = Permission::where('code', $code)->first();
    if (empty($permission)) {
        return false;
    }

    if ($permission->unit_permission == 0) {
        return Permission::hasPermission($code);
    }

    return Permission::hasPermissionUnit($code);
}

function link_download($data_path) {
    if (empty($data_path)) {
        return false;
    }

    $storage = \Storage::disk('local');
    if ($storage->exists($data_path)) {
        return route('download_file', ['path' => Crypt::encryptString($data_path)]);
    }

    //$file_name = basename($data_path);
    //$working_dir = urlencode(str_replace('/filemanager', '', str_replace('/'. $file_name, '', $data_path)));
    //return url('/') . '/filemanager/download?working_dir=' . $working_dir . '&type=files&file='. urlencode($file_name);
    return false;
}

function if_empty($var, $default) {
    if (!isset($var)) {
        return $default;
    }
    return empty($var) ? $default : $var;
}

function path_upload($file_path)
{
    $path = explode('uploads/', $file_path);
    if (isset($path[1])) {
        return $path[1];
    }

    $path = explode('filemanager/', $file_path);
    if (isset($path[1])) {
        return $path[1];
    }

    return $file_path;
}

function cal_date($date1, $date2) {
    $diff = abs(strtotime($date2) - strtotime($date1));
    $years = floor($diff / (365*60*60*24));
    $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
    $days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));

    $total_year = $years + $months/12 + $days/365;

    return number_format($total_year, 2);
}

function cal_date_by_month($date1, $date2){
    $diff = abs(strtotime($date2) - strtotime($date1));
    $years = floor($diff / (365*60*60*24));
    $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
    $days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));

    $total_month = $years*12 + $months;

    return number_format($total_month, 2);
}

function calculate_time_span($date1, $date2){
    $diff = abs($date1 - $date2);

    $years = floor($diff / (365*60*60*24));
    $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
    $days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24) / (60*60*24));
    $hours = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24) / (60*60));
    $mins = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24 - $hours*60*60) / 60);
    $secs = floor($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24 - $hours*60*60 - $mins*60);

    $time = $hours > 0 ? ($hours .":". $mins.":" .$secs."s") : ($mins." phút " .$secs." giây");

    return $time;
}

function is_json($string) {
    json_decode($string);
    return (json_last_error() == JSON_ERROR_NONE);
}

function check_format_date($date){
    if (preg_match("/^(((0[1-9]|[12]\d|3[01])\/(0[13578]|1[02])\/((19|[2-9]\d)\d{2}))|((0[1-9]|[12]\d|30)\/(0[13456789]|1[012])\/((19|[2-9]\d)\d{2}))|((0[1-9]|1\d|2[0-8])\/02\/((19|[2-9]\d)\d{2}))|(29\/02\/((1[6-9]|[2-9]\d)(0[48]|[2468][048]|[13579][26])|((16|[2468][048]|[3579][26])00))))$/",$date)) {
        return true;
    } else {
        return false;
    }
}

function data_locale($name, $name_en) {
    $locale = \App::getLocale();
    if ($locale == 'en') {
        if (empty($name_en)) {
            return $name;
        }
        return $name_en;
    }

    return $name;
}

function url_query($to, array $params = [], array $additional = []) {
    return \Str::finish(url($to, $additional), '?') . \Arr::query($params);
}

function is_youtube_url(string $url) {
    if (strpos($url, 'youtube.com') !== false) {
        return true;
    }

    return false;
}

function get_youtube_id(string $url) {
    $parts = parse_url($url);
    if(isset($parts['query'])){
        parse_str($parts['query'], $qs);
        if(isset($qs['v'])){
            return $qs['v'];
        }else if(isset($qs['vi'])){
            return $qs['vi'];
        }
    }
    if(isset($parts['path'])){
        $path = explode('/', trim($parts['path'], '/'));
        return $path[count($path)-1];
    }
    return false;
}

function shuffle_refer($length=7){
    $chars = '0123456789';
    $arr = str_split($chars, 1);
    shuffle($arr);
    return 'M'.substr(implode('', $arr), 0, $length);
}

function encrypt_array($data = []) {
    $crypt = Crypt::encryptString(json_encode($data));
    $crypt = base64_encode($crypt);
    return urlencode($crypt);
}

function decrypt_array($string) {
    $crypt = urldecode($string);
    $crypt = base64_decode($crypt);
    $crypt = Crypt::decryptString($crypt);
    $crypt = json_decode($crypt, true);
    return $crypt;
}

function userCan($permission = ''){
    if(is_array($permission)) {
        foreach ($permission as $perm) {
            if (!auth()->user()->can($perm)) {
                return false;
            }
        }
        return true;
    }
    return auth()->user()->can($permission);
}

function getPathVideo($path){
    if (strpos($path, 'video')){
        $path = str_replace('/video/', '', $path);
        $path = explode('|', $path);

        $path_name = isset($path[1]) ? $path[1] : '';

        $path = decrypt_array($path[0]);
        return $path['file_path'] . ($path_name ? '|' . $path_name : '') ;
    }
    return $path;
}

function status_register_class($status) {
    switch ($status) {
        case 1: return 'success';
        case 2: return 'danger';
        case 3: return 'danger';
        case 4: return 'success';
        case 5: return 'warning';
        case 6: return 'danger';
        case 7: return 'info';
    }

    return '';
}

function status_register_text($status) {
    switch ($status) {
        case 1: return trans('laother.register');
        case 2: return trans('laother.expired_registration');
        case 3: return trans('laother.finished');
        case 4: return trans('latraining.go_course');
        case 5: return trans('latraining.not_approved');
        case 6: return trans('latraining.deny');
        case 7: return trans('laother.unopened');
        case 8: return trans('latraining.happenning');
        case 9: return trans('latraining.not_complete');
        case 10: return trans('latraining.completed');
    }

    return '';
}

function get_config($name, $default = null) {
    return Config::getConfig($name, $default);
}

function set_config($name, $value) {
    return Config::setConfig($name, $value);
}

function get_uri($url) {
    return str_replace(request()->getSchemeAndHttpHost() . '/', '', $url);
}

function getUserType() {
    $profile = profile();
    return $profile->type_user;
}

function getUserId() {
    $user_id = profile()->user_id;
    if (\Auth::check()) {
        return $user_id;
    }

    if (\Auth::guard('secondary')->check()) {
        return \Auth::guard('secondary')->id();
    }

    return null;
}
function numberFormat($number,$decimail=0,$culture='general'){
    if ($culture=='vn')
        return number_format($number,$decimail,',','.');
    return number_format($number,$decimail);
}
function dateDiffSql($fdate,$tdate,$interval='day'){
    if (strtotime($tdate) !== false)
        $tdate ="'".$tdate."'";
    if (strtotime($fdate) !== false)
        $fdate ="'".$fdate."'";
    $dbDriver = strtolower(\DB::connection()->getDriverName());
    if ($dbDriver=='mysql'){
        return "TIMESTAMPDIFF($interval,$fdate,$tdate)";
    }elseif ($dbDriver=='sqlsrv')
        return "DATEDIFF($interval,$fdate,$tdate)";
    return '';
}
function unix_timestamp_sql($date=null){
    $dbDriver = strtolower(\DB::connection()->getDriverName());
    if ($dbDriver=='mysql'){
        if ($date)
            return "UNIX_TIMESTAMP($date)";
        else
            return "UNIX_TIMESTAMP()";
    }elseif ($dbDriver=='sqlsrv')
        if ($date)
            return "DATEDIFF_BIG(SECOND, '1970-01-01 00:00:00', $date)";
        else
            return "DATEDIFF_BIG(SECOND, '1970-01-01 00:00:00', GETDATE())";
    return '';
}
function unix_todatetime_sql($timestamp){
    $dbDriver = strtolower(\DB::connection()->getDriverName());
    if ($dbDriver=='mysql'){
            return "FROM_UNIXTIME($timestamp)";
    }elseif ($dbDriver=='sqlsrv')
            return "DATEDIFF_BIG(SECOND, $timestamp,'1970-01-01 00:00:00')";
    return '';
}
function current_datetime_sql(){
    $dbDriver = strtolower(\DB::connection()->getDriverName());
    if ($dbDriver=='mysql'){
        return "now()";
    }elseif ($dbDriver=='sqlsrv')
        return "GETDATE()";
    return '';
}
function dateAddSql($date, $num, $interval='month'){
    $dbDriver = strtolower(\DB::connection()->getDriverName());
    if ($dbDriver=='mysql'){
        if (strtotime($date) !== false){
            $date ="'".get_date($date, 'Y-m-d')."'";
        }

        $interval = \Illuminate\Support\Str::upper($interval);
        return "DATE_ADD($date, INTERVAL $num $interval)";

    }elseif ($dbDriver=='sqlsrv'){
        if (strtotime($date) !== false){
            $date ="'".get_date($date, 'Y/m/d')."'";
        }

        return "DATEADD($interval, $num, $date)";
    }

    return '';
}
function url_mobile(){
    //return strtolower(request()->segment(1))=='AppM'?true:false;
    $domain = parse_url(request()->url(), PHP_URL_HOST);
    if ($domain == parse_url(config('app.mobile_url'),PHP_URL_HOST) || strtolower(request()->segment(1)) == 'AppM'){
        return true;
    }

    return false;
}

function userThird(){
    if (\Auth::check()) {
        $user = profile();
        if ($user && $user->type_user == 2){
            return true;
        }
    }

    return false;
}

function getMailSignature($user_id = null, $user_type = 1){
    if ($user_type == 1){
        $user_id = empty($user_id) ? profile()->user_id : $user_id;
        $company = Profile::getCompany($user_id);
    }else{
        $user_id = empty($user_id) ? \Auth::guard('secondary')->id() : $user_id;
        $company = QuizUserSecondary::getCompany($user_id);
    }
    $signature = MailSignature::where('unit_id', $company)->first();

    return ($signature ? $signature->content : '');
}
function isMobile() {
    return preg_match("/(android|webos|avantgo|iphone|ipad|ipod|blackberry|iemobile|bolt|boost|cricket|docomo|fone|hiptop|mini|opera mini|kitkat|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
}

function isFilePdf($attachment) {
    if (empty($attachment)) {
        return false;
    }

    $extention = pathinfo($attachment, PATHINFO_EXTENSION);
    if ($extention == 'pdf' || $extention == 'PDF') {
        return true;
    }

    return false;
}

function get_menu_child($name) {

}

function setMailConfig($company){
//    $mail = Config::getConfigEmail();
    $mail= ConfigEmail::where(['company'=>$company])->first();
    if(isset($mail)) {
        $settings['mailers']['smtp_'.$mail->company] =[
            'transport'     => $mail->driver,
            'host'       => $mail->host,
            'port'       => (int) $mail->port,
            'from'       => [
                'address'   => $mail->address,
                'name'      => $mail->from_name
            ],
            'encryption' => $mail->encryption,
            'username'   => $mail->user,
            'password'   => $mail->password,
        ];
        \Illuminate\Support\Facades\Config::set('mail', $settings);
    }

}

function getSurveyPopup(){
    $profile = profile();

    SurveyPopup::addGlobalScope(new CompanyScope());
    $query = SurveyPopup::query();
    $query->select([
        'el_survey.id',
        'el_survey.name',
        'el_survey.num_popup'
    ])->disableCache();
    $query->leftJoin('el_survey', 'el_survey.id', 'el_survey_popup.survey_id');
    if (!Permission::isAdmin()) {
        $query->whereIn('el_survey_popup.survey_id', function ($subquery2) use ($profile) {
            $subquery2->select(['survey_id'])
                ->from('el_survey_object')
                ->where('user_id', '=', $profile->user_id)
                ->orWhere('title_id', '=', @$profile->title_id)
                ->orWhere('unit_id', '=', @$profile->unit_id);
        });
    }
    $query->whereNotIn('el_survey_popup.survey_id', function ($subquery2) use ($profile) {
        $subquery2->select(['survey_id'])
            ->from('el_survey_user')
            ->where('user_id', '=', $profile->user_id)
            ->where('send', 1);
    });
    $query->where('el_survey.num_popup', '>', 0);
    $query->orderByDesc('el_survey_popup.created_at');
    $query->where('el_survey_popup.start_date', '<=', date('Y-m-d H:i:s'));
    $query->where('el_survey_popup.end_date', '>=', date('Y-m-d H:i:s'));

    $surveys = $query->get();

    return $surveys;
}

//pass có viết hoa, thường, số, ký tự đặc biệt ít nhất 8 ký tự
function check_password($password){
    if (preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/",$password)) {
        return true;
    } else {
        return false;
    }
}

function getUserUnit(){
    $unit_id = session()->get('user_unit');
    if ($unit_id)
        return $unit_id;
    return Profile::getUnitIdPermission();
}

//Convert tiếng việt sang không dấu
function convert_vi_to_en($str) {
    $str = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/", "a", $str);
    $str = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", "e", $str);
    $str = preg_replace("/(ì|í|ị|ỉ|ĩ)/", "i", $str);
    $str = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/", "o", $str);
    $str = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", "u", $str);
    $str = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ)/", "y", $str);
    $str = preg_replace("/(đ)/", "d", $str);

    $str = preg_replace("/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/", "A", $str);
    $str = preg_replace("/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/", "E", $str);
    $str = preg_replace("/(Ì|Í|Ị|Ỉ|Ĩ)/", "I", $str);
    $str = preg_replace("/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/", "O", $str);
    $str = preg_replace("/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/", "U", $str);
    $str = preg_replace("/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/", "Y", $str);
    $str = preg_replace("/(Đ)/", "D", $str);

    return $str;
}

//Convert Url Web sang Url Mobile
function convert_url_web_to_app($link){
    if(url_mobile()){
        $link = str_replace(config('app.url'), config('app.mobile_url'), $link);
    }

    // Check domain đang truy cập có giống domain setup không. Này dùng cho 2 domain web cùng lúc
    $domain = parse_url(request()->url(), PHP_URL_HOST);
    if($domain != parse_url(config('app.url'), PHP_URL_HOST)){
        $link = str_replace(config('app.url'), 'https://'.$domain, $link); //Gán lại domain hiện tại, dùng cho view pdf vs domain không phải chính
    }

    return $link;
}

// CHỨC NĂNG MÀU SÁNG
function luminance($hex, $percent) {
    $hash = '';
    if (stristr($hex, '#')) {
        $hex = str_replace('#', '', $hex);
        $hash = '#';
    }

    $rgb = [hexdec(substr($hex, 0, 2)), hexdec(substr($hex, 2, 2)), hexdec(substr($hex, 4, 2))];
    for ($i = 0; $i < 3; $i++) {
        if ($percent > 0) {
            $rgb[$i] = round($rgb[$i] * $percent) + round(255 * (1 - $percent));
        } else {
            $positivePercent = $percent - ($percent * 2);
            $rgb[$i] = round($rgb[$i] * (1 - $positivePercent)); // round($rgb[$i] * (1-$positivePercent));
        }
        if ($rgb[$i] > 255) {
            $rgb[$i] = 255;
        }
    }
    $hex = '';
    for ($i = 0; $i < 3; $i++) {
        $hexDigit = dechex($rgb[$i]);
        if (strlen($hexDigit) == 1) {
            $hexDigit = "0" . $hexDigit;
        }
        $hex .= $hexDigit;
    }
    return $hash . $hex;
}
function myasset($path_file){
    $file = $path_file;
    if (file_exists($file))
        return asset($file.'?v='.filemtime($file));
    return asset($file);
}

function getParentUnit($user_unit) {
    $unit = Unit::find($user_unit, ['id', 'code', 'level']);
    if($unit->level != 0) {
        $parent_units = Unit::getTreeParentUnit($unit->code);
        foreach ($parent_units as $key => $parent) {
            if($parent->level == 0) {
                $parent_unit = $parent->id;
            } else {
                continue;
            }
        }
    } else {
        $parent_unit = $unit->id;
    }

    return $parent_unit;
}
function profile(){
    return session()->get('profile');
}
