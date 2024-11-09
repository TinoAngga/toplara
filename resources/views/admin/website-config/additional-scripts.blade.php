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
                <form action="{{ url()->current() }}" method="POST" id="config-form" enctype="multipart/form-data">
                    @method('POST')
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label>Tag / Script Tambahan Head</label>
                            <textarea name="additional_head_scripts" class="form-control" cols="30" rows="10">{{ getConfig('additional_head_scripts') ?? old('additional_head_scripts') }}</textarea>
                            <small class="text-danger additional_head_scripts-invalid"></small>
                        </div>
                        <div class="form-group col-md-12">
                            <label>Tag / Script Tambahan Sebelum Body</label>
                            <textarea name="additional_body_scripts" class="form-control" cols="30" rows="10">{{ getConfig('additional_body_scripts') ?? old('additional_body_scripts') }}</textarea>
                            <small class="text-danger additional_body_scripts-invalid"></small>
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
