<!-- My Profile Start -->
<div class="tab-pane fade" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">
    <div class="card m-b-30 shadow">
        <div class="card-header bg-primary">
            <h5 class="card-title-custom text-white mb-0"><i class="mdi mdi-account-outline mr-2"></i>Profil</h5>
        </div>
        <div class="card-body">
            <div class="profilebox pt-4 text-center">
                <ul class="list-inline">
                    <li class="list-inline-item">
                        <img src="{{ asset('cdn/profile.svg') }}" class="img-fluid" alt="profile">
                        <h6 class="text-center mt-2">{{ strtoupper(Auth::user()->username) }}</h6>
                        <span class="text-center">{{ strtoupper(Auth::user()->level) }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="card m-b-30 shadow">
        <div class="card-header bg-primary">
            <h5 class="card-title-custom mb-0 text-white"><i class="mdi mdi-square-edit-outline mr-2"></i> Edit Profil</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('account.update') }}" method="POST" id="update-account">
            @csrf
            @method('POST')
                <div class="row">
                    <div class="form-group col-md-12">
                        <label for="useremail">Email</label>
                        <input type="email" class="form-control text-white" value="{{ Auth::user()->email }}" readonly>
                    </div>
                    <div class="form-group col-md-6 mt-1">
                        <label for="username">Nama Lengkap</label>
                        <input type="text" class="form-control" name="full_name" placeholder="Nama Lengkap" value="{{ Auth::user()->full_name }}">
                        <small class="text-danger full_name-invalid"></small>
                    </div>
                    <div class="form-group col-md-6 mt-1">
                        <label for="usermobile">Nomor HP</label>
                        <input type="text" class="form-control" name="phone_number" placeholder="Nomor HP / Whatsapp" value="{{ Auth::user()->phone_number }}">
                        <small class="text-danger phone_number-invalid"></small>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6 mt-1">
                        <label for="userpassword">Password Baru</label>
                        <input type="password" class="form-control" name="new_password" placeholder="Password baru">
                        <small class="text-danger new_password-invalid"></small>
                    </div>
                    <div class="form-group col-md-6 mt-1">
                        <label for="userconfirmedpassword">Konfirmasi Password Baru</label>
                        <input type="password" class="form-control" name="new_password_confirmation" placeholder="Konfirmasi password baru">
                        <small class="text-danger new_password_confirmation-invalid"></small>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="form-group col-md-12">
                        <label for="userpassword">Password</label>
                        <input type="password" class="form-control" name="password" placeholder="Password">
                        <small class="text-danger password-invalid"></small>
                    </div>
                </div>
                <div class="form-group col-md-6 mt-3">
                    <button type="submit" class="btn btn-primary justify-content-center font-16"><i class="feather icon-save mr-2"></i>Ubah</button>
                </div>
            </form>
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
    $(document).ready(function() {
        $("#update-account").on('submit', function (e) {
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
                        $('#update-account')[0].reset();
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
