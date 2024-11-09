@extends('primary.layouts.app')
@section('title')
{{ getConfig('title') . ' - ' . $page['title'] }}
@endsection
@section('style')
<style>
    table {
        color: var(--font-theme-1) !important;
    }
</style>
@endsection
@section('content')
<div class="container my-5">
    @include('primary.layouts.app.menu.nav')
    <div class="row">
        @include('primary.account.partials.leftbar')
        <!-- Start col -->
        <div class="col-lg-7 col-xl-9">
            <div class="tab-content" id="v-pills-tabContent">
                @include('primary.account.partials.pills.dashboard')
                @include('primary.account.partials.pills.orders')
                @include('primary.account.partials.pills.wallet')
                @include('primary.account.partials.pills.profile')
                @include('primary.account.partials.pills.configuration-api')
                <!-- My Logout Start -->
                <div class="tab-pane fade" id="v-pills-logout" role="tabpanel" aria-labelledby="v-pills-logout-tab">
                    <div class="card m-b-30 shadow">
                        <div class="card-header bg-primary">
                            <h5 class="card-title mb-0 text-white"><i class="feather icon-log-out mr-2"></i> Logout</h5>
                        </div>
                        <div class="card-body">
                            <div class="row justify-content-center">
                                <div class="col-lg-6 col-xl-4">
                                    <div class="logout-content text-center my-5">
                                        <img src="{{ asset('assets/'.getConfig('main_template').'/images/ecommerce/logout.svg') }}"
                                            class="img-fluid mb-5" alt="logout">
                                        <h2 class="text-success">Logout ?</h2>
                                        <p class="my-4">Apakah anda yakin untuk keluar?</p>
                                        <div class="button-list">
                                            <a type="button" href="{{ route('logout') }}"
                                                class="btn btn-danger font-16 text-white font-weight-bold"><i
                                                    class="feather icon-check mr-2"></i>Ya</a>
                                            <a type="button"
                                                class="btn btn-success font-16 text-white font-weight-bold"><i
                                                    class="feather icon-x mr-2 "></i>Tidak</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- My Logout End -->
            </div>
        </div>
        <!-- End col -->
    </div>
</div>
@push('script')
<script>
function salin(text, label_text) {
    navigator.clipboard.writeText(text);
    Swal.fire('Berhasil!.', text, 'success');
}
</script>
@endpush
@endsection
