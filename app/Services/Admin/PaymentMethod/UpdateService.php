<?php

namespace App\Services\Admin\PaymentMethod;

use App\Models\PaymentMethod;
use App\Libraries\CustomException;

class UpdateService
{
    public function handle(Object $data, Object $paymentMethod){
        $isExists = PaymentMethod::where('name', $data->name)->first();
        if ($data->name <> $paymentMethod->name AND $isExists) {
            $validator = makeValidator($data->all(), [
                'name' => 'required|unique:payment_methods,name',
            ], [], ['name' => 'Nama']);
            if ($validator->fails()) {
                throw new CustomException([
                    'status'  => false,
                    'type'    => 'validation',
                    'msg' => $validator->errors()->toArray()
                ]);
            }
        }
        $paymentMethod->type = $data->type;
        $paymentMethod->name = $data->name ?? 'SALDO';
        $paymentMethod->slug = ($data->name == 'saldo') ? 'saldo' : makeSlug($data->name);
        $paymentMethod->fee = $data->fee ?? 0;
        $paymentMethod->fee_percent = $data->fee_percent ?? 0;
        if ($data->has('img')) {
            $file_name = $this->_upload_data_img($data->file('img'), config('constants.options.asset_img_payment_method'));
            if (file_exists(config('constants.options.asset_img_payment_method') . $paymentMethod->img)) {
                unlink(config('constants.options.asset_img_payment_method') . $paymentMethod->img);
            }
            $paymentMethod->img = $file_name;
        }
        $paymentMethod->description = $data->description;
        $paymentMethod->information = $data->information;
        $paymentMethod->min_amount = $data->min_amount ?? 1;
        $paymentMethod->max_amount = $data->max_amount ?? 10000000;
        $paymentMethod->payment_gateway = $data->payment_gateway ?? null;
        $paymentMethod->payment_gateway_code = $data->payment_gateway_code ?? null;
        $paymentMethod->time_used = (!is_null($data->time_used)) ? date('H:i', strtotime($data->time_used)) : null;
        $paymentMethod->time_stopped = (!is_null($data->time_stopped)) ? date('H:i', strtotime($data->time_stopped)) : null;
        $paymentMethod->is_qrcode = $data->is_qrcode ?? 0;
        if ($data->is_qrcode == 1 AND $data->has('qrcode')) {
            $file_name = ($data->is_qrcode) ? $this->_upload_data_img($data->file('qrcode'), config('constants.options.asset_img_qr_code')) : null;
            if (file_exists(config('constants.options.asset_img_qr_code') . $paymentMethod->qrcode)) {
                unlink(config('constants.options.asset_img_qr_code') . $paymentMethod->qrcode);
            }
            $paymentMethod->qrcode = $file_name;
        }
        $paymentMethod->is_manual = $data->is_manual ?? 0;
        $paymentMethod->is_public = $data->is_public ?? 0;
        $paymentMethod->is_active = $data->is_active ?? 0;
        $paymentMethod->save();
        return $paymentMethod;
    }

    protected function _upload_data_img(Object $file, String $path){
        $file_name = md5($file->getClientOriginalName()) . '.' . $file->getClientOriginalExtension();
        $file->move($path, $file_name);
        return $file_name;
    }
}
