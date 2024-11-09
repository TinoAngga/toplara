<form method="post" action="{{ route('admin.'. request()->segment(2) .'.store') }}" id="main-form-modal" enctype="multipart/form-data">
    @csrf
    @method('POST')
    <div class="form-group">
        <label for="">Tipe Layanan</label>
        <select name="service_type" id="service_type" class="form-control">
            <option value="">Pilih tipe layanan...</option>
            @foreach ($serviceType as $key => $value)
                <option value="{{ $value->slug }}">{{ ucwords(str_replace('-', ' ', $value->name)) }}</option>
            @endforeach
        </select>
        <small class="text-danger service_type-invalid"></small>
    </div>
    <div class="form-group">
        <label>Nama </label>
        <input type="text" class="form-control" name="name" value="{{ old('name') }}">
        <small class="text-danger name-invalid"></small>
    </div>
    <hr>
    <div class="form-group">
        <label>Gambar </label>
        <input type="file" class="dropify" name="img" value="{{ old('img') }}">
        <small class="text-danger img-invalid"></small>
    </div>
    <div class="form-group">
        <label>Gambar Petunjuk </label>
        <input type="file" class="dropify" name="guide_img" value="{{ old('guide_img') }}">
        <small class="text-danger guide_img-invalid"></small>
    </div>
    <div class="form-group">
        <label for="">Deskripsi</label>
        <textarea name="description" class="form-control summernote">{{ old('description') }}</textarea>
        <small class="text-danger description-invalid"></small>
    </div>
    <div class="form-group">
        <label for="">Information</label>
        <textarea name="information" class="summernote">{{ old('information') }}</textarea>
        <small class="text-danger information-invalid"></small>
    </div>
    <div class="form-group">
        <label>Check ID </label>
        <div class="custom-control custom-switch">
            <input type="checkbox" name="is_check_id" value="1" class="custom-control-input" id="checkID">
            <label class="custom-control-label" for="checkID"></label>
        </div>
        <small class="text-danger is_check_id-invalid"></small>
    </div>
    <div class="form-group d-none" id="input_get_nickname_code">
        <label>Kode Validasi Nickname</label>
        <input type="text" class="form-control" name="get_nickname_code" value="{{ old('get_nickname_code') }}">
        <small class="text-danger get_nickname_code-invalid"></small>
    </div>
    <div class="form-group">
        <label>Zone ID </label>
        <div class="custom-control custom-switch">
            <input type="checkbox" name="is_additional_data" value="1" class="custom-control-input" id="zoneID" checked>
            <label class="custom-control-label" for="zoneID"></label>
        </div>
        <small class="text-danger is_additional_data-invalid"></small>
    </div>
    <h5 class="border-bottom">Setting Form</h5>
    <div class="form-group">
        <label>Placeholder Data / User ID <i class="text-danger">* Opsional</i></label>
        <input type="text" class="form-control" name="form_setting[placeholder_data]" placeholder="ex: masukkan user id">
        <small class="text-danger form_setting_placeholder_data-invalid"></small>
    </div>
    <div class="form-group">
        <label>Placeholder Additional Data / Zone ID <i class="text-danger">* Opsional</i></label>
        <input type="text" class="form-control" name="form_setting[placeholder_additional_data]" value="" placeholder="ex: masukkan zone id">
        <small class="text-danger form_setting_placeholder_additional_data-invalid"></small>
    </div>
    <div class="form-group">
        <label>Server List Additional Data <i class="text-danger">* Kosongkan jika tidak membutuhkan list server, ex: asia|Asia</i></label>
        <textarea cols="30" rows="10" class="form-control" name="form_setting[form_additional_data]" value=""
            placeholder="ex: asia|Asia. gunakan ( | ) dan enter untuk membuat baris baru"
        ></textarea>
        <small class="text-danger form_setting_placeholder_additional_data-invalid"></small>
    </div>
    <div class="border-bottom"></div>
    <div class="form-group">
        <label>Status </label>
        <div class="custom-control custom-switch">
            <input type="checkbox" name="is_active" value="1" class="custom-control-input" id="isActive" checked>
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
    $('input[name="is_check_id"]').change(function (e) {
        e.preventDefault();
        if ($('input[name="is_check_id"]').is(':checked')) {
			$('#input_get_nickname_code').removeClass('d-none');
		} else {
			$('#input_get_nickname_code').addClass('d-none');
		}
    });
</script>
<script src="{{ asset('custom/main.custom.js') }}"></script>
