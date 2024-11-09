<?php

namespace App\Http\Requests\Primary\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class RegisterRequest extends FormRequest
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
            'full_name' => 'required|min:4|max:50',
            'email' => 'required|email|unique:users,email',
            'username' => 'required|min:6|max:12|unique:users,username',
            'password' => 'required|confirmed|min:6',
            'password_confirmation' => 'required',
            'phone_number' => 'required|numeric|min:10|phone_number'
        ];
    }
    public function attributes() {
        return [
            'full_name' => 'Nama Lengkap',
            'email' => 'Email',
            'username' => 'Username',
            'password' => 'Password',
            'password_confirmation' => 'Konfirmasi Password',
            'phone_number' => 'Nomor Handphone atau Whatsapp'
        ];
    }
    public function messages()
    {
        return [
            'phone_number.phone_number' => 'Harus diawali dengan 62',
        ];
    }
}
