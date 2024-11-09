<form action="{{ route('admin.'.request()->segment(2).'.store') }}" method="post" id="main-form-modal">
    @method('POST')
    <div class="form-group">
        <label for="">Provider</label>
        <select name="provider_id" id="provider_id" class="form-control">
            <option value="">Pilih provider...</option>
            @foreach ($provider as $key => $value)
            <option value="{{ $value->id }}">{{ $value->name }}</option>
            @endforeach
        </select>
        <small class="text-danger provider_id-invalid"></small>
    </div>
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
    <div class="form-group">
        <label for="">Sub Kategori Layanan</label>
        <select name="sub_category" id="sub_category" class="form-control select2">
            <option value="">Pilih sub kategori...</option>
            @foreach ($subCategory as $key => $value)
                <option value="{{ $value->name }}">{{ $value->name }}</option>
            @endforeach
        </select>
        <small class="text-danger sub_category-invalid"></small>
    </div>
    <div class="form-group">
      <label for="">Kode Layanan Provider</label>
      <input type="text" name="provider_service_code" id="provider_service_code" class="form-control" value="{{ old('provider_service_code') }}">
      <small class="text-danger provider_service_code-invalid"></small>
    </div>
    <div class="form-group">
        <label for="">Nama Layanan</label>
        <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}">
        <small class="text-danger name-invalid"></small>
    </div>
    <hr class="bg-primary border">
    <div class="form-group">
        <label>Rate Koin </label>
        <div class="custom-control custom-switch">
            <input type="checkbox" name="is_rate_coin" value="1" class="custom-control-input" id="isRateCoin">
            <label class="custom-control-label" for="isRateCoin"></label>
        </div>
        <small class="text-danger is_rate_coin-invalid"></small>
    </div>
    <div class="d-none" id="form-rate-coin">
        <div class="form-group">
            <label for="">Rate Koin</label>
            <input type="number" name="rate_coin" id="rate_coin" class="form-control" value="{{ old('rate_coin') }}">
            <small class="text-danger rate_coin-invalid"></small>
        </div>
        <div class="form-group">
            <label for="">Harga Koin</label>
            <input type="number" name="price_rate_coin" id="price_rate_coin" class="form-control" value="{{ old('price_rate_coin') }}">
            <small class="text-danger price_rate_coin-invalid"></small>
        </div>
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
    <div class="form-group">
        <label for="">Harga Layanan Provider</label>
        <input type="number" name="price_provider" id="price_provider" class="form-control">
        <small class="text-danger price_provider-invalid"></small>
    </div>
    <div class="row">
        @foreach (config('constants.options.member_level') as $key => $value)
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">Harga {{ $value }}</label>
                <input class="form-control" name="price[{{ $key }}]" type="number" placeholder="Harga {{ $value }}">
                <small class="text-danger price_{{ $key }}-invalid"></small>
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">Profit {{ $value }}</label>
                <input class="form-control" name="profit[{{ $key }}]" type="number" placeholder="Harga {{ $value }}">
                <small class="text-danger profit_{{ $key }}-invalid"></small>
            </div>
        </div>
        <div class="col-md-12">
            <div class="mb-3">
                <label class="form-label">Total Harga {{ $value }}</label>
                <input class="form-control" type="number" id="total_harga_{{ $key }}" readonly>
            </div>
        </div>
        @endforeach
    </div>
    <div class="form-group">
        <label>Status </label>
        <div class="custom-control custom-switch">
            <input type="checkbox" name="is_active" value="1" class="custom-control-input" id="isActive" checked>
            <label class="custom-control-label" for="isActive"></label>
        </div>
        <small class="text-danger is_active-invalid"></small>
    </div>
    <hr class="bg-primary border">
    <div class="text-right">
        <button type="reset" class="btn btn-danger"><i class="fa fa-redo"></i> Reset</button>
        <button type="submit" class="btn btn-success" name="button_form_modal"><i class="fa fa-check"></i>
            Submit</button>
    </div>

</form>

<script>
    $('input[name="is_rate_coin"]').change(function (e) {
        e.preventDefault();
        if ($('input[name="is_rate_coin"]').is(':checked')) {
			$('#form-rate-coin').removeClass('d-none');
		} else {
			$('#form-rate-coin').addClass('d-none');
		}
    });
    $(document).ready(function () {
        $('#price_rate_coin').keyup(function() {
            let rate_coin = $('#rate_coin').val()
            let price_rate_coin = $('#price_rate_coin').val()
            let countPrice = rate_coin * price_rate_coin
            $('input[type=number][name="price_provider"]').val(countPrice)
            for (let i = 0; i < level.length; i++) {
                $('input[type=number][name="price[' + level[i] + ']"]').val(countPrice)
            }
        });
        $('input[type=number][name="price_provider"]').keyup(function() {
            let price = $('input[type=number][name="price_provider"]').val()
            for (let i = 0; i < level.length; i++) {
                $('input[type=number][name="price[' + level[i] + ']"]').val(price)
            }
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
            $('#total_harga_' + level[i]).val(total)
        });
    }
</script>
<script src="{{ asset('custom/main.custom.js') }}"></script>
