@section('title')
{{ getConfig('title') }} - {{ $page }}
@endsection
@extends('admin.layouts.app')
@section('style')
@endsection
@section('content')
<div class="row">
    @include('admin.' .request()->segment(2). '.filter')
    <div class="col-lg-12">
        <div class="card m-b-30">
            <div class="card-header bg-primary">
                <h6 class="m-0 font-weight-bold text-white"><i class="mdi mdi-format-list-bulleted"></i> {{ $page }} </h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    {!! $dataTable->table(['class' => 'table table-borderless table-hover table-bordered mb-0'], false) !!}
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
    const updateBalance = (url) => {
        $.ajax({
            url: url,
            method: 'GET',
            processData: false,
            dataType: 'json',
            beforeSend: function () {
                swal.fire({
                    title: 'Mohon tunggu...',
                    allowOutsideClick: false,
                    didOpen: function () {
                        swal.showLoading()
                    }
                })
            },
            success: function (data) {
                swal.close();
                if (data.status == false) {
                    swal.fire("Gagal!", data.msg, "error");
                } else {
                    Toast.fire("Berhasil!", data.msg, "success")
                    $('#datatable').DataTable().draw()
                }
            },
            error: function () {
                swal.close();
                swal.fire("Gagal!", "Terjadi kesalahan.", "error");
            },
        });
    }
    const syncServiceProvider = (url) => {
        $.ajax({
            url: url,
            method: 'GET',
            processData: false,
            dataType: 'json',
            beforeSend: function () {
                swal.fire({
                    title: 'Mohon tunggu...',
                    allowOutsideClick: false,
                    didOpen: function () {
                        swal.showLoading()
                    }
                })
            },
            success: function (data) {
                swal.close();
                if (data.status == false) {
                    swal.fire("Gagal!", data.msg, "error");
                } else {
                    Toast.fire("Berhasil!", data.msg, "success")
                    $('#datatable').DataTable().draw()
                }
            },
            error: function () {
                swal.close();
                swal.fire("Gagal!", "Terjadi kesalahan.", "error");
            },
        });
    }
</script>
@endsection
