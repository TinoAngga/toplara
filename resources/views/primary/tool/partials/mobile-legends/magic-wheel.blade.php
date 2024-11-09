@section('title')
{{ getConfig('title') }} - {{ $page['title'] }}
@endsection
@extends('primary.layouts.app')
@section('style')
@endsection
@section('content')
<div class="container my-5 py-5">
    <div class="row">
        <div class="col-lg-12">
            <div class="card m-b-30">
                <div class="card-header bg-primary">
                    <h6 class="m-0 font-weight-bold text-white"><i class="mdi mdi-format-list-bulleted"></i> {{ $page['title'] }} </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h6>Geser Sesuai Point Magic Wheel Anda </h6>
                                </div>
                                <div class="card-body">
                                    <div class="form-group mb-3">
                                        <label class="text-white fw-bold">Total Magic Wheel : <h4 id="total-magic-wheel" class="fw-bold" style="color: var(--flat-color-1);">0</h4></label>
                                        <input type="range" class="slider-range" min="0" max="199" id="cal-magic-wheel" onchange="showValue(this.value);" value="0">
                                        Membutuhkan Maksimal : <span id="magic-value-result" class="fw-bold" style="color: var(--flat-color-1);">0</span> <i class="fas fa-gem fw-bold" style="color: var(--flat-color-1);"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h6>Kalkulator Magic Wheel</h6>
                                </div>
                                <div class="card-body">
                                    <p>Kalkulator Magic Wheel berfungsi untuk mengetahui total maksimal diamond yang kamu butuhkan untuk mendapatkan Skin Legend.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@push('script')
<script>

var text;
function showValue(x) {
    if (x < 196) {
        remain = 200 - x;
        count = Math.ceil(remain / 5);
        result = count * 270;
    }
    if (x > 195) {
        remain = 200 - x;
        result = remain * 60;
    }
    document.getElementById("total-magic-wheel").innerHTML = x;
    document.getElementById("magic-value-result").innerHTML = result;
}

</script>
@endpush
@endsection
