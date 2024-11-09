<form method="post" action="{{ route('admin.'. request()->segment(2) .'.update', $serviceCategory->id) }}" id="main-form-modal" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="form-group">
        <label for="">Kategori Layanan</label>
        <select name="service_category_id" id="service_category_id" class="form-control">
            <option value="">Pilih kategori...</option>
            @foreach ($category as $key => $value)
                <option value="{{ $value->id }}" {{ ($value->id == $service->service_category_id) ? 'selected' : '' }}>{{ ucwords(str_replace('-', ' ', $value->service_type)) }} - {{ $value->name }}</option>
            @endforeach
        </select>
        <small class="text-danger service_category_id-invalid"></small>
    </div>
    <div class="form-group">
        <label>Nama </label>
        <input type="text" class="form-control" name="name" value="{{ $serviceCategory->name }}">
        <small class="text-danger name-invalid"></small>
    </div>
    <hr>
    <div class="text-right">
        <button type="reset" class="btn btn-danger"><i class="fa fa-redo"></i> Reset</button>
        <button type="submit" class="btn btn-success" name="button_form_modal"><i class="fa fa-check"></i>
            Submit</button>
    </div>
</form>
<script src="{{ asset('custom/main.custom.js') }}"></script>
