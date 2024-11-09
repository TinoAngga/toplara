<form method="post" action="{{ route('admin.'. request()->segment(2) .'.update', $banner->id) }}" id="main-form-modal"  enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="form-group">
        <label>Nama </label>
        <input type="text" class="form-control" name="name" value="{{ $banner->name }}">
        <small class="text-danger name-invalid"></small>
    </div>
    <div class="form-group">
        <label>Url Produk </label>
        <input type="text" class="form-control" name="url" value="{{ $banner->url }}">
        <small class="text-danger url-invalid"></small>
    </div>
    <hr>
    <div class="form-group">
        <label>Gambar </label>
        <input type="file" class="dropify" name="value"
            data-default-file="{{ asset(config('constants.options.asset_img_banner') . $banner->value) }}"
            value="{{ asset(config('constants.options.asset_img_banner') . $banner->value) }}
        ">
        <small class="text-danger value-invalid"></small>
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
</script>
<script src="{{ asset('custom/main.custom.js') }}"></script>
