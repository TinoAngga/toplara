<div class="container">
    @if (session()->has('alertMsg'))

    <div class="row my-5">
        <div class="col-md-12">
            <div class="alert alert-{{ session('alertClass') }} alert-dismissible fade show" role="alert">
                <h4 class="alert-heading mx-4 pt-2">{{ session('alertTitle') }}</h4>
                <div class="alert-body mx-4 pb-2">
                    {!! session('alertMsg') !!}
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    </div>
    @endif

</div>
