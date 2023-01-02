<?php

namespace App\Http\Requests\Unit;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class UnitRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if ($this->getMethod()=='POST')
            $rules = [
                'code'  =>'required|string|max:255',
    //            'name' => 'required|string|max:255',
            ];
        else {
            $rules = [
                'name' => 'required|string|max:255'
            ];
        }
        return $rules;
    }

    public function failedValidation(Validator $validator)
    {
//        throw new HttpResponseException(response()->json($validator->errors(), 422));
        throw new HttpResponseException(response()->json([
            'errors' => $validator->errors()
        ], Response::HTTP_UNPROCESSABLE_ENTITY));
    }
}
