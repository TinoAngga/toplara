<?php

namespace App\Http\Requests\Primary\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ForgotPasswordRequest extends FormRequest
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
            'username' => 'required|exists:users,username',
        ];
    }
    public function attributes() {
        return [
            'username' => 'Username',
        ];
    }

    public function messages()
    {
        return [
            'username.exists' => '- Pengguna tidak ditemukan.',
        ];
    }
}
