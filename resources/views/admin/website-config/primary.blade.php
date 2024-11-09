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
                <h6 class="m-0 font-weight-bold text-white"><i class="mdi mdi-format-list-bulleted"></i>
                    {{ $page }} </h6>
            </div>
            <div class="card-body">
                <form action="{{ url()->current() }}" method="POST" id="config-form"  enctype="multipart/form-data">
                    @method('POST')
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label>Title Website</label>
                            <input type="text" name="title" class="form-control"
                                value="{{ getConfig('title') ?? old('title') }}" />
                            <small class="text-danger title-invalid"></small>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Bar Title Website <em class="text-danger">*Bar tag title halaman utama</em></label>
                            <input type="text" name="bartitle" class="form-control" value="{{ getConfig('bartitle') ?? old('bartitle') }}" />
                            <small class="text-danger bartitle-invalid"></small>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Short Title Website</label>
                            <input type="text" name="short_title" class="form-control"
                                value="{{ getConfig('short_title') ?? old('short_title') }}" />
                            <small class="text-danger short_title-invalid"></small>
                        </div>
                        <div class="form-group col-md-12">
                            <label>Description Website</label>
                            <textarea name="description" class="form-control" cols="30" rows="10">{{ getConfig('description') ?? old('description') }}</textarea>
                            <small class="text-danger description-invalid"></small>
                        </div>
                        <div class="form-group col-md-12">
                            <label>Short Description Website</label>
                            <input type="text" name="short_description" class="form-control"
                                value="{{ getConfig('short_description') ?? old('short_description') }}" />
                            <small class="text-danger short_description-invalid"></small>
                        </div>
                        <div class="form-group col-md-12">
                            <label>Favicon</label>
                            <input type="file" class="dropify" name="favicon"
                                data-default-file="{{ asset(config('constants.options.asset_img_website') . getConfig('favicon') ?? old('favicon')) }}"
                                value="{{ asset(config('constants.options.asset_img_website') . getConfig('favicon') ?? old('favicon')) }}
                        ">
                            <small class="text-danger favicon-invalid"></small>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Logo</label>
                            <input type="file" class="dropify" name="logo"
                                data-default-file="{{ asset(config('constants.options.asset_img_website') . getConfig('logo') ?? old('logo')) }}"
                                value="{{ asset(config('constants.options.asset_img_website') . getConfig('logo') ?? old('logo')) }}
                        ">
                            <small class="text-danger logo-invalid"></small>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Logo Small</label>
                            <input type="file" class="dropify" name="logo_sm"
                                data-default-file="{{ asset(config('constants.options.asset_img_website') . getConfig('logo_sm') ?? old('logo_sm')) }}"
                                value="{{ asset(config('constants.options.asset_img_website') . getConfig('logo_sm') ?? old('logo_sm')) }}
                        ">
                            <small class="text-danger logo_sm-invalid"></small>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Footer Description Website</label>
                            <input type="text" name="footer_description" class="form-control"
                                value="{{ getConfig('footer_description') ?? old('footer_description') }}" />
                            <small class="text-danger footer_description-invalid"></small>
                        </div>
                        {{-- <div class="form-group col-md-12">
                            <label for="">Theme Website</label>
                            <br>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="main_template1" name="main_template"
                                    class="custom-control-input" value="dark-horizontal" {{ getConfig('main_template') == 'dark-horizontal' ? 'checked' : '' }}>
                                <label class="custom-control-label" for="main_template1">Dark Theme</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="main_template2" name="main_template"
                                    class="custom-control-input" value="light-horizontal" {{ getConfig('main_template') == 'light-horizontal' ? 'checked' : '' }}>
                                <label class="custom-control-label" for="main_template2">Light Theme</label>
                            </div>
                        </div> --}}
                        <div class="form-group col-md-12">
                            <label for="">Payment Template</label>
                            <br>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="payment_template1" name="payment_template"
                                    class="custom-control-input" value="with-collapse" {{ getConfig('payment_template') == 'with-collapse' ? 'checked' : '' }}>
                                <label class="custom-control-label" for="payment_template1">With Collapse</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="payment_template2" name="payment_template"
                                    class="custom-control-input" value="without-collapse" {{ getConfig('payment_template') == 'without-collapse' ? 'checked' : '' }}>
                                <label class="custom-control-label" for="payment_template2">Without Collapse</label>
                            </div>
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
