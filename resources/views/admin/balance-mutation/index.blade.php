@section('title')
{{ getConfig('title') }} - {{ $page }}
@endsection
@extends('admin.layouts.app')
@section('style')

@endsection
@section('content')
<div class="row">
    @include('admin.' . request()->segment(2) . '.filter')
    <div class="col-md-12" style="margin-bottom: 20px; margin-top: -10px;">
    </div>
    <div class="col-lg-12">
        <div class="card m-b-30">
            <div class="card-header bg-primary">
                <h6 class="m-0 font-weight-bold text-white"><i class="mdi mdi-format-list-bulleted"></i> {{ $page }} </h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    {!! $dataTable->table(['class' => 'display table table-striped table-bordered mb-'], false) !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')

{!! $dataTable->scripts() !!}
<script>
    $('body').tooltip({selector: '[data-toggle="tooltip"]'});

    $('#filter-form').on('submit', function (e) {
        $('#datatable').DataTable().draw()
        e.preventDefault();
    });
</script>
@endsection
