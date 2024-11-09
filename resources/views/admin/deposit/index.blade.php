@section('title')
{{ getConfig('title') }} - {{ $page }}
@endsection
@extends('admin.layouts.app')
@section('style')

@endsection
@section('content')
<div class="row">
    @include('admin.' . request()->segment(2) . '.filter')
    <div class="col-md-12 mb-1 d-flex justify-content-end">
        <button onclick="refresh()" class="btn btn-primary btn-sm text-white"><i class="mdi mdi-refresh"></i></button>
    </div>
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
    $(document).ready(function () {
        selectSearch('#filter_user','{{ route('admin.deposit.index', ['type' => 'select2']) }}')
    });
    $('body').tooltip({selector: '[data-toggle="tooltip"]'});

    $('#filter-form').on('submit', function (e) {
        $('#datatable').DataTable().draw()
        e.preventDefault();
    });
    $('#filter_user').on('change', function (e) {
        $('#datatable').DataTable().draw()
        e.preventDefault();
    });
    $('#search').on('keyup', function (e) {
        $('#datatable').DataTable().draw()
        e.preventDefault();
    });
    function refresh() {
        $('#datatable').DataTable().draw()
    }
    function depositChangeStatus(url, title) {
        swal.fire({
            title: 'Apakah anda yakin?',
            text: "Anda akan mengganti status deposit dengan nomor invoice #" + title,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: `Ya`,
            cancelButtonText: `Batal`,
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: url,
                    method: 'POST',
                    dataType: 'json',
                    data: '_method=POST',
                    contentType: false,
                    beforeSend: function () {
                        swal.fire({
                            title: 'Mohon Tunggu...',
                            didOpen: function () {
                                swal.showLoading()
                            }
                        })
                    },
                    success: function (data) {
                        swal.close();
                        if (data.status == false) {
                            if (data.type == 'alert') {
                                Toast.fire("Gagal!", data.msg, "error");
                            }
                        } else {
                            Toast.fire("Berhasil!", data.msg, "success");
                            $('#datatable').DataTable().draw()
                            e.preventDefault();
                        }
                    },
                    error: function () {
                        Toast.fire("Gagal!", "Terjadi kesalahan.", "error");
                    },
                });
            }
        });
    }
    function paid(url, title) {
        swal.fire({
            title: 'Apakah anda yakin?',
            text: "Anda akan mengkonfirmasi " + title,
            icon: 'warning',
            showCancelButton: true,
            showDenyButton: true,
            confirmButtonText: `Konfirmasi Pembayaran`,
            cancelButtonText: `Batal`,
            denyButtonText: `Tolak Pembayaran`,
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: url + '/1',
                    method: 'POST',
                    dataType: 'json',
                    data: '_method=POST',
                    contentType: false,
                    beforeSend: function () {
                        swal.fire({
                            title: 'Mohon Tunggu...',
                            didOpen: function () {
                                swal.showLoading()
                            }
                        })
                    },
                    success: function (data) {
                        swal.close();
                        if (data.status == false) {
                            if (data.type == 'alert') {
                                Toast.fire("Gagal!", data.msg, "error");
                            }
                        } else {
                            Toast.fire("Berhasil!", data.msg, "success");
                            $('#datatable').DataTable().draw()
                            e.preventDefault();
                        }
                    },
                    error: function () {
                        Toast.fire("Gagal!", "Terjadi kesalahan.", "error");
                    },
                });
            } else if (result.isDenied) {
                $.ajax({
                    url: url + '/0',
                    method: 'POST',
                    dataType: 'json',
                    data: '_method=POST',
                    contentType: false,
                    beforeSend: function () {
                        swal.fire({
                            title: 'Mohon Tunggu...',
                            icon: 'loading',
                            didOpen: function () {
                                swal.showLoading()
                            }
                        })
                    },
                    success: function (data) {
                        swal.close();
                        if (data.status == false) {
                            if (data.type == 'alert') {
                                Toast.fire("Gagal!", data.msg, "error");
                            }
                        } else {
                            Toast.fire("Berhasil!", data.msg, "success");
                            $('#datatable').DataTable().draw()
                            e.preventDefault();
                        }
                    },
                    error: function () {
                        Toast.fire("Gagal!", "Terjadi kesalahan.", "error");
                    },
                });
            }
        });
    }
</script>
@endsection
