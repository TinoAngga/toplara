<?php

namespace App\Services\Admin\Service;

use App\Models\Provider;
use App\Models\ServiceCategory;
use App\Models\Service;
use App\Libraries\CustomException;
use App\Services\Primary\Provider\ServiceGetService;

class StoreMassService
{
    protected $serviceGetService;

    public function __construct() {
        $this->serviceGetService = new ServiceGetService;
    }
    public function handle(Object $request, Provider $provider){
        try {
            $category = ServiceCategory::find($request->service_category_id); // SERVICE CATEGORY
            if ($category == false) {
                throw new CustomException([
                    'status'  => false,
                    'type' => 'validation',
                    'msg' => [
                        'service_category_id' => ['Kategori tidak tersedia']
                    ]
                ]);
            }
            $providerService = $this->serviceGetService->handle($provider, 'GET_ALL_BY_CATEGORY', $request->service_category_provider);
            $createOrUpdateService = false;
            foreach ($providerService as $key => $value) {
                $data_input = [];
                $data_input['service_type'] =$category->service_type ?? 'top-up-game'; // service category type
                $data_input['service_category_id'] = $request->service_category_id; // service category id
                $data_input['provider_id'] = $provider->id; // provider id
                $data_input['provider_service_code'] = $value['provider_service_code']; // provider code

                // SET SERVICE NAME //
                $data_input['name'] = str_replace(strtolower($request->cut_string ?? ''), '', strtolower($value['name']));
                if ($request->add_string <> null) {
                    if ($request->place_string == 'right') $data_input['name'] = $data_input['name'] . ' ' . $request->add_string;
                    if ($request->place_string == 'left') $data_input['name'] = $request->add_string . ' ' . $data_input['name'];
                }
                $data_input['name'] = ucwords(strtolower($data_input['name']));
                // END SET SERVICE NAME //

                $data_input['profit_type'] = $request->profit_type_mass;

                $request->price = (object) $request->price; // SET data PRICE TO OBJECT
                $request->profit_mass = (object) $request->profit_mass; // SET data PROFIT TO OBJECT

                $data_input['profit_config'] = [
                    'type' => $request->profit_type_mass,
                    'public' => $request->profit_mass->public,
                    'silver' => $request->profit_mass->silver,
                    'gold' => $request->profit_mass->gold,
                    'vip' => $request->profit_mass->vip
                ];
                $data_input['price'] = [
                    'public' => ($request->profit_type_mass === 'flat') ? $value['price'] + $request->profit_mass->public : ceil($value['price'] + ($value['price'] * convertPercent($request->profit_mass->public))),
                    'silver' => ($request->profit_type_mass === 'flat') ? $value['price'] + $request->profit_mass->silver : ceil($value['price'] + ($value['price'] * convertPercent($request->profit_mass->silver))),
                    'gold' => ($request->profit_type_mass === 'flat') ? $value['price'] + $request->profit_mass->gold : ceil($value['price'] + ($value['price'] * convertPercent($request->profit_mass->gold))),
                    'vip' => ($request->profit_type_mass === 'flat') ? $value['price'] + $request->profit_mass->vip : ceil($value['price'] + ($value['price'] * convertPercent($request->profit_mass->vip)))
                ];
                $data_input['profit'] = [
                    'public' => ($request->profit_type_mass === 'flat') ? $request->profit_mass->public : ceil($value['price'] * convertPercent($request->profit_mass->public)),
                    'silver' => ($request->profit_type_mass === 'flat') ? $request->profit_mass->silver : ceil($value['price'] * convertPercent($request->profit_mass->silver)),
                    'gold' => ($request->profit_type_mass === 'flat') ? $request->profit_mass->gold : ceil($value['price'] * convertPercent($request->profit_mass->gold)),
                    'vip' => ($request->profit_type_mass === 'flat') ? $request->profit_mass->vip : ceil($value['price']  * convertPercent($request->profit_mass->vip)),
                ];

                $data_input['description'] = '-';
                $data_input['is_rate_coin'] = $request->is_rate_coin ?? 0;
                $data_input['rate_coin'] = $request->rate_coin ?? 0;
                $data_input['price_rate_coin'] = $request->price_rate_coin ?? 0;
                $data_input['is_active'] = ($value['status'] == 'on') ? 1 : 0;

                $createOrUpdateService = Service::updateOrCreate([
                    'provider_service_code' => $data_input['provider_service_code'],
                    'provider_id' => $provider->id,
                ], $data_input);
                // if (Service::where('provider_service_code', $value['provider_service_code'])->where('provider_id', $provider->id)->exists()) {
                //     $createOrUpdateService = Service::where('provider_service_code', $value['provider_service_code'])->update($data_input);
                // } else {
                //     $createOrUpdateService = Service::create($data_input);
                // }
            }
            return ($createOrUpdateService)
                ? ['status' => true, 'total' => count($providerService)] : ['status' => false, 'total' => 0];
        } catch (\Throwable $e) {
            \Log::error($e->getMessage());
            throw new CustomException([
                'status'  => false,
                'type' => 'alert',
                'msg' => 'Terjadi kesalahan'
            ]);

        }
    }
}
