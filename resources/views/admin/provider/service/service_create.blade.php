<form action="{{ route('admin.service.store') }}" method="post" id="service-form-modal">
    @method('POST')
    <div class="form-group">
        <label for="">Provider</label>
        <select name="provider_id" id="provider_id" class="form-control">
            <option value="{{ $provider->id }}" selected>{{ $provider->name }}</option>
        </select>
        <small class="text-danger provider_id-invalid"></small>
    </div>
    <div class="form-group">
        <label for="">Kategori Layanan</label>
        <select name="service_category_id" id="service_category_id" class="form-control">
            <option value="">Pilih salah satu</option>
            @foreach ($category as $key => $value)
                <option value="{{ $value->id }}">{{ $value['name'] }}</option>
            @endforeach
        </select>
        <small class="text-danger service_category_id-invalid"></small>
    </div>
    <div class="form-group">
      <label for="">Kode Layanan Provider</label>
      <input type="text" name="provider_service_code" id="provider_service_code" class="form-control" value="{{ $service['provider_service_code'] }}" readonly>
      <small class="text-danger provider_service_code-invalid"></small>
    </div>
    <div class="form-group">
        <label for="">Nama Layanan</label>
        <input type="text" name="name" id="name" class="form-control" value="{{ $service['name'] }}">
        <small class="text-danger name-invalid"></small>
    </div>
    <div class="form-group">
        <label for="">Harga Layanan Provider</label>
        <input type="text" name="price_provider" id="price_provider" class="form-control" value="{{ $service['price'] }}" readonly>
        <small class="text-danger price_provider-invalid"></small>
    </div>
    <hr class="bg-primary border">
    <div class="form-group">
        <label for="">Tipe Profit</label>
        <select name="profit_type" id="profit_type" class="form-control">
            <option value="">Pilih salah satu...</option>
            <option value="flat">Flat</option>
            <option value="percent">Persen</option>
        </select>
        <small class="text-danger profit_type-invalid"></small>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">Harga Public</label>
                <input class="form-control" name="price[public]" type="number"
                    placeholder="Harga Public" value="{{ (int) $service['price'] }}">
                <small class="text-danger price_public-invalid"></small>
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">Profit Public</label>
                <input class="form-control" name="profit[public]" type="number"
                    placeholder="Profit Public" step=".01">
                <small class="text-danger profit_public-invalid"></small>
            </div>
        </div>
        <div class="col-md-12">
            <div class="mb-3">
                <label class="form-label">Total Harga Public</label>
                <input class="form-control" type="number" id="total_harga_public" readonly>
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">Harga Silver</label>
                <input class="form-control" name="price[silver]" type="number"  placeholder="Harga Silver" value="{{ $service['price'] }}">
                <small class="text-danger price_silver-invalid"></small>
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">Profit Silver</label>
                <input class="form-control" name="profit[silver]" type="number" placeholder="Profit Silver" step=".01">
                <small class="text-danger profit_silver-invalid"></small>
            </div>
        </div>
        <div class="col-md-12">
            <div class="mb-3">
                <label class="form-label">Total Harga Silver</label>
                <input class="form-control" type="number" id="total_harga_silver" readonly>
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">Harga Gold</label>
                <input class="form-control" name="price[gold]" type="number" placeholder="Harga Gold" value="{{ $service['price'] }}">
                <small class="text-danger price_gold-invalid"></small>
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">Profit Gold</label>
                <input class="form-control" name="profit[gold]" type="number" placeholder="Profit Gold" step=".01">
                <small class="text-danger profit_gold-invalid"></small>
            </div>
        </div>
        <div class="col-md-12">
            <div class="mb-3">
                <label class="form-label">Total Harga Gold</label>
                <input class="form-control" type="number" id="total_harga_gold" readonly>
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">Harga VIP</label>
                <input class="form-control" name="price[vip]" type="number" placeholder="Harga VIP" value="{{ $service['price'] }}">
                <small class="text-danger price_gold-invalid"></small>
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">Profit VIP</label>
                <input class="form-control" name="profit[vip]" type="number" placeholder="Profit VIP" step=".01">
                <small class="text-danger profit_gold-invalid"></small>
            </div>
        </div>
        <div class="col-md-12">
            <div class="mb-3">
                <label class="form-label">Total Harga VIP</label>
                <input class="form-control" type="number" id="total_harga_vip" readonly>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label>Status </label>
        <div class="custom-control custom-switch">
            <input type="checkbox" name="is_active" value="1" class="custom-control-input" id="isActive" checked>
            <label class="custom-control-label" for="isActive"></label>
        </div>
        <small class="text-danger is_active-invalid"></small>
    </div>
    <hr>
    <div class="text-right">
        <button type="reset" class="btn btn-danger"><i class="fa fa-redo"></i> Reset</button>
        <button type="submit" class="btn btn-success" name="button_form_modal"><i class="fa fa-check"></i>
            Submit</button>
    </div>

</form>
<script>
function convertPercent(x) {
    let percent = x / 100
    return percent;
}
function reset_button_modal(value = 0) {
    if (value == 0) {
        $('button[name="button_form_modal"]').attr('disabled', 'true');
        $('button[name="button_form_modal"]').text('');
        $('button[name="button_form_modal"]').append('<span class=\"spinner-grow spinner-grow-sm mb-1\"></span> Mohon tunggu...');
        $('button[type="reset"]').hide();
    } else {
        $('button[name="button_form_modal"]').removeAttr('disabled');
        $('button[name="button_form_modal"]').removeAttr('span');
        $('button[name="button_form_modal"]').text('');
        $('button[name="button_form_modal"]').append('<i class=\"fa fa-check\"></i> Submit');
        $('button[type="reset"]').show();
    }
}
$(function() {
    $("#service-form-modal").on('submit', function(e) {
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
                    $('#modal-service').modal('hide');
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
for (let i = 0; i < level.length; i++) {
    $('input[type=number][name="profit[' + level[i] + ']"]').keyup(function() {
        let price = $('input[type=number][name="price[' + level[i] + ']"]').val()
        let profit = $('input[type=number][name="profit[' + level[i] + ']"]').val()
        let total;
        if ($('select[name=profit_type] option:selected').val() == 'flat') {
            total  = Math.floor(price) + Math.floor(profit)
        } else {
            total = Math.floor(price) + (Math.floor(price) * convertPercent(Math.floor(profit)))
        }
        console.log(profit);
        $('#total_harga_' + level[i]).val(total)
    });
}
</script>
