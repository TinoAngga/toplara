@if (session()->has('alertMsg'))
<div class="row">
    <div class="col-md-12">
        <div class="alert alert-{{ session('alertClass') }}" role="alert">
            <h4 class="alert-heading"><strong>Response:</strong> {{ session('alertTitle') }}</span></h4>
            <div class="alert-body">
                <strong>Message:</strong> {!! session('alertMsg') !!}</span>
            </div>
        </div>
    </div>
</div>
@endif
