@section('title')
{{ getConfig('title') }} - {{ $page }}
@endsection
@extends('admin.layouts.app')
@section('style')

@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card m-b-30">
            <div class="card-header bg-primary">
                <h6 class="m-0 font-weight-bold text-white"><i class="mdi mdi-format-list-bulleted"></i> {{ $page }} </h6>
            </div>
            <div class="card-body">
                <form action="{{ url()->current() }}" method="POST" id="config-form">
                    @method('POST')
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label>Host</label>
                            <input type="text" name="mail_host" class="form-control" value="{{ getConfig('mail_host') ?? old('mail_host') }}" />
                            <small class="text-danger mail_host-invalid"></small>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Email Dari</label>
                            <input type="email" name="mail_from" class="form-control" value="{{ getConfig('mail_from') ?? old('mail_from') }}" />
                            <small class="text-danger mail_from-invalid"></small>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Enkripsi</label>
                                <select name="mail_encryption" class="form-control">
                                    <option value="0" {{ getConfig('mail_encryption') == null ? 'selected' : '' }}>Tidak ada</option>
                                    <option value="ssl" {{ getConfig('mail_encryption') == 'ssl' ? 'selected' : '' }}>SSL</option>
                                    <option value="tls" {{ getConfig('mail_encryption') == 'tsl' ? 'selected' : '' }}>TLS</option>
                                </select>
                                <small class="text-danger mail_encryption-invalid"></small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Port</label>
                                <input type="text" name="mail_port" class="form-control" value="{{ getConfig('mail_port') ?? old('mail_port') }}" />
                                <small class="text-danger mail_port-invalid"></small>
                            </div>
                        </div>
                        <div class="form-group col-md-12">
                            <div class="custom-control custom-switch mb-3">
                                <input id="mail_auth" name="mail_auth" type="checkbox" class="custom-control-input" value="1" {{ getConfig('mail_auth') == '1' ? 'checked' : '' }}>
                                <label class="custom-control-label" for="mail_auth">Autentikasi</label>
                            </div>
                            <small class="text-danger mail_auth-invalid"></small>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Username</label>
                            <input type="text" name="mail_username" class="form-control" value="{{ getConfig('mail_username') ?? old('mail_username') }}"/>
                            <small class="text-danger mail_username-invalid"></small>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Password</label>
                            <input type="password" name="mail_password" class="form-control" value="{{ getConfig('mail_password') ?? old('mail_password') }}" />
                            <small class="text-danger mail_password-invalid"></small>
                        </div>
                    </div>
                    <div class="my-3">
                        <a href="javascript:void()" onclick="testMail()" class="btn btn-outline-info">Kirim Email Percobaan</a>
                        <small class="form-text text-muted">Sistem akan mengirim email ke nilai dari bidang <strong> Email Dari </strong> yang Anda tetapkan di atas. Pastikan untuk menyimpan pengaturan terlebih dahulu!</small>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-md">
                            Submit
                        </button>
                    </div>
                </form>
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
            $('button[type="submit"]').text('Submit');
        }
    }

    function testMail() {
        $.ajax({
            url: '{{ route('admin.website-config.mail.test') }}',
            method: 'POST',
            data: '_method=POST',
            processData: false,
            dataType: 'json',
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
                if (data.status == false) {
                    if (data.type == 'alert') {
                        swal.fire("Gagal!", data.msg, "error");
                    }
                } else {
                    $('#config-form')[0].reset();
                    swal.fire("Berhasil!", data.msg, "success")
                }
            },
            error: function () {
                reset_button(1);
                swal.fire("Gagal!", "Terjadi kesalahan.", "error");
            },
        });
    }
    $(function () {
        $("#config-form").on('submit', function (e) {
            e.preventDefault();
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
                                $("input[name=" + key + "]")
                                    .addClass('is-invalid');
                                $('small.' + key + '-invalid').text(
                                    val[0]);
                            });
                        }
                        if (data.type == 'alert') {
                            swal.fire("Gagal!", data.msg, "error");
                        }
                    } else {
                        $('#config-form')[0].reset();
                        swal.fire("Berhasil!", data.msg, "success").then(
                            function () {
                                window.location =
                                    "{{ url()->current() }}";
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
