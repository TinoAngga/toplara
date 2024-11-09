<?php

namespace App\Http\Controllers\Cronjob;

use App\Models\Order;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function refund()
    {
        $orders = Order::query()
                ->select('orders.id', 'orders.user_id', 'orders.payment_id', 'orders.price', 'orders.profit', 'orders.is_paid', 'orders.is_refund', 'orders.status')
                ->where([
                    ['orders.payment_id', '=', 1],
                    ['orders.status', '=', 'gagal'],
                    ['orders.is_refund', '=', 0],
                    ['orders.is_paid', '=', 1],
                ])
                ->inRandomOrder()
                ->limit(20)
                ->get();
        if (!$orders->isEmpty()) {
            foreach ($orders as $key => $order) {
                DB::beginTransaction();
                try {
                    $user = User::find($order->user_id);
                    $beginningBalance = $user->balance;

                    $user->balance = $user->balance + $order->price;
                    $user->save();

                    $user->mutation()->create([
                        'type' => 'debit',
                        'category' => 'refund',
                        'description' => 'Refund order #'.$order->id,
                        'amount' => $order->price,
                        'beginning_balance' => $beginningBalance,
                        'last_balance' => $user->balance,
                    ]);

                    $order->price = 0;
                    $order->profit = 0;
                    $order->is_refund = 1;
                    $order->save();

                    print("Refund order #".$order->id." SUCCESS<br/ >");
                    DB::commit();
                } catch (\Throwable $th) {
                    Log::error($th->getMessage());
                    print("Refund order #".$order->id." ERROR <br/ >");
                    DB::rollback();
                    continue;
                }
                flush();
            }
        } else {
            print("No orders to refund");
        }
    }

    public function cancel()
    {
        $orders = Order::query()
            ->select('orders.id', 'orders.is_paid', 'orders.status', 'orders.created_at', 'orders.price', 'orders.profit')
            ->where([
                ['orders.is_paid', '=', 0],
            ])
            ->inRandomOrder()
            ->limit(20)
            ->get();
        if (!$orders->isEmpty()) {
            foreach ($orders as $key => $order) {
                if (diff_date(now(), $order->created_at) < 0) {
                    $order->price = 0;
                    $order->profit = 0;
                    $order->status = 'gagal';
                    $order->save();

                    print("Cancel order #".$order->id."<br/ >");
                }
                flush();
            }
        } else {
            print("No orders to cancel");
        }
    }

    public function send()
    {
        Log::info('cronjob order sent to provider is running');
        $orders = Order::select('id')
            ->where([
            ['status', '=', 'pending'],
            ['is_paid', '=', 1],
            ['provider_order_id', '=', null],
        ])
        ->inRandomOrder()
        ->limit(20)
        ->get();
        // $orders = Order::where(['status' => 'pending', 'provider_order_id' => null, 'is_paid' => 1])
        //     ->inRandomOrder()
        //     ->limit(20)
        //     ->get();
        if (!empty($orders)) {
            foreach ($orders as $key => $order) {
                $response = (new \App\Services\Primary\Order\SendToProviderService())->handle($order->id);
                if ($response['status'] == false) continue;
                print('Order sent to provider: ' . $order->id . ' <br/ >');
                Log::info('Order sent to provider: ' . $order->id);
                flush();
            }
        } else {
            exit('ORDER NOT FOUND!!');
        }
    }

    public function status()
    {
        Log::info('cronjob check status order provider is running');
        $orders = Order::select('id')
            ->where([
            ['status', '=', 'proses'],
            ['is_paid', '=', 1],
            ['provider_order_id', '!=', null],
        ])
        ->inRandomOrder()
        ->limit(20)
        ->get();
        // $orders = Order::where(['status' => 'proses', 'is_paid' => 1])
        //     ->where('provider_order_id', '!=', null)
        //     ->inRandomOrder()
        //     ->limit(20)
        //     ->get();
        if (!empty($orders)) {
            foreach ($orders as $key => $order) {
                (new \App\Services\Primary\Order\CheckStatusProviderService())->handle($order->id);
                print('Order check status sent to provider: ' . $order->id . ' <br/ >');
                Log::info('Order check status sent to provider: ' . $order->id);
                flush();
            }
        } else {
            exit('ORDER NOT FOUND!!');
        }
    }

}
