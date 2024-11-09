<form method="post" action="{{ route('admin.'. request()->segment(2) .'.update', $provider->id) }}" id="main-form-modal">
    @csrf
    @method('PUT')
    <div class="form-group">
        <label>Nama </label>
        <input type="text" class="form-control" name="name" value="{{ $provider->name }}">
        <small class="text-danger name-invalid"></small>
    </div>
    <div class="form-group">
        <label>Manual ? </label>
        <div class="custom-control custom-switch">
            <input type="checkbox" name="is_manual" value="1" class="custom-control-input" id="isManual" {{ ($provider->is_manual == 1) ? 'checked' : '' }}>
            <label class="custom-control-label" for="isManual"></label>
        </div>
        <small class="text-danger is_manual-invalid"></small>
    </div>
    <hr>
    <div class="{{ ($provider->is_manual == 1) ? 'd-none' : '' }}" id="form-required">
        <div class="form-group">
            <label>API Username / API ID <span class="text-danger">* Optional</span></label>
            <input type="text" class="form-control" name="api_username" value="{{ $provider->api_username }}">
            <small class="text-danger api_username-invalid"></small>
        </div>
        <div class="form-group">
            <label>API Key </label>
            <input type="text" class="form-control" name="api_key" value="{{ $provider->api_key }}">
            <small class="text-danger api_key-invalid"></small>
        </div>
        <div class="form-group">
            <label>API Additional <span class="text-danger">* Optional</span></label>
            <input type="text" class="form-control" name="api_additional" value="{{ $provider->api_additional }}">
            <small class="text-danger api_additional-invalid"></small>
        </div>
        <div class="form-group">
            <label>API URL Order </label>
            <input type="text" class="form-control" name="api_url_order" value="{{ $provider->api_url_order }}">
            <small class="text-danger api_url_order-invalid"></small>
        </div>
        <div class="form-group">
            <label>API URL Status </label>
            <input type="text" class="form-control" name="api_url_status" value="{{ $provider->api_url_status }}">
            <small class="text-danger api_url_status-invalid"></small>
        </div>
        <div class="form-group">
            <label>API URL Service </label>
            <input type="text" class="form-control" name="api_url_service" value="{{ $provider->api_url_service }}">
            <small class="text-danger api_url_service-invalid"></small>
        </div>
        <div class="form-group">
            <label>API URL Profile </label>
            <input type="text" class="form-control" name="api_url_profile" value="{{ $provider->api_url_profile }}">
            <small class="text-danger api_url_profile-invalid"></small>
        </div>
        <div class="form-group">
            <label>Peringatan Saldo </label>
            <input type="text" class="form-control" name="api_balance_alert" value="{{ $provider->api_balance_alert }}">
            <small class="text-danger api_balance_alert-invalid"></small>
        </div>
    </div>
    <div class="form-group">
        <label>Auto Update Layanan</label>
        <div class="custom-control custom-switch">
            <input type="checkbox" name="is_auto_update" value="1" class="custom-control-input" id="isAutoUpdate" {{ ($provider->is_auto_update == 1) ? 'checked' : '' }}>
            <label class="custom-control-label" for="isAutoUpdate"></label>
        </div>
        <small class="text-danger is_auto_update-invalid"></small>
    </div>
    <div class="form-group">
        <label>Status </label>
        <div class="custom-control custom-switch">
            <input type="checkbox" name="is_active" value="1" class="custom-control-input" id="isActive" {{ ($provider->is_active == 1) ? 'checked' : '' }}>
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
    $('input[name="is_manual"]').change(function (e) {
        e.preventDefault();
        if ($('input[name="is_manual"]').is(':checked')) {
			$('#form-required').addClass('d-none');
		} else {
			$('#form-required').removeClass('d-none');
		}
    });
</script>
<script src="{{ asset('custom/main.custom.js') }}"></script>
