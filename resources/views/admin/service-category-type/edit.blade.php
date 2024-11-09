<form method="post" action="{{ route('admin.'. request()->segment(2) .'.update', $serviceCategoryType->id) }}" id="main-form-modal" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="form-group">
        <label>Nama </label>
        <input type="text" class="form-control" value="{{ ucwords(strtolower(str_replace('-', ' ', $serviceCategoryType->name))) }}" readonly>
    </div>
    <hr>
    <div class="form-group">
        <label>Ikon</label>
        <input type="text" class="form-control" name="icon" value="{{ $serviceCategoryType->icon }}" placeholder="ex: fa fa-phone, mdi mdi-cart">
        <small class="text-danger icon-invalid"></small>
    </div>
    <div class="form-group">
        <label>Posisi</label>
        <input type="numeric" class="form-control" name="position" value="{{ $serviceCategoryType->position }}" placeholder="ex: 1, 2, 3">
        <small class="text-danger position-invalid"></small>
    </div>
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
