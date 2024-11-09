<?php

namespace App\Http\Requests\Primary;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserUpgradeRequest extends FormRequest
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
            'level' => 'required|exists:user_levels,id',
            'payment' => 'required|exists:payment_methods,id',
        ];
    }
    public function attributes() {
        return [
            'level' => 'Level',
            'payment' => 'Metode Pembayaran',
        ];
    }
}
