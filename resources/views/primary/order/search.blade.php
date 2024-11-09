@section('title')
{{ getConfig('title') }} - {{ $page['title'] }}
@endsection
@extends('primary.layouts.app')
@section('style')

@endsection
@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <div class="pt-3 pb-4">
                <h5><i class="mdi mdi-feature-search-outline"></i> Cari Pesanan</h5>
                <span class="strip-primary"></span>
            </div>
            <div class="section">
                <div class="card-body">
                    <form method="POST" action="{{ route('order.search.post') }}" id="search-form">
                        @csrf
                        @method('POST')
                        <p class="text-white">No. Invoice</p>
                        <div class="form-group mb-3">
                            <input type="text" name="search" id="search" class="form-control" autocomplete="off" placeholder="Nomor Invoice" 
                                pattern="^[A-Za-z0-9\-#]+$" title="Hanya huruf, angka, tanda strip (-), dan pagar (#) yang diperbolehkan">
                            <small class="text-danger search-invalid"></small>
                        </div>
                        <div class="text-left">
                            <button type="submit" name="submit" value="submit" disabled class="btn btn-primary" style="font-size: 0.8rem;">
                                <i class="mdi mdi-feature-search-outline"></i> Cari Pesanan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script>
    function reset_button(value = 0) {
        if (value == 0) {
            $('button[name="submit"]').attr('disabled', 'true');
            $('button[type="reset"]').attr('disabled', 'true');
            $('button[name="submit"]').text('');
            $('button[name="submit"]').append(
                '<span class=\"spinner-grow spinner-grow-sm\" role=\"status\" aria-hidden=\"true\"></span>Mohon Tunggu...'
            );
        } else {
            $('button[name="submit"]').removeAttr('disabled');
            $('button[type="reset"]').removeAttr('disabled');
            $('button[name="submit"]').removeAttr('span');
            $('button[name="submit"]').html('<i class="mdi mdi-feature-search-outline"></i> Cari Pesanan');
        }
    }

    $(function () {
        $('button[name="submit"]').removeAttr('disabled');

        // Validasi input di sisi client
        $('#search').on('input', function () {
            let value = $(this).val();
            let regex = /^[A-Za-z0-9\-#]+$/;

            if (!regex.test(value)) {
                $('button[name="submit"]').attr('disabled', 'true');
                $('small.search-invalid').text('Nomor Invoice hanya boleh berisi huruf, angka, tanda - dan #');
            } else {
                $('button[name="submit"]').removeAttr('disabled');
                $('small.search-invalid').text('');
            }
        });

        $("#search-form").on('submit', function (e) {
            e.preventDefault();
            console.log(new FormData(this));
            $.ajax({
                url: $(this).attr('action'),
                method: $(this).attr('method'),
                data: new FormData(this),
                processData: false,
                dataType: 'json',
                contentType: false,
                beforeSend: function () {
                    reset_button(0);
                    $(document).find('small.text-danger').text('');
                    $(document).find('input').removeClass('is-invalid');
                    swal.fire({
                        title: 'Mohon tunggu...',
                        allowOutsideClick: false,
                        didOpen: function () {
                            swal.showLoading()
                        }
                    });
                },
                success: function (data) {
                    reset_button(1);
                    if (data.status == false) {
                        if (data.type == 'validation') {
                            swal.fire("Gagal!", "Harap mengisi input!.", "error");
                            $.each(data.msg, function (key, val) {
                                $("input[name=" + key + "]").addClass('is-invalid');
                                $('small.' + key + '-invalid').text(val[0]);
                            });
                        }
                        if (data.type == 'alert') {
                            swal.fire("Gagal!", data.msg, "error");
                        }
                    } else {
                        $('#search-form')[0].reset();
                        swal.fire("Berhasil!", data.msg, "success").then(function(){
                            window.location = data.redirect_url;
                        });
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
