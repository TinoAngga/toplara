<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\UserUpgradeDataTable;
use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use App\Models\UserUpgrade;
use App\Models\User;
use App\Models\UserLevel;

class UserUpgradeController extends Controller
{
    public function index(UserUpgradeDataTable $dataTable)
    {
        if (request()->ajax() AND request('type') == 'select2_user' AND !empty(request('search'))) {
            $users = User::where(function($query){
                $query->where('full_name', 'LIKE', '%'.request('search').'%')
                ->orWhere('username', 'LIKE', '%'.request('search').'%')
                ->orWhere('email', 'LIKE', '%'.request('search').'%');
            })->get();
            $data = [];
            foreach ($users as $key => $value) {
                $data[] = [
                    'id' => $value->id,
                    'text' => $value->username
                ];
            }
            return $data;
        }
        $page = 'Peningkatan Pengguna';
        $paid = ['1' => 'Lunas', '0' => 'Belum Lunas'];
        $payments = PaymentMethod::paymentActive()->get();
        $status = [
            'pending', 'sukses', 'gagal', 'kadaluarsa'
        ];
        return $dataTable->render('admin.user-upgrade.index', compact('page', 'paid', 'payments', 'status'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(UserUpgrade $userUpgrade)
    {
        $userUpgrade->with('user', 'payment');
        return view('admin.user-upgrade.show', compact('order'));
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy(UserUpgrade $userUpgrade)
    {
        $userUpgrade->delete();
        return response()->json([
            'status'  => true,
            'type' => 'alert',
            'msg' => 'Berhasil menghapus data #'.$userUpgrade->invoice.'.'
        ]);
    }

    public function confirmPaid(Request $request, UserUpgrade $userUpgrade, $paid){
        if (!$request->ajax()) abort(405);
        if (in_array($paid, ['0','1']) == false OR in_array($userUpgrade->status, ['pending']) == false) return response()->json([
            'status'  => false,
            'type' => 'alert',
            'msg' => 'Permintaan pembayaran tidak valid.'
        ]);
        $payment = PaymentMethod::find($userUpgrade->payment_id);
        if ($payment == null) return response()->json([
            'status'  => false,
            'type' => 'alert',
            'msg' => 'Metode pembayaran tidak tersedia.'
        ]);
        $user = User::find($userUpgrade->user_id);
        if ($user == false) return response()->json([
            'status'  => false,
            'type' => 'alert',
            'msg' => 'Pengguna tidak tersedia.'
        ]);
        \DB::beginTransaction();
        try {
            if ($paid == 1) {
                $level = UserLevel::where('name', $userUpgrade->level)->first();

                $userUpgrade->is_paid = 1;
                $userUpgrade->status = 'sukses';
                $userUpgrade->save();

                $user->level = $userUpgrade->level;
                $user->save();

                if ($level->get_balance > 0) {
                    $user->balance = $user->balance + $level->get_balance;
                    $user->save();
                    $user->mutation()->create([
                        'type' => 'debit',
                        'category' => 'upgrade-level',
                        'description' => 'Bonus upgrade level '.$userUpgrade->level.'.',
                        'amount' => $level->get_balance,
                        'beginning_balance' => $user->balance - $level->get_balance,
                        'last_balance' => $user->balance,
                        'ip_address' => $userUpgrade->ip_address,
                    ]);
                }

                \DB::commit();
                return response()->json([
                    'status'  => true,
                    'type' => 'alert',
                    'msg' => 'Konfirmasi pembayaran <b>#' . $userUpgrade->invoice . '</b> berhasil dilakukan.'
                ]);
            } elseif ($paid == 0) {
                $userUpgrade->is_paid = 0;
                $userUpgrade->status = 'gagal';
                $userUpgrade->save();

                \DB::commit();
                return response()->json([
                    'status'  => true,
                    'type' => 'alert',
                    'msg' => 'Konfirmasi pembayaran <b>#' . $userUpgrade->invoice . '</b> ditolak.'
                ]);
            }
        } catch (\Throwable $e) {
            return response()->json([
                'status'  => false,
                'type' => 'alert',
                'msg' => $e->getMessage()
            ]);
        }
    }

    public function changeStatus(Request $request, UserUpgrade $userUpgrade, $status){
        if (!$request->ajax()) abort(405);
        if (in_array($status, ['pending','sukses', 'proses', 'gagal', 'kadaluarsa']) == false) return response()->json([
            'status'  => false,
            'type' => 'alert',
            'msg' => 'Permintaan tidak valid.'
        ]);
        $userUpgrade->status = $status;
        $userUpgrade->save();
        return response()->json([
            'status'  => true,
            'type' => 'alert',
            'msg' => 'Berhasil merubah status pesanan <b>#'.$userUpgrade->invoice.'</b>.'
        ]);
    }
}
