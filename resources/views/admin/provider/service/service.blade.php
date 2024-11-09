<div class="row">
    <div class="col-md-12 mb-2">
        <a href="javascript:;" data-toggle="modal" data-target=".modal-service-mass" class="btn btn-primary btn-input-form font-weight-bold">
            <i class="mdi mdi-plus icon-input-form mr-2"></i> Input Semua Layanan
        </a>
    </div>
    <div class="col-md-12">
        <hr class="mb-2 border bg-primary">
    </div>
</div>
<div class="table-responsive mt-2">
    <table class="table table-borderless table-hover table-bordered mb-0">
        <thead class="bg-primary">
            <tr>
                <th>Kode Layanan API</th>
                <th>Nama Layanan</th>
                <th>Harga Layanan</th>
                <th class="text-center">Status Layanan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($service as $key => $value)
            <tr>
                <td>{{ $value['provider_service_code'] }}</td>
                <td>{{ $value['name'] }}</td>
                <td>{{ 'Rp ' . currency($value['price']) }}</td>
                <td align="center">{!! ($value['status'] == 'on') ? '<span class="badge badge-success font-weight-bold">ON</span>' : '<span class="badge badge-danger font-weight-bold">OFF</span>' !!}</td>
                <td>
                    <a onclick="modal('add', '#{{ $value['provider_service_code'] }}', '{{ url('admin/provider/service/'.$provider->id.'/service/' . $value['provider_service_code']) }}')" class="btn btn-primary btn-sm" href="#" role="button"><i class="fa fa-plus"></i></a>
                </td>
            </tr>
            @empty

            @endforelse
        </tbody>
    </table>
</div>
<div class="modal fade" id="modal-service" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" style="display: none;">
	<div class="modal-dialog modal-dialog-centered modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"><i class="fa fa-plus fa-fw"></i> Tambah Layanan</h5>
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			</div>
			<div class="modal-body" id="modal-service-body">
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
			</div>
		</div>
	</div>
</div>
<div class="modal fade modal-service-mass" id="modal-service-mass" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" style="display: none;">
	<div class="modal-dialog modal-dialog-centered modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"><i class="mdi mdi-cogs"></i> Pengaturan</h5>
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			</div>
			<div class="modal-body" id="modal-service-mass-body">
                <form action="{{ route('admin.service.storeMass', request()->segment(4)) }}" id="form-input-mass" method="POST">
                    @method('POST')
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="">Kategori Layanan Provider</label>
                                <input type="text" class="form-control" name="service_category_provider" value="{{ $serviceCategoryProvider }}" readonly>
                                <small class="text-danger service_category_provider-invalid"></small>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="">Kategori Layanan</label>
                                <select name="service_category_id" id="service_category_id" class="form-control">
                                    <option value="">Pilih kategori...</option>
                                    @foreach ($category as $key => $value)
                                        <option value="{{ $value->id }}">{{ $value->name }}</option>
                                    @endforeach
                                </select>
                                <small class="text-danger service_category_id-invalid"></small>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="">String yang di potong</label>
                                <input type="text" class="form-control" name="cut_string" value="" placeholder="String yang di potong">
                                <small class="text-danger cut_string-invalid"></small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">String yang di tambahkan</label>
                                <input type="text" class="form-control" name="add_string" value="" placeholder="String yang di potong">
                                <small class="text-danger add_string-invalid"></small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Letak string</label>
                                <br />
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="customRadioInline1" name="place_string" value="right" class="custom-control-input">
                                    <label class="custom-control-label" for="customRadioInline1">Kanan</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="customRadioInline2" name="place_string" value="left" class="custom-control-input">
                                    <label class="custom-control-label" for="customRadioInline2">Kiri</label>
                                </div>
                                <small class="text-danger place_string-invalid"></small>
                            </div>
                        </div>
                        <hr class="bg-primary border">
                        <div class="form-group col-md-12">
                            <label for="">Tipe Profit</label>
                            <select name="profit_type_mass" id="profit_type_mass" class="form-control">
                                <option value="">Pilih salah satu...</option>
                                <option value="flat">Flat</option>
                                <option value="percent">Persen</option>
                            </select>
                            <small class="text-danger profit_type_mass-invalid"></small>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Profit Public</label>
                                <input class="form-control" name="profit_mass[public]" type="number"
                                    placeholder="Profit Public" step=".01">
                                <small class="text-danger profit_public-invalid"></small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Profit Silver</label>
                                <input class="form-control" name="profit_mass[silver]" type="number" placeholder="Profit Silver" step=".01">
                                <small class="text-danger profit_silver-invalid"></small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Profit Gold</label>
                                <input class="form-control" name="profit_mass[gold]" type="number" placeholder="Profit Gold" step=".01">
                                <small class="text-danger profit_gold-invalid"></small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Profit VIP</label>
                                <input class="form-control" name="profit_mass[vip]" type="number" placeholder="Profit VIP" step=".01">
                                <small class="text-danger profit_gold-invalid"></small>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <button class="btn btn-primary btn-md" name="form_input_mass" type="submit"><i class="fa fa-check fa-fw"></i> Submit</button>
                    </div>
                </form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
    function reset_button_modal(value = 0) {
        if (value == 0) {
            $('button[name="form_input_mass"]').attr('disabled', 'true');
            $('button[name="form_input_mass"]').text('');
            $('button[name="form_input_mass"]').append('<span class=\"spinner-grow spinner-grow-sm mb-1\"></span> Mohon tunggu...');
            $('button[type="reset"]').hide();
        } else {
            $('button[name="form_input_mass"]').removeAttr('disabled');
            $('button[name="form_input_mass"]').removeAttr('span');
            $('button[name="form_input_mass"]').text('');
            $('button[name="form_input_mass"]').append('<i class=\"fa fa-check\"></i> Submit');
            $('button[type="reset"]').show();
        }
    }
    $(document).ready(function() {
        $("#form-input-mass").on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: $(this).attr('action'),
                method: $(this).attr('method'),
                data: new FormData(this),
                processData: false,
                dataType: 'json',
                contentType: false,
                beforeSend: function() {
                    reset_button_modal(0);
                    $(document).find('small.text-danger').text('');
                    $(document).find('input').removeClass('is-invalid');
                    $(document).find('select').removeClass('is-invalid');
                    $(document).find('textarea').removeClass('is-invalid');
                },
                success: function(data) {
                    reset_button_modal(1);
                    if (data.status == false) {
                        if (data.type == 'validation') {
                            $.each(data.msg, function(key, val) {
                                $("input[name=" + key.replace(".", "_") + "]").addClass('is-invalid').focus();
                                $("select[name=" + key.replace(".", "_") + "]").focus();
                                // $("option").addClass('is-invalid').focus();
                                $("textarea[name=" + key.replace(".", "_") + "]").addClass('is-invalid').focus();
                                $('small.' + key.replace(".", "_") +'-invalid').text(val[0]).focus();
                            });
                        }
                        if (data.type == 'alert') {
                            swal.fire("Gagal!", data.msg, "error");
                        }
                    } else {
                        reset_button_modal(1);
                        $('#modal-service-mass').modal('hide');
                        swal.fire("Berhasil!", data.msg, "success");
                    }
                },
                error:function() {
                    reset_button_modal(1);
                    $(document).find('small.text-danger').text('');
                    $(document).find('input').removeClass('is-invalid');
                    $(document).find('select').removeClass('is-invalid');
                    $(document).find('textarea').removeClass('is-invalid');
                    swal.fire("Gagal!", "Terjadi kesalahan.", "error");
                },
            });
        });
    });
	function addService(service) {
		$.ajax({
			type: "GET",
			url: `{{ url('admin/provider/service/'.$provider->id.'/service/') }}/${ service }`,
            dataType: 'json',
            beforeSend: function () {
                swal.fire({
                    title: 'Mohon tunggu...',
                    allowOutsideClick: false,
                    didOpen: function () {
                        swal.showLoading()
                    }
                })
            },
            success: function (data) {
                swal.close();
                if (data.status == false) {
                    swal.fire("Gagal!", data.msg, "error");
                } else {
                    $('#modal-service').modal({backdrop: 'static', keyboard: false});
                    $('#modal-service').modal('show');
                    $('#modal-service-body').html(data.data);
                }
            },
            error: function () {
                swal.close();
                swal.fire("Gagal!", "Terjadi kesalahan.", "error");
            },
		});
	}
</script>
