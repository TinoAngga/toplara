@section('title')
{{ getConfig('title') }} - {{ $page['title'] }}
@endsection
@extends('primary.layouts.app')
@section('style')

@endsection
@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card m-b-30">
                <div class="card-header bg-primary">
                    <h6 class="m-0 font-weight-bold text-white"><i class="mdi mdi-lock-open-outline"></i> {{ $page['title'] }} </h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('auth.forgot-password.post') }}" method="POST" id="forgot-form">
                        @csrf
                        @method('POST')
                        <div class="form-group mb-2">
                            <label for="">Username</label>
                            <input type="text" name="username" id="username" class="form-control" placeholder="Username">
                            <small class="text-danger username-invalid"></small>
                        </div>
                        <div class="form-group">
                            <button type="reset" class="btn btn-danger"><i class="mdi mdi-refresh"></i> Reset</button>
                            <button type="submit" class="btn btn-primary"><i class="mdi mdi-lock-open-outline"></i> Lupa password</button>
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
            $('button[type="submit"]').attr('disabled', 'true');
            $('button[type="submit"]').text('');
            $('button[type="submit"]').append(
                '<span class=\"spinner-grow spinner-grow-sm\" role=\"status\" aria-hidden=\"true\"></span>Mohon Tunggu...'
            );
        } else {
            $('button[type="submit"]').removeAttr('disabled');
            $('button[type="submit"]').removeAttr('span');
            $('button[type="submit"]').html('<i class="mdi mdi-lock-open-outline"></i> Lupa password');
        }
    }
    $(function () {
        $("#forgot-form").on('submit', function (e) {
            e.preventDefault();
            console.log(this);
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
                        title: 'Mohon Tunggu...',
                        allowOutsideClick: false,
                        didOpen: function () {
                            swal.showLoading()
                        }
                    })
                },
                success: function (data) {
                    reset_button(1);
                    if (data.status == false) {
                        if (data.type == 'validation') {
                            swal.close();
                            $.each(data.msg, function (key, val) {
                                $("input[name=" + key + "]").addClass('is-invalid');
                                $('small.' + key + '-invalid').text(val[0]);
                            });
                        }
                        if (data.type == 'alert') {
                            swal.fire("Gagal!", data.msg, "error");
                        }
                    } else {
                        $('#forgot-form')[0].reset();
                        swal.fire("Berhasil!", data.msg, "success");
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

