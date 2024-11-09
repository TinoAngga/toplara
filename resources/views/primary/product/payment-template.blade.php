<style>
    input[type="radio"]:disabled+label {
        background: grey !important;
    }
    .img-pg-group {
        float: right;
        background-color: white;
        border-radius: 3px;
        border: 1px solid white;
        height: 20px !important;
        width: auto !important;
        margin-top: -10px;
        margin-left: 3px;
    }
    .img-payment {
        float: left;
       /* background-color: white; */
        border-radius: 5px;
      /*  border: 1px solid white; */
        height: 20px !important;
        width: auto !important;
        margin-left: -7px;
    }
    .img-payment-2 {
        float: left;
        background-color: white;
        border-radius: 5px;
    }
</style>
@switch(getConfig('payment_template'))
@case('with-collapse')
<div class="mb-2">
    <div class="area-list-payment-method">
        @forelse (config('constants.options.payment_method_type_list') as $k => $v)
        <div class="child-box payment-drawwer shadow">
            <div class="header short-payment-support-info-head" onclick="openPaymentDrawer(this)">
                <div class="left">
                    <b><i class="{{ $v['icon'] }} me-1"></i>{{ ucwords($v['text']) }}</b>
                </div>
                <div class="float-right">
                    <b class="text-white payment-type-{{ $k }}" id="{{ $k }}"></b>
                </div>
            </div>
            <div class="button-action-payment" style="display: none;">
                <div class="row">
                    @forelse ($payments as $key => $value)
                        @if ($k == $value['type'])
                            <div class="col-md-6 col-12 p-1">
                            <input type="radio" id="payment_{{ $value['id'] }}" class="radio-payment" name="payment" value="{{ $value['id'] }}" data-payment-type="{{ $value['type'] }}" data-payment-name="{{ $value['name'] }}">
                            <label for="payment_{{ $value['id'] }}">
                                <div class="row mx-auto">
                                    <div class="col-6">
                                        <img src="{{ asset(config('constants.options.asset_img_payment_method') . $value['img']) }}"
                                            width="100px" class="img-payment">
                                    </div>
                                    <div class="col-6 p-1 text-right">
                                        <b id="payment-method-{{ $value['id'] }}"
                                            style="float: right; font-size: 10px;margin-left: -10px;" class="mb-3">~</b>
                                    </div>
                                    <div class="form-phone-ewallet-{{ $value['id'] }} form-phone-ewallet">

                                    </div>
                                    <hr>
                                    <div class="col-12">
                                        <p class="mb-2" style="font-size: 10px;float: left;" class="mb-2">{{ $value['name'] }}</p>
                                        <p class="mb-2" style="float: left"><em class="payment-description-{{ $value['id'] }}"></em></p>
                                    </div>

                                </div>
                            </label>
                        </div>
                        @endif
                    @empty
                    <div class="text-center">
                        <h5>Tidak ada pembayaran yang tersedia</h5>
                    </div>
                    @endforelse
                </div>
            </div>
            <div class="short-payment-support-info" onclick="openPaymentDrawer(this)">
                @forelse ($payments as $key => $value)
                    @if ($k == $value['type'])
                    <img src="{{ asset(config('constants.options.asset_img_payment_method') . $value['img']) }}" class="m-1 img-pg-group">
                    @endif
                @empty
                @endforelse

                <a class="open-button-action-payment text-white">
                    <i class="fas fa-chevron-down"></i>
                </a>
            </div>
        </div>
        @empty
        @endforelse
        @auth
        <div class="child-box payment-drawwer shadow">
            <div class="header short-payment-support-info-head" onclick="openPaymentDrawer(this)">
                <div class="left">
                    <b><i class="mdi mdi-wallet me-1"></i>Saldo</b>
                </div>
                <div class="float-right">
                    <b class="text-white payment-type-saldo" id="saldo"></b>
                </div>
            </div>
            <div class="button-action-payment" style="display: none;">
                <div class="row">
                    <div class="col-md-12 col-12 p-1">
                        <input type="radio" id="payment_1" class="radio-payment" name="payment" value="1">
                        <label for="payment_1">
                            <div class="row mx-auto">
                                <div class="col-6">
                                    <div style="display: inline-block;">
                                        <i class="mdi mdi-wallet bg-primary p-2 text-white mr-2 mb-2" style="font-size: 20px; border-radius:5px"></i>
                                    </div>
                                </div>
                                <div class="col-6 p-1 text-right">
                                    <b id="payment-method-1"
                                        style="float: right; font-size: 10px;margin-left: -10px;" class="mb-3">~</b>
                                </div>
                                <hr>
                                <div class="col-12">
                                    <p class="mb-2" style="font-size: 10px;float: left;" class="mb-2">Saldo</p>
                                </div>
                            </div>
                        </label>
                        <div class="saldo d-none">
                            <div class="alert alert-warning text-white" role="alert" style="padding-top: 10px; padding-bottom: 10px;">
                                <h6 style="margin-left: 10px">Sisa saldo {{ 'Rp ' . currency(Auth::user()->balance) }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="short-payment-support-info" onclick="openPaymentDrawer(this)">
                <div style="display: inline-block;">
                    <i class="mdi mdi-wallet bg-primary p-2 text-white mr-2 mb-2" style="font-size: 20px; border-radius:5px"></i>
                </div>
                <a class="open-button-action-payment text-white">
                    <i class="fas fa-chevron-down"></i>
                </a>
            </div>
        </div>
        @endauth
    </div>
</div>
@break
@case('without-collapse')
    @forelse ($payments as $key => $value)
        <input type="radio" id="payment_{{ $value['id'] }}" class="radio-payment" name="payment" value="{{ $value['id'] }}">
        <label for="payment_{{ $value['id'] }}">
            <div class="row mx-auto">
                <div class="col-6">
                    <img src="{{ asset(config('constants.options.asset_img_payment_method') . $value['img']) }}" width="100px" class="img-payment-2">
                </div>
                <div class="col-12 text-right">
                    <b id="payment-method-{{ $value['id'] }}" style="float: right; font-size: 15px; margin-bottom: 10px;">-</i></b>
                </div>
                <hr>
                <div class="col-12">
                    <em style="font-size: 12px;float: right;" class="mb-2"><i class="fa fa-info-circle"></i> {{ $value['name'] }}</em>
                </div>
            </div>
        </label>
    @empty
    @endforelse
    @auth
    <input type="radio" id="payment_1" class="radio-payment" name="payment" value="1">
    <label for="payment_1">
        <div class="row mx-auto">
            <div class="col-6">
                <div style="display: inline-block;"><i class="mdi mdi-wallet bg-primary p-2 text-white mb-2" style="font-size: 20px; border-radius:5px; margin-right: .6rem"></i>
                </div>
                <div style="display: inline-block;" class="mt-2">
                    <h5 class="text-white">Saldo</h5>
                </div>
            </div>
            <div class="col-6 text-right">
                <b id="payment-method-1" style="float: right; font-size: 15px;">~</b>
            </div>
            <div class="col-12">
                <em style="font-size: 12px;float: right;" class="mb-2">- Saldo</em>
            </div>
        </div>
    </label>
    <div class="saldo d-none">
        <div class="alert alert-warning text-white" role="alert" style="padding-top: 10px; padding-bottom: 10px;">
            <h6 style="margin-left: 10px">Sisa saldo {{ 'Rp ' . currency(Auth::user()->balance) }}</h6>
        </div>
    </div>
    @endauth
@break
@default
@endswitch
