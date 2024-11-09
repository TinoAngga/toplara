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
    $(document).ready(function () {
        selectSearch('#filter_user','{{ route('admin.order.index', ['type' => 'select2_user']) }}')
        selectSearch('#filter_service','{{ route('admin.order.index', ['type' => 'select2_service']) }}')
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
    $('#filter_service').on('change', function (e) {
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
    function orderChangeStatus(url, title) {
        swal.fire({
            title: 'Apakah anda yakin?',
            text: "Anda akan mengganti status order dengan nomor invoice #" + title,
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
                            title: 'SABAR KIMAK LAGI KIRIM EMAIL...',
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
    
    // BARU
//     function orderChangeStatus(url, title) {
//     // Gunakan swal hanya untuk konfirmasi awal
//     swal.fire({
//         title: 'Apakah anda yakin?',
//         text: "Anda akan mengganti status order dengan nomor invoice #" + title,
//         icon: 'warning',
//         showCancelButton: true,
//         confirmButtonText: `Ya`,
//         cancelButtonText: `Batal`,
//     }).then((result) => {
//         if (result.isConfirmed) {
//             $.ajax({
//                 url: url,
//                 method: 'POST',
//                 dataType: 'json',
//                 data: { _method: 'POST' }, // Menggunakan object data
//                 beforeSend: function () {
//                     // Tidak perlu loading swal, cukup gunakan toastr
//                     Toast.fire({
//                         icon: 'info',
//                         title: 'Memproses perubahan...'
//                     });
//                 },
//                 success: function (data) {
//                     if (data.status == false) {
//                         if (data.type == 'alert') {
//                             Toast.fire({
//                                 icon: 'error',
//                                 title: 'Gagal: ' + data.msg
//                             });
//                         }
//                     } else {
//                         Toast.fire({
//                             icon: 'success',
//                             title: 'Berhasil: ' + data.msg
//                         });
//                         // Segera refresh DataTable tanpa swal penutupan
//                         $('#datatable').DataTable().draw();
//                     }
//                 },
//                 error: function () {
//                     Toast.fire({
//                         icon: 'error',
//                         title: 'Terjadi kesalahan.'
//                     });
//                 },
//             });
//         }
//     });
// }

    
    
    
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
