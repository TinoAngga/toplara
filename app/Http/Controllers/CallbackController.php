<?php

namespace App\Http\Controllers;

use App\Services\Primary\Callback\TripayCallbackService;
use App\Services\Primary\Callback\XenditCallbackService;
use App\Services\Primary\Callback\PaydisiniCallbackService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Libraries\Curl;

class CallbackController extends Controller
{
    public function tripay(Request $request, TripayCallbackService $tripayCallbackService)
    {
        $callbackSignature = $request->server('HTTP_X_CALLBACK_SIGNATURE');
        $json = $request->getContent();
        $signature = hash_hmac('sha256', $json, getConfig('tripay_private_key') ?? config('constants.options.tripay_private_key'));

        if ($signature !== $callbackSignature) {
            return 'Invalid signature';
        }

        if ('payment_status' !== (string) $request->server('HTTP_X_CALLBACK_EVENT')) {
            return 'Invalid callback event, no action was taken';
        }

        try {
            $requestServices = $tripayCallbackService->handle($request);
            return response()->json(['success' => $requestServices], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function xendit(Request $request, XenditCallbackService $xenditCallbackService)
    {
        $callbackSignature = $request->header('x-callback-token');
        $json = json_decode(file_get_contents('php://input'), true);
        Log::info(file_get_contents('php://input'));
        Curl::request('POST', 'https://indocuan.id/library/callback/xendit.php', file_get_contents('php://input'));
        // Log::info(json_encode($json));
        // return;
        $signature = getConfig('xendit_private_key') ?? config('constants.options.xendit_callback_token');
        // if ($signature !== $callbackSignature) {
        //     return 'Invalid signature';
        // }
        // return response()->json([
        //     'success' => true
        // ]);
        try {
            $requestService = $xenditCallbackService->handle($json);
            return response()->json(['success' => $requestService], 200);
        } catch (\Throwable $th) {
            Log::info($th);
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function paydisini(Request $request, PaydisiniCallbackService $paydisiniCallbackService)
    {

        if ($request->key <> getConfig('paydisini_api_key')) abort(404);
        Log::info("PAYDISINI CALLBACK : [".$request->ip()."] " . json_encode($request->all()));
        // $callbackSignature = $request->server('HTTP_X_CALLBACK_SIGNATURE');
        // $json = $request->getContent();
        // $signature = hash_hmac('sha256', $json, getConfig('tripay_private_key') ?? config('constants.options.tripay_private_key'));

        // if ($signature !== $callbackSignature) {
        //     return 'Invalid signature';
        // }

        // if ('payment_status' !== (string) $request->server('HTTP_X_CALLBACK_EVENT')) {
        //     return 'Invalid callback event, no action was taken';
        // }

        try {
            $requestServices = $paydisiniCallbackService->handle($request);
            return response()->json(['success' => $requestServices], 200);
        } catch (\Throwable $th) {
            Log::info('PAYDISINI CALLBACK ERROR : ' . $th->getMessage());
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

}
