<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class PaymentMethodRequest extends FormRequest
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
        $payment_gateway = implode(',', config('constants.options.payment_gateway_arr'));
        $type = implode(',', config('constants.options.payment_method_type_arr'));
        if (request()->segment(3) == null) {
            if (request('type') == 'saldo') {
                return [
                    'type' => 'required|in:' . $payment_gateway,
                    'is_active'   => 'required|in:0,1'
                ];
            }
            if (request('is_payment_gateway') == 1 || !is_null(request('is_payment_gateway'))) {
                return [
                    'is_payment_gateway' => 'in:0,1',
                    'payment_gateway' => 'required|in:' . $payment_gateway,
                    'payment_gateway_code' => 'required',
                    'name' => 'required|unique:payment_methods,name|max:100',
                    'type' => 'required|in:' . $type,
                    'fee' => '',
                    'fee_percent' => '',
                    'img' => 'required|image',
                    'description' => 'required',
                    'information' => 'required',
                    'min_amount' => 'required|numeric|min:100',
                    'max_amount' => 'required|numeric|min:' . request('min_amount') . '|max:10000000',
                    'is_manual'   => 'in:0,1',
                    'is_public'   => 'in:0,1',
                    'is_active'   => 'required|in:0,1'
                ];
            }
            if (request('is_qrcode') == 1 || !is_null(request('is_qrcode'))) {
                return [
                    'is_qrcode' => 'in:0,1',
                    'qrcode' => 'required|image',
                    'name' => 'required|unique:payment_methods,name|max:100',
                    'type' => 'required|in:' . $type,
                    'fee' => '',
                    'fee_percent' => '',
                    'img' => 'required|image',
                    'description' => 'required',
                    'information' => 'required',
                    'min_amount' => 'required|numeric|min:100',
                    'max_amount' => 'required|numeric|min:' . request('min_amount') . '|max:10000000',
                    'is_manual'   => 'in:0,1',
                    'is_public'   => 'in:0,1',
                    'is_active'   => 'required|in:0,1'
                ];
            }
            return [
                'name' => 'required|unique:payment_methods,name|max:100',
                'type' => 'required|in:' . $type,
                'fee' => '',
                'fee_percent' => '',
                'img' => 'required|image',
                'description' => 'required',
                'information' => 'required',
                'min_amount' => 'required|numeric|min:100',
                'max_amount' => 'required|numeric|min:' . request('min_amount') . '|max:10000000',
                'is_manual'   => 'in:0,1',
                'is_public'   => 'in:0,1',
                'is_active'   => 'required|in:0,1'
            ];
        }
        if (request('type') == 'saldo') {
            return [
                'name' => 'unique:payment_methods,name',
                'type' => 'required|in:' . $payment_gateway,
                'is_active'   => 'required|in:0,1'
            ];
        }
        if (request('is_payment_gateway') == 1 || !is_null(request('is_payment_gateway'))) {
            return [
                'is_payment_gateway' => 'in:0,1',
                'payment_gateway' => 'required|in:' . $payment_gateway,
                'payment_gateway_code' => 'required',
                'name' => 'required|max:100',
                'type' => 'required|in:' . $type,
                'fee' => '',
                'fee_percent' => '',
                'img' => 'image',
                'description' => 'required',
                'information' => 'required',
                'min_amount' => 'required|numeric|min:100',
                'max_amount' => 'required|numeric|min:' . request('min_amount') . '|max:10000000',
                'is_manual'   => 'in:0,1',
                'is_public'   => 'in:0,1',
                'is_active'   => 'required|in:0,1'
            ];
        }
        if (request('is_qrcode') == 1 || !is_null(request('is_qrcode'))) {
            return [
                'is_qrcode' => 'required|in:0,1',
                'qrcode' => 'image',
                'name' => 'required|max:100',
                'type' => 'required|in:' . $type,
                'fee' => '',
                'fee_percent' => '',
                'img' => 'image',
                'description' => 'required',
                'information' => 'required',
                'min_amount' => 'required|numeric|min:100',
                'max_amount' => 'required|numeric|min:' . request('min_amount') . '|max:10000000',
                'is_manual'   => 'in:0,1',
                'is_public'   => 'in:0,1',
                'is_active'   => 'required|in:0,1'
            ];
        }
        return [
            'name' => 'required|max:100',
            'type' => 'required|in:' . $type,
            'fee' => '',
            'fee_percent' => '',
            'img' => 'image',
            'description' => 'required',
            'information' => 'required',


            'is_manual'   => 'in:0,1',
            'is_public'   => 'in:0,1',
            'is_active'   => 'required|in:0,1'
        ];
    }
    public function attributes()
    {
        return [
            'name' => 'Nama',
            'type' => 'Tipe',
            'fee' => 'Fee',
            'fee_percent' => 'Fee Persen',
            'img' => 'Gambar',
            'description' => 'Deskripsi',
            'information' => 'Informasi',
            'min_amount' => 'Minimal Nominal Pembelian',
            'max_amount' => 'Maksimal Nominal Pembelian',
            'time_used' => 'Waktu mulai',
            'time_stopped' => 'Waktu berhenti',
            'payment_gateway' => 'Payment Gateway',
            'payment_gateway_code' => 'Payment Gateway Kode',
            'is_qrcode' => 'QRCode /QRIS',
            'qr_code' => 'Gambar QRCode / QRIS',
            'is_manual'   => 'Manual',
            'is_public'   => 'Public',
            'is_active'   => 'Status'
        ];
    }
}
