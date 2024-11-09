<?php

namespace App\Http\Requests\Primary\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class LoginRequest extends FormRequest
{
    protected function getValidatorInstance() {
		$instance = parent::getValidatorInstance();
        if ($instance->fails() == true) {
			throw new HttpResponseException(response()->json([
				'status'  => false,
				'type'    => 'validation',
				'msg' => parent::getValidatorInstance()->errors()
			]));
		}
        return parent::getValidatorInstance();
    }
    public function rules() {
        return [
            'username' => 'required',
            'password' => 'required',
        ];
    }
    public function attributes() {
        return [
            'username' => 'Username',
            'password' => 'Password',
        ];
    }

    public function messages()
    {
        return [
            
        ];
    }
}
