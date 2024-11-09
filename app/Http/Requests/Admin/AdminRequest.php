<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class AdminRequest extends FormRequest
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
        $level = implode(',', config('constants.options.admin_level_arr'));
        if (request()->segment(3) == null) {
            return [
                'full_name' => 'required|min:4|max:50',
                'username' => 'required|min:6|max:12|unique:admins,username',
                'password' => 'required|min:6',
                'level' => 'required|in:' . $level,
            ];
        }
        return [
            'full_name' => 'required|min:4|max:50',
            'username' => 'required|min:6|max:12',
            'level' => 'required|in:' . $level,
        ];
    }
    public function attributes() {
        return [
            'full_name' => 'Nama Lengkap',
            'username' => 'Username',
            'password' => 'Password',
            'level' => 'Level'
        ];
    }
}
