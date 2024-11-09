<?php

namespace App\Services\Admin\PaymentMethod;

use App\Models\PaymentMethod;
use App\Libraries\CustomException;

class StoreService
{
    public function handle(Object $data){
        $paymentMethod = new PaymentMethod();
        $paymentMethod->type = $data->type;
        $paymentMethod->name = $data->name ?? 'SALDO';
        $paymentMethod->slug = ($data->name == 'saldo') ? 'saldo' : makeSlug($data->name);
        $paymentMethod->fee = $data->fee ?? 0;
        $paymentMethod->fee_percent = $data->fee_percent ?? 0;
        $paymentMethod->img = $this->_upload_data_img($data->file('img'), config('constants.options.asset_img_payment_method'));
        $paymentMethod->description = $data->description;
        $paymentMethod->information = $data->information;
        $paymentMethod->min_amount = $data->min_amount ?? 1;
        $paymentMethod->max_amount = $data->max_amount ?? 10000000;

        $paymentMethod->payment_gateway = $data->payment_gateway ?? null;
        $paymentMethod->payment_gateway_code = $data->payment_gateway_code ?? null;
        $paymentMethod->time_used = (!is_null($data->time_used)) ? date('H:i', strtotime($data->time_used)) : null;
        $paymentMethod->time_stopped = (!is_null($data->time_stopped)) ? date('H:i', strtotime($data->time_stopped)) : null;
        $paymentMethod->is_qrcode = $data->is_qrcode ?? 0;
        $paymentMethod->qrcode = ($data->is_qrcode) ? $this->_upload_data_img($data->file('qrcode'), config('constants.options.asset_img_qr_code')) : null;
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
