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
                        <div class="form-group col-md-12">
                            <div class="table-responsive">
                                <table class="table table-bordered mb-0" id="tbl-widget">
                                    <thead>
                                        <tr>
                                            <th>IKON SVG</th>
                                            <th>JUDUL</th>
                                            <th>DESKRIPSI</th>
                                            <th>WARNA BACKGROUND</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $aboutSection = (getConfig('about_section') == null) ? [] : json_decode(getConfig('about_section'), true);
                                        @endphp
                                        @forelse ($aboutSection as $key => $value)
                                        <tr>
                                            <td>
                                                <textarea class="form-control" name="about_section[icon][]"  rows="5">{{ $value['icon'] }}</textarea>
                                            </td>
                                            <td><input type="text" class="form-control" name="about_section[title][]" value="{{ $value['title'] }}"></td>
                                            <td>
                                                <textarea class="form-control" name="about_section[description][]"  rows="5">{{ $value['description'] }}</textarea>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="about_section[bg_color][]" value="{{ $value['bg_color'] }}" placeholder="#3481234">
                                            </td>
                                            <td align="center">
                                                <button type="button" class="btn btn-sm btn-danger btn-remove-widget"><i class="fa fa-trash"></i></button>
                                            </td>

                                        </tr>
                                        @empty
                                        <tr>
                                            <td>
                                                <textarea class="form-control" name="about_section[icon][]" rows="5"></textarea>
                                            </td>
                                            <td><input type="text" class="form-control" name="about_section[title][]"></td>
                                            <td>
                                                <textarea class="form-control" name="about_section[description][]" rows="5"></textarea>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="about_section[bg_color][]" value="" placeholder="#3481234">
                                            </td>
                                            <td align="center">
                                                <button type="button" class="btn btn-sm btn-danger btn-remove-widget"><i class="fa fa-trash"></i></button>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="5">
                                                <button type="button" class="btn btn-sm btn-success btn-add-widget"><i class="fa fa-plus"></i></button>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-md-12">
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

    $('.dropify').dropify({
        messages: {
            'default': 'Drag and drop a file here or click',
            'replace': 'Drag and drop or click to replace',
            'remove': 'Remove',
            'error': 'Ooops, something wrong happended.'
        }
    });
    $(function () {
        $('.btn-add-widget').on('click', function() {
            var tr = $('#tbl-widget > tbody tr:last').clone(true, true);
            tr.find('input').val('');
            tr.find('textarea').val('');
            $(tr).appendTo('#tbl-widget > tbody');
        });

        $('.btn-remove-widget').on('click', function() {
            if ($('#tbl-widget > tbody tr').length > 1) {
                $(this).parents('tr').remove();
            }
        });
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
