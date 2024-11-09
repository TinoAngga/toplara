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
                                    <h6>Geser Sesuai Poin Zodiac Anda </h6>
                                </div>
                                <div class="card-body">
                                    <div class="form-group mb-3">
                                        <label class="text-white fw-bold">Total Point Zodiac : <h4 id="total-point-zodiac" class="fw-bold" style="color: var(--flat-color-1);">0</h4></label>
                                        <input type="range" class="slider-range" min="0" max="199" id="cal-magic-wheel" onchange="showValue(this.value);" value="0">
                                        Membutuhkan Maksimal : <span id="point-result" class="fw-bold" style="color: var(--flat-color-1);">0</span> <i class="fas fa-gem fw-bold" style="color: var(--flat-color-1);"></i>
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

function showValue(x) {
    if (x < 90) {
        result = Math.ceil((2000 - x * 20) * 850 / 1000);
    }
    if (x > 89) {
        result = Math.ceil((2000 - x * 20));
    }
    document.getElementById("total-point-zodiac").innerHTML = x;
    document.getElementById("point-result").innerHTML = result;
}

</script>
@endpush
@endsection
