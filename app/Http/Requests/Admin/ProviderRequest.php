<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ProviderRequest extends FormRequest
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
            if (request('is_manual') == 1 || !is_null(request('is_manual'))) {
                return [
                    'name' => 'required|unique:providers,name|max:100',
                    'is_auto_update' => 'in:0,1',
                    'is_active'   => 'required|in:0,1'
                ];
            }
            return [
                'name' => 'required|unique:providers,name|max:100',
                // 'api_username' => 'required',
                'api_key' => 'required',
                'api_url_order' => 'required',
                'api_url_status' => 'required',
                'api_url_service' => 'required',
                'api_url_profile' => 'required',
                'api_balance_alert' => 'required|numeric',
                'is_auto_update' => 'in:0,1',
                'is_active'   => 'required|in:0,1'
            ];
        }
        if (request('is_manual') == 1 || is_null(request('is_manual'))) {
            return [
                'name' => 'required',
                'is_auto_update' => 'in:0,1',
                'is_active'   => 'required|in:0,1'
            ];
        }
        return [
            'name' => 'required',
            // 'api_username' => 'required',
            'api_key' => 'required',
            'api_url_order' => 'required',
            'api_url_status' => 'required',
            'api_url_service' => 'required',
            'api_url_profile' => 'required',
            'is_auto_update' => 'in:0,1',
            'is_active'   => 'required|in:0,1'
        ];
    }
    public function attributes()
    {
        return [
            'name' => 'Nama',
            'api_username' => 'API Username / API ID',
            'api_key' => 'API Key',
            'api_url_order' => 'API URL Order',
            'api_url_status' => 'API URL Status',
            'api_url_service' => 'API URL Service',
            'api_url_profile' => 'API URL Profile',
            'is_auto_update' => 'Auto Update',
            'is_active'   => 'Status'
        ];
    }
}
