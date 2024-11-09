@extends('primary.layouts.app')
@section('title')
{{ getConfig('title') }} - {{ $page['title'] }}
@endsection
@section('style')

@endsection
@section('content')
<div class="container my-5">
    @include('primary.layouts.app.menu.nav')
    <div class="row justify-content-center mt-4">
        <div class="col-md-12">
            <form method="POST" id="upgrade-form" action="{{ route('account.upgrade.post') }}">
                @csrf
                @method('POST')
                <div class="card">
                    <div class="card-body">
                        <div class="row justify-content-center">
                            <div class="col-md-5 mb-2">
                                <div class="num-page">
                                    <div>1</div>
                                    <h5>
                                        Pilih Level
                                    </h5>
                                </div>
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row mt-3">
                                            @foreach ($levels as $key => $value)
                                            <div class="col-md-12 col-12">
                                                <input type="radio" id="level_{{ $value['id'] }}" class="radio-service"
                                                    name="level" value="{{ $value['id'] }}">
                                                <label for="level_{{ $value['id'] }}">{{ strtoupper($value['name']) }} - Rp {{ currency($value['price']) }}</label>
                                            </div>
                                            @endforeach
                                            <br />
                                            <small class="text-danger level-invalid"></small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-7 mb-2">
                                <div class="num-page">
                                    <div>2</div>
                                    <h5>
                                        Pilih Pembayaran
                                    </h5>
                                </div>
                                <div class="card shadow">
                                    <div class="card-body">
                                        <div class="mt-3">
                                            <!-- <span class="strip-primary" style="margin-left: 54px"></span> -->
                                            <style>
                                                input[type="radio"]:disabled+label {
                                                    background: var(--theme-color-3);
                                                }

                                            </style>
                                            @include('primary.account.upgrade.payment-template')
                                            <small class="text-danger payment-invalid"></small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 mb-3">
                                <button type="submit" class="btn btn-md btn-primary btn-block shadow font-weight-bold"><i class="mdi mdi-arrow-up mr-2"></i>
                                    Upgrade Level
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@section('script')
<script>
    function reset_button(value = 0) {
        if (value == 0) {
            $('button[type="submit"]').attr('disabled', 'true');
            $('button[type="submit"]').text('');
            $('button[type="submit"]').append(
                '<span class=\"spinner-grow spinner-grow-sm\" role=\"status\" aria-hidden=\"true\"></span>Mohon Tunggu...'
            );
        } else {
            $('button[type="submit"]').removeAttr('disabled');
            $('button[type="submit"]').removeAttr('span');
            $('button[type="submit"]').html('<i class="ion ion-md-arrow-up mr-2"></i> Upgrade Level');
        }
    }
    function openPaymentDrawer(elem) {
            var $this = $(elem);
            if ($('input[type="radio"][name="level"]:checked').val() == null){
                $('html,body').animate({scrollTop: $('#upgrade-form').offset().top - 200}, 400);
                Swal.fire('Ups!','Harap pilih level terlebih dahulu.','error');
                return;
            }
            $('.payment-drawwer').not(this).each(function() {
                var $parents = $(this);
                $parents.find('.button-action-payment').slideUp(function() {
                    $parents.removeClass('active');
                });
                $parents.find('.short-payment-support-info').find('.img-pg-group').slideDown();
                $parents.find('.short-payment-support-info').find('i').removeClass('fa-chevron-up').addClass('fa-chevron-down');
            });
            var $parents = $this.parents('.child-box');
            if (!$parents.find('.button-action-payment').is(":hidden")) {
                $parents.find('.button-action-payment').slideUp(function() {
                    $parents.removeClass('active');
                });
                $parents.find('.short-payment-support-info').find('.img-pg-group').slideDown();
                $parents.find('.short-payment-support-info').find('.fa-chevron-up').removeClass('fa-chevron-up').addClass('fa-chevron-down');
            } else {
                $parents.find('.button-action-payment').slideDown(function() {
                    $parents.addClass('active');
                });
                $parents.find('.short-payment-support-info').find('.img-pg-group').slideUp();
                $parents.find('.short-payment-support-info').find('.fa-chevron-down').addClass('fa-chevron-up').removeClass('fa-chevron-down');
            }
        }
    $(document).ready(function() {
        $('input[name="payment"]').change(function() {
            let paymentType = $(this).data("payment-type");
            let paymentName = $(this).data("payment-name");
            let inputPhoneEWalletHTML = `<label for="phone_ewallet"></label>
                <input type="text" class="form-control" name="phone_ewallet" id="phone_ewallet" placeholder="Nomor E-Wallet" required>
                <small class="text-white phone_ewallet-invalid" style="font-size: 10px"></small>`;
            var paymentValue = $("input[name='payment']:checked").val();
            $(".form-phone-ewallet").empty();
            if (paymentValue && paymentType === 'e_wallet') {
                $(".form-phone-ewallet-" + paymentValue).append(inputPhoneEWalletHTML);
            } else {
                $(".form-phone-ewallet-" + paymentValue).append('');
            }
            if ($(this).val() == 1) {
                $('.saldo').removeClass('d-none');
            } else {
                $('.saldo').addClass('d-none');
            }
        });
        $("#upgrade-form").on('submit', function (e) {
            e.preventDefault();
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
                        title: 'Mohon Tunggu...',
                        didOpen: function () {
                            swal.showLoading()
                        }
                    })
                },
                success: function (data) {
                    reset_button(1);
                    if (data.status == false) {
                        if (data.type == 'validation') {
                            swal.close();
                            $.each(data.msg, function (key, val) {
                                $("input[name=" + key + "]").addClass('is-invalid');
                                $('small.' + key + '-invalid').text(val[0]);
                            });
                        }
                        if (data.type == 'alert') {
                            swal.fire("Gagal!", data.msg, "error");
                        }
                    } else {
                        $('#upgrade-form')[0].reset();
                        swal.fire("Berhasil!", data.msg, "success").then(function () {
                            location.href = data.redirect_url
                        });
                    }
                },
                error: function () {
                    reset_button(1);
                    swal.fire("Gagal!", "Terjadi kesalahan.", "error");
                },
            });
        });
        $('input[type=radio][name=level]').change(function() {
            var level = $("input[type=radio][name=level]:checked").val();
            $.ajax({
                url: "{{ route('account.upgrade.get-price') }}",
                type: "POST",
                data: {
                    level: level
                },
                dataType: "json",
                success: function(data) {
                    if (data.status == true) {
                        $.each(data.data, function(i, item) {
                            $('#payment-method-' + item.id).html(item.price.string);
                            $('#total-price-' + item.id).val(item.price.integer);
                            $('.payment-type-' + item.type).text(item.price.string);
                        });
                    } else {
                        Swal.fire('Ups!', data.msg ,'error');
                    }
                }, error: function(jqXHR, exception) {
                    Swal.fire('Ups!','Terjadi kesalahan, silakan refresh halaman ini.','error');
                }
            });
        });
    });
    </script>
@endsection
