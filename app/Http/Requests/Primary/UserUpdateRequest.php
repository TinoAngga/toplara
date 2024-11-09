<?php

namespace App\Http\Requests\Primary;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserUpdateRequest extends FormRequest
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
        if (request('new_password') <> null OR request('new_password_confirmation') <> null) {
            return [
                'full_name' => 'min:4|max:50',
                'new_password' => 'required|min:6',
                'new_password_confirmation' => 'required|same:new_password',
                'password' => 'required',
                'phone_number' => 'numeric|min:10|phone_number'
            ];
        }
        return [
            'full_name' => 'min:4|max:50',
            'password' => 'required',
            'phone_number' => 'numeric|min:10|phone_number'
        ];
    }
    public function attributes() {
        return [
            'full_name' => 'Nama Lengkap',
            'username' => 'Username',
            'password' => 'Password',
            'new_password_confirmation' => 'Password Baru',
            'new_password_confirmation' => 'Konfirmasi Password Baru',
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
