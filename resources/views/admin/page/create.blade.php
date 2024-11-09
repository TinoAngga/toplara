<form method="post" action="{{ route('admin.'. request()->segment(2) .'.store') }}" id="main-form-modal" enctype="multipart/form-data">
    @csrf
    @method('POST')
    <div class="form-group">
        <label>Judul </label>
        <input type="text" class="form-control" name="title" value="{{ old('title') }}">
        <small class="text-danger title-invalid"></small>
    </div>
    <hr>
    <div class="form-group">
        <label>Gambar </label>
        <input type="file" class="dropify" name="img" value="{{ old('img') }}">
        <small class="text-danger img-invalid"></small>
    </div>
    <div class="form-group">
        <label for="">Isi Konten</label>
        <textarea name="content" class="form-control summernote">{{ old('content') }}</textarea>
        <small class="text-danger content-invalid"></small>
    </div>
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
</script>
<script src="{{ asset('custom/main.custom.js') }}"></script>
