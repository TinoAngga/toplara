@if(user())
<div class="row justify-content-center">
    <div class="col-md-12">
        <nav class="navbar navbar-expand-lg navbar-dark mb-3" style="background: var(--theme-color-2);border-radius: 10px;">
            <div class="container">
                <button class="navbar-toggler mb-3" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu-custom"
                    aria-controls="navbar-menu-custom" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse navbar-menu-custom" id="navbar-menu-custom" style="background: transparent;">
                    <div class="navbar-nav">
                        <a style="margin-right: .6rem !important" class="nav-link @if (request()->segment(1) === 'account') active @endif" href="{{ url('account') }}">
                            <i class="mdi mdi-view-dashboard-outline mr-2"></i> Dashboard
                        </a>
                        <a style="margin-right: .6rem !important" class="nav-link @if (request()->segment(2) === 'search') active @endif" href="{{ url('order/search') }}">
                            <i class="mdi mdi-feature-search-outline mr-2"></i> Cari Pesanan
                        </a>
                        <a style="margin-right: .6rem !important" class="nav-link @if (request()->segment(1) === 'order' AND request()->segment(2) === 'history') active @endif" href="{{ url('order/history') }}">
                            <i class="mdi mdi-cart-outline mr-2"></i> Riwayat Pesanan
                        </a>
                        <a style="margin-right: .6rem !important" class="nav-link @if (request()->segment(1) === 'deposit' AND request()->segment(2) === null) active @endif" href="{{ url('deposit') }}">
                            <i class="mdi mdi-credit-card-outline mr-2"></i> Deposit
                        </a>
                        <a style="margin-right: .6rem !important" class="nav-link @if (request()->segment(1) === 'deposit' AND request()->segment(2) === 'history') active @endif" href="{{ url('deposit/history') }}">
                            <i class="mdi mdi-credit-card-outline mr-2"></i> Riwayat Deposit
                        </a>
                        <a style="margin-right: .6rem !important" class="nav-link" href="javascript:void()" onclick="logout()">
                            <i class="mdi mdi-logout-variant mr-2"></i> Logout
                        </a>
                    </div>
                </div>

            </div>
        </nav>

    </div>
</div>

@endif
