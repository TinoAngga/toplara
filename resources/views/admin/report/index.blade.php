@section('title')
{{ getConfig('title') }} - {{ $page }}
@endsection
@extends('admin.layouts.app')
@section('style')
@endsection
@section('content')
<div class="row">
    <!-- Start col -->
    <div class="col-md-12 col-lg-12 col-xl-12">
        <div class="card m-b-30 shadow">
            <div class="card-header bg-primary">
                <h5 class="card-title mb-0 text-white font-weight-bold"><i class="mdi mdi-cart-outline"></i> Pesanan
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12 mb-2 mt-2">
                        <div class="card shadow">
                            <div class="card-header bg-primary">
                                <h5 class="card-title text-white mb-0 font-weight-bold"><i class="mdi mdi-cart-outline"></i> Total Semua Pesanan</h5>
                            </div>
                            <div class="card-body">
                                <h5 class="mb-0" id="order-count-all">
                                    <div class="spinner-border" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                </h5>
                                <br>
                                <h6 class="text-right mb-0" id="order-sum-all">
                                    <div class="spinner-border" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                </h6>
                            </div>
                        </div>
                    </div>
                    @foreach (config('constants.options.status.order') as $key => $value)
                    <div class="col-lg-12 col-md-6 col-xl-4 mb-2 mt-2">
                        <div class="card shadow">
                            <div class="card-header {{ getBgStatus($value) }}">
                                <h5 class="card-title text-white mb-0 font-weight-bold"><i
                                        class="mdi mdi-cart-outline"></i> {{ strtoupper($value) }}</h5>
                            </div>
                            <div class="card-body">
                                <h5 class="mb-0" id="order-count-{{ $value }}">
                                    <div class="spinner-border" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                </h5>
                                <br>
                                <h6 class="text-right mb-0" id="order-sum-{{ $value }}">
                                    <div class="spinner-border" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                </h6>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12 col-lg-12 col-xl-12">
        <div class="card m-b-30 shadow">
            <div class="card-header bg-primary">
                <h5 class="card-title mb-0 text-white font-weight-bold"><i class="mdi mdi-credit-card"></i> Deposit
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12 mb-2 mt-2">
                        <div class="card shadow">
                            <div class="card-header bg-primary">
                                <h5 class="card-title text-white mb-0 font-weight-bold"><i class="mdi mdi-credit-card"></i> Total Semua Deposit</h5>
                            </div>
                            <div class="card-body">
                                <h5 class="mb-0" id="deposit-count-all">
                                    <div class="spinner-border" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                </h5>
                                <br>
                                <h6 class="text-right mb-0" id="deposit-sum-all">
                                    <div class="spinner-border" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                </h6>
                            </div>
                        </div>
                    </div>
                    @foreach (config('constants.options.status.deposit') as $key => $value)
                    <div class="col-lg-12 col-md-6 col-xl-4 mb-2 mt-2">
                        <div class="card shadow">
                            <div class="card-header {{ getBgStatus($value) }}">
                                <h5 class="card-title text-white mb-0 font-weight-bold"><i
                                        class="mdi mdi-credit-card"></i> {{ strtoupper($value) }}</h5>
                            </div>
                            <div class="card-body">
                                <h5 class="mb-0" id="deposit-count-{{ $value }}">
                                    <div class="spinner-border" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                </h5>
                                <br>
                                <h6 class="text-right mb-0" id="deposit-sum-{{ $value }}">
                                    <div class="spinner-border" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                </h6>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12 col-lg-12 col-xl-12">
        <div class="card m-b-30 shadow">
            <div class="card-header bg-primary">
                <h5 class="card-title mb-0 text-white font-weight-bold"><i class="mdi mdi-account-multiple-outline"></i> Peningkatan Level Pengguna
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12 mb-2 mt-2">
                        <div class="card shadow">
                            <div class="card-header bg-primary">
                                <h5 class="card-title text-white mb-0 font-weight-bold"><i class="mdi mdi-account-multiple-outline"></i> Total Peningkatan Level Pengguna</h5>
                            </div>
                            <div class="card-body">
                                <h5 class="mb-0" id="upgrade-level-count-all">
                                    <div class="spinner-border" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                </h5>
                                <br>
                                <h6 class="text-right mb-0" id="upgrade-level-sum-all">
                                    <div class="spinner-border" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                </h6>
                            </div>
                        </div>
                    </div>
                    @foreach (config('constants.options.status.upgrade_level') as $key => $value)
                    <div class="col-lg-12 col-md-6 col-xl-4 mb-2 mt-2">
                        <div class="card shadow">
                            <div class="card-header {{ getBgStatus($value) }}">
                                <h5 class="card-title text-white mb-0 font-weight-bold"><i
                                        class="mdi mdi-credit-card"></i> {{ strtoupper($value) }}</h5>
                            </div>
                            <div class="card-body">
                                <h5 class="mb-0" id="upgrade-level-count-{{ $value }}">
                                    <div class="spinner-border" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                </h5>
                                <br>
                                <h6 class="text-right mb-0" id="upgrade-level-sum-{{ $value }}">
                                    <div class="spinner-border" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                </h6>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <!-- End col -->
</div>
@endsection
@section('script')
<script>
    getOrder();
    getDeposit();
    getUpgradeLevel();
    async function getOrder(){
        let data = await $.getJSON('{{ route('admin.report.index', ['type' => 'order']) }}');
        $.each(data, function(key, value){
            $('#order-count-' + key).html(value.count);
            $('#order-sum-' + key).html(value.sum);
        });
    }
    async function getDeposit(){
        let data = await $.getJSON('{{ route('admin.report.index', ['type' => 'deposit']) }}');
        $.each(data, function(key, value){
            $('#deposit-count-' + key).html(value.count);
            $('#deposit-sum-' + key).html(value.sum);
        });
    }
    async function getUpgradeLevel(){
        let data = await $.getJSON('{{ route('admin.report.index', ['type' => 'upgrade-level']) }}');
        $.each(data, function(key, value){
            $('#upgrade-level-count-' + key).html(value.count);
            $('#upgrade-level-sum-' + key).html(value.sum);
        });
    }
</script>
@endsection
