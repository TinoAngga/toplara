<?php

namespace App\Http\Requests\Primary\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ResetPasswordRequest extends FormRequest
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
            'password' => 'required|confirmed|min:6',
            'password_confirmation' => 'required',
        ];
    }
    public function attributes() {
        return [
            'password' => 'Password Baru',
            'password_confirmation' => 'Konfirmasi Password Baru',
        ];
    }

    public function messages()
    {
        return [
            'username.exists' => '- Pengguna tidak ditemukan.',
        ];
    }
}
