<!-- Dashboard Start -->
<div class="tab-pane fade show active" id="v-pills-dashboard" role="tabpanel" aria-labelledby="v-pills-dashboard-tab">
    <div class="card m-b-30 shadow">
        <div class="card-header bg-primary">
            <h5 class="card-title-custom mb-0  text-white"><i class="mdi mdi-view-dashboard-outline mr-2"></i> Dashboard</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <a href="{{ route('account.upgrade.get') }}" class="btn btn-primary btn-md shadow mb-2 float-start"><i class="mdi mdi-arrow-up-bold-box-outline mr-2"></i> Upgrade Level</a>
                    <a href="{{ route('account.upgrade.history') }}" class="btn btn-success btn-md shadow mb-2 float-end"><i class="mdi mdi-history mr-2"></i>Riwayat Upgrade Level</a>
                </div>
                <div class="col-md-12 py-4 mt-3">
                    <img src="{{ asset('cdn/profile.svg') }}" class="img-fluid mx-auto d-block" alt="profile">
                    <div class="text-center mt-2">
                        <h5 class="ft-1 text-shadow">{{ strtoupper(user()->username) }}</h5>
                        <p class="text-muted ft-1 text-shadow">{{ strtoupper(user()->level) }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Start row -->
    <div class="row">
        <!-- Start col -->
        <div class="col-lg-12 col-xl-4">
            <div class="card m-b-20 shadow">
                <div class="card-body">
                    <a href="{{ route('order.history') }}" style="text-decoration: none">
                        <div class="ecom-dashboard-widget">
                            <div class="media">
                                <div class="media-body">
                                    <h5><i class="mdi mdi-cart-outline mr-2"></i> Pesanan</h5>
                                    <h5 class="text-end" style="color: #edff00;">{{ user()->order->count() }}</h5>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        <!-- End col -->
        <!-- Start col -->
        <div class="col-lg-12 col-xl-4">
            <div class="card m-b-20 shadow">
                <div class="card-body">
                    <a href="{{ route('deposit.history') }}" style="text-decoration: none">
                        <div class="ecom-dashboard-widget">
                            <div class="media">
                                <div class="media-body">
                                    <h5><i class="mdi mdi-credit-card-outline mr-2"></i> Deposit</h5>
                                    <h5 class="text-end" style="color: #3dff00;">{{ user()->deposit->count() }}</h5>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        <!-- End col -->
        <!-- Start col -->
        <div class="col-lg-12 col-xl-4">
            <div class="card m-b-20 shadow">
                <div class="card-body">
                    <a href="{{ route('deposit.index') }}" style="text-decoration: none">
                        <div class="ecom-dashboard-widget">
                            <div class="media">
                                <i class="feather icon-credit-card"></i>
                                <div class="media-body">
                                    <h5><i class="mdi mdi-wallet mr-2"></i> Saldo</h5>
                                    <h5 class="text-end">Rp {{ currency(user()->balance) }}</h5>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        <!-- End col -->
    </div>
    <!-- End row -->
</div>
<!-- Dashboard End -->
