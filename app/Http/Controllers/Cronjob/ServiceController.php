<?php

namespace App\Http\Controllers\Cronjob;

use App\Http\Controllers\Controller;
use App\Models\Provider;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\ServiceCategoryType;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ServiceController extends Controller
{
    public function getGame(Provider $provider)
    {
        if ($provider->type <> 'game') abort(404);
        $profitConfig = 'game';
        $setProfit = [
            'profit_type' => getConfig('profit_type_' . $profitConfig),
            'profit' => [
                'public' =>  getTypeData(getConfig('profit_public_' . $profitConfig)),
                'reseller' =>  getTypeData(getConfig('profit_reseller_' . $profitConfig)),
                'h2h' => getTypeData(getConfig('profit_h2h_' . $profitConfig)),
            ],
            'profit_config' => [
                'type' => getConfig('profit_type_' . $profitConfig),
                'public' =>  getTypeData(getConfig('profit_public_' . $profitConfig)),
                'reseller' =>  getTypeData(getConfig('profit_reseller_' . $profitConfig)),
                'h2h' =>  getTypeData(getConfig('profit_h2h_' . $profitConfig)),
            ],
        ];
        $services = (new \App\Services\Primary\Provider\ServiceGetService())->handle($provider, 'GET_ALL');
        foreach ($services as $k => $service) {
            switch ($provider->id) {
                case 2:
                    $service = settingServiceGameDigiflazz($service);
                    break;
                default:
                    throw new Exception('Action not found');
                    break;
            }
            $service = (object) $service; // SET ARRAY TO OBJECT

            if ($service->type != 'voucher-game') continue;

            DB::beginTransaction();
            try {
                $serviceCategory = ServiceCategory::query()
                ->select('id', 'name', 'service_type', 'img', 'guide_img', 'get_nickname_code', 'is_additional_data', 'is_check_id')
                ->where([
                    ['slug', '=', makeSlug($service->category)],
                    ['real_service_type', '=', 'top-up-game'],
                    ['name', '=', ucwords(strtolower($service->category))]
                ])->first();
                if ($serviceCategory) {
                    $categoryImg = $serviceCategory->img ?? 'dummy-img.webp';
                    $categoryGuideImg = $serviceCategory->guide_img ?? 'dummy-img.webp';
                    $categoryNicknameCode = $serviceCategory->get_nickname_code;
                    $categoryAdditionalData = $serviceCategory->is_additional_data;
                    $categoryCheckId = $serviceCategory->is_check_id;
                } else {
                    $categoryImg = 'dummy-img.webp';
                    $categoryGuideImg = 'dummy-img.webp';
                    $categoryNicknameCode = null;
                    $categoryAdditionalData = 0;
                    $categoryCheckId = 0;
                }
                // CREATE OR UPDATE SERVICE CATEGORY
                if ($serviceCategory == null) {
                    $serviceCategory = ServiceCategory::create([
                        'real_service_type' => 'top-up-game',
                        'service_type' => 'top-up-game',
                        'name' => ucwords(strtolower($service->category)),
                        'slug' => makeSlug($service->category),
                        'brand' => ucwords(strtolower($service->category)),
                        'get_nickname_code' => $categoryNicknameCode,
                        'img' => $categoryImg,
                        'guide_img' => $categoryGuideImg,
                        'description' => '-',
                        'information' => '-',
                        'is_additional_data' => $categoryAdditionalData,
                        'is_check_id' => $categoryCheckId,
                        'is_active' => 1
                    ]);
                }
                $dataService = Service::query()
                    ->select('id', 'price', 'profit', 'profit_type', 'profit_config', 'name')
                    ->where('provider_id', $provider->id)
                    ->where('provider_service_code', $service->provider_service_code)
                    ->first();
                if (getConfig('profit_setting_by') === 'service' AND $dataService <> null) {
                    $setProfit = [
                        'profit_type' => $dataService->profit_type,
                        'profit' => [
                            'public' => $dataService->profit_config->public,
                            'reseller' => $dataService->profit_config->reseller,
                            'h2h' => $dataService->profit_config->h2h,
                        ],
                        'profit_config' => [
                            'type' => $dataService->profit_type,
                            'public' => $dataService->profit_config->public,
                            'reseller' => $dataService->profit_config->reseller,
                            'h2h' => $dataService->profit_config->h2h,
                        ],
                    ];
                }
                $isUpdate = false;
                if ($dataService === null) {
                    $isUpdate = false;
                    $dataService = Service::create([
                        'real_service_type' => $serviceCategory->service_type,
                        'service_type' => $serviceCategory->service_type,
                        'service_category_id' => $serviceCategory->id,
                        'provider_id' => $provider->id,
                        'provider_service_code' => $service->provider_service_code,
                        'name' => ucwords(strtolower($service->name)),
                        'brand' => ucwords(strtolower($service->category)),
                        'price' => [
                            'public' => ($setProfit['profit_type']) === 'flat' ? ceil($service->price + $setProfit['profit']['public']) : ceil($service->price + ($service->price * convertPercent($setProfit['profit']['public']))),
                            'reseller' => ($setProfit['profit_type']) === 'flat' ? ceil($service->price + $setProfit['profit']['reseller']) : ceil($service->price + ($service->price * convertPercent($setProfit['profit']['reseller']))),
                            'h2h' => ($setProfit['profit_type']) === 'flat' ? ceil($service->price + $setProfit['profit']['h2h']) : ceil($service->price + ($service->price * convertPercent($setProfit['profit']['h2h'])))
                        ],
                        'profit_type' => $setProfit['profit_type'],
                        'profit' => [
                            'public' => ($setProfit['profit_type']) === 'flat' ?  $setProfit['profit']['public'] : ceil($service->price * convertPercent($setProfit['profit']['public'])),
                            'reseller' => ($setProfit['profit_type']) === 'flat' ?  $setProfit['profit']['reseller'] : ceil($service->price * convertPercent($setProfit['profit']['reseller'])),
                            'h2h' => ($setProfit['profit_type']) === 'flat' ?  $setProfit['profit']['h2h'] : ceil($service->price * convertPercent($setProfit['profit']['h2h']))
                        ],
                        'profit_config' => [
                            'type' => $setProfit['profit_type'],
                            'public' => $setProfit['profit']['public'],
                            'reseller' => $setProfit['profit']['reseller'],
                            'h2h' => $setProfit['profit']['h2h']
                        ],
                        'description' => '-',
                        'is_rate_coin' => 0,
                        'rate_coin' => 0,
                        'price_rate_coin' => 0,
                        'is_active' => $service->status === 'on' ? 1 :0,
                    ]);
                } else {
                    $isUpdate = true;
                    $dataService->update([
                        'price' => [
                            'public' => ($setProfit['profit_type']) === 'flat' ? ceil($service->price + $setProfit['profit']['public']) : ceil($service->price + ($service->price * convertPercent($setProfit['profit']['public']))),
                            'reseller' => ($setProfit['profit_type']) === 'flat' ? ceil($service->price + $setProfit['profit']['reseller']) : ceil($service->price + ($service->price * convertPercent($setProfit['profit']['reseller']))),
                            'h2h' => ($setProfit['profit_type']) === 'flat' ? ceil($service->price + $setProfit['profit']['h2h']) : ceil($service->price + ($service->price * convertPercent($setProfit['profit']['h2h'])))
                        ],
                        'profit_type' => $setProfit['profit_type'],
                        'profit' => [
                            'public' => ($setProfit['profit_type']) === 'flat' ?  $setProfit['profit']['public'] : ceil($service->price * convertPercent($setProfit['profit']['public'])),
                            'reseller' => ($setProfit['profit_type']) === 'flat' ?  $setProfit['profit']['reseller'] : ceil($service->price * convertPercent($setProfit['profit']['reseller'])),
                            'h2h' => ($setProfit['profit_type']) === 'flat' ?  $setProfit['profit']['h2h'] : ceil($service->price * convertPercent($setProfit['profit']['h2h']))
                        ],
                        'profit_config' => [
                            'type' => $setProfit['profit_type'],
                            'public' => $setProfit['profit']['public'],
                            'reseller' => $setProfit['profit']['reseller'],
                            'h2h' => $setProfit['profit']['h2h']
                        ],
                        'is_active' => $service->status === 'on' ? 1 :0,
                    ]);
                }
                DB::commit();
                if ($isUpdate === true) {
                    print '<font color="blue"><pre>';
                    print "[+] $serviceCategory->name - $dataService->name { Berhasil di update }<br>";
                } else {
                    print '<font color="green"><pre>';
                    print "[+] $serviceCategory->name - $dataService->name { Berhasil di tambahkan }<br>";
                }
                print "Status: $service->status<br>";
                print "Kategori: $serviceCategory->name<br>";
                print "Harga Provider: Rp " . currency($service->price) . "<br>";
                print "Harga Public: Rp " . currency($dataService->price->public) . "<br>";
                print "Harga Reseller: " . currency($dataService->price->reseller) . "<br>";
                print "Harga H2H: " . currency($dataService->price->h2h) . "<br>";
                print '</pre></font><hr>';
                flush();
            } catch (\Throwable $e) {
                DB::rollback();
                Log::info($e);
                print 'Terjadi kesalahan pada system';
                continue;
            }
        }
    }

    public function getPPOB(Provider $provider)
    {
        if ($provider->type <> 'ppob') abort(404);
        $profitConfig = 'ppob';
        $setProfit = [
            'profit_type' => getConfig('profit_type_' . $profitConfig),
            'profit' => [
                'public' => getTypeData(getConfig('profit_public_' . $profitConfig)),
                'reseller' => getTypeData(getConfig('profit_reseller_' . $profitConfig)),
                'h2h' => getTypeData(getConfig('profit_h2h_' . $profitConfig)),
            ],
            'profit_config' => [
                'type' => getConfig('profit_type_' . $profitConfig),
                'public' => getTypeData(getConfig('profit_public_' . $profitConfig)),
                'reseller' => getTypeData(getConfig('profit_reseller_' . $profitConfig)),
                'h2h' => getTypeData(getConfig('profit_h2h_' . $profitConfig)),
            ],
        ];
        $services = (new \App\Services\Primary\Provider\ServiceGetService())->handle($provider, 'GET_ALL');

        foreach ($services as $k => $service) {
            switch ($provider->id) {
                case 3:
                    $service = settingServicePPOBDigiflazz($service);
                    break;
                default:
                    throw new Exception('Action not found');
                    break;
            }

            $service = (object) $service; // SET ARRAY TO OBJECT
            // if (!in_array($service->type, ['pulsa-reguler', 'streaming-tv', 'token-pln', 'saldo-emoney', 'pulsa-international', 'lainnya'])) continue;

            if ($service->type == 'voucher-game') continue;

            $service->category = ucwords(str_replace('-', ' ', $service->category));
            $service->category = preg_replace('/\s+/',' ', $service->category);

            DB::beginTransaction();
            try {
                ServiceCategoryType::updateOrCreate([
                    'slug' => $service->type,
                ], [
                    'slug' => makeSlug($service->type),
                    'name' => $service->type
                ]);

                $serviceCategory = ServiceCategory::query()
                    ->select('id', 'service_type', 'name', 'img', 'guide_img', 'get_nickname_code', 'is_additional_data', 'is_check_id')
                    ->where([
                        ['real_service_type', '=', $service->type],
                        ['brand', '=', ucwords(strtolower($service->category))],
                        ['name', '=', ucwords(strtolower($service->category))]
                    ])->first();

                if ($serviceCategory == null) {
                    $serviceCategory = ServiceCategory::create([
                        'real_service_type' => $service->type,
                        'service_type' => $service->type,
                        'name' => ucwords(strtolower($service->category)),
                        'sub_name' => ucwords(strtolower($service->category)),
                        'slug' => makeSlug($service->category),
                        'brand' => ucwords(strtolower($service->category)),
                        'get_nickname_code' => null,
                        'img' => 'dummy-img.webp',
                        'guide_img' => 'dummy-img.webp',
                        'description' => '-',
                        'information' => '-',
                        'is_additional_data' => 0,
                        'is_check_id' => 0,
                        'is_active' => 1
                    ]);
                }

                $dataService = Service::query()
                    ->select('id', 'price', 'profit', 'profit_type', 'profit_config', 'name')
                    ->where('provider_id', $provider->id)
                    ->where('provider_service_code', $service->provider_service_code)
                    ->first();

                if (getConfig('profit_setting_by') === 'service' AND $dataService <> null) {
                    $setProfit = [
                        'profit_type' => $dataService->profit_type,
                        'profit' => [
                            'public' => $dataService->profit_config->public,
                            'reseller' => $dataService->profit_config->reseller,
                            'h2h' => $dataService->profit_config->h2h,
                        ],
                        'profit_config' => [
                            'type' => $dataService->profit_type,
                            'public' => $dataService->profit_config->public,
                            'reseller' => $dataService->profit_config->reseller,
                            'h2h' => $dataService->profit_config->h2h,
                        ],
                    ];
                }

                $isUpdate = false;

                if ($dataService === null) {
                    $isUpdate = false;
                    $dataService = Service::create([
                        'service_type' => $serviceCategory->service_type,
                        'service_category_id' => $serviceCategory->id,
                        'provider_id' => $provider->id,
                        'provider_service_code' => $service->provider_service_code,
                        'name' => ucwords(strtolower($service->name)),
                        'brand' => ucwords(strtolower($service->category)),
                        'price' => [
                            'public' => ($setProfit['profit_type']) === 'flat' ? ceil($service->price + $setProfit['profit']['public']) : ceil($service->price + ($service->price * convertPercent($setProfit['profit']['public']))),
                            'reseller' => ($setProfit['profit_type']) === 'flat' ? ceil($service->price + $setProfit['profit']['reseller']) : ceil($service->price + ($service->price * convertPercent($setProfit['profit']['reseller']))),
                            'h2h' => ($setProfit['profit_type']) === 'flat' ? ceil($service->price + $setProfit['profit']['h2h']) : ceil($service->price + ($service->price * convertPercent($setProfit['profit']['h2h'])))
                        ],
                        'profit_type' => $setProfit['profit_type'],
                        'profit' => [
                            'public' => ($setProfit['profit_type']) === 'flat' ?  $setProfit['profit']['public'] : ceil($service->price * convertPercent($setProfit['profit']['public'])),
                            'reseller' => ($setProfit['profit_type']) === 'flat' ?  $setProfit['profit']['reseller'] : ceil($service->price * convertPercent($setProfit['profit']['reseller'])),
                            'h2h' => ($setProfit['profit_type']) === 'flat' ?  $setProfit['profit']['h2h'] : ceil($service->price * convertPercent($setProfit['profit']['h2h']))
                        ],
                        'profit_config' => [
                            'type' => $setProfit['profit_type'],
                            'public' => $setProfit['profit']['public'],
                            'reseller' => $setProfit['profit']['reseller'],
                            'h2h' => $setProfit['profit']['h2h']
                        ],
                        'description' => preg_replace('/\s+/',' ',$service->description),
                        'is_rate_coin' => 0,
                        'rate_coin' => 0,
                        'price_rate_coin' => 0,
                        'is_active' => $service->status === 'on' ? 1 :0,
                    ]);
                } else {
                    $isUpdate = true;
                    $dataService->update([
                        'price' => [
                            'public' => ($setProfit['profit_type']) === 'flat' ? ceil($service->price + $setProfit['profit']['public']) : ceil($service->price + ($service->price * convertPercent($setProfit['profit']['public']))),
                            'reseller' => ($setProfit['profit_type']) === 'flat' ? ceil($service->price + $setProfit['profit']['reseller']) : ceil($service->price + ($service->price * convertPercent($setProfit['profit']['reseller']))),
                            'h2h' => ($setProfit['profit_type']) === 'flat' ? ceil($service->price + $setProfit['profit']['h2h']) : ceil($service->price + ($service->price * convertPercent($setProfit['profit']['h2h'])))
                        ],
                        'profit_type' => $setProfit['profit_type'],
                        'profit' => [
                            'public' => ($setProfit['profit_type']) === 'flat' ?  $setProfit['profit']['public'] : ceil($service->price * convertPercent($setProfit['profit']['public'])),
                            'reseller' => ($setProfit['profit_type']) === 'flat' ?  $setProfit['profit']['reseller'] : ceil($service->price * convertPercent($setProfit['profit']['reseller'])),
                            'h2h' => ($setProfit['profit_type']) === 'flat' ?  $setProfit['profit']['h2h'] : ceil($service->price * convertPercent($setProfit['profit']['h2h']))
                        ],
                        'profit_config' => [
                            'type' => $setProfit['profit_type'],
                            'public' => $setProfit['profit']['public'],
                            'reseller' => $setProfit['profit']['reseller'],
                            'h2h' => $setProfit['profit']['h2h']
                        ],
                        'description' => '-',
                        'is_active' => $service->status === 'on' ? 1 :0,
                    ]);
                }
                DB::commit();
                if ($isUpdate === true) {
                    print '<font color="blue"><pre>';
                    print "[+] $serviceCategory->name - $dataService->name { Berhasil di update }<br>";
                } else {
                    print '<font color="green"><pre>';
                    print "[+] $serviceCategory->name - $dataService->name { Berhasil di tambahkan }<br>";
                }
                print "Status: $service->status<br>";
                print "Kategori: $serviceCategory->name<br>";
                print "Harga Provider: Rp " . currency($service->price) . "<br>";
                print "Harga Public: Rp " . currency($dataService->price->public) . "<br>";
                print "Harga reseller: " . currency($dataService->price->reseller) . "<br>";
                print "Harga H2H: " . currency($dataService->price->h2h) . "<br>";
                print '</pre></font><hr>';
                flush();
            } catch (\Throwable $e) {
                DB::rollback();
                Log::info($e);
                print $e->getMessage() . '<br />';
                print 'Terjadi kesalahan pada system';
                continue;
            }
        }
    }

    public function sync(Provider $provider)
    {
        // if ($provider->type <> 'game') abort(404);
        $profitConfig = $provider->type === 'game' ? 'game' : 'ppob';
        $setProfit = [
            'profit_type' => getConfig('profit_type_' . $profitConfig),
            'profit' => [
                'public' => getTypeData(getConfig('profit_public_' . $profitConfig)),
                'reseller' => getTypeData(getConfig('profit_reseller_' . $profitConfig)),
                'h2h' => getTypeData(getConfig('profit_h2h_' . $profitConfig)),
            ],
            'profit_config' => [
                'type' => getConfig('profit_type_' . $profitConfig),
                'public' => getTypeData(getConfig('profit_public_' . $profitConfig)),
                'reseller' => getTypeData(getConfig('profit_reseller_' . $profitConfig)),
                'h2h' => getTypeData(getConfig('profit_h2h_' . $profitConfig)),
            ],
        ];


        $services = (new \App\Services\Primary\Provider\ServiceGetService)->handle($provider, 'GET_ALL');
        foreach ($services as $k => $v) {
            switch ($provider->id) {
                case 2:
                    $v = settingServiceGameDigiflazz($v);
                    break;
                case 3:
                    $v = settingServicePPOBDigiflazz($v);
                    break;
                default:
                    throw new Exception('Action not found');
                    break;
            }
            $v = (object) $v; // SET ARRAY TO OBJECT
            $service = Service::where('provider_service_code', $v->provider_service_code)
                    ->select('id', 'price', 'profit', 'profit_type', 'profit_config', 'name', 'is_active')
                    ->where('provider_id', $provider->id)
                    ->first();
            if ($service == null) continue;

            if (getConfig('profit_setting_'.$profitConfig.'_by') == 'service') {
                $setProfit = [
                    'profit_type' => $service->profit_type,
                    'profit' => [
                        'public' => $service->profit_config->public,
                        'reseller' => $service->profit_config->reseller,
                        'h2h' => $service->profit_config->h2h
                    ],
                    'profit_config' => [
                        'type' => $service->profit_type,
                        'public' => $service->profit_config->public,
                        'reseller' => $service->profit_config->reseller,
                        'h2h' => $service->profit_config->h2h
                    ],
                ];
            }
            DB::beginTransaction();
            try {
                $lastProfit = ($setProfit['profit_type']) == 'flat' ?  $setProfit['profit']['public'] : ceil($v->price * convertPercent($setProfit['profit']['public']));
                $lastPrice = ceil($service->price->public - $lastProfit);
                $lastStatusProvider = ($v->status == 'on') ? 1 : 0;
                if ($v->price <> $lastPrice || $service->is_active <> $lastStatusProvider) {
                    // if ($v->provider_service_code == 'ML5_0-S10') {
                    //     dump('update');
                    //     dump('profit type : ' . $setProfit['profit_type']);
                    //     dump('profit public : ' . $setProfit['profit']['public']);
                    //     dd('price provider : ' . $v->price, 'price public : ' . $service->price->public, 'last profit : ' . $lastProfit, 'last price: ' . $lastPrice);
                    // }

                    $service->profit_type = $setProfit['profit_type'];
                    $service->price = [
                        'public' => ($setProfit['profit_type']) == 'flat' ? ceil($v->price + $setProfit['profit']['public']) : ceil($v->price + ($v->price * convertPercent($setProfit['profit']['public']))),
                        'reseller' => ($setProfit['profit_type']) == 'flat' ? ceil($v->price + $setProfit['profit']['reseller']) : ceil($v->price + ($v->price * convertPercent($setProfit['profit']['reseller']))),
                        'h2h' => ($setProfit['profit_type']) == 'flat' ? ceil($v->price + $setProfit['profit']['h2h']) : ceil($v->price + ($v->price * convertPercent($setProfit['profit']['h2h'])))
                    ];
                    $service->profit = [
                        'public' => ($setProfit['profit_type']) == 'flat' ?  $setProfit['profit']['public'] : ceil($v->price * convertPercent($setProfit['profit']['public'])),
                        'reseller' => ($setProfit['profit_type']) == 'flat' ?  $setProfit['profit']['reseller'] : ceil($v->price * convertPercent($setProfit['profit']['reseller'])),
                        'h2h' => ($setProfit['profit_type']) == 'flat' ?  $setProfit['profit']['h2h'] : ceil($v->price * convertPercent($setProfit['profit']['h2h']))
                    ];
                    $service->profit_config = [
                        'type' => $setProfit['profit_type'],
                        'public' => $setProfit['profit']['public'],
                        'reseller' => $setProfit['profit']['reseller'],
                        'h2h' => $setProfit['profit']['h2h'],
                    ];
                    $service->is_active = ($v->status == 'on') ? 1 : 0;
                    $service->save();
                    print '<font color="blue"><pre>';
                    print "[+] $service->name [ Berhasil di update ]<br>";
                    print "Status: $v->status<br>";
                    print "Harga Provider: Rp " . currency($v->price) . "<br>";
                    print "Harga Public: Rp " . currency($service->price->public) . "<br>";
                    print "Harga Reseller: " . currency($service->price->reseller) . "<br>";
                    print "Harga H2H: " . currency($service->price->h2h) . "<br>";
                    print '</pre></font><hr>';
                } else {
                    print '<font color="green"><pre>';
                    print "[+] $service->name [ Tidak ada perubahan ]<br>";
                    print "Status: $v->status<br>";
                    print "Harga Provider: Rp " . currency($v->price) . "<br>";
                    print "Harga Public: Rp " . currency($service->price->public) . "<br>";
                    print "Harga reseller: " . currency($service->price->reseller) . "<br>";
                    print "Harga H2H: " . currency($service->price->h2h) . "<br>";
                    print '</pre></font><hr>';
                }
                DB::commit();
            } catch (\Throwable $e) {
                DB::rollback();
                Log::info('Dari cronjob sync service '.$provider->name.' error: '.$e->getMessage());
                print 'error ' .$service->id. ' <br />';
                continue;
            }
            flush();
        }
    }
}
