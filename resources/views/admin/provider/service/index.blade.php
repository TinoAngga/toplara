@section('title')
{{ getConfig('title') }} - {{ $page }}
@endsection
@extends('admin.layouts.app')
@section('style')
@endsection
@section('content')
<div class="row">
    <div class="col-lg-12">
        <a href="{{ route('admin.provider.index') }}" class="btn btn-warning btn-md mb-2"><i class="fa fa-arrow-left" aria-hidden="true"></i> Kembali</a>
    </div>
    <div class="col-lg-12">
        <div class="card m-b-30">
            <div class="card-header bg-primary-rgba">
                <h6 class="m-0 font-weight-bold"><i class="mdi mdi-format-list-bulleted"></i>
                    {{ $page . ' - ' . $provider->name }} </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 mb-2">
                        <form action="{{ route('admin.provider.service.get', $provider->id) }}" method="POST"
                            id="request-api-service">
                            @method('POST')
                            <input type="hidden" name="id" value="{{ $provider->id }}">
                            <div class="form-group">
                                <label for="">Kategori</label>
                                <select class="form-control" name="service_category" id="service_category">
                                    <option value="">Pilih kategori...</option>
                                </select>
                                <small id="helpId" class="text-muted service_category-invalid"></small>
                            </div>
                            <button type="submit" class="btn btn-primary btn-lg btn-block d-none" id="btn-submit"> <i class="fa fa-check"></i>
                                Submit
                            </button>
                        </form>
                    </div>
                    <div class="col-md-12 mb-2">
                        <div id="result-api-service" class="d-none ">
                            <div class="card">
                                <div class="card-header bg-primary-rgba">
                                    <h6 class="m-0 font-weight-bold"><i class="mdi mdi-border-all"></i> Daftar Layanan
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div id="service">

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script>
    let level = ['public', 'silver', 'gold', 'vip'];
    $('body').ready(function () {
        var provider_id = '{{ $provider->id }}';
        $.ajax({
            url: '{{ route('admin.provider.service.category', $provider->id) }}',
            method: 'POST',
            data: "id="+ provider_id,
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
                    setTimeout(function(){
                        location.href = '{{ route('admin.provider.index') }}'
                    } , 3000);
                } else {
                    $('#btn-submit').removeClass('d-none');
                    $("#service_category").html(data.msg);
                }
            },
            error: function () {
                swal.close();
                swal.fire("Gagal!", "Terjadi kesalahan.", "error");
                setTimeout(function(){
                    location.href = '{{ route('admin.provider.index') }}'
                } , 3000);
            },
        });
    });
    $(function () {
        function reset_button(value = 0) {
            if (value == 0) {
                $('button[type="submit"]').attr('disabled', 'true');
                $('button[type="submit"]').text('');
                $('button[type="submit"]').append(
                    '<span class=\"spinner-grow spinner-grow-sm mb-1\"></span> Mohon tunggu...');
                $('button[type="reset"]').hide();
            } else {
                $('button[type="submit"]').removeAttr('disabled');
                $('button[type="submit"]').removeAttr('span');
                $('button[type="submit"]').text('');
                $('button[type="submit"]').append('<i class=\"fa fa-check\"></i> Submit');
                $('button[type="reset"]').show();
            }
        }
        $("#service_category").on('change', function() {
            $("#result-api-service").addClass('d-none');
            $("#service").html('');
        })
        $("#request-api-service").on('submit', function (e) {
            e.preventDefault();
            if ($('#service_category').val() == '') {
                return swal.fire("Gagal!", "Harap isi semua input!!", "error");
            }
            $.ajax({
                url: $(this).attr('action'),
                method: $(this).attr('method'),
                data: new FormData(this),
                processData: false,
                dataType: 'json',
                contentType: false,
                beforeSend: function () {
                    swal.fire({
                        title: 'Mohon tunggu...',
                        allowOutsideClick: false,
                        didOpen: function () {
                            swal.showLoading()
                        },
                    })
                },
                success: function (data) {
                    swal.close();
                    if (data.status == false) {
                        swal.fire("Gagal!", data.msg, "error");
                    } else {
                        Toast.fire("Berhasil!", data.msg, "success");
                        $("#result-api-service").removeClass('d-none');
                        $("#service").html(data.data);
                    }
                },
                error: function () {
                    reset_button(1);
                    swal.fire("Gagal!", "Terjadi kesalahan.", "error");
                },
            });
        });
    });

</script>
@endsection
