@section('title')
{{ getConfig('title') }} - {{ $page['title'] }}
@endsection
@extends('primary.layouts.app')
@section('style')
<style>
    .select2-results__option, .select2-results__option--selectable {
        color: #000 !important;
    }
</style>
@endsection
@section('content')
<div class="container my-5">
    @include('primary.layouts.app.menu.nav')
    <div class="row">
        @include('primary.' . request()->segment(1) . '.filter')
        <div class="col-md-12" style="margin-bottom: 20px; margin-top: -10px;">
        </div>
        <div class="col-lg-12">
            <div class="card m-b-30">
                <div class="card-header bg-primary">
                    <h6 class="m-0 font-weight-bold text-white"><i class="mdi mdi-history"></i> {{ $page['title'] }} </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        {!! $dataTable->table(['class' => 'display table table-striped table-bordered mb-0'], false) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
{{ $dataTable->scripts(attributes: ['type' => 'module']) }}
<script>
    $(document).ready(function () {
        selectSearch('#filter_service','{{ route('order.history', ['type' => 'select2_service']) }}')

    });
    // $('body').tooltip({selector: '[data-toggle="tooltip"]'});

    $('#filter-form').on('submit', function (e) {
        $('#datatable').DataTable().draw()
        e.preventDefault();
    });
    $('#filter_service').on('change', function (e) {
        $('#datatable').DataTable().draw()
        e.preventDefault();
    });
    $('#filter_payment').on('change', function (e) {
        $('#datatable').DataTable().draw()
        e.preventDefault();
    });
    $('#search').on('keyup', function (e) {
        $('#datatable').DataTable().draw()
        e.preventDefault();
    });
</script>
@endsection
