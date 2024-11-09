<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class PageRequest extends FormRequest
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
                'title' => 'required|unique:pages,title|max:100',
                'img' => 'nullable|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
                'content' => 'required',
                'is_active'   => 'required|in:0,1'
            ];
        }
        return [
            'title' => 'required',
            'content' => 'required',
            'is_active'   => 'required|in:0,1'
        ];
    }
    public function attributes()
    {
        return [
            'title' => 'Judul',
            'img' => 'Gambar',
            'content' => 'Isi Konten',
            'is_active' => 'Status'
        ];
    }
}
