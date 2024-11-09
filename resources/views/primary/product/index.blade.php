@section('title')
{{ getConfig('title') }} - {{ $page['title'] }}
@endsection
@extends('primary.layouts.app')
@section('style')
<style>
    .swal2-title {
        position: relative !important;
        max-width: 100% !important;
        margin: 0 !important;
        padding: 0.8em 1em 0 !important;
        color: inherit !important;
        font-size: 1.875em !important;
        font-weight: 600 !important;
        text-align: center !important;
        text-transform: none !important;
        word-wrap: break-word !important;
    }
</style>
@endsection
@section('content')
@livewire('primary.product.product-single', [
    'serviceType' => $type,
    'category' => $category,
])
@endsection
@section('script')
<script type="text/javascript">
    function openPaymentDrawer(elem) {
        var $this = $(elem);
        if ($('input[type="radio"][name="service"]:checked').val() == null){
            $('html,body').animate({scrollTop: $('#order-form').offset().top - 200}, 400);
            Swal.fire('Ups!','Harap pilih layanan terlebih dahulu.','error');
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
    function selectService() {
        var service = $("input[name='service']:checked").val();
        @if ($category->slug == 'joki-mobile-legends-ranked')
            var data = {
                service: service,
                star: $("input[name='star']").val(),
            }
        @else
            var data = {
                service: service
            }
        @endif
        if (service) {
            $('html,body').animate({scrollTop: $('#payment-card').offset().top - 200}, 400);
            $.ajax({
                url: "{{ route('product.get-price') }}",
                type: "POST",
                data: data,
                dataType: "json",
                success: function(data) {
                    if (data.status == true) {
                        $.each(data.data, function(i, item) {
                            $('#payment_' + item.id).attr('disabled', false);
                            $('#payment_' + item.id).removeClass('disabled');
                            $('.payment-description-' + item.id).html('');
                            if (item.offline == true) {
                                $('#payment_' + item.id).attr('disabled', 'disable');
                                $('#payment_' + item.id).addClass('disabled');
                                $('.payment-description-' + item.id).html(item.description);
                            }
                            $('#payment-method-' + item.id).html(item.price.string);
                            $('#total-price-' + item.id).html(item.price.integer);
                            $('.payment-type-' + item.type).html(item.price.string);
                            $('.payment-description-' + item.id).html(item.description);
                        });
                    } else {
                        Swal.fire('Ups!','Terjadi kesalahan, silakan refresh halaman ini.','error');
                    }
                },
                error: function(jqXHR, exception) {
                    Swal.fire('Ups!','Terjadi kesalahan, silakan refresh halaman ini.','error');
                }
            });
        }
    }
    document.addEventListener('livewire:init', function () {
        Livewire.hook('morph.updated', ({ el, component }) => {
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
        })

    });
    $(document).ready(function() {
        $('#order-button').click(function() {
            $("#order-form").text(function() {
                var formInput = $(this).serialize();
                $.ajax({
                    url: "{{ route('product.get-detail', $category->id) }}",
                    data: formInput,
                    timeout: false,
                    type: "POST",
                    dataType: "json",
                    success: function(data) {
                        $("#order-button").html('<i class="mdi mdi-cart"></i> Beli Sekarang');
                        $("input").removeAttr("disabled", "disabled");
                        $("#order-button").removeAttr("disabled", "disabled");
                        if (data.status == false) {
                            if (data.type == 'validation') {
                                Swal.fire('Gagal!!', 'Harap mengisi semua input', 'error');
                                $.each(data.msg, function (key, val) {
                                    $("input[name=" + key + "]").addClass('is-invalid').focus();
                                    $('small.' + key + '-invalid').text(val[0]);
                                });
                            }
                            if (data.type == 'alert') {
                                Swal.fire('Gagal!!', data.msg, 'error');
                            }
                        } else {
                            Swal.close();
                            $('#modal-order').modal('show');
                            $('#modal-order-title').html(data.title);
                            $('#modal-order-body').html(data.body);
                        }
                        $("input.disabled").attr("disabled", "disabled");
                    },
                    error: function(e) {
                        $("input").removeAttr("disabled", "disabled");
                        $("#order-button").removeAttr("disabled", "disabled");
                        $("#order-button").html('<i class="mdi mdi-cart"></i> Beli Sekarang');
                        Swal.fire('Gagal!', 'Terjadi kesalahan!!', 'error');
                        $("input.disabled").attr("disabled", "disabled");
                    },
                    beforeSend: function() {
                        swal.fire({
                            title: 'Mohon tunggu...',
                            allowOutsideClick: false,
                                didOpen: function () {
                                    swal.showLoading()
                            }
                        });
                        $("input").attr("disabled", "disabled");
                        $("#order-button").attr("disabled", "disabled");
                    }
                });
            });
        });
        $('#modal-order-button').click(function(e) {
            e.preventDefault();
            var formInput = $('#order-form').serialize();
            $.ajax({
                type: "POST",
                url: '{{ route('order.checkout') }}',
                data: formInput,
                dataType: "json",
                beforeSend: function (data) {
                    $("input").attr("disabled", "disabled");
                    $("button").attr("disabled", "disabled");
                    swal.fire({
                        title: 'Mohon tunggu...',
                        allowOutsideClick: false,
                            didOpen: function () {
                                swal.showLoading()
                        }
                    })
                }, success: function (data) {
                    if (data.status == false) {
                        Swal.fire('Gagal!!', data.msg, 'error')
                        $("input").removeAttr("disabled", "disabled");
                        $("button").removeAttr("disabled", "disabled");
                    } else {
                        $('#modal-order').modal('hide');
                        swal.fire('Berhasil!', data.msg, 'success').then(function () {
                            location.href = data.redirect_url
                        });
                    }
                    $("input.disabled").attr("disabled", "disabled");
                }, error: function (e) {
                    swal.close
                    Swal.fire('Gagal!!', 'Terjadi kesalahan !! Harap hubungi admin !!.', 'error')
                    $("input").removeAttr("disabled", "disabled");
                    $("button").removeAttr("disabled", "disabled");
                    $("input.disabled").attr("disabled", "disabled");
                }
            });
        });
        @if ($category->slug == 'joki-mobile-legends-ranked')
            $('input[name=star]').on('keyup', function() {
                var service = $("input[name='service']:checked").val();

                @if ($category->slug == 'joki-mobile-legends-ranked')
                    var data = {
                        service: service,
                        star: $("input[name='star']").val(),
                    }
                @else
                    var data = {
                        service: service
                    }
                @endif

                if (service) {
                    $.ajax({
                        url: "{{ route('product.get-price') }}",
                        type: "POST",
                        data: data,
                        dataType: "json",
                        success: function(data) {
                            if (data.status == true) {
                                $.each(data.data, function(i, item) {
                                    if (item.offline == true) {
                                        $('#payment_' + item.id).attr('disabled', 'disable');
                                        $('.payment-description-' + item.id).html(item.description);
                                    }
                                    $('#payment-method-' + item.id).html(item.price.string);
                                    $('#total-price-' + item.id).html(item.price.integer);
                                    $('.payment-type-' + item.type).html(item.price.string);

                                });
                            } else {
                                Swal.fire('Ups!','Terjadi kesalahan, silakan refresh halaman ini.','error');
                            }
                        },
                        error: function(jqXHR, exception) {
                            Swal.fire('Ups!','Terjadi kesalahan, silakan refresh halaman ini.','error');
                        }
                    });
                }
            });
        @endif
    });
</script>
@endsection
