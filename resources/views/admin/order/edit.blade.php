<form method="post" action="{{ route('admin.'. request()->segment(2) .'.update', $order->id) }}" id="main-form-modal">
    @csrf
    @method('PUT')
    <div class="form-group">
        <label>Keterangan </label>
        <textarea type="text" class="form-control" name="provider_order_description">{{ $order->provider_order_description }}</textarea>
        <small class="text-danger provider_order_description-invalid"></small>
    </div>
    <hr />
    <div class="text-right">
        <button type="reset" class="btn btn-danger"><i class="fa fa-redo"></i> Reset</button>
        <button type="submit" class="btn btn-success" name="button_form_modal"><i class="fa fa-check"></i>
            Submit</button>
    </div>
</form>
<script src="{{ asset('custom/main.custom.js') }}"></script>
