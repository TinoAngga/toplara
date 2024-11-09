@section('title')
{{ getConfig('title') }} - {{ $page['title'] }}
@endsection
@extends('primary.layouts.app')
@section('style')
<!-- DataTables css -->
<link href="{{ asset('assets/' . getConfig('main_template') . '/plugins/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/' . getConfig('main_template') . '/plugins/datatables/buttons.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
<!-- Responsive Datatable css -->
<link href="{{ asset('assets/' . getConfig('main_template') . '/plugins/datatables/responsive.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
<div class="container my-5">
    <div class="row">
        @include('primary.layouts.app.menu.nav')
        @include('primary.' . request()->segment(1) . '.upgrade.filter')
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
<!-- Datatable js -->
<script src="{{ asset('assets/' . getConfig('main_template') . '/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/' . getConfig('main_template') . '/plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>
{!! $dataTable->scripts() !!}
<script>
    $('body').tooltip({selector: '[data-toggle="tooltip"]'});

    $('#filter-form').on('submit', function (e) {
        $('#datatable').DataTable().draw()
        e.preventDefault();
    });
</script>
@endsection
