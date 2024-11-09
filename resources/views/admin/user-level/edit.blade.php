<form method="post" action="{{ route('admin.'. request()->segment(2) .'.update', $userLevel->id) }}" id="main-form-modal">
    @csrf
    @method('PUT')
    <div class="form-group">
        <label class="form-label">Harga</label>
        <input class="form-control" name="price" type="number"
            placeholder="Harga" value="{{ $userLevel->price }}">
        <small class="text-danger price-invalid"></small>
    </div>
    <div class="form-group">
        <label class="form-label">Saldo Didapat</label>
            <input class="form-control" name="get_balance" type="number"
                placeholder="Saldo Didapat" value="{{ $userLevel->get_balance }}">
            <small class="text-danger get_balance-invalid"></small>
    </div>
    <div class="form-group">
        <label for="">Deskripsi</label>
        <textarea name="description" class="summernote">{{ $userLevel->description }}</textarea>
        <small class="text-danger description-invalid"></small>
    </div>
    <div class="text-right">
        <button type="reset" class="btn btn-danger"><i class="fa fa-redo"></i> Reset</button>
        <button type="submit" class="btn btn-success" name="button_form_modal"><i class="fa fa-check"></i>
            Submit</button>
    </div>
</form>>
<script src="{{ asset('custom/main.custom.js') }}"></script>
