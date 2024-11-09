<!-- My Profile Start -->
<div class="tab-pane fade" id="v-pills-api" role="tabpanel" aria-labelledby="v-pills-api-tab">
    <div class="card m-b-30 shadow">
        <div class="card-header bg-primary">
            <h5 class="card-title-custom mb-0 text-white"><i class="mdi mdi-fire mr-2"></i> Konfigurasi API</h5>
        </div>
        <div class="card-body">
           @if (Auth::user()->level == 'h2h')
           <form action="{{ route('account.updateAPI') }}" method="POST" id="update-api">
            @csrf
            @method('POST')
                <div class="row">
                    <div class="form-group col-md-12">
                        <label for="useremail">API KEY</label>
                        <input type="text" class="form-control text-white" id="api_key" value="{{ Auth::user()->api_key }}" readonly>
                    </div>
                    <div class="form-group col-md-4 my-2">
                        <button type="button" onclick="generateAPIKey()" class="btn btn-primary justify-content-center font-12"><i class="mdi mdi-refresh mr-2"></i>Generate</button>
                        <button type="button" onclick="salin('{{ Auth::user()->api_key }}')" class="btn btn-primary justify-content-center font-16"><i class="mdi mdi-clipboard mr-2"></i>Salin</button>
                    </div>
                    <div class="form-group col-md-12 mt-1">
                        <label for="whitelist_ip">Whitelist IP</label>
                        <input type="text" class="form-control" name="whitelist_ip" placeholder="Pisahkan dengan koma (,)" value="{{ Auth::user()->whitelist_ip }}">
                        <small class="text-danger whitelist_ip-invalid"></small>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-12">
                        <label for="userpassword">Password</label>
                        <input type="password" class="form-control" name="password" placeholder="Password">
                        <small class="text-danger password-invalid"></small>
                    </div>
                    <div class="form-group col-md-6 mt-3">
                        <button type="submit" class="btn btn-primary justify-content-center font-16"><i class="feather icon-save mr-2"></i>Update</button>
                    </div>
                </div>
            </form>
           @else
                <div class="row">
                    <div class="col-12 text-center">
                        Level harus H2H terlebih dahulu.
                    </div>
                </div>
           @endif
        </div>
    </div>
</div>
<!-- My Profile End -->
@push('script')
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
            $('button[type="submit"]').html('<i class="feather icon-save mr-2"></i>Ubah');
        }
    }
    function generateAPIKey()
    {
        $.ajax({
            url: '{{ route('account.generateAPIKey') }}',
            method: 'POST',
            dataType: 'json',
            beforeSend: function () {
                reset_button(0);
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
                    swal.fire("Gagal!", data.msg, "error");
                } else {
                    swal.fire("Berhasil!", data.msg, "success");
                    $('input[id="api_key"]').val(data.v);
                }
            },
            error: function () {
                reset_button(1);
                swal.fire("Gagal!", "Terjadi kesalahan.", "error");
            },
        });
    }
    $(document).ready(function() {
        $("#update-api").on('submit', function (e) {
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
                        $('#update-api')[0].reset();
                        swal.fire("Berhasil!", data.msg, "success").then(function () {
                            location.href = '{{ url()->current() }}';
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
@endpush
