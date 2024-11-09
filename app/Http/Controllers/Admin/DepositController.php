<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\DepositDataTable;
use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use App\Models\Deposit;
use App\Models\User;

class DepositController extends Controller
{
    public function index(DepositDataTable $dataTable)
    {
        if (request()->ajax() AND request('type') == 'select2' AND !empty(request('search'))) {
            $users = User::where(function($query){
                $query->where('full_name', 'LIKE', '%'.request('search').'%')
                ->orWhere('username', 'LIKE', '%'.request('search').'%');
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
        $page = 'Deposit';
        $paid = ['1' => 'Lunas', '0' => 'Belum Lunas'];
        $payments = PaymentMethod::paymentActive()->get();
        $status = [
            'pending', 'sukses', 'gagal', 'kadaluarsa'
        ];
        return $dataTable->render('admin.deposit.index', compact('page', 'paid', 'payments', 'status'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(Deposit $deposit)
    {
        return view('admin.deposit.show', compact('deposit'));
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy(Deposit $deposit)
    {
        $deposit->delete();
        return response()->json([
            'status'  => true,
            'type' => 'alert',
            'msg' => 'Berhasil menghapus data #'.$deposit->invoice.'.'
        ]);
    }

    public function confirmPaid(Request $request, Deposit $deposit, $paid){
        if (!$request->ajax()) abort(405);
        if (in_array($paid, ['0','1']) == false OR in_array($deposit->status, ['pending']) == false) return response()->json([
            'status'  => false,
            'type' => 'alert',
            'msg' => 'Permintaan pembayaran tidak valid.'
        ]);
        $payment = PaymentMethod::find($deposit->payment_id);
        if ($payment == null) return response()->json([
            'status'  => false,
            'type' => 'alert',
            'msg' => 'Metode pembayaran tidak tersedia.'
        ]);
        $user = User::find($deposit->user_id);
        if ($user == false) return response()->json([
            'status'  => false,
            'type' => 'alert',
            'msg' => 'Pengguna tidak tersedia.'
        ]);
        \DB::beginTransaction();
        try {
            if ($paid == 1) {
                $deposit->is_paid = 1;
                $deposit->status = 'sukses';
                $deposit->save();

                $user->balance = $user->balance + $deposit->balance;
                $user->save();

                 // INSERT TO MUTATION BALANCE
                 $balanceMutation = new \App\Models\BalanceMutation();
                 $balanceMutation->user_id = $deposit->user_id;
                 $balanceMutation->type = 'debit';
                 $balanceMutation->category = 'deposit';
                 $balanceMutation->amount = $deposit->balance;
                 $balanceMutation->description = 'Deposit dengan ' . $deposit->payment->name . ' #'. $deposit->invoice;
                 $balanceMutation->beginning_balance = $user->balance - $deposit->balance;
                 $balanceMutation->last_balance = $deposit->user->balance;
                 $balanceMutation->save();

                \DB::commit();
                return response()->json([
                    'status'  => true,
                    'type' => 'alert',
                    'msg' => 'Konfirmasi pembayaran <b>#' . $deposit->invoice . '</b> berhasil dilakukan.'
                ]);
            } elseif ($paid == 0) {
                $deposit->is_paid = 0;
                $deposit->status = 'gagal';
                $deposit->save();

                \DB::commit();
                return response()->json([
                    'status'  => true,
                    'type' => 'alert',
                    'msg' => 'Konfirmasi pembayaran <b>#' . $deposit->invoice . '</b> ditolak.'
                ]);
            }

        } catch (\Throwable $e) {
            \Log::error($e);
            \DB::rollBack();
            return response()->json([
                'status'  => false,
                'type' => 'alert',
                'msg' => $e->getMessage()
            ]);
        }
    }

    public function changeStatus(Request $request, Deposit $deposit, $status){
        if (!$request->ajax()) abort(405);
        if (in_array($status, ['pending','sukses', 'gagal', 'kadaluarsa']) == false) return response()->json([
            'status'  => false,
            'type' => 'alert',
            'msg' => 'Permintaan tidak valid.'
        ]);
        $deposit->status = $status;
        $deposit->save();
        return response()->json([
            'status'  => true,
            'type' => 'alert',
            'msg' => 'Berhasil merubah status deposit <b>#'.$deposit->invoice.'</b>.'
        ]);
    }
}
