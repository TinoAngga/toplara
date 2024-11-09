<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ServiceCategoryRequest extends FormRequest
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
                'service_type' => 'required',
                'name' => 'required|max:100',
                'img' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
                'guide_img' => 'mimes:jpeg,png,jpg,gif,svg,webp|max:5000',
                'description' => 'required',
                'information' => 'required',
                'is_zone_id'   => 'in:0,1',
                'is_check_id'   => 'in:0,1',
                'is_active'   => 'in:0,1'
            ];
        }
        return [
            'service_type' => 'required',
            'name' => 'required',
            'description' => 'required',
            'information' => 'required',
            'is_zone_id'   => 'in:0,1',
            'is_check_id'   => 'in:0,1',
            'is_active'   => 'in:0,1'
        ];
    }
    public function attributes()
    {
        return [
            'service_type' => 'Tipe Layanan',
            'name' => 'Nama',
            'img' => 'Gambar',
            'guide_img' => 'Gambar petunjuk',
            'description' => 'Deskripsi',
            'information' => 'Informasi',
            'is_zone_id' => 'Zone ID',
            'is_check_id' => 'Check ID',
            'is_active' => 'Status'
        ];
    }
}
