<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class CronjobController extends Controller
{
    public function refundOrders()
    {
        $orders = Order::query()
                ->with('payment:id,name')
                ->select('orders.id', 'orders.user_id', 'orders.payment_id', 'orders.price', 'orders.is_paid', 'orders.is_refund', 'orders.status')
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
                \DB::beginTransaction();
                try {
                    $order->is_refund = 1;
                    $order->save();

                    $user = User::find($order->user_id);
                    $user->balance = $user->balance + $order->price;
                    $user->save();

                    $user->mutation()->create([
                        'type' => 'debit',
                        'category' => 'refund',
                        'description' => 'Refund order #'.$order->id,
                        'amount' => $order->price,
                        'beginning_balance' => $user->balance - $order->price,
                        'last_balance' => $user->balance,
                    ]);

                    print("Refund order #".$order->id." SUCCESS<br/ >");
                    \DB::commit();
                } catch (\Throwable $th) {
                    \Log::error($th->getMessage());
                    print("Refund order #".$order->id." ERROR <br/ >");
                    \DB::rollback();
                }
            }
        } else {
            print("No orders to refund");
        }
    }

    public function cancelOrders()
    {
        $orders = Order::query()
            ->select('orders.id', 'orders.is_paid', 'orders.status', 'orders.created_at')
            ->where([
                ['orders.is_paid', '=', 0],
            ])
            ->inRandomOrder()
            ->limit(20)
            ->get();
        if (!$orders->isEmpty()) {
            foreach ($orders as $key => $order) {
                if (diff_date(now(), $order->created_at) < 0) {
                    $order->status = 'gagal';
                    $order->save();

                    print("Cancel order #".$order->id."<br/ >");
                }
            }
        } else {
            print("No orders to cancel");
        }
    }
}
