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
                    <form action="#">
                        <div class="form-group mb-3">
                            <label class="text-white">Total Pertandingan</label>
                            <input type="number" placeholder="Ex: 910" class="form-control" id="total-match">
                        </div>
                        <div class="form-group mb-3">
                            <label class="text-white">Total Win Rate</label>
                            <input type="number" placeholder="Ex: 99%" class="form-control" id="total-win-rate">
                        </div>
                        <div class="form-group mb-3">
                            <label class="text-white">Win Rate Yang Diinginkan</label>
                            <input type="number" placeholder="Ex: 90%" class="form-control" id="total-req-win">
                        </div>
                        <div class="form-group mb-3">
                            <button class="btn btn-primary d-block w-100 mt-4" type="button" id="count-win-rate" onclick="submitWinRate();">Hitung</button>
                        </div>
                        <div class="mb-0">
                            <span id="result-text" class="text-center d-block">Hasil akan tampil disini</span>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@push('script')
<script>

var text;
function submitWinRate() {
    const countWinRate = document.querySelector("#count-win-rate");
    const resultText = document.querySelector("#result-text");
    const totalMatch = parseFloat(document.querySelector("#total-match").value);
    const totalWinRate = parseFloat(document.querySelector("#total-win-rate").value);
    const totalReqWin = parseFloat(document.querySelector("#total-req-win").value);

    const result = rumus(totalMatch, totalWinRate, totalReqWin);
    const resultLose = rumusLose(totalMatch, totalWinRate, totalReqWin);

    if (isNaN(totalMatch) || isNaN(totalWinRate) || isNaN(totalReqWin)) {
        text = `Mohon isi semua input.`;
        console.log(text);
        return resultText.innerHTML = text;
    } else if (totalMatch < 0 || totalWinRate < 0 || totalReqWin < 0) {
        text = `Mohon isi semua input.`;
        return resultText.innerHTML = text;
    } else if (totalMatch % 1 != 0) {
        text = `<b>Total Pertandingan</b> harus bilangan bulat.`;
        return resultText.innerHTML = text;
    } else if (totalWinRate == 100 && totalReqWin == 100) {
        text = `Kamu memerlukan sekitar <b>0</b> win tanpa lose untuk mendapatkan win rate <b>${totalReqWin}%.</b>`;
        return resultText.innerHTML = text;
    } else if (totalReqWin > 100 || totalWinRate > 100) {
        text = `<b>Total Win Rate</b> tidak boleh lebih dari <b>100%.</b>`;
        return resultText.innerHTML = text;
    } else if (totalWinRate > totalReqWin) {
        text = `Kamu memerlukan sekitar <b>${resultLose}</b> lose tanpa win untuk mendapatkan win rate <b>${totalReqWin}%.</b>`;
        return resultText.innerHTML = text;
    } else if (totalMatch == 0 && totalWinRate == 0 && totalReqWin == 100) {
        text = `Kamu memerlukan sekitar <b>1</b> win tanpa lose untuk mendapatkan win rate <b>${totalReqWin}%.</b>`;
        return resultText.innerHTML = text;
    } else if (totalReqWin == 100) {
        text = `Kamu tidak akan bisa mencapai <b>100% win rate.</b>`;
        return resultText.innerHTML = text;
    } else if (result >= 100000) {
        text = `Kamu memerlukan sekitar lebih dari <b>100.000</b> win tanpa lose untuk mendapatkan win rate <b>${totalReqWin}%.</b>`;
        return resultText.innerHTML = text;
    } else {
        console.log(text);
        text = `Kamu memerlukan sekitar <b>${result}</b> win tanpa lose untuk mendapatkan win rate <b>${totalReqWin}%.</b>`;
        console.log(text);
        return resultText.innerHTML = text;
    }
}
function rumus(totalMatch, totalWinRate, totalReqWin) {
    let tWin = totalMatch * (totalWinRate / 100);
    let tLose = totalMatch - tWin;
    let sisaWr = 100 - totalReqWin;
    let wrResult = 100 / sisaWr;
    let seratusPersen = tLose * wrResult;
    let final = seratusPersen - totalMatch;
    return Math.round(final);
}

function rumusLose(totalMatch, totalWinRate, totalReqWin) {
    let totalWin = (totalMatch * totalWinRate) / 100;
    let win = (totalWin / (totalReqWin / 100)) - totalMatch;
    return Math.round(win);
}
</script>
@endpush
@endsection
