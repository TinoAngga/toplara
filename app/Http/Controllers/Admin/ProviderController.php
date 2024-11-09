<?php

namespace App\Http\Controllers\Admin;

use App\Models\Provider;
use App\Models\ServiceCategory;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\DataTables\Admin\ProviderDataTable;
use App\Http\Requests\Admin\ProviderRequest;
use App\Libraries\CustomException;
use App\Models\Service;
use App\Services\Primary\Provider\ServiceGetService;
use App\Services\Primary\Provider\GetBalanceService;

class ProviderController extends Controller
{
    public function index(ProviderDataTable $dataTable)
    {
        $page = 'Provider Layanan';
        $status = ['1' => 'Aktif', '0' => 'Nonaktif'];
        $is_manual = ['1' => 'Manual', '0' => 'Otomatis'];
        $is_auto_update = ['1' => 'Ya', '0' => 'Tidak'];
        return $dataTable->render('admin.provider.index', compact('page', 'status', 'is_manual', 'is_auto_update'));
    }
    public function create()
    {
        abort(404);
        return view('admin.' . request()->segment(2) . '.create');
    }
    public function store(ProviderRequest $request)
    {
        abort(404);
        if ($request->ajax() == false) abort('404');
        $provider = new Provider();
        $provider->name = ucwords($request->name);
        $provider->api_username = $request->api_username;
        $provider->api_key = $request->api_key;
        $provider->api_additional = $request->api_additional;
        $provider->api_url_order = $request->api_url_order;
        $provider->api_url_status = $request->api_url_status;
        $provider->api_url_service = $request->api_url_service;
        $provider->api_url_profile = $request->api_url_profile;
        $provider->api_balance_alert = $request->api_balance_alert ?? 0;
        $provider->is_auto_update = $request->is_auto_update ?? 0;
        $provider->is_manual = $request->is_manual ?? 0;
        $provider->is_active = $request->is_active ?? 0;
        $provider->save();

        return response()->json([
            'status'  => true,
            'type' => 'alert',
            'msg' => 'Provider layanan berhasil ditambahkan #' . $provider->id . '.'
        ]);
    }
    public function show(Provider $provider)
    {
        return view('admin.provider.show', compact('provider'));
    }
    public function edit(Provider $provider)
    {
        return view('admin.provider.edit', compact('provider'));
    }
    public function update(ProviderRequest $request, Provider $provider)
    {
        if ($request->ajax() == false) abort('404');
        $isExists = Provider::where('name', $request->name)->first();
        if ($request->name <> $provider->name AND $isExists) {
            $validator = makeValidator($request->all(), [
                'name' => 'required|unique:provider,name',
            ], [], ['name' => 'Nama']);
            if ($validator->fails()) {
                return response()->json([
                    'status'  => false,
                    'type'    => 'validation',
                    'msg' => $validator->errors()->toArray()
                ]);
            }
        }
        $provider->name = ucwords($request->name);
        $provider->api_username = $request->api_username;
        $provider->api_key = $request->api_key;
        $provider->api_additional = $request->api_additional;
        $provider->api_url_order = $request->api_url_order;
        $provider->api_url_status = $request->api_url_status;
        $provider->api_url_service = $request->api_url_service;
        $provider->api_url_profile = $request->api_url_profile;
        $provider->api_balance_alert = $request->api_balance_alert ?? 0;
        $provider->is_auto_update = $request->is_auto_update ?? 0;
        $provider->is_manual = $request->is_manual ?? 0;
        $provider->is_active = $request->is_active ?? 0;
        $provider->save();

        return response()->json([
            'status'  => true,
            'type' => 'alert',
            'msg' => 'Provider layanan berhasil diubah #' . $provider->id . '.'
        ]);
    }
    public function destroy(Provider $provider)
    {
        abort(404);
        if (in_array($provider->id, ['2', '3', '4'])) {
            return response()->json([
                'status'  => false,
                'type' => 'alert',
                'msg' => 'Provider layanan utama tidak dapat dihapus agar tidak mengganggu sistem.'
            ]);
        }
        $provider->service()->find($provider->id)->delete();
        $provider->delete();
        return response()->json([
            'status'  => true,
            'type' => 'alert',
            'msg' => 'Berhasil menghapus data #'.$provider->id.'.'
        ]);
    }
    public function switchStatus(Request $request, Provider $provider){
        if ($request->ajax() == false) abort('404');

        if (in_array($request->type, ['status', 'auto_update', 'manual']) == false OR in_array($request->value, ['0', '1']) == false) {
            return response()->json([
                'status'  => false,
                'type' => 'alert',
                'msg' => 'Aksi tidak diperbolehkan'
            ]);
        }
        if ($request->type == 'auto_update' AND $provider->is_manual == 1 AND $request->value == 1) {
            return response()->json([
                'status'  => false,
                'type' => 'alert',
                'msg' => 'Aksi tidak diperbolehkan'
            ]);
        }
        if ($request->type == 'status') $provider->is_active = $request->value;
        if ($request->type == 'auto_update') $provider->is_auto_update = $request->value;
        $provider->save();
        return response()->json([
            'status'  => true,
            'type' => 'alert',
            'msg' => 'Berhasil mengubah data #'.$provider->id.'.'
        ]);
    }
    public function getBalance(Request $request, Provider $provider, GetBalanceService $getBalanceService){
        if ($request->ajax() == false) abort(404);
        try {
            $getBalanceService->handle($provider);
            return response()->json(['status' => true, 'type' => 'alert', 'msg' => 'Permintaan cek saldo <b>' . $provider->name . '</b> telah berhasil dilakukan']);
        } catch (CustomException $e) {
            return response()->json($e->getCustomMessage());
        }
    }
    public function service(Provider $provider){
        if ($provider->is_manual == 1 || $provider->type <> 'game'){
            session()->flash('alertClass', 'danger');
            session()->flash('alertTitle', 'Gagal.');
            session()->flash('alertMsg', 'Provider ini tidak memiliki akses.');
            return redirect()->back();
        }
        $page = 'Layanan API';
        return view('admin.provider.service.index', compact('provider', 'page'));
    }
    public function serviceCategory(Request $request, Provider $provider, ServiceGetService $serviceGetService){
        if ($request->ajax() == false) abort(404);

        try {
            $serviceCategory = $serviceGetService->handle($provider, 'GET_CATEGORY');
            return response()->json(['status' => true, 'type' => 'html', 'msg' => $serviceCategory]);
        } catch (CustomException $e) {
            return response()->json($e->getCustomMessage());
        }
    }
    public function serviceGet(Request $request, Provider $provider, ServiceGetService $serviceGetService){
        if ($request->ajax() == false) abort(404);
        if (empty($request->service_category)) abort(404);
        try {
            $service = $serviceGetService->handle($provider, 'GET_ALL_BY_CATEGORY', $request->service_category);
            $category = ServiceCategory::all();
            $serviceCategoryProvider = $request->service_category;
            return response()->json([
                'status' => true,
                'type' => 'html',
                'msg' => 'Berhasil mendapatkan data layanan API',
                'data' => view('admin.provider.service.service', compact('service', 'provider', 'category', 'serviceCategoryProvider'))->render()
            ]);
        } catch (CustomException $e) {
            return response()->json($e->getCustomMessage());
        }
    }
    public function serviceSyncProvider(Request $request, Provider $provider, ServiceGetService $serviceGetService){
        if ($request->ajax() == false) abort(404);
        $profitConfig = $provider->type === 'game' ? 'game' : 'ppob';
        try {
            $i = 0;
            $setProfit = [
                'profit_type' => getConfig('profit_type_' . $profitConfig),
                'profit' => [
                    'public' => getConfig('profit_public_' . $profitConfig),
                    'silver' => getConfig('profit_silver_' . $profitConfig),
                    'gold' => getConfig('profit_gold_' . $profitConfig),
                    'vip' => getConfig('profit_vip_' . $profitConfig),
                ],
                'profit_config' => [
                    'type' => 'flat',
                    'public' => getConfig('profit_public_' . $profitConfig),
                    'silver' => getConfig('profit_silver_' . $profitConfig),
                    'gold' => getConfig('profit_gold_' . $profitConfig),
                    'vip' => getConfig('profit_vip_' . $profitConfig),
                ],
            ];
            $services = $serviceGetService->handle($provider, 'GET_ALL');
            foreach ($services as $k => $v) {
                switch ($provider->id) {
                    case 2:
                        $v = settingServiceGameIndoCuan($v);
                        break;
                    case 3:
                        $v = settingServicePPOBIndoCuan($v);
                        break;
                    default:
                        throw new Exception('Action not found');
                        break;
                }
                $v = (object) $v; // SET ARRAY TO OBJECT
                $service = Service::where('provider_service_code', $v->provider_service_code)
                    ->where('provider_id', $provider->id)
                    ->first();
                if ($service == null) continue;
                if (getConfig('profit_setting_'.$profitConfig.'_by') === 'service') {
                    $setProfit = [
                        'profit_type' => $service->profit_type,
                        'profit' => [
                            'public' => $service->profit_config->public,
                            'silver' => $service->profit_config->silver,
                            'gold' => $service->profit_config->gold,
                            'vip' => $service->profit_config->vip,
                        ],
                        'profit_config' => [
                            'type' => $service->profit_type,
                            'public' => $service->profit_config->public,
                            'silver' => $service->profit_config->silver,
                            'gold' => $service->profit_config->gold,
                            'vip' => $service->profit_config->vip,
                        ],
                    ];
                }

                $service->profit_type = $setProfit['profit_type'];
                $service->price = [
                    'public' => ($setProfit['profit_type']) === 'flat' ? ceil($v->price + $setProfit['profit']['public']) : ceil($v->price + ($v->price * convertPercent($setProfit['profit']['public']))),
                    'silver' => ($setProfit['profit_type']) === 'flat' ? ceil($v->price + $setProfit['profit']['silver']) : ceil($v->price + ($v->price * convertPercent($setProfit['profit']['silver']))),
                    'gold' => ($setProfit['profit_type']) === 'flat' ? ceil($v->price + $setProfit['profit']['gold']) : ceil($v->price + ($v->price * convertPercent($setProfit['profit']['gold']))),
                    'vip' => ($setProfit['profit_type']) === 'flat' ? ceil($v->price + $setProfit['profit']['vip']) : ceil($v->price + ($v->price * convertPercent($setProfit['profit']['vip']))),
                ];
                $service->profit = [
                    'public' => ($setProfit['profit_type']) === 'flat' ?  $setProfit['profit']['public'] : ceil($v->price * convertPercent($setProfit['profit']['public'])),
                    'silver' => ($setProfit['profit_type']) === 'flat' ?  $setProfit['profit']['silver'] : ceil($v->price * convertPercent($setProfit['profit']['silver'])),
                    'gold' => ($setProfit['profit_type']) === 'flat' ?  $setProfit['profit']['gold'] : ceil($v->price * convertPercent($setProfit['profit']['gold'])),
                    'vip' => ($setProfit['profit_type']) === 'flat' ?  $setProfit['profit']['vip'] : ceil($v->price * convertPercent($setProfit['profit']['vip'])),
                ];

                $service->is_active = ($v->status === 'on') ? 1 : 0;
                $service->save();
                $i++;
            }
            return response()->json([
                'status' => true,
                'type' => 'alert',
                'msg' => 'Berhasil update '.$i.' layanan',
            ]);
        } catch (CustomException $e) {
            return response()->json($e->getCustomMessage());
        }
    }
    public function serviceCreate(Request $request, Provider $provider, ServiceGetService $serviceGetService, $provider_service_code){
        if ($request->ajax() == false) abort('404');
        if ($provider->is_manual == 1){
            return response()->json(['status'  => false, 'type' => 'validation', 'msg' => 'Provider ini tidak memiliki akses.']);
        }
        $category = ServiceCategory::all();
        try {
            $service = $serviceGetService->handle($provider, 'GET_SINGLE', $provider_service_code);
            return response()->json([
                'status' => true,
                'type' => 'html',
                'msg' => 'Berhasil mendapatkan data layanan API',
                'data' => view('admin.provider.service.service_create', compact('service', 'provider', 'category'))->render()
            ]);
        } catch (CustomException $e) {
            return response()->json($e->getCustomMessage());
        }

    }
}
