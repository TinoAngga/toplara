<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ServiceCategoryTypeRequest extends FormRequest
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
        if (request()->segment(3) == null) {
            return [
                'icon' => 'required|max:100',
                'positin' => 'required|numeric'
            ];
        }
        return [
            'icon' => 'required|max:100',
            'positin' => 'required|numeric'
        ];
    }
    public function attributes()
    {
        return [
            'icon' => 'Ikon',
            'position' => 'Posisi'
        ];
    }
}
