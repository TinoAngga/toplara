<?php

namespace App\Http\Controllers\Primary;

use App\Http\Controllers\Controller;
use App\Http\Requests\Primary\OrderRequest;
use App\Models\PaymentMethod;
use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;
use App\Libraries\GameChecker;
use App\Models\Banner;
use App\Models\ServiceCategoryType;
use Illuminate\Support\Facades\Auth;
use App\Libraries\AppDolananKode;

class ProductController extends Controller
{
    public function category(ServiceCategoryType $target)
    {
        if ($target->is_active === 0) abort(404);
        $banners = Banner::query()->get();
        $serviceCategoryType = ServiceCategoryType::query()
            ->whereIn('is_active', [1])
            ->select('slug', 'name', 'icon')
            ->orderBy('position', 'ASC')
            ->get();

        $populers = getPopularCategory();

        $page = [
            'type' => $target->slug ?? 'game',
            'title' => $target == null ? 'Top Up Game' : $target->name,
            'breadcrumb' => [
                'first' => $target == null ? 'Top Up Game' : $target->name
            ]
        ];
        return view('primary.home.index', compact('page', 'banners', 'serviceCategoryType', 'populers'));
    }

    public function search(Request $request)
    {
        $search = $request->q;

        $serviceCategories = ServiceCategory::query()
            ->where('name', 'LIKE', "%{$search}%")
            ->where('is_active', 1)
            ->select('id', 'name', 'slug', 'service_type', 'img')
            ->orderBy('name', 'ASC')
            ->get();

        return response()->json([
            'status' => false,
            'html' => view('primary.product.ajax.search', compact('serviceCategories', 'search'))->render()
        ]);
    }

    public function order($type, $category){

        $category = ServiceCategory::query()
            ->where('service_type', $type)
            ->where('slug', $category)
            ->first();

        if ($category == null) abort(404, 'Not Found');

        // $services = Service::where('service_category_id', $category->id)
        //     ->where('service_type', $type)
        //     ->where('is_active', 1)
        //     ->select('id', 'name', 'price')
        //     ->orderByRaw("CAST(JSON_EXTRACT(price,'$.public') AS UNSIGNED) ASC")->get();

        // $payments = DB::table('payment_methods')
        //         ->select('id', 'img', 'name', 'type')
        //         ->where(function ($query) {
        //             $query
        //                 ->where('is_active', 1)
        //                 ->whereNotIn('type', ['saldo'])
        //                 ->when(user() === false, function ($query) {
        //                     $query
        //                         ->where('is_public', 1);
        //                 });
        //         })
        //         ->get();
        $page = [
            'title' => $category->name,
            'breadcrumb' => [
                'first' => 'Produk',
                'second' => $category->name
            ]
        ];
        return view('primary.product.index', compact('page', 'type', 'category'));
    }

    public function getDetail(OrderRequest $request, ServiceCategory $serviceCategory, GameChecker $gameChecker)
{
    if (!$request->ajax()) abort(404);

    // VALIDASI EMAIL
    $request->validate([
        'email' => 'nullable|email', // Pastikan email diisi dan valid
    ]);

    // SIMPAN EMAIL KE DALAM EMAIL_ORDER
    $emailOrder = $request->input('email');

    // START CHECK NICKNAME GAME //
    $getNickname = null;
    if ($serviceCategory->is_check_id == true) {
        $getNickname = (new AppDolananKode())->checkNickname($serviceCategory->get_nickname_code, $request->data, $request->additional_data);
        if ($getNickname['status'] == false) {
            return response()->json([
                'status' => false,
                'type' => 'alert',
                'msg' => $getNickname['msg']
            ]);
        }
        session(['nickname_game' => $getNickname['data']]);
    }
    // END CHECK NICKNAME GAME //

    // START CHECK SERVICE //
    $service = Service::query()
        ->with('category:id,name,slug')
        ->where('id', $request->service)
        ->select('service_category_id', 'name', 'price')
        ->first();
    if (!$service) {
        return response()->json([
            'status' => false,
            'type' => 'alert',
            'msg' => 'Layanan tidak tersedia'
        ]);
    }
    // END START CHECK SERVICE //

    // START CHECK PAYMENT //
    $payment = PaymentMethod::query()
        ->where('id', $request->payment)
        ->select('fee', 'fee_percent', 'name', 'min_amount', 'max_amount', 'time_used', 'time_stopped')
        ->first();
    if (!$payment) {
        return response()->json([
            'status' => false,
            'type' => 'alert',
            'msg' => 'Pembayaran tidak tersedia'
        ]);
    }
    // END START CHECK PAYMENT //

    // CHECK ONLINE OR OFFLINE PAYMENT //
    if (!is_null($payment->time_used) && !is_null($payment->time_stopped)) {
        if (onlineHours($payment->time_used, $payment->time_stopped) == false) {
            return response()->json([
                'status' => false,
                'type' => 'alert',
                'msg' => 'Saat ini metode pembayaran ini sedang offline'
            ]);
        }
    }

    // START CHECK ADDITIONAL INFO //
    $additionalInfo = [
        'data' => $request->data,
        'additional_data' => $request->additional_data,
        'email_order' => $emailOrder // Simpan email order di additional info
    ];

    if (preg_match("/joki-mobile-legend/i", $service->category->slug)) {
        $additionalInfo['hero'] = $request->hero;
        $additionalInfo['login'] = $request->login;
        $additionalInfo['whatsapp'] = $request->whatsapp;
        $additionalInfo['note'] = $request->note;
        $additionalInfo['user_nickname'] = $request->user_nickname;
        if ($service->category->slug == 'joki-mobile-legends-ranked') $additionalInfo['star'] = $request->star;
    }
    // END CHECK ADDITIONAL INFO //

    $getUserLevel = Auth::check() == true ? Auth::user()->level : 'public';

    $price['price'] = $service->price->{$getUserLevel};
    $price['fee'] = $payment->fee;
    $price['feePercent'] = $payment->fee_percent;

    if ($service->category->slug == 'joki-mobile-legends-ranked') {
        $price['price'] = $price['price'] * $request->star;
    }

    $price['totalPrice'] = $price['price'] + $payment->fee + ($price['price'] * convertPercent($payment->fee_percent));

    if ($price['totalPrice'] < $payment->min_amount) {
        return response()->json([
            'status' => false,
            'type' => 'alert',
            'msg' => 'Minimal pembayaran Rp ' . currency($payment->min_amount)
        ]);
    } else if ($price['totalPrice'] > $payment->max_amount) {
        return response()->json([
            'status' => false,
            'type' => 'alert',
            'msg' => 'Maksimal pembayaran Rp ' . currency($payment->max_amount)
        ]);
    }

    $target = ($request->additional_data <> null) ? $request->data . ' (' . $request->additional_data . ') ' : $request->data;

    return response()->json([
        'status' => true,
        'title' => $serviceCategory->name . ' - ' . $service->name,
        'body' => view('primary.product.ajax.get-detail', compact('getNickname', 'price', 'payment', 'service', 'target', 'additionalInfo'))->render()
    ]);
}


    public function getPrice(Request $request) {
        if(!$request->ajax()) abort(404);
        $service = Service::find((int)$request->service);
        if(!$service || $service->is_active == 0) abort(404);
        $service->load('category:id,service_type,slug');

        $payments = PaymentMethod::paymentPublicActive()->get();
        $payment_methods = [];
		foreach ($payments as $key => $value) {
            $payment_offline = false;
            $payment_description = null;
            if(!is_null($value->time_used) && !is_null($value->time_stopped)){
                if (onlineHours($value->time_used, $value->time_stopped) == false) {
                    $payment_offline = true;
                    $payment_description = 'Saat ini metode pembayaran ini sedang offline';
                }
                // if (strtotime(date('H:i')) <= strtotime($value->time_used) && strtotime(date('H:i')) >= strtotime($value->time_stopped)) {
                //     echo 'yes';
                //     $payment_offline = true;
                //     $payment_description = 'Saat ini metode pembayaran ini sedang offline';
                // }
            }
            $getUserLevel = Auth::check() == true ? Auth::user()->level : 'public';
            $totalPrice = $service->price->{$getUserLevel};

            if ($service->category->slug == 'joki-mobile-legends-ranked') $totalPrice = $totalPrice * $request->star;

            $totalPrice = $totalPrice + $value->fee + ($totalPrice * convertPercent($value->fee_percent));

            if ($value->min_amount > $totalPrice) {
                $payment_offline = true;
                $payment_description = 'Minimal pembayaran Rp ' . currency($value->min_amount);
            } else if ($value->max_amount < $totalPrice) {
                $payment_offline = true;
                $payment_description = 'Maksimal pembayaran Rp ' . currency($value->max_amount);
            }
            // if ($service->category->slug == 'joki-mobile-legends-ranked') {
            //     $totalPrice = $totalPrice * $request->star;
            // }
			$payment_methods[] = [
                'id' => $value->id,
                'type' => $value->type,
				'price' => [
                    'integer' => ($payment_offline === false) ? ceil($totalPrice) : 0,
                    'string' => ($payment_offline === false) ? 'Rp ' . currency(ceil($totalPrice)) : '<i>Offline</i>'
                ],
                'offline' => $payment_offline,
                'description' => $payment_description,
                'valid_amount' => [
                    'min' => $value->min_amount,
                    'max' => $value->max_amount
                ]
			];
		}
        return response()->json([
            'status' => true,
            'data' => $payment_methods
        ]);
    }
}
