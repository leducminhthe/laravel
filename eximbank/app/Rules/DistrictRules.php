<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class DistrictRules implements Rule
{
    protected $type = '';

    public function __construct()
    {
        //
    }

    public function passes($attribute, $value)
    {
        $pattern = '/[a-z]/';
        if (!preg_match($pattern, $value)){
            $this->type = 1;
            return false;
        }
    }

    public function message()
    {
        if($this->type == 1) {
            return 'Thành phố không được chứa ký tự đặc biệt.';
        }
    }
}
