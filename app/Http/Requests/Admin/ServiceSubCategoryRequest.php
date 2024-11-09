<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ServiceSubCategoryRequest extends FormRequest
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
                'name' => 'required|unique:service_sub_categories,name'
            ];
        }
        return [
            // 'service_category_id' => 'required|numeric|exists:service_categories,id',
            'name' => 'required'
        ];
    }
    public function attributes()
    {
        return [
            // 'service_category_id' => 'Kategori',
            'name' => 'Nama'
        ];
    }
}
