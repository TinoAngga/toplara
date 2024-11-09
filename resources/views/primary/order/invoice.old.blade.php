@section('title')
{{ getConfig('title') }} - {{ $page['title'] }}
@endsection
@extends('primary.layouts.app')
@section('style')

@endsection
@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <!-- Start col -->
        <div class="col-md-12 col-lg-10 col-xl-10">
            <div class="card m-b-30">
                <div class="card-body">
                    <div class="invoice">
                        <div class="invoice-head">
                            <div class="row">
                                <!--div class="col-12 col-md-7 col-lg-7">
                                    <!--<div class="invoice-logo">
                                        {{ getConfig('title') }}
                                    </div>
                                    <h4>{{ getConfig('title') }}.</h4>
                                    <p>{{ getConfig('short_description') ?? 'Best Website TopUp Game' }}</p>

                                </div>-->
                                <div class="col-12 col-md-5 col-lg-5">
                                    <div class="invoice-name">
                                        <h5 class="text-uppercase mb-3">Invoice Pembelian</h5>
                                        <p class="mb-1">No : #{{ $order->invoice }}</p>
                                        <p class="mb-0">{{ format_datetime($order->created_at) }}</p>
                                        <h4 class="text-success mb-0 mt-3">{{ 'Rp ' . currency($order->price) }}</h4>
                                        <p class="mb-1 mt-3">{!! badgeStatus($order->status, $order->is_paid) !!}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="invoice-billing">
                            <div class="row mt-3">
                                <div class="col-sm-6 col-md-8 col-lg-8">
                                    <div class="invoice-address">
                                        <h6 class="mb-3">Detail Pesanan</h6>
                                        <ul>
                                            <li><b>Data</b> : {{ $order->data }}</li>
                                            <li><b>Additional Data</b> : {{ $order->additional_data }}</li>
                                            <li><b>Nickname</b> : {{ $order->additional_info ?? '-' }}</li>
                                            <li><b>Note</b> : {{ $order->provider_order_description }}</li>
                                        </ul>
                                    </div>
                                </div>

                        <!--<div class="invoice-summary">
                            <div class="table-responsive ">
                                <table class="table table-borderless">
                                    <thead>
                                        <tr>
                                            <th scope="col" class="text-white">ID</th>
                                            <th scope="col" class="text-white">Kategori</th>
                                            <th scope="col" class="text-white">Produk</th>
                                            <th scope="col" class="text-white">Harga</th>
                                            <th scope="col" class="text-right text-white">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th scope="row" class="text-white">1</th>
                                            <td class="text-white">{{ $order->service->category->name }}</td>
                                            <td class="text-white">{{ $order->service->name }}</td>
                                            <td class="text-white">{{ 'Rp ' . currency($order->service->price->{$order->user->level ?? 'public'}) }}</td>
                                            <td class="text-right text-white">{{ 'Rp ' . currency($order->price) }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>-->
                        <div class="invoice-summary-total">
                            <div class="row">

                                <div class="col-md-12 order-1 order-lg-2 col-lg-12 col-xl-12">
                                    <div class="order-total table-responsive ">
                                        <table class="table table-borderless text-left">
                                            <tbody>
                                                <tr class="text-white">
                                                    <td>Kategori :</td>
                                                    <td>{{ $order->service->category->name }}</td>
                                                </tr>
                                                <tr class="text-white">
                                                    <td>Produk :</td>
                                                    <td>{{ $order->service->name }}</td>
                                                </tr>
                                                <tr class="text-white">
                                                    <td>Harga :</td>
                                                    <td>{{ 'Rp ' . currency($order->price - $order->fee - $order->unique_code) }}</td>
                                                </tr>
                                                <tr class="text-white">
                                                    <td>Fee :</td>
                                                    <td>{{ 'Rp ' . currency($order->fee) }}</td>
                                                </tr>
                                                <tr class="text-white">
                                                    <td>Biaya Admin :</td>
                                                    <td>{{ 'Rp ' . currency($order->unique_code) }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="text-white f-w-7 font-18">Total yang harus dibayar :</td>
                                                    <td class="text-white f-w-7 font-18">{{ 'Rp ' . currency($order->price) }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Start col -->
                    <div class="col-md-12 col-lg-10 col-xl-10">
                        <div class="card m-b-30">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-12 col-md-6">
                                        <div class="invoice-address">
                                            <div class="card">
                                                <div class="card-body text-center">
                                                    <h5>Pembayaran</h5>
                                                    <h3 class="text-white"><i class="mdi mdi-wallet font-weight-bold text-white"></i></h3>
                                                    <p class="font-weight-bold">{{ strtoupper($order->payment->name) }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-6">
                                        <div class="order-note mb-2">
                                            <p class="mb-3 mt-3">{!! isPaidText($order->is_paid) !!}</p>
                                            <h6 class="mb-1">Catatan :</h6>
                                            @if ($order->payment->payment_gateway == 'tripay')
                                                @php
                                                    $paymentCheckout = json_decode($order->payment_gateway_request_response)
                                                @endphp
                                                @if ($order->is_paid == 0)
                                                    <b>Klik tombol dibawah untuk melakukan pembayaran</b>
                                                    <br />
                                                    <a href="{{ $paymentCheckout->data->checkout_url }}" target="_blank" class="btn btn-md btn-success font-weight-bold text-uppercase"> Bayar</a>
                                                @else
                                                    -
                                                @endif
                                            @elseif ($order->payment->payment_gateway == 'xendit' AND $order->payment->payment_gateway_code === 'qris')
                                                    @php
                                                        $paymentCheckout = json_decode($order->payment_gateway_request_response)
                                                    @endphp
                                                    @if ($order->is_paid == 0 AND $paymentCheckout)
                                                        {!! convertString($order->payment->information, $order->price) !!}
                                                        <br />
                                                        <p style="margin-bottom: -10px;">SCAN QR CODE DI BAWAH INI!</p>
                                                        <br />
                                                        {{ \QrCode::size(250)->generate($paymentCheckout->qr_string ?? '') }}
                                                    @else

                                                    @endif
                                            @elseif ($order->payment->payment_gateway == 'paydisini')
                                                    @php
                                                        $paymentCheckout = json_decode($order->payment_gateway_request_response)
                                                    @endphp
                                                    @if ($order->is_paid == 0 AND $paymentCheckout)
                                                        @if (preg_match('/qris/i', $order->payment->name))
                                                            {!! convertString($order->payment->information, $order->price) !!}
                                                            <br />
                                                            <p style="margin-bottom: -10px;">SCAN QR CODE DI BAWAH INI!</p>
                                                            <br />
                                                            <img src="<?= qrImage($paymentCheckout->data->qr_content) ?>" style="height: 300px; width: 300px">
                                                        @elseif(preg_match('/virtual account/i', $order->payment->name))
                                                            {!! convertString($order->payment->information, $order->price) !!}
                                                            <h4 class="mt-3">Virtual Account:</h4>
                                                            <b style="font-size: 1rem" data-bs-toggle="tooltip" data-bs-placement="top" title="Copy">
                                                                <?= $paymentCheckout->data->virtual_account ?>
                                                                <i class="fa fa-copy ms-1 text-success" onclick="salin('<?= $paymentCheckout->data->virtual_account ?>');"></i>
                                                            </b>
                                                        @endif
                                                    @else

                                                    @endif
                                            @else
                                                @if ($order->payment->is_qrcode)
                                                    {!! convertString($order->payment->information, $order->price) !!}
                                                    <br />
                                                    SCAN QRCODE DI BAWAH INI !!!
                                                    <br />
                                                    <img src="{{ asset(config('constants.options.asset_img_qr_code') . $order->payment->qrcode) }}" class="img-fluid" height="300px" width="300px" alt="">
                                                @else
                                                    {!! convertString($order->payment->information, $order->price) !!}
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                </div>
                             </div>
                        </div>
                    </div>

        <!-- Start col -->
        <div class="col-md-12 col-lg-10 col-xl-10">
            <div class="card m-b-30">
                <div class="card-body">
                        <div class="invoice-meta">
                            <div class="row">
                                <div class="col-sm-12 col-md-12 col-lg-12">
                                    <div class="invoice-meta-box">
                                        <h6 class="mt-3">Kontak Kami</h6>
                                        <ul class="list-unstyled">
                                            <!--<li><i class="feather icon-aperture mr-2"></i>{{ str_replace('https:\\', '', url('')) }}</li>-->
                                            <!--<li><i class="feather icon-mail mr-2"></i>{{ getConfig('contact_email_address') ?? '-' }}</li>-->
                                            <!--<li><i class="feather icon-phone mr-2"></i>{{ getConfig('social_media_whatsapp') ?? '-' }}</li>-->
                                            <a href="https://instagram.com/{{ getConfig('social_media_instagram') }}" target="_blank"
                                                style="font-size: 15px; text-decoration: none;">
                                                <i class="mdi mdi-instagram mr-4"></i><font size="2"> {{ getConfig('social_media_instagram') }} </font>
                                            </a><br>
                                            <a href="https://wa.me/{{ getConfig('social_media_whatsapp') }}" target="_blank"
                                                style="font-size: 15px; text-decoration: none;">
                                                <i class="mdi mdi-whatsapp mr-4"></i><font size="2"> {{ getConfig('social_media_whatsapp') }} </font>
                                            </a><br>
                                            <a href="https://facebook.com/{{ getConfig('social_media_facebook_url') }}" target="_blank"
                                                style="font-size: 15px; text-decoration: none;">
                                                <i class="mdi mdi-facebook mr-4"></i><font size="2"> {{ getConfig('social_media_facebook_name') }} </font>
                                            </a><br>
                                            <a href="mailto:{{ getConfig('contact_email_address') }}" target="_blank"
                                                style="font-size: 15px; text-decoration: none;">
                                                <i class="mdi mdi-email mr-4"></i><font size="2"> {{ getConfig('contact_email_address') }} </font>
                                            </a>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="invoice-footer">
                            <div class="row" style="text-align: center;">
                                <div class="col-md-12">
                                    <p class="mb-0">Terimakasih telah melakukan pembelian di {{ getConfig('title') }}.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End row -->
@endsection
@section('script')
<script>
function salin(text, label_text) {
	navigator.clipboard.writeText(text);
    Swal.fire('Berhasil!.', text, 'success');
}
</script>
@endsection
