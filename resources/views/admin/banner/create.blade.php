<form method="post" action="{{ route('admin.'. request()->segment(2) .'.store') }}" id="main-form-modal" enctype="multipart/form-data">
    @csrf
    @method('POST')
    <div class="form-group">
        <label>Nama </label>
        <input type="text" class="form-control" name="name" value="{{ old('name') }}">
        <small class="text-danger name-invalid"></small>
    </div>
    <div class="form-group">
        <label>Url Produk </label>
        <input type="text" class="form-control" name="url" value="{{ old('url') }}">
        <small class="text-danger url-invalid"></small>
    </div>
    <hr>
    <div class="form-group">
        <label>Gambar </label>
        <input type="file" class="dropify" name="value" value="{{ old('value') }}">
        <small class="text-danger img-invalid"></small>
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
