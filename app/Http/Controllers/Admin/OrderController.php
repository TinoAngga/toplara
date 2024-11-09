<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\OrderDataTable;
use App\Events\OrderPlaced;
use App\Http\Controllers\Controller;
use App\Jobs\OrderNotifyJob;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Provider;
use App\Models\Service;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use App\Mail\OrderNotifyMail;
use \App\Libraries\WhatsappGateway\AxSender;
use App\Libraries\WhatsappGateway\InvoiceGenerator;

class OrderController extends Controller
{
    public function index(OrderDataTable $dataTable)
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
        } else if (request()->ajax() AND request('type') == 'select2_service' AND !empty(request('search'))) {
            $services = Service::with('category')->where(function($query){
                $query
                    ->whereHas('category', function($query) {
                        $query->where('name', 'like', "%".request('search')."%");
                    })
                    ->orWhere('name', 'LIKE', '%'.request('search').'%');
            })->get();
            $data = [];
            foreach ($services as $key => $value) {
                $data[] = [
                    'id' => $value->id,
                    'text' => $value->name . ' ( '.$value->category->name.' ) '
                ];
            }
            return $data;
        }
        $page = 'Pesanan';
        $paid = ['1' => 'Lunas', '0' => 'Belum Lunas'];
        $orderType = ['public' => 'Public', 'member' => 'Member'];
        $payments = PaymentMethod::paymentActive()->select('id', 'name')->get();
        $providers = Provider::query()->select('id', 'name')->get();
        $status = [
            'pending', 'proses', 'sukses', 'gagal', 'kadaluarsa', 'SALAH ID'
        ];
        return $dataTable->render('admin.order.index', compact('page', 'paid', 'payments', 'providers', 'status', 'orderType'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(Order $order)
    {
        return view('admin.order.show', compact('order'));
    }

    public function edit(Order $order)
    {
        return view('admin.order.edit', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        if (!$request->ajax()) abort(405);
        $order->provider_order_description = $request->provider_order_description;
        $order->save();

        return response()->json([
            'status'  => true,
            'type' => 'alert',
            'msg' => 'Pesanan berhasil diubah #' . $order->id . '.'
        ]);
    }

    public function destroy(Order $order)
    {
        $order->delete();
        return response()->json([
            'status'  => true,
            'type' => 'alert',
            'msg' => 'Berhasil menghapus data #'.$order->invoice.'.'
        ]);
    }

    public function confirmPaid(Request $request, Order $order, $paid){
        if (!$request->ajax()) abort(405);
        if (in_array($paid, ['0','1']) == false OR in_array($order->status, ['pending']) == false) return response()->json([
            'status'  => false,
            'type' => 'alert',
            'msg' => 'Permintaan pembayaran tidak valid.'
        ]);
        $payment = PaymentMethod::find($order->payment_id);
        if ($payment == null) return response()->json([
            'status'  => false,
            'type' => 'alert',
            'msg' => 'Metode pembayaran tidak tersedia.'
        ]);
        $service = Service::find($order->service_id);
        if ($service == null) return response()->json([
            'status'  => false,
            'type' => 'alert',
            'msg' => 'Layanan tidak tersedia.'
        ]);
        $user = User::find($order->user_id);
        if ($order->order_type == 'member') {
            if ($user == false) return response()->json([
                'status'  => false,
                'type' => 'alert',
                'msg' => 'Pengguna tidak tersedia.'
            ]);
        }
        DB::beginTransaction();
        try {
            if ($paid == 1) {
                $order->is_paid = 1;
                $order->status = 'pending';
                $order->save();

                // OrderPlaced::dispatch($order);

                DB::commit();
                return response()->json([
                    'status'  => true,
                    'type' => 'alert',
                    'msg' => 'Konfirmasi pembayaran <b>#' . $order->invoice . '</b> berhasil dilakukan.'
                ]);
            } elseif ($paid == 0) {
                $order->is_paid = 0;
                $order->status = 'gagal';
                $order->save();

                DB::commit();
                return response()->json([
                    'status'  => true,
                    'type' => 'alert',
                    'msg' => 'Konfirmasi pembayaran <b>#' . $order->invoice . '</b> ditolak.'
                ]);
            }
        } catch (\Throwable $th) {
            Log::info('confirm paid from admin order invoice : ' . $order->invoice . ' | Error :' . $th->getMessage());
            DB::rollBack();
            return response()->json([
                'status'  => false,
                'type' => 'alert',
                'msg' => 'Konfirmasi pembayaran <b>#' . $order->invoice . '</b> gagal dilakukan.'
            ]);
        }
    }

   public function changeStatus(Request $request, Order $order, $status) {
    if (!$request->ajax()) abort(405);

    if (!in_array($status, ['pending', 'sukses', 'proses', 'gagal', 'kadaluarsa', 'SALAH ID'])) {
        return response()->json([
            'status'  => false,
            'type' => 'alert',
            'msg' => 'Permintaan tidak valid.'
        ]);
    }

    $lastStatus = $order->status;
    $order->status = $status;
    $order->save();
    
    $invoiceNumber = $order->invoice;
    $email = $order->email_order;

    // // Kirim email jika status berubah menjadi 'sukses'
    // if ($order->status == 'sukses' && $lastStatus != $order->status) {
    //     // Jika email null atau kosong, biarkan dan selesai
    //     if (!empty($email)) {
    //         // Kirim email menggunakan Mailable
    //         Mail::to($email)->send(new OrderNotifyMail($order));
    //     }
    // }

    // // Kirim email jika status berubah menjadi 'SALAH ID'
    // if ($order->status == 'SALAH ID' && $lastStatus != $order->status) {
    //     try {
    //         $message = "Pesanan dengan Nomor Invoice: *$invoiceNumber* tidak dapat diproses karena ID yang dimasukkan salah. Harap periksa kembali ID Anda.";

    //         Mail::raw($message, function ($mail) use ($order) {
    //             $mail->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
    //             $mail->to($order->email_order)
    //                  ->subject('Pesanan Anda Salah ID');
    //         });

    //         Log::info("Email berhasil dikirim untuk pesanan salah ID #$invoiceNumber.");

    //     } catch (\Throwable $th) {
    //         Log::error('Gagal mengirim email: ' . $th->getMessage());
    //     }
    // }
    
// Kirim notifikasi WhatsApp jika status berubah menjadi 'sukses'
// if ($order->status == 'sukses' && $lastStatus != $order->status) {
//     try {
//         $message = "✅ Pesanan Berhasil #" . $order->invoice . "\n\n";
//         $message .= "Detail Pesanan:\n";
//         $message .= "📦 Layanan: " . $order->service->name . "\n";
//         $message .= "📝 ID GAME: " . $order->data . "\n";
//         $message .= "📅 Tanggal: " . $order->created_at->format('d/m/Y H:i') . "\n\n";
//         $message .= "Terima kasih telah menggunakan layanan kami! 🙏";

//         // Initialize WhatsApp sender
//         $sender = new AxSender();
        
//         // Ambil nomor WhatsApp dari database
//         $phoneNumber = $order->whatsapp_order; // Mengambil nomor WhatsApp dari kolom whatsapp_order
        
//         if ($phoneNumber) {
//             // Send WhatsApp message
//             $sent = $sender->sendMessage($message, [
//                 'target' => $phoneNumber,
//             ]);

//             if ($sent) {
//                 Log::info("WhatsApp notification sent for order #$invoiceNumber");
//             } else {
//                 Log::error("Failed to send WhatsApp notification for order #$invoiceNumber");
//             }
//         } else {
//             Log::warning("No WhatsApp number found for order #$invoiceNumber");
//         }
//     } catch (\Throwable $th) {
//         Log::error('Failed to send WhatsApp notification: ' . $th->getMessage());
//     }
// }

// if ($order->status == 'sukses' && $lastStatus != $order->status) {
//     try {
//         // Siapkan caption untuk pesan media
//         $caption = "✅ Pesanan Berhasil #" . $order->invoice . "\n\n";
//         $caption .= "Detail Pesanan:\n";
//         $caption .= "📦 Layanan: " . $order->service->name . "\n";
//         $caption .= "📝 ID GAME: " . $order->data . "\n";
//         $caption .= "📅 Tanggal: " . $order->created_at->format('d/m/Y H:i') . "\n\n";
//         $caption .= "Terima kasih telah menggunakan layanan kami! 🙏";

//         // Inisialisasi pengirim WhatsApp
//         $sender = new AxSender();
        
//         // Ambil nomor WhatsApp dari database
//         $phoneNumber = $order->whatsapp_order; // Mengambil nomor WhatsApp dari kolom whatsapp_order
        
//         if ($phoneNumber) {
//             // Kirim pesan media WhatsApp
//             $sent = $sender->sendMedia($caption, [
//                 'target' => $phoneNumber,
//                 'media_type' => 'image', // Jenis media
//                 'url' => url('assets/img/check.png'), // URL ke gambar
//             ]);

//             if ($sent) {
//                 Log::info("WhatsApp notification sent for order #" . $order->invoice);
//             } else {
//                 Log::error("Failed to send WhatsApp notification for order #" . $order->invoice);
//             }
//         } else {
//             Log::warning("No WhatsApp number found for order #" . $order->invoice);
//         }
//     } catch (\Throwable $th) {
//         Log::error('Failed to send WhatsApp notification: ' . $th->getMessage());
//     }
// }


    // Menjadwalkan pekerjaan pengiriman notifikasi jika status berhasil diubah
    if (in_array($order->status, ['sukses', 'gagal', 'SALAH ID']) && $lastStatus != $order->status) {
        OrderNotifyJob::dispatch($order)->delay(now()->addSeconds(2));
    }

    return response()->json([
        'status'  => true,
        'type' => 'alert',
        'msg' => 'Berhasil merubah status pesanan <b>#'.$order->invoice.'</b>.'
    ]);
}


}
