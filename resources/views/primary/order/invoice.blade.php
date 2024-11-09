@section('title')
{{ getConfig('title') }} - {{ $page['title'] }}
@endsection
@extends('primary.layouts.app')
@section('style')
<link href="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-star-rating@4.1.2/css/star-rating.min.css" media="all" rel="stylesheet" type="text/css" />

<!-- with v4.1.0 Krajee SVG theme is used as default (and must be loaded as below) - include any of the other theme CSS files as mentioned below (and change the theme property of the plugin) -->
<link href="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-star-rating@4.1.2/themes/krajee-svg/theme.css" media="all" rel="stylesheet" type="text/css" />
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
                                        <h5 class="text-uppercase my-2">Invoice Pembelian</h5>
                                        <div class="card my-3" style="border: none;">
                                            <div class="card-body text-center">
                                                <h4>Segera Lakukan Pembayaran Sebelum</h4>
                                                <h5 class="mb-0 fw-700 text-danger">
                                                    {{ format_datetime(parseCarbon($order->created_at)->addDays(1)) }} WIB</h5>
                                                <h7>Setelah Melakukan Pembayaran Harap Periksa Email Anda</h7>
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
                                                            <td>: #{{ $order->invoice }}</td>
                                                        </tr>
                                                        <tr class="text-white">
                                                            <td>Tanggal</td>
                                                            <td>: {{ format_datetime($order->created_at) }} WIB</td>
                                                        </tr>
                                                        <tr class="text-white">
                                                            <td>Kategori</td>
                                                            <td>: {{ $order->service->category->name }}</td>
                                                        </tr>
                                                        <tr class="text-white">
                                                            <td>Produk</td>
                                                            <td>: {{ $order->service->name }}</td>
                                                        </tr>
                                                        <tr class="text-white">
                                                            <td>Tujuan</td>
                                                            <td>: {{ $order->data . ' (' . $order->additional_data }})
                                                            </td>
                                                        </tr>
                                                        <tr class="text-white">
                                                            <td>Catatan</td>
                                                            <td>: {{ $order->provider_order_description ?? '-' }}</td>
                                                        </tr>
                                                        <tr class="text-white">
                                                            <td>Harga</td>
                                                            <td>:
                                                                {{ 'Rp ' . currency($order->price - $order->fee - $order->unique_code) }}
                                                            </td>
                                                        </tr>
                                                        <tr class="text-white">
                                                            <td>Tax</td>
                                                            <td>: {{ 'Rp ' . currency($order->fee) }}</td>
                                                        </tr>
                                                        <tr class="text-white">
                                                            <td>Biaya Admin</td>
                                                            <td>: {{ 'Rp ' . currency($order->unique_code) }}</td>
                                                        </tr>
                                                        <tr class="text-white">
                                                            <td>Status Pesanan</td>
                                                            <td>: {!! badgeStatus($order->status, $order->is_paid) !!}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-white f-w-7 font-18">Total</td>
                                                            <td class="text-white f-w-7 font-18">:
                                                                {{ 'Rp ' . currency($order->price) }}</td>
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
                            <!--<div class="card">
                                                <div class="card-body text-center">
                                                    <h5>Pembayaran</h5>
                                                    <h3 class="text-white"><i class="mdi mdi-wallet font-weight-bold text-white"></i></h3>
                                                    <p class="font-weight-bold">{{ strtoupper($order->payment->name) }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>-->
                            <div class="col-sm-12">
                                <div class="order-note mb-2">
                                    <p class="mb-3 mt-3 text-end">{!! isPaidText($order->is_paid) !!}</p>
                                    <div class="order-total table-responsive ">
                                        <table class="table table-borderless text-left">
                                            <tbody>
                                                <tr class="text-white">
                                                    <td>Metode Pembayaran</td>
                                                    <td>{{ strtoupper($order->payment->name) }}</td>
                                                </tr>
                                                <tr class="text-white">
                                                    <td>Catatan Pembayaran</td>
                                                    <td>
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
                                                    @elseif ($order->payment->payment_gateway == 'xendit' AND
                                                        $order->payment->payment_gateway_code === 'qris')
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
                                                                <img src="<?= $paymentCheckout->data->qrcode_url ?>" style="height: 250px; width: 250px">
                                                            @elseif(preg_match('/virtual account/i', $order->payment->name))
                                                                {!! convertString($order->payment->information, $order->price) !!}
                                                                <h4 class="mt-3">Virtual Account:</h4>
                                                                <b style="font-size: 1rem" data-bs-toggle="tooltip" data-bs-placement="top" title="Copy">
                                                                    <?= $paymentCheckout->data->virtual_account ?>
                                                                    <i class="fa fa-copy ms-1 text-success" onclick="salin('<?= $paymentCheckout->data->virtual_account ?>');"></i>
                                                                </b>
                                                            @elseif($order->payment->type == 'convience_store')
                                                                {!! convertString($order->payment->information, $order->price) !!}
                                                                <h4 class="mt-3">Kode Pembayaran:</h4>
                                                                <b style="font-size: 1rem" data-bs-toggle="tooltip" data-bs-placement="top" title="Copy">
                                                                    <?= $paymentCheckout->data->payment_code ?>
                                                                    <i class="fa fa-copy ms-1 text-success" onclick="salin('<?= $paymentCheckout->data->payment_code ?>');"></i>
                                                                </b>
                                                            @elseif ($order->payment->type == 'e_wallet')
                                                                @if ($order->is_paid == 0)
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
                                                        @if ($order->payment->is_qrcode)
                                                        {!! convertString($order->payment->information, $order->price) !!}
                                                        <br />
                                                        SCAN QRCODE DI BAWAH INI !!!
                                                        <br />
                                                        <img src="{{ asset(config('constants.options.asset_img_qr_code') . $order->payment->qrcode) }}"
                                                            class="img-fluid" height="300px" width="300px" alt="">
                                                        @else
                                                        {!! convertString($order->payment->information, $order->price) !!}
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

        @if ($order->status == 'sukses')
        <div class="col-md-12">
            <div class="num-page">
                <div>
                    <i class="mdi mdi-star"></i>
                </div>
                <h5>
                    Ulasan
                </h5>
            </div>
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('order.review.post', $order->invoice) }}" id="review-form" method="POST">
                        @csrf
                        @method('POST')
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="">Rating</label>
                                <input name="rating" type="number" class="rating" min="1" max="5" step="1" data-size="sm" data-rtl="false" data-show-caption="true" data-show-clear="false" value="{{ $order->review->rating ?? 0 }}" @if($order->review) disabled @endif>
                                <small class="text-danger rating-invalid"></small>
                            </div>
                            <div class="form-group col-md-12">
                                <label for="">Ulasan</label>
                                <textarea name="comment" class="form-control" rows="5" placeholder="Tulis ulasan anda disini..." @if($order->review) disabled @endif>{{ $order->review->comment ??  '' }}</textarea>
                                <small class="text-danger comment-invalid"></small>
                            </div>
                            @if ($order->review == null)
                            <div class="form-group col-md-12 my-2">
                                <button type="submit" class="btn btn-primary">Kirim Ulasan</button>
                            </div>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
</div>
</div>
<!-- End row -->
@endsection
@section('script')
<script src="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-star-rating@4.1.2/js/star-rating.min.js" type="text/javascript"></script>
<script>
    function salin(text, label_text) {
        navigator.clipboard.writeText(text);
        Swal.fire('Berhasil!.', text, 'success');
    }
    function reset_button(value = 0) {
        if (value == 0) {
            $('button[name="submit"]').attr('disabled', 'true');
            $('button[type="reset"]').attr('disabled', 'true');
            $('button[name="submit"]').text('');
            $('button[name="submit"]').append(
                '<span class=\"spinner-grow spinner-grow-sm\" role=\"status\" aria-hidden=\"true\"></span>Mohon Tunggu...'
            );
        } else {
            $('button[name="submit"]').removeAttr('disabled');
            $('button[type="reset"]').removeAttr('disabled');
            $('button[name="submit"]').removeAttr('span');
            $('button[name="submit"]').html('Kirim Ulasan');
        }
    }
    $(function () {
        $('button[name="submit"]').removeAttr('disabled');
        $("#review-form").on('submit', function (e) {
            e.preventDefault();
            console.log(new FormData(this));
            $.ajax({
                url: $(this).attr('action'),
                method: $(this).attr('method'),
                data: new FormData(this),
                processData: false,
                dataType: 'json',
                contentType: false,
                beforeSend: function () {
                    reset_button(0);
                    $(document).find('small.text-danger').text('');
                    $(document).find('input').removeClass('is-invalid');
                    swal.fire({
                        title: 'Mohon tunggu...',
                        allowOutsideClick: false,
                        didOpen: function () {
                            swal.showLoading()
                        }
                    });
                },
                success: function (data) {
                    reset_button(1);
                    if (data.status == false) {
                        if (data.type == 'validation') {
                            swal.fire("Gagal!", "Harap mengisi input!.", "error");
                            $.each(data.msg, function (key, val) {
                                $("input[name=" + key + "]").addClass('is-invalid');
                                $('small.' + key + '-invalid').text(val[0]);
                            });
                        }
                        if (data.type == 'alert') {
                            swal.fire("Gagal!", data.msg, "error");
                        }
                    } else {
                        $('#review-form')[0].reset();
                        swal.fire("Berhasil!", data.msg, "success").then(function(){
                            window.location = data.redirect_url;
                        });
                    }
                },
                error: function () {
                    reset_button(1);
                    swal.fire("Gagal!", "Terjadi kesalahan.", "error");
                },
            });
        });
    });
</script>
@endsection
