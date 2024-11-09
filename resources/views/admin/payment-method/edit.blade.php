<form method="post" action="{{ route('admin.'. request()->segment(2) .'.update', $paymentMethod->id) }}" id="main-form-modal">
    @csrf
    @method('PUT')
    <div class="form-group">
        <label>Tipe </label>
        <select name="type" id="type" class="form-control">
            <option value="">Pilih salah satu</option>
            @foreach ($type as $key => $value)
                <option value="{{ $key }}" {{ ($paymentMethod->type == $key) ? 'selected' : '' }}> {{ $value }}</option>
            @endforeach
        </select>
        <small class="text-danger type-invalid"></small>
    </div>
    <div class="form-group">
        <label>Nama </label>
        <input type="text" class="form-control" name="name" value="{{ $paymentMethod->name }}">
        <small class="text-danger name-invalid"></small>
    </div>
    <hr>
    <div class="row">
        <div class="form-group col-md-6">
            <label>Fee <span class="text-danger"> * Optional</span></label>
            <input type="number" class="form-control" name="fee" value="{{ $paymentMethod->fee }}">
            <small class="text-danger fee-invalid"></small>
        </div>
        <div class="form-group col-md-6">
            <label>Fee persen <span class="text-danger"> * Optional (Hanya angka saja)</span></label>
            <input type="number" class="form-control" name="fee_percent" value="{{ $paymentMethod->fee_percent }}"  min="0" step="0.01">
            <small class="text-danger fee_percent-invalid"></small>
        </div>
    </div>
    <hr>
    <div class="form-group">
        <label>Gambar </label>
        <input type="file" class="dropify" name="img"
        data-default-file="{{ asset(config('constants.options.asset_img_payment_method') . $paymentMethod->img) }}"
        value="{{ asset(config('constants.options.asset_img_payment_method') . $paymentMethod->img) }}"
        >
        <small class="text-danger img-invalid"></small>
    </div>
    <hr>
    <div class="form-group">
        <label for="">Deskripsi</label>
        <textarea name="description" class="form-control summernote">{{ $paymentMethod->description }}</textarea>
        <small class="text-danger description-invalid"></small>
    </div>
    <div class="form-group">
        <label for="">Information</label>
        <textarea name="information" class="summernote">{{ $paymentMethod->information }}</textarea>
        <small class="text-danger information-invalid"></small>
    </div>
    <div class="row">
        <div class="form-group col-md-6">
            <label>Minimal Nominal Pembelian </label>
            <input type="number" class="form-control" name="min_amount" value="{{ $paymentMethod->min_amount }}" min="0" step="1">
            <small class="text-danger min_amount-invalid"></small>
        </div>
        <div class="form-group col-md-6">
            <label>Maksimal Nominal Pembelian </label>
            <input type="number" class="form-control" name="max_amount" value="{{ $paymentMethod->max_amount }}" min="0" step="1">
            <small class="text-danger max_amount-invalid"></small>
        </div>
    </div>
    <hr>
    <div class="form-group">
        <label>QRCode / QRIS ? </label>
        <div class="custom-control custom-switch">
            <input type="checkbox" name="is_qrcode" value="1" class="custom-control-input" id="is_qr_code" {{ $paymentMethod->is_qrcode == 1 ? 'checked' : '' }}>
            <label class="custom-control-label" for="is_qr_code"></label>
        </div>
        <small class="text-danger is_qrcode-invalid"></small>
    </div>
    <div class="form-group {{ ($paymentMethod->is_qrcode == 1) ? '' : 'd-none' }}" id="form-qrcode">
        <label>Gambar QRCode / QRIS</label>
        <input type="file" class="dropify" name="qrcode"
        data-default-file="{{ asset(config('constants.options.asset_img_qr_code') . $paymentMethod->qrcode) }}"
        value="{{ asset(config('constants.options.asset_img_qr_code') . $paymentMethod->qrcode) }}"
        >
        <small class="text-danger qrcode-invalid"></small>
    </div>
    <hr>
        <div class="row">
            <div class="form-group col-md-6">
                <label for="">Waktu mulai</label>
                <input type="time" name="time_used" id="time_used" class="form-control" value="{{ $paymentMethod->time_used ? date('H:i', strtotime($paymentMethod->time_used)) : '' }}">
                <small class="text-danger time_used-invalid"></small>
            </div>
            <div class="form-group col-md-6">
                <label for="">Waktu berhenti</label>
                <input type="time" name="time_stopped" id="time_stopped" class="form-control" value="{{ $paymentMethod->time_stopped ? date('H:i', strtotime($paymentMethod->time_stopped)) : '' }}">
                <small class="text-danger time_stopped-invalid"></small>
            </div>
        </div>
    <hr>
    <div class="form-group">
        <label>Payment Gateway ?</label>
        <div class="custom-control custom-switch">
            <input type="checkbox" name="is_payment_gateway" value="1" class="custom-control-input" id="is_payment_gateway" {{ !is_null($paymentMethod->payment_gateway) ? 'checked' : '' }}>
            <label class="custom-control-label" for="is_payment_gateway"></label>
        </div>
        <small class="text-danger is_payment_gateway-invalid"></small>
    </div>
    <div class="{{ !is_null($paymentMethod->payment_gateway) ? '' : 'd-none' }}" id="form-payment-gateway">
        <div class="form-group">
            <label>Payment Gateway </label>
            <select name="payment_gateway" id="payment_gateway" class="form-control">
                <option value="">Pilih salah satu</option>
                @foreach ($payment_gateway as $key => $value)
                    <option value="{{ $key }}" {{ ($paymentMethod->payment_gateway == $key) ? 'selected' : '' }}> {{ $value }}</option>
                @endforeach
            </select>
            <small class="text-danger payment_gateway-invalid"></small>
        </div>
        <div class="form-group">
            <label>Payment Gateway Code </label>
            <input type="text" class="form-control" name="payment_gateway_code" value="{{ $paymentMethod->payment_gateway_code }}">
            <small class="text-danger payment_gateway_code-invalid"></small>
        </div>
    </div>
    <div class="form-group">
        <label>Manual </label>
        <div class="custom-control custom-switch">
            <input type="checkbox" name="is_manual" value="1" class="custom-control-input" id="is_manual" {{ $paymentMethod->is_manual == 1 ? 'checked' : '' }}>
            <label class="custom-control-label" for="is_manual"></label>
        </div>
        <small class="text-danger is_active-invalid"></small>
    </div>
    <div class="form-group">
        <label>Untuk Publik ? </label>
        <div class="custom-control custom-switch">
            <input type="checkbox" name="is_public" value="1" class="custom-control-input" id="is_public" {{ $paymentMethod->is_public == 1 ? 'checked' : '' }}>
            <label class="custom-control-label" for="is_public"></label>
        </div>
        <small class="text-danger is_public-invalid"></small>
    </div>
    <div class="form-group">
        <label>Status </label>
        <div class="custom-control custom-switch">
            <input type="checkbox" name="is_active" value="1" class="custom-control-input" id="isActive" {{ $paymentMethod->is_active == 1 ? 'checked' : '' }}>
            <label class="custom-control-label" for="isActive"></label>
        </div>
        <small class="text-danger is_active-invalid"></small>
    </div>
    <hr />
    <div class="text-right">
        <button type="reset" class="btn btn-danger"><i class="fa fa-redo"></i> Reset</button>
        <button type="submit" class="btn btn-success" name="button_form_modal"><i class="fa fa-check"></i>
            Submit</button>
    </div>
</form>
<script>
    $('.dropify').dropify({
        messages: {
            'default': 'Drag and drop a file here or click',
            'replace': 'Drag and drop or click to replace',
            'remove': 'Remove',
            'error': 'Ooops, something wrong happended.'
        }
    });
    $('input[name="is_payment_gateway"]').change(function (e) {
        e.preventDefault();
        if ($('input[name="is_payment_gateway"]').is(':checked')) {
			$('#form-payment-gateway').removeClass('d-none');
		} else {
			$('#form-payment-gateway').addClass('d-none');
		}
    });
    $('input[name="is_qrcode"]').change(function (e) {
        e.preventDefault();
        if ($('input[name="is_qrcode"]').is(':checked')) {
			$('#form-qrcode').removeClass('d-none');
		} else {
			$('#form-qrcode').addClass('d-none');
		}
    });
</script>
<script src="{{ asset('custom/main.custom.js') }}"></script>
