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
                        <div class="col-md-12 d-flex justify-content-center">
                            <h4 class="text-primary font-weight-bold"> TRIPAY </h4>
                        </div>
                        <div class="form-group col-md-12">
                            <label>Tripay Merchant Code</label>
                            <input type="text" name="tripay_merchant_code" class="form-control" value="{{ getConfig('tripay_merchant_code') ?? old('tripay_merchant_code') }}" />
                            <small class="text-danger tripay_merchant_code-invalid"></small>
                        </div>
                        <div class="form-group col-md-12">
                            <label>Tripay API Key</label>
                            <input type="text" name="tripay_api_key" class="form-control" value="{{ getConfig('tripay_api_key') ?? old('tripay_api_key') }}" />
                            <small class="text-danger tripay_api_key-invalid"></small>
                        </div>
                        <div class="form-group col-md-12">
                            <label>Tripay Private Key</label>
                            <input type="text" name="tripay_private_key" class="form-control" value="{{ getConfig('tripay_private_key') ?? old('tripay_private_key') }}" />
                            <small class="text-danger tripay_private_key-invalid"></small>
                        </div>
                        <hr>
                        <div class="col-md-12 d-flex justify-content-center">
                            <h4 class="text-primary font-weight-bold"> PAYDISINI </h4>
                        </div>
                        <div class="form-group col-md-12">
                            <label>Paydisini API Key</label>
                            <input type="text" name="paydisini_api_key" class="form-control" value="{{ getConfig('paydisini_api_key') ?? old('paydisini_api_key') }}" />
                            <small class="text-danger paydisini_api_key-invalid"></small>
                        </div>
                        <hr>
                        <div class="col-md-12 d-flex justify-content-center my-2">
                            <h4 class="text-primary font-weight-bold"> LINKQU </h4>
                        </div>
                        <div class="form-group col-md-12">
                            <label>LinkQU Client ID</label>
                            <input type="text" name="linkqu_client_id" class="form-control" value="{{ getConfig('linkqu_client_id') ?? old('linkqu_client_id') }}" />
                            <small class="text-danger linkqu_client_id-invalid"></small>
                        </div>
                        <div class="form-group col-md-12">
                            <label>LinkQU Client Secret</label>
                            <input type="text" name="linkqu_client_secret" class="form-control" value="{{ getConfig('linkqu_client_secret') ?? old('linkqu_client_secret') }}" />
                            <small class="text-danger linkqu_client_secret-invalid"></small>
                        </div>
                        <div class="form-group col-md-12">
                            <label>LinkQU Client Username</label>
                            <input type="text" name="linkqu_client_username" class="form-control" value="{{ getConfig('linkqu_client_username') ?? old('linkqu_client_username') }}" />
                            <small class="text-danger linkqu_client_username-invalid"></small>
                        </div>
                        <div class="form-group col-md-12">
                            <label>LinkQU Client PIN</label>
                            <input type="text" name="linkqu_client_pin" class="form-control" value="{{ getConfig('linkqu_client_pin') ?? old('linkqu_client_pin') }}" />
                            <small class="text-danger linkqu_client_pin-invalid"></small>
                        </div>
                        <div class="form-group col-md-12">
                            <label>LinkQU Client Server Key</label>
                            <input type="text" name="linkqu_client_server_key" class="form-control" value="{{ getConfig('linkqu_client_server_key') ?? old('linkqu_client_server_key') }}" />
                            <small class="text-danger linkqu_client_server_key-invalid"></small>
                        </div>
                        <div class="form-group col-md-12">
                            <button type="submit" class="btn btn-primary btn-md">
                                Submit
                            </button>
                        </div>
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

    $('.dropify').dropify({
        messages: {
            'default': 'Drag and drop a file here or click',
            'replace': 'Drag and drop or click to replace',
            'remove': 'Remove',
            'error': 'Ooops, something wrong happended.'
        }
    });
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
