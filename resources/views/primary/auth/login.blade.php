@section('title')
{{ getConfig('title') }} - {{ $page['title'] }}
@endsection
@extends('primary.layouts.app')
@section('style')

@endsection
@section('content')
<div class="container my-5 py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="pt-3 pb-4">
                <h5>{{ $page['title'] }}</h5>
                <span class="strip-primary"></span>
            </div>
            <div class="pb-3">
                <div class="section">
                    <div class="card shadow">
                        <div class="card-body">
                            <form action="{{ route('auth.login.post') }}" method="POST" id="login-form">
                                @csrf
                                @method('POST')
                                <div class="form-group mb-2">
                                    <p class="text-white">Username</p>
                                    <input type="text" name="username" class="form-control" autocomplete="off" style="border-radius: 10px;" placeholder="masukan username">
                                    <small class="text-danger username-invalid"></small>
                                </div>
                                <div class="form-group mb-2">
                                    <p class="text-white">Password</p>
                                    <input type="password" name="password" class="form-control" style="border-radius: 10px;" placeholder="masukan password">
                                    <small class="text-danger password-invalid"></small>
                                </div>
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox text-left">
                                        <input type="checkbox" class="custom-control-input" name="remember" id="rememberme">
                                        <label class="custom-control-label font-14" for="rememberme">Ingat Saya ? </label>
                                    </div>
                                </div>
                                <button type="submit" value="submit" class="btn btn-primary btn-block shadow" style="font-size: 900;"><i class="mdi mdi-login"></i> Login</button>
                            </form>
                            <hr style="background-color: #b8b8b8; opacity: .3;">
                            <a href="{{ url('auth/register') }}" class="btn btn-primary shadow" style="font-size: 900;">Register</a>
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
            $('button[type="submit"]').html('<i class="mdi mdi-login"></i> Login');
        }
    }
    $(function () {
        $("#login-form").on('submit', function (e) {
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
                        $('#login-form')[0].reset();
                        swal.fire("Berhasil!", data.msg, "success").then(function () {
                            window.location = "{{ route('account.index') }}";
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
