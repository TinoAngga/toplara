<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ServiceRequest extends FormRequest
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
        $levelValidator = null;
        foreach (config('constants.options.member_level') as $key => $value) {
            $levelValidator['price.' . $key] = 'required';
            $levelValidator['profit.' . $key] = 'required';
        }
        if (request()->segment(3) == null) {
            if (request('is_rate_coin') == 1 || !is_null(request('is_rate_coin'))) {
                return array_merge([
                    'name' => 'required|max:100',
                    'service_category_id' => 'required|numeric|exists:service_categories,id',
                    'provider_id' => 'required|numeric|exists:providers,id',
                    'provider_service_code' => 'required',
                    'profit_type' => 'required|in:percent,flat',
                    'is_active'   => 'required|in:0,1'
                ], $levelValidator);
            }
            return array_merge([
                'name' => 'required|max:100',
                'service_category_id' => 'required|numeric|exists:service_categories,id',
                'provider_id' => 'required|numeric|exists:providers,id',
                'provider_service_code' => 'required',
                'profit_type' => 'required|in:percent,flat',
                'is_active'   => 'required|in:0,1'
            ], $levelValidator);
        }
        if (request('is_rate_coin') == 1 || !is_null(request('is_rate_coin'))) {
            return array_merge([
                'name' => 'required|max:100',
                'service_category_id' => 'required|numeric|exists:service_categories,id',
                'provider_id' => 'required|numeric|exists:providers,id',
                'provider_service_code' => 'required',
                'profit_type' => 'required|in:percent,flat',
                'is_rate_coin' => 'required|in:0,1',
                'rate_coin' => 'required|numeric',
                'price_rate_coin'   => 'required|numeric',
                'is_active'   => 'required|in:0,1'
            ], $levelValidator);
        }
        return array_merge([
            'name' => 'required|max:100',
            'service_category_id' => 'required|numeric|exists:service_categories,id',
            'provider_id' => 'required|numeric|exists:providers,id',
            'provider_service_code' => 'required',
            'profit_type' => 'required|in:percent,flat',
            'is_active'   => 'required|in:0,1'
        ], $levelValidator);
    }
    public function attributes()
    {
        $levelAttr = [];
        foreach (config('constants.options.member_level') as $key => $value) {
            $levelAttr['price.' . $key] = 'Harga ' . $value;
            $levelAttr['profit.' . $key] = 'Profit ' . $value;
        }
        return array_merge([
            'name' => 'Nama',
            'service_category_id' => 'Kategori',
            'provider_id' => 'Provider',
            'provider_service_code' => 'Kode Layanan Provider',
            'profit_type' => 'Tipe Profit',
            'is_rate_coin' => 'Setting Rate Koin',
            'rate_coin' => 'Rate Koin',
            'price_rate_coin'   => 'Harga Rate Koin',
            'is_active'   => 'Status'
        ], $levelAttr);
    }
}
