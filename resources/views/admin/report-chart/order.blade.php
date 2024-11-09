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
                <form method="GET" action="">
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label for="">Filter Tipe</label>
                            <select name="type" id="type" class="form-control">
                                <option value="">Semua</option>
                                <option value="public" {{ request('type') == 'public' ? 'selected' : '' }}>Publik / Tamu</option>
                                <option value="member" {{ request('type') == 'member' ? 'selected' : '' }}>Member</option>
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="">Filter Tanggal Awal</label>
                            <input type="date" name="start_date" id="start_date" value="{{ request('start_date') ?? date('Y-m-01') }}"
                                class="form-control">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="">Filter Tanggal Akhir</label>
                            <input type="date" name="end_date" id="end_date" value="{{ request('end_date') ?? date('Y-m-t') }}"
                                class="form-control">
                        </div>
                        <div class="form-group col-md-12 mt-1">
                            <button type="submit" class="btn btn-primary">Submit</button>
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
                    <div class="col-md-12 mb-5">
                        {!! $orderChartLine->container() !!}
                    </div>
                    <hr>
                    <div class="col-md-12 mb-5">
                        {!! $orderChartPie->container() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script src="{{ $orderChartLine->cdn() }}"></script>

{{ $orderChartLine->script() }}
{{ $orderChartPie->script() }}
<script>

</script>
@endsection
