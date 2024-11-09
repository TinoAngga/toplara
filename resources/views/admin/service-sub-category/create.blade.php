<form method="post" action="{{ route('admin.'. request()->segment(2) .'.store') }}" id="main-form-modal" enctype="multipart/form-data">
    @csrf
    @method('POST')
    <div class="form-group">
        <label>Nama </label>
        <input type="text" class="form-control" name="name" value="{{ old('name') }}">
        <small class="text-danger name-invalid"></small>
    </div>
    <hr>
    <hr />
    <div class="text-right">
        <button type="reset" class="btn btn-danger"><i class="fa fa-redo"></i> Reset</button>
        <button type="submit" class="btn btn-success" name="button_form_modal"><i class="fa fa-check"></i>
            Submit</button>
    </div>
</form>
<script src="{{ asset('custom/main.custom.js') }}"></script>
