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
                    <div class="form-group mb-3">
                        <label class="text-white">Total Pertandingan</label>
                        <input type="number" placeholder="Ex: 910" class="form-control" id="total-match">
                    </div>
                    <div class="form-group mb-3">
                        <label class="text-white">Total Win Rate</label>
                        <input type="number" placeholder="Ex: 99%" class="form-control" id="total-win-rate">
                    </div>
                    <div class="mb-3">
                        <button class="btn btn-primary d-block w-100 mt-4" type="button" id="count-win-lose" onclick="submitWinLose();">Hitung</button>
                    </div>
                    <div class="mb-0">
                        <span id="result-text" class="text-center d-block">Hasil akan tampil disini</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@push('script')
<script>

var text;
function submitWinLose() {
    const resultText = document.querySelector("#result-text");
    const totalMatch = parseFloat(document.querySelector("#total-match").value);
    const TotalWinrate = parseFloat(document.querySelector("#total-win-rate").value);

    const countWin = win(totalMatch, TotalWinrate);
    const countLose = lose(totalMatch, TotalWinrate);

    if (isNaN(totalMatch) || isNaN(TotalWinrate)) {
        text = `Mohon isi semua input.`;
        return resultText.innerHTML = text;
    } else if (totalMatch < 0 || TotalWinrate < 0) {
        text = `Mohon isi semua input.`;
        return resultText.innerHTML = text;
    } else if (totalMatch % 1 != 0) {
        text = `<b>Total Pertandingan</b> harus bilangan bulat.`;
        return resultText.innerHTML = text;
    } else if (TotalWinrate > 100) {
        text = `<b>Total Win Rate</b> tidak boleh lebih dari <b>100%.</b>`;
        return resultText.innerHTML = text;
    } else {
        text = `Total Win: <b>${countWin}</b> match.<br> Total Lose: <b>${countLose}</b> match.`;
        return resultText.innerHTML = text;
    }
}
function win(totalMatch, totalWinRate) {
    return Math.round(totalMatch * (totalWinRate / 100));
}

function lose(totalMatch, totalWinRate) {
    return Math.round(totalMatch - (totalMatch * (totalWinRate / 100)));
}
</script>
@endpush
@endsection
