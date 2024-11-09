<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class BannerRequest extends FormRequest
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
                'name' => 'required|max:100',
                'value' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:5000',
                'url' => 'required'
            ];
        }
        return [
            'name' => 'required',
            'url' => 'required'
        ];
    }
    public function attributes()
    {
        return [
            'name' => 'Nama',
            'value' => 'Gambar',
            'url' => 'Url Produk',
        ];
    }
}
