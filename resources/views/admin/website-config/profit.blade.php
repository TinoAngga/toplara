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
                        <div class="col-md-12 mb-2 mt-2">
                            <div class="alert alert-warning" role="alert">
                                <div class="alert-body">
                                    <strong>Catatan:</strong> Jika profit <b>persen</b> silahkan input angka, atau desimal dengan symbol <b>(.)</b>
                                    <br />
                                    Contoh:
                                    <ul>
                                        <li>10% maka input 10, 2% maka input 2</li>
                                        <li>1.2% maka input 1.2, 2.5% maka input 2.5</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-12">
                            <h3 class="border-bottom"> Profit PPOB</h3>
                        </div>
                        <div class="form-group col-md-12">
                            <label for="">Tipe Profit</label>
                            <select name="profit_type_ppob" id="profit_type_ppob" class="form-control">
                                <option value="">Pilih salah satu...</option>
                                <option value="flat" @if(getConfig('profit_type_ppob') === 'flat') selected @endif>Flat</option>
                                <option value="percent" @if(getConfig('profit_type_ppob') === 'percent') selected @endif>Persen</option>
                            </select>
                            <small class="text-danger profit_type_ppob-invalid"></small>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-1">
                                <label class="form-label">Profit Public</label>
                                <input class="form-control" name="profit_public_ppob" type="number" step=".01" placeholder="Profit Public" value="{{ getConfig('profit_public_ppob') }}">
                                <small class="text-danger profit_public_ppob-invalid"></small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-1">
                                <label class="form-label">Profit Reseller</label>
                                <input class="form-control" name="profit_reseller_ppob" type="number" step=".01" placeholder="Profit Reseller" value="{{ getConfig('profit_reseller_ppob') }}">
                                <small class="text-danger profit_reseller_ppob-invalid"></small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-1">
                                <label class="form-label">Profit H2H</label>
                                <input class="form-control" name="profit_h2h_ppob" type="number" step=".01" placeholder="Profit H2H" value="{{ getConfig('profit_h2h_ppob') }}">
                                <small class="text-danger profit_h2h_ppob-invalid"></small>
                            </div>
                        </div>
                        <hr>
                        <div class="form-group col-md-12">
                            <label>Profit By  </label>
                            <select name="profit_setting_ppob_by" id="profit_setting_ppob_by" class="form-control">
                                <option value="">Pilih salah satu...</option>
                                <option value="config" @if(getConfig('profit_setting_ppob_by') === 'config') selected @endif>Config</option>
                                <option value="service" @if(getConfig('profit_setting_ppob_by') === 'service') selected @endif>Service</option>
                            </select>
                            <small class="text-danger profit_setting_ppob_by-invalid"></small>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-12">
                            <h3 class="border-bottom"> Profit Game</h3>
                        </div>
                        <div class="form-group col-md-12">
                            <label for="">Tipe Profit</label>
                            <select name="profit_type_game" id="profit_type_game" class="form-control">
                                <option value="">Pilih salah satu...</option>
                                <option value="flat" @if(getConfig('profit_type_game') === 'flat') selected @endif>Flat</option>
                                <option value="percent" @if(getConfig('profit_type_game') === 'percent') selected @endif>Persen</option>
                            </select>
                            <small class="text-danger profit_type_game-invalid"></small>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-1">
                                <label class="form-label">Profit Public</label>
                                <input class="form-control" name="profit_public_game" type="number" step=".01" placeholder="Profit Public" value="{{ getConfig('profit_public_game') }}">
                                <small class="text-danger profit_public_game-invalid"></small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-1">
                                <label class="form-label">Profit Reseller</label>
                                <input class="form-control" name="profit_reseller_game" type="number" step=".01" placeholder="Profit Reseller" value="{{ getConfig('profit_reseller_game') }}">
                                <small class="text-danger profit_reseller_game-invalid"></small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-1">
                                <label class="form-label">Profit H2H</label>
                                <input class="form-control" name="profit_h2h_game" type="number" step=".01" placeholder="Profit H2H" value="{{ getConfig('profit_h2h_game') }}">
                                <small class="text-danger profit_h2h_game-invalid"></small>
                            </div>
                        </div>
                        <hr>
                        <div class="form-group col-md-12">
                            <label>Profit By  </label>
                            <select name="profit_setting_game_by" id="profit_setting_game_by" class="form-control">
                                <option value="">Pilih salah satu...</option>
                                <option value="config" @if(getConfig('profit_setting_game_by') === 'config') selected @endif>Config</option>
                                <option value="service" @if(getConfig('profit_setting_game_by') === 'service') selected @endif>Service</option>
                            </select>
                            <small class="text-danger profit_setting_game_by-invalid"></small>
                        </div>
                    </div>
                    <div class="row">
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
