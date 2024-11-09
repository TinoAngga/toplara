<?php

namespace App\Services\Primary\Callback;

use App\Models\Deposit;
use App\Models\Order;
use App\Models\User;
use App\Models\UserLevel;
use App\Models\UserUpgrade;
use Illuminate\Support\Facades\DB;

Class TripayCallbackService
{

    public function handle($data)
    {
        $explodeMerchantRef = explode('-', $data->merchant_ref);
        switch ($explodeMerchantRef[0]) {
            case 'DEPOSIT':
                return $this->deposit((object) $data->all());
                break;
            case 'ORDER':
                return $this->order((object) $data->all());
                break;
            case 'UPLEVEL':
                return $this->upgradeLevel((object) $data->all());
                break;
            default:
                throw new \Exception('Action not found');
                break;
        }
    }

    public function deposit($data)
    {
        $deposit = Deposit::where('payment_gateway_request_response->data->merchant_ref', $data->merchant_ref)
                ->with('user', 'payment')
                ->first();
        if (!$deposit || $deposit->is_paid == 1 ||$deposit->status !== 'pending') {
            throw new \Exception('Invoice not found or status already paid');
        }
        // if ((int) $deposit->total_amount !== (int) $deposit->amount) {
        //     Invalid amount, Expected: ' . $deposit->amount . ' - Received: ' . $data->total_amount;
        // }
        DB::beginTransaction();
        try {
            $user = User::find($deposit->user_id);
            switch ($data->status) {
                case 'PAID':
                    // SET BALANCE USER
                   $user->increment('balance', $deposit->balance);
                   $user->save();

                    // SET STATUS DEPOSIT
                    $deposit->is_paid = 1;
                    $deposit->status = 'sukses';
                    $deposit->payment_gateway_callback_response = json_encode((array) $data);
                    $deposit->save();

                    // INSERT TO MUTATION BALANCE
                    $balanceMutation = new \App\Models\BalanceMutation();
                    $balanceMutation->user_id = $deposit->user_id;
                    $balanceMutation->type = 'debit';
                    $balanceMutation->category = 'deposit';
                    $balanceMutation->amount = $deposit->balance;
                    $balanceMutation->description = 'Deposit dengan ' . $deposit->payment->name . ' #'. $deposit->invoice;
                    $balanceMutation->beginning_balance = $$user->balance - $deposit->amount;
                    $balanceMutation->last_balance = $user->balance;
                    $balanceMutation->save();

                    DB::commit();
                    return true;
                    break;
                case 'EXPIRED':
                    $deposit->status = 'kadaluarsa';
                    $deposit->payment_gateway_callback_response = json_encode((array) $data);
                    $deposit->save();
                    DB::commit();
                    return true;

                case 'FAILED':
                    $deposit->status = 'gagal';
                    $deposit->payment_gateway_callback_response = json_encode((array) $data);
                    $deposit->save();
                    DB::commit();
                    return true;
                    break;
                default:
                    return false;
                    break;
            }
        } catch (\Throwable $th) {
            DB::rollback();
            throw new \Exception($th->getMessage());
        }
    }

    public function order(Object $data)
    {
        $order = Order::where('payment_gateway_request_response->data->merchant_ref', $data->merchant_ref)
                ->with('user', 'payment')
                ->first();
        if (!$order || $order->is_paid == 1 || $order->status !== 'pending' || $order->payment->type == 'saldo') {
            throw new \Exception('Invoice not found or status already paid or payment with saldo');
        }

        // if ((int) $data->total_amount !== (int) $order->amount) {
        //     throw new \Exception('Invalid amount, Expected: ' . $order->amount . ' - Received: ' . $data->total_amount);
        // }

        DB::beginTransaction();
        try {
            switch ($data->status) {
                case 'PAID':
                    // SET STATUS order
                    $order->is_paid = 1;
                    $order->status = 'pending';
                    $order->payment_gateway_callback_response = json_encode((array) $data);
                    $order->save();
                    DB::commit();
                    return true;
                    break;
                case 'EXPIRED':
                    $order->status = 'kadaluarsa';
                    $order->payment_gateway_callback_response = json_encode((array) $data);
                    $order->save();
                    DB::commit();
                    return true;

                case 'FAILED':
                    $order->status = 'gagal';
                    $order->payment_gateway_callback_response = json_encode((array) $data);
                    $order->save();
                    DB::commit();
                    return true;
                    break;

                default:
                    return false;
                    break;
            }
        } catch (\Throwable $th) {
            DB::rollback();
            throw new \Exception($th->getMessage());

        }
    }

    public function upgradeLevel(Object $data)
    {
        $userUpgrade = UserUpgrade::where('payment_gateway_request_response->data->merchant_ref', $data->merchant_ref)
                ->with('payment')
                ->first();
        if (!$userUpgrade || $userUpgrade->is_paid == 1 || $userUpgrade->status !== 'pending' || $userUpgrade->payment->type == 'saldo') {
            throw new \Exception('Invoice not found or status already paid or payment with saldo');
        }

        if ((int) $data->total_amount !== (int) $userUpgrade->amount) {
            throw new \Exception('Invalid amount, Expected: ' . $userUpgrade->amount . ' - Received: ' . $data->total_amount);
        }

        DB::beginTransaction();
        try {
            $user = User::find($userUpgrade->user_id);
            $level = UserLevel::where('name', $userUpgrade->level)->first();
            switch ($data->status) {
                case 'PAID':
                    // SET STATUS UPGRADE USER
                    $userUpgrade->is_paid = 1;
                    $userUpgrade->status = 'sukses';
                    $userUpgrade->payment_gateway_callback_response = json_encode((array) $data);
                    $userUpgrade->save();

                    // SET STATUS UPGRADE USER
                    $user->level = $level->name;
                    $user->save();


                    // INSERT TO MUTATION BALANCE
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
                        ]);
                    }
                    DB::commit();
                    return true;
                    break;
                case 'EXPIRED':
                    $userUpgrade->status = 'kadaluarsa';
                    $userUpgrade->payment_gateway_callback_response = json_encode((array) $data);
                    $userUpgrade->save();
                    DB::commit();
                    return true;

                case 'FAILED':
                    $userUpgrade->status = 'gagal';
                    $userUpgrade->payment_gateway_callback_response = json_encode((array) $data);
                    $userUpgrade->save();
                    DB::commit();
                    return true;
                    break;

                default:
                    return false;
                    break;
            }
        } catch (\Throwable $th) {
            DB::rollback();
            throw new \Exception($th->getMessage());
        }
    }
}
