<?php
namespace Modules\Quiz\Imports;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Modules\Quiz\Entities\QuizSettingAlert;
use Modules\Quiz\Entities\QuizUserSecondary;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class UserSecondaryImport implements ToModel, WithStartRow
{
    public $errors;

    public function __construct()
    {
        $this->errors = [];
    }

    public function model(array $row)
    {
        $error = false;
        $user_code = $row[1];
        $full_name = (string) $row[2];
        $username = (string) $row[3];
        $password = (string) $row[4];
        $dob = (string) $row[5];
        $email = (string) $row[6];
        $identity_card = $row[7];

        $code = Profile::where('code', '=', $user_code)->first();
        $user_name = User::where('username', '=',$username)->first();
        $email_exists = User::where('username', '=',$email)->first();

        if(empty($username)){
            $this->errors[] = 'Tên đăng nhập dòng <b>'. $row[0] .'</b> không được trống';
            $error = true;
        }

        if(empty($password)){
            $this->errors[] = 'Mật khẩu dòng <b>'. $row[0] .'</b> không được trống';
            $error = true;
        }

        if(empty($user_code)){
            $this->errors[] = 'Mã nhân viên dòng <b>'. $row[0] .'</b> không được trống';
            $error = true;
        }

        if(empty($full_name)){
            $this->errors[] = 'Họ tên dòng <b>'. $row[0] .'</b> không được trống';
            $error = true;
        }

        if(strlen($username) < 6 || strlen($username) > 32){
            $this->errors[] = 'Tên đăng nhập <b>'. $username .'</b> phải trong khoảng 6 - 32 ký tự';
            $error = true;
        }

        if(strlen($password) < 8 || strlen($password) > 32){
            $this->errors[] = 'Mật khẩu <b>'. $row[4] .'</b> phải trong khoảng 8 - 32 ký tự';
            $error = true;
        }

        if(strlen($identity_card) < 9 || strlen($identity_card) > 14){
            $this->errors[] = 'Số CMND <b>'. $row[7] .'</b> phải trong khoảng 9 - 14 ký tự';
            $error = true;
        }

        if(isset($code)){
            $this->errors[] = 'Mã nhân viên <b>'. $row[1] .'</b> đã tồn tại';
            $error = true;
        }

        if(isset($user_name)){
            $this->errors[] = 'Tên đăng nhập <b>'. $username .'</b> đã tồn tại';
            $error = true;
        }
        if(isset($email_exists)){
            $this->errors[] = 'Email <b>'. $email .'</b> đã tồn tại';
            $error = true;
        }
        if($error) {
            return null;
        }
        $parts = explode(" ", $full_name);
        if(count($parts) > 1) {
            $lastname = array_pop($parts);
            $firstname = implode(" ", $parts);
        }
        else
        {
            $firstname = $full_name;
            $lastname = " ";
        }
            $user = User::firstOrNew(['code' => $user_code]);
            $user->username = $username;
            $user->password = password_hash($password, PASSWORD_DEFAULT);
            $user->firstname = $lastname;
            $user->lastname = $firstname;
            $user->email = $email;
            if ($user->save()) {
                $profile = Profile::firstOrNew(['id' => $user->id]);
                $profile->id = $user->id;
                $profile->code = $user_code;
                $profile->user_id = $user->id;
                $profile->firstname = $lastname;
                $profile->lastname = $firstname;
                $profile->email = $email;
                $profile->type_user = 2;
                $profile->identity_card = $identity_card;
                if ($dob)
                    $profile->dob = date_convert($dob);
                $profile->save() ;
            }

    }

    public function startRow(): int
    {
        return 2;
    }
}
