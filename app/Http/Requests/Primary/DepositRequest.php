<?php

namespace App\Http\Requests\Primary;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class DepositRequest extends FormRequest
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
            'amount' => 'required|numeric|min_amount',
            'payment' => 'required|exists:payment_methods,id',
        ];
    }
    public function attributes() {
        return [
            'amount' => 'Nominal',
            'payment' => 'Metode Pembayaran',
        ];
    }
    public function messages()
    {
        return [
            'min_amount' => '- Minimal deposit Rp 10.000'
        ];
    }
}
