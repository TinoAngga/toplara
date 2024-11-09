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
                                <div class="col-md-12">
                                    <div class="invoice-address">
                                        <h5 class="text-uppercase my-2">Invoice Upgrade level</h5>
                                        <div class="card my-3" style="border: none;">
                                            <div class="card-body text-center">
                                                <h5>Segera Lakukan Pembayaran Sebelum</h5>
                                                <h6 class="mb-0 fw-700 text-danger">
                                                    {{ format_datetime(parseCarbon($invoice->created_at)->addDays(1)) }} WIB</h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="invoice-summary-total">
                                    <div class="row">

                                        <div class="col-md-12 order-1 order-lg-2 col-lg-12 col-xl-12">
                                            <div class="order-total table-responsive ">
                                                <table class="table table-borderless text-left">
                                                    <tbody>
                                                        <tr class="text-white">
                                                            <td>Invoice</td>
                                                            <td>: #{{ $invoice->invoice }}</td>
                                                        </tr>
                                                        <tr class="text-white">
                                                            <td>Tanggal</td>
                                                            <td>: {{ format_datetime($invoice->created_at) }} WIB</td>
                                                        </tr>
                                                        <tr class="text-white">
                                                            <td>Harga</td>
                                                            <td>:
                                                                {{ 'Rp ' . currency($invoice->price - $invoice->fee - $invoice->unique_code) }}
                                                            </td>
                                                        </tr>
                                                        <tr class="text-white">
                                                            <td>Tax</td>
                                                            <td>: {{ 'Rp ' . currency($invoice->fee) }}</td>
                                                        </tr>
                                                        <tr class="text-white">
                                                            <td>Biaya Admin</td>
                                                            <td>: {{ 'Rp ' . currency($invoice->unique_code) }}</td>
                                                        </tr>
                                                        <tr class="text-white">
                                                            <td>Status Pesanan</td>
                                                            <td>: {!! badgeStatus($invoice->status, $invoice->is_paid) !!}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-white f-w-7 font-18">Total</td>
                                                            <td class="text-white f-w-7 font-18">:
                                                                {{ 'Rp ' . currency($invoice->price) }}</td>
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
                        <div class="invoice-address">
                            <div class="col-sm-12">
                                <div class="order-note mb-2">
                                    <p class="mb-3 mt-3 text-end">{!! isPaidText($invoice->is_paid) !!}</p>
                                    <div class="order-total table-responsive ">
                                        <table class="table table-borderless text-left">
                                            <tbody>
                                                <tr class="text-white">
                                                    <td>Metode Pembayaran</td>
                                                    <td>{{ strtoupper($invoice->payment->name) }}</td>
                                                </tr>
                                                <tr class="text-white">
                                                    <td>Catatan Pembayaran</td>
                                                    <td>
                                                    @if ($invoice->payment->payment_gateway == 'tripay')
                                                        @php
                                                            $paymentCheckout = json_decode($invoice->payment_gateway_request_response)
                                                        @endphp
                                                        @if ($invoice->is_paid == 0)
                                                            <b>Klik tombol dibawah untuk melakukan pembayaran</b>
                                                            <br />
                                                            <a href="{{ $paymentCheckout->data->checkout_url }}" target="_blank" class="btn btn-md btn-success font-weight-bold text-uppercase"> Bayar</a>
                                                        @else
                                                            -
                                                        @endif
                                                    @elseif ($invoice->payment->payment_gateway == 'xendit' AND
                                                        $invoice->payment->payment_gateway_code === 'qris')
                                                        @php
                                                        $paymentCheckout = json_decode($invoice->payment_gateway_request_response)
                                                        @endphp
                                                        @if ($invoice->is_paid == 0 AND $paymentCheckout)
                                                            {!! convertString($invoice->payment->information, $invoice->price) !!}
                                                            <br />
                                                            <p style="margin-bottom: -10px;">SCAN QR CODE DI BAWAH INI!</p>
                                                            <br />
                                                            {{ \QrCode::size(250)->generate($paymentCheckout->qr_string ?? '') }}
                                                        @else

                                                        @endif
                                                    @elseif ($invoice->payment->payment_gateway == 'paydisini')
                                                        @php
                                                            $paymentCheckout = json_decode($invoice->payment_gateway_request_response)
                                                        @endphp
                                                        @if ($invoice->is_paid == 0 AND $paymentCheckout)
                                                            @if (preg_match('/qris/i', $invoice->payment->name))
                                                                {!! convertString($invoice->payment->information, $invoice->price) !!}
                                                                <br />
                                                                <p style="margin-bottom: -10px;">SCAN QR CODE DI BAWAH INI!</p>
                                                                <br />
                                                                <img src="<?= $paymentCheckout->data->qrcode_url ?>" style="height: 250px; width: 250px">
                                                            @elseif(preg_match('/virtual account/i', $invoice->payment->name))
                                                                {!! convertString($invoice->payment->information, $invoice->price) !!}
                                                                <h4 class="mt-3">Virtual Account:</h4>
                                                                <b style="font-size: 1rem" data-bs-toggle="tooltip" data-bs-placement="top" title="Copy">
                                                                    <?= $paymentCheckout->data->virtual_account ?>
                                                                    <i class="fa fa-copy ms-1 text-success" onclick="salin('<?= $paymentCheckout->data->virtual_account ?>');"></i>
                                                                </b>
                                                            @elseif($invoice->payment->type == 'convience_store')
                                                                {!! convertString($invoice->payment->information, $invoice->price) !!}
                                                                <h4 class="mt-3">Kode Pembayaran:</h4>
                                                                <b style="font-size: 1rem" data-bs-toggle="tooltip" data-bs-placement="top" title="Copy">
                                                                    <?= $paymentCheckout->data->payment_code ?>
                                                                    <i class="fa fa-copy ms-1 text-success" onclick="salin('<?= $paymentCheckout->data->payment_code ?>');"></i>
                                                                </b>
                                                            @elseif ($invoice->payment->type == 'e_wallet')
                                                                @if ($invoice->is_paid == 0)
                                                                    <b>Klik tombol dibawah untuk melakukan pembayaran</b>
                                                                    <br />
                                                                    <a href="{{ $paymentCheckout->data->checkout_url }}" target="_blank" class="btn btn-md btn-success font-weight-bold text-uppercase"> Bayar</a>
                                                                @else
                                                                    -
                                                                @endif
                                                            @endif
                                                        @else

                                                        @endif
                                                    @else
                                                        @if ($invoice->payment->is_qrcode)
                                                        {!! convertString($invoice->payment->information, $invoice->price) !!}
                                                        <br />
                                                        SCAN QRCODE DI BAWAH INI !!!
                                                        <br />
                                                        <img src="{{ asset(config('constants.options.asset_img_qr_code') . $invoice->payment->qrcode) }}"
                                                            class="img-fluid" height="300px" width="300px" alt="">
                                                        @else
                                                        {!! convertString($invoice->payment->information, $invoice->price) !!}
                                                        @endif
                                                    @endif
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>


                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="text-white fs-12 p-1 mb-3 text-center">Terimakasih telah melakukan pembelian di
                        <b>{{ getConfig('title') }}</b>, untuk pertanyaan, kritik atau saran bisa di sampaikan langsung
                        melalui
                        halaman Kontak Kami.
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
