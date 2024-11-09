<?php

namespace App\Http\Controllers\Primary;

use App\DataTables\Primary\UserUpgradeDataTable;
use App\DataTables\Primary\BalanceMutationDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Primary\UserUpdateRequest;
use App\Http\Requests\Primary\UserUpgradeRequest;
use App\Libraries\CustomException;
use App\Models\PaymentMethod;
use App\Models\User;
use App\Models\UserLevel;
use App\Models\UserUpgrade;
use App\Services\Primary\Account\UpgradeLevelService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    public function index()
    {
        $orderHistory = user()
                    ->order()
                    ->with('service:id,name')
                    ->orderBy('orders.created_at', 'desc')
                    ->limit(2)
                    ->get();

        $mutationHistory = user()
                    ->mutation()
                    ->latest()
                    ->limit(5)
                    ->get();
        $page = [
            'title' => 'Profil',
            'breadcrumb' => [
                'first' => 'Profil'
            ]
        ];
        return view('primary.account.index', compact('page', 'orderHistory', 'mutationHistory'));
    }
    public function mutation(BalanceMutationDataTable $balanceMutationDataTable)
    {
        $page = [
            'title' => 'Mutasi Saldo',
            'breadcrumb' => [
                'first' => 'Profil',
                'second' => 'Mutasi Saldo'
            ]
        ];
        $type = ['debit' => 'Debet', 'credit' => 'Kredit'];
        $category = ['service','order','refund','upgrade-level','others'];
        return $balanceMutationDataTable->render('primary.account.mutation.index', compact('page', 'type', 'category'));
    }
    public function upgrade(Request $request)
    {
        $page = [
            'title' => 'Upgrade Level',
            'breadcrumb' => [
                'first' => 'Profil',
                'second' => 'Upgrade Level'
            ]
        ];
        $payments = PaymentMethod::paymentActive()
                ->whereNotIn('type', ['saldo'])
                ->get();
        $levels = UserLevel::query()
            ->whereNotIn('name', [
                'public', strtolower(Auth::user()->level)
            ])
            ->get();
        return view('primary.account.upgrade.index', compact('levels', 'page', 'payments'));

    }
    public function postUpgrade(UserUpgradeRequest $request)
    {
        try {
            $upgradeLevelService = (new UpgradeLevelService())->handle($request);
            if ($upgradeLevelService['status'] === true) {
                return response()->json([
                    'status' => true,
                    'type' => 'alert',
                    'msg' => 'Pesanan berhasil dilakukan dengan nomor invoice <b>#' . $upgradeLevelService['invoice'] . '</b>',
                    'redirect_url' => $upgradeLevelService['redirect_url']
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'type' => 'alert',
                    'msg' => $upgradeLevelService['msg'],
                ]);
            }
        } catch (CustomException $e) {
            return response()->json($e->getCustomMessage());
        }
    }
    public function getPriceUpgrade(Request $request)
    {
        if(!$request->ajax()) abort(404);
        $level = UserLevel::find($request->level);
        if ($level == null) abort(404);
        $payments = PaymentMethod::paymentActive()->get();
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
            $totalPrice = $level->price + $value->fee + ($level->price * convertPercent($value->fee_percent));
            if ($value->min_amount > $totalPrice) {
                $payment_offline = true;
                $payment_description = 'Minimal pembayaran Rp ' . currency($value->min_amount);
            } else if ($value->max_amount < $totalPrice) {
                $payment_offline = true;
                $payment_description = 'Maksimal pembayaran Rp ' . currency($value->max_amount);
            }
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
    public function upgradeInvoice(String $invoice = null)
    {
        $invoice = UserUpgrade::with('payment')
            ->where('invoice', $invoice)
            ->first();
        $level = UserLevel::query()
            ->where('name', $invoice->level)
            ->first();
        if($invoice == null){
            session()->flash('alertClass', 'danger');
            session()->flash('alertTitle', 'Gagal.');
            session()->flash('alertMsg', 'Pesanan tidak ditemukan.');
            return redirect()->route('account.upgrade.history');
        }
        $page = [
            'title' => 'Invoice #' . $invoice->invoice,
            'breadcrumb' => [
                'first' => 'Profil',
                'second' => '#' . $invoice->invoice
            ]
        ];
        return view('primary.account.upgrade.invoice', compact('invoice', 'page', 'level'));
    }
    public function upgradeHistory(UserUpgradeDataTable $dataTable)
    {
        $page = [
            'title' => 'Riwayat Upgrade Level',
            'breadcrumb' => [
                'first' => 'Profil',
                'second' => 'Riwayat Upgrade Level'
            ]
        ];
        $paid = ['1' => 'Lunas', '0' => 'Belum Lunas'];
        $payments = PaymentMethod::paymentActive()->get();
        $status = [
            'pending', 'sukses', 'gagal', 'kadaluarsa'
        ];
        return $dataTable->render('primary.account.upgrade.history', compact('page', 'paid', 'payments', 'status'));
    }
    public function update(UserUpdateRequest $request)
    {
        if (!$request->ajax()) abort(404);
        $user = Auth::user();
        if (Hash::check($request->password, Auth::user()->password) == true) {
            $user->password = Hash::make($request->password);
            if ($request->new_password <> '') {
                $user->password = Hash::make($request->new_password);
            }
            $user->full_name = $request->full_name;
            $user->phone_number = $request->phone_number;
            $user->save();
            session()->flash('alertClass', 'success');
            session()->flash('alertTitle', 'Berhasil.');
            session()->flash('alertMsg', 'Profil berhasil diubah.');
            return response()->json([
                'status'  => true,
                'msg' => 'Profil berhasil diubah.'
            ]);
        } else {
            return response()->json([
                'status'  => false,
                'type'    => 'alert',
                'msg' => 'Password salah.'
            ]);
        }
    }

    public function updateAPI(Request $request)
    {
        if (!$request->ajax()) abort(404);

        $validator = makeValidator([
            'password' => 'required'
        ], [
            'password.required' => 'Password tidak boleh kosong.'
        ], $request->all());

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'type'    => 'alert',
                'msg' => $validator->errors()->toArray()
            ]);
        }

        if (Hash::check($request->password, Auth::user()->password) == false) {
            return response()->json([
                'status'  => false,
                'type'    => 'alert',
                'msg' => 'Password salah.'
            ]);
        }

        $user = Auth::user();
        $user->whitelist_ip = $request->whitelist_ip;
        $user->save();
        return response()->json([
            'status'  => true,
            'msg' => 'Konfigurasi API berhasil dilakukan.'
        ]);
    }

    public function generateAPIKey(Request $request)
    {
        if (!$request->ajax()) abort(404);
        $user = Auth::user();
        $user->api_key = encrypt(md5($user->id . '-' . uniqid() . '-' . time()));
        $user->save();

        return response()->json([
            'status'  => true,
            'v' => $user->api_key,
            'msg' => 'API Key berhasil diubah.'
        ]);

    }
}
