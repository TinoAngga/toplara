<?php

namespace App\Http\Requests\Primary;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class OrderRequest extends FormRequest
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
        if (request('additional_data_is_true') == 1 AND !preg_match("/joki-mobile-legend/i", request()->__v)) {
            return [
                'data' => 'required',
                'service' => 'required|exists:services,id',
                'payment' => 'required|exists:payment_methods,id',
                'whatsapp' => 'required|numeric|min:10|phone_number',
                'phone_ewallet' => (in_array(request()->payment, [20, 34, 35, 37, 46])) ? 'required|numeric|min:10|phone_number_ewallet' : '',
                'email' => [
                    'nullable',
                    'email',
                    function ($attribute, $value, $fail) {
                        if (!preg_match('/@gmail\.com$|@yahoo\.com$/', $value)) {
                            $fail('Email harus menggunakan domain @gmail.com atau @yahoo.com.');
                        }
                    },
                ] // Tambahkan aturan untuk email
                
            ];
        }
        if (preg_match("/joki-mobile-legend/i", request()->__v) == 1) {
            return [
                'data' => 'required',
                'additional_data' => 'required',
                'login' => 'required|in:Moonton,Facebook,VK',
                'hero' => 'required',
                'note' => 'required',
                'service' => 'required|exists:services,id',
                'payment' => 'required|exists:payment_methods,id',
                'star' => 'required|numeric',
                'phone_ewallet' => (in_array(request()->payment, [20, 34, 35, 37, 46])) ? 'required|numeric|min:10|phone_number_ewallet' : ''
            ];
        }
        return [
            'data' => 'required',
            'service' => 'required|exists:services,id',
            'payment' => 'required|exists:payment_methods,id',
            'whatsapp' => 'required|numeric|min:10|phone_number',
            'email' => [
        'nullable',
        'email',
        function ($attribute, $value, $fail) {
            if (!preg_match('/@gmail\.com$|@yahoo\.com$/', $value)) {
                $fail('Email harus menggunakan domain @gmail.com atau @yahoo.com.');
            }
        },
    ],
            'phone_ewallet' => (in_array(request()->payment, [20, 34, 35, 37, 46])) ? 'required|numeric|min:10|phone_number_ewallet' : ''
        ];
    }
    public function attributes() {
        return [
            'data' => 'User ID / Data / Target',
            'additional_data' => 'Zone ID / Data Tambahan',
            'service' => 'Layanan',
            'payment' => 'Metode Pembayaran',
            'email' => 'Email',
            'login' => 'Tipe Login',
            'hero' => 'Hero',
            'note' => 'Catatan untuk penjoki',
            'whatsapp' => 'Whatsapp',
            'star' => 'Jumlah Bintang',
            'phone_ewallet' => 'Nomor E Wallet'
        ];
    }

    public function messages()
    {
        return [
            'whatsapp.phone_number' => 'Harus diawali dengan 62xxxx',
            'phone_ewallet.phone_number_ewallet' => 'Harus diawali dengan 081xxxx',
            'email.email_order' => 'Email harus @Gmail.com atau @Yahoo.com',
        ];
    }
}
