<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Libraries\GameChecker;
use App\Models\Order;
use App\Models\Service;
use App\Services\Primary\Order\CheckoutService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class APIController extends Controller
{
    public function __construct(Request $request) {
        $this->middleware('API');
    }

    public function index(Request $request)
    {
        if ($request->action == 'profile') return $this->profile($request);
        if ($request->action == 'check-nickname') return $this->checkNickname($request);
        if ($request->action == 'service') return $this->service($request);
        if ($request->action == 'order') return $this->order($request);
        if ($request->action == 'status') return $this->status($request);

        return response()->json([
            'status' => false,
            'message' => 'Parameter action tidak sesuai.'
        ]);
    }

    public function profile(Request $request)
    {
        $user = $request->get('user');
        return response()->json([
            'status' => true,
            'data' => [
                'name' => $user->full_name,
                'email' => $user->email,
                'balance' => $user->balance,
                'level' => $user->level,
            ],
            'message' => 'Sukses'
        ]);
    }

    protected function checkNickname(Request $request)
    {
        $gameChecker = new GameChecker();
        if (!$request->target || !$request->game) {
            return response()->json([
                'status' => false,
                'message' => 'Parameter tidak sesuai.'
            ]);
        }

        $getNickname = $gameChecker->validation($request->game, $request->target, $request->additional_target);
        if ($getNickname['status'] == false) {
            return response()->json([
                'status' => false,
                'message' => $getNickname['data']['msg']
            ]);
        }

        return response()->json([
            'status' => true,
            'data' => $getNickname['data'],
            'message' => 'Sukses'
        ]);
    }

    protected function service(Request $request)
    {

        $level = $request->get('user')->level;

        if ($request->has('filter_status') AND in_array($request->filter_status, ['1', '0']) == false) {
            return response()->json([
                'status' => false,
                'message' => 'Parameter filter_status tidak sesuai.'
            ]);
        }

        $services = Service::with([
            'category' => function ($query) {
                $query
                    ->select('id', 'name')
                    ->where('is_active', 1);
            }])
            ->when($request->has('filter_status') AND $request->filter_status <> '', function ($query) use ($request) {
                return $query->where('is_active', $request->filter_status);
            })
            ->get();

        $result = [];

        foreach ($services as $service) {
            $result[] = [
                'id' => $service->id,
                'name' => $service->name,
                'category' => $service->category->name,
                'price' => $service->price->{$level},
                'status' => $service->is_active,
            ];
        }

        return response()->json([
            'status' => true,
            'data' => $result,
            'message' => 'Sukses'
        ]);
    }

    protected function order(Request $request)
    {
        if (!$request->has('service') OR !$request->has('target')) {
            return response()->json([
                'status' => false,
                'message' => 'Parameter tidak sesuai.'
            ]);
        }

        try {
            $orderCheckout = (new CheckoutService())->handle($request, true);

            if ($orderCheckout['status'] == false) {
                return response()->json([
                    'status' => false,
                    'message' => $orderCheckout['msg']
                ]);
            } else {
                return response()->json([
                    'status' => true,
                    'data' => $orderCheckout,
                    'message' => 'Sukses'
                ]);
            }
        } catch (\Throwable $th) {
            Log::error($th);
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan.'
            ]);
        }
    }

    public function status(Request $request)
    {
        if (!$request->has('id')) {
            return response()->json([
                'status' => false,
                'message' => 'Parameter tidak sesuai.'
            ]);
        }

        $user = $request->get('user');

        $order = Order::with('service:id,name')
            ->where('user_id', $user->id)
            ->where('invoice', $request->id)
            ->first();

        if (!$order) {
            return response()->json([
                'status' => false,
                'message' => 'Pesanan tidak ditemukan.'
            ]);
        }

        return response()->json([
            'status' => true,
            'data' => [
                'id' => $order->id,
                'service' => $order->service->name,
                'target' => $order->data,
                'additional_target' => $order->additional_data,
                'price' => $order->price,
                'note' => $order->provider_order_description ?? '',
                'last_balance' => $user->balance,
                'status' => $order->status,
                'created_at' => Carbon::parse($order->created_at)->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::parse($order->updated_at)->format('Y-m-d H:i:s'),
            ],
            'message' => 'Sukses'
        ]);
    }
}
