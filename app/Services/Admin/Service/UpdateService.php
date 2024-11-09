<?php

namespace App\Services\Admin\Service;

use App\Models\Provider;
use App\Models\ServiceCategory;
use App\Models\Service;
use App\Libraries\CustomException;

class UpdateService
{
    public function handle(Object $data, Object $service){
        $category = ServiceCategory::find($data->service_category_id); // SERVICE CATEGORY
        if ($category == false) {
            throw new CustomException([
                'status'  => false,
                'type' => 'validation',
                'msg' => [
                    'service_category_id' => ['Kategori tidak tersedia']
                ]
            ]);
        }
        $provider = Provider::find($data['provider_id']); // PROVIDER
        if ($provider == false) {
            throw new CustomException([
                'status'  => false,
                'type' => 'validation',
                'msg' => [
                    'provider_id' => ['Provider tidak tersedia']
                ]
            ]);
        }
        $isExists = Service::where('name', $data->name)->first();
        if ($data->name <> $service->name AND $isExists) {
            $validator = makeValidator($data->all(), [
                'name' => 'required|unique:services,name',
            ], [], ['name' => 'Nama']);
            if ($validator->fails()) {
                throw new CustomException([
                    'status'  => false,
                    'type'    => 'validation',
                    'msg' => $validator->errors()->toArray()
                ]);
            }
        }
        $isExists = Service::where('provider_service_code', $data->provider_service_code)->where('provider_id', $provider->id)->exists();
        if ($data->provider_service_code <> $service->provider_service_code AND $isExists) {
            $validator = makeValidator($data->all(), [
                'provider_service_code' => 'required|unique:services,provider_service_code',
            ], [], ['provider_service_code' => 'Kode Layanan Provider']);
            if ($validator->fails()) {
                throw new CustomException([
                    'status'  => false,
                    'type'    => 'validation',
                    'msg' => $validator->errors()->toArray()
                ]);
            }
        }
        $service->service_type = $category->service_type ?? 'top-up-game';
        $service->service_category_id = $category->id;
        $service->sub_category= $data->sub_category;
        $service->provider_id = $provider->id;
        $service->provider_service_code = $data->provider_service_code;
        $service->name = ucwords(strtolower($data->name));
        $service->profit_type = $data->profit_type;

        $data->price = (object) $data->price; // SET data PRICE TO OBJECT
        $data->profit = (object) $data->profit; // SET data PROFIT TO OBJECT

        $service->profit_config = [
            'type' => $data->profit_type,
            'public' => $data->profit->public,
            'reseller' => $data->profit->reseller,
            'h2h' => $data->profit->h2h
        ];

        $service->price = [
            'public' => ($data->profit_type == 'flat') ? $data->price->public + $data->profit->public : ceil($data->price->public + ($data->price->public * convertPercent($data->profit->public))),
            'reseller' => ($data->profit_type == 'flat') ? $data->price->reseller + $data->profit->reseller : ceil($data->price->reseller + ($data->price->reseller * convertPercent($data->profit->reseller))),
            'h2h' => ($data->profit_type == 'flat') ? $data->price->h2h + $data->profit->h2h : ceil($data->price->h2h + ($data->price->h2h * convertPercent($data->profit->h2h)))
        ];

        $service->profit = [
            'public' => ($data->profit_type == 'flat') ? $data->profit->public : ceil($data->price->public * convertPercent($data->profit->public)),
            'reseller' => ($data->profit_type == 'flat') ? $data->profit->reseller : ceil($data->price->reseller * convertPercent($data->profit->reseller)),
            'h2h' => ($data->profit_type == 'flat') ? $data->profit->h2h : ceil($data->price->h2h * convertPercent($data->profit->h2h))
        ];

        $service->description = $data->description ?? '-';
        $service->is_rate_coin = $data->is_rate_coin ?? 0;
        $service->rate_coin = $data->rate_coin ?? 0;
        $service->price_rate_coin = $data->price_rate_coin ?? 0;
        $service->is_active = $data->is_active ?? 0;
        $service->save();
        return $service;
    }
}