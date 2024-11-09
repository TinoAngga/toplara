@section('title')
{{ getConfig('title') }} - {{ $page }}
@endsection
@extends('admin.layouts.app')
@section('style')
@endsection
@section('content')
<div class="row mb-3">
    <div class="col-md-12">
        <a class="btn btn-{{ request()->segment(2) == 'report' ? 'primary' : 'danger' }}" href="{{ route('admin.report.' . request()->segment(3)) }}" role="button">Laporan</a>
        <a class="btn btn-{{ request()->segment(2) == 'report-chart' ? 'primary' : 'danger' }}" href="{{ route('admin.report-chart.' . request()->segment(3)) }}" role="button">Grafik</a>
    </div>
</div>
<div class="row mb-3">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-primary">
                <h4 class="card-title m-b-0 text-white"><i class="mdi mdi-filter"></i> Filter</h4>
            </div>
            <div class="card-body">
                <form id="form-submit">
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label for="">Filter Tipe</label>
                            <select name="type" id="type" class="form-control">
                                <option value="">Semua</option>
                                <option value="public">Publik / Tamu</option>
                                <option value="member">Member</option>
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="">Filter Tanggal Awal</label>
                            <input type="date" name="start_date" id="start_date" value="{{ date('Y-m-01') }}"
                                class="form-control">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="">Filter Tanggal Akhir</label>
                            <input type="date" name="end_date" id="end_date" value="{{ date('Y-m-t') }}"
                                class="form-control">
                        </div>
                        <div class="form-group col-md-12 mt-1">
                            <button type="button" class="btn btn-primary" onclick="filter()">Submit</button>
                            <button type="reset" class="btn btn-danger">Reset</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <!-- Start col -->
    <div class="col-md-12 col-lg-12 col-xl-12">
        <div class="card m-b-30 shadow">
            <div class="card-header bg-primary">
                <h5 class="card-title mb-0 text-white font-weight-bold"><i class="mdi mdi-cart-outline"></i> Pesanan</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 mb-2 mt-2">
                        <div class="card shadow">
                            <div class="card-header bg-primary">
                                <h5 class="card-title text-white mb-0 font-weight-bold"> Total Semua Penghasilan</h5>
                            </div>
                            <div class="card-body">
                                <h5 class="mb-0 loader" id="order-count-all">
                                    <div class="spinner-border" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                </h5>
                                <br>
                                <h5 class="text-right mb-0 loader" id="order-sum-all">
                                    <div class="spinner-border" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                </h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-2 mt-2">
                        <div class="card shadow">
                            <div class="card-header bg-danger">
                                <h5 class="card-title text-white mb-0 font-weight-bold"> Total Penghasilan Kotor</h5>
                            </div>
                            <div class="card-body">
                                <h5 class="mb-0 loader" id="order-count-gross">
                                    <div class="spinner-border" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                </h5>
                                <br>
                                <h5 class="text-right mb-0 loader" id="order-sum-gross">
                                    <div class="spinner-border" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                </h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-2 mt-2">
                        <div class="card shadow">
                            <div class="card-header bg-success">
                                <h5 class="card-title text-white mb-0 font-weight-bold"> Total Penghasilan Bersih</h5>
                            </div>
                            <div class="card-body">
                                <h5 class="mb-0 loader" id="order-count-net">
                                    <div class="spinner-border" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                </h5>
                                <br>
                                <h5 class="text-right mb-0 loader" id="order-sum-net">
                                    <div class="spinner-border" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                </h5>
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
                                <h5 class="mb-0 loader" id="order-status-count-{{ $value }}">
                                    <div class="spinner-border" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                </h5>
                                <br>
                                <h5 class="text-right mb-0 loader" id="order-status-sum-{{ $value }}">
                                    <div class="spinner-border" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                </h5>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script>
    async function filter() {
        let form = $('#form-submit');
        let data = form.serialize();
        let url = form.attr('action');
        let method = form.attr('method');
        try {
            $('.loader').html(`<div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div>`);
            $('button').attr('disabled', 'disabled');
            let result = await $.ajax({
                url: url,
                method: method,
                data: data,
                dataType: 'json'
            });
            $('button').attr('disabled', false);
            $.each(result, function(key, value){
                $('#order-count-' + key).html(`${currency(value.count)} Pesanan`);
                $('#order-sum-'+ key).html(`Rp ${currency(value.sum ?? 0)}`);
            });
            $.each(result.status, function(key, value){
                $('#order-status-count-'+ key).html(`${currency(value.count)} Pesanan`);
                $('#order-status-sum-'+ key).html(`Rp ${currency(value.sum ?? 0)}`);
            });
        } catch (error) {
            console.log(error);
        }
    }
    getWidget();
    async function getWidget(){
        let data = await $.getJSON('{{ route('admin.report.order', ['widget' => 1]) }}');
        $.each(data, function(key, value){
            $('#order-count-'+ key).html(`${currency(value.count)} Pesanan`);
            $('#order-sum-'+ key).html(`Rp ${currency(value.sum ?? 0)}`);
        });
        $.each(data.status, function(key, value){
            $('#order-status-count-' + key).html(`${currency(value.count)} Pesanan`);
            $('#order-status-sum-' + key).html(`Rp ${currency(value.sum ?? 0)}`);
        });
    }

    function currency(amount = 0) {
        var	number = amount.toString(),
        sisa 	= number.length % 3,
        rupiah 	= number.substr(0, sisa),
        ribuan 	= number.substr(sisa).match(/\d{3}/g);
        if (ribuan) {
            separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }
        return rupiah;
    }
</script>
@endsection
