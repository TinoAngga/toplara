<div class="navigationbar">
    <!-- Start container-fluid -->
    <div class="container-fluid">
        <!-- Start Horizontal Nav -->
        <nav class="horizontal-nav mobile-navbar fixed-navbar">
            <div class="collapse navbar-collapse" id="navbar-menu">
              <ul class="horizontal-menu">
                <li class="scroll"><a href="{{ url('/') }}"><i class="mdi mdi-view-dashboard-outline"></i><span>Halaman Utama</span></a></li>
                <li class="scroll"><a href="{{ route('order.search.get') }}"><i class="mdi mdi-feature-search-outline"></i><span>Cari pesanan</span></a></li>
                @guest
                <li class="scroll"><a href="{{ route('auth.login.get') }}"><i class="mdi mdi-login"></i><span>Login</span></a></li>
                <li class="scroll"><a href="{{ route('auth.register.get') }}"><i class="mdi mdi-account-plus-outline"></i><span>Register</span></a></li>
                <li class="scroll"><a href="{{ route('auth.forgot-password.get') }}"><i class="mdi mdi-lock-open-outline"></i><span>Lupa Password</span></a></li>
                @endguest
                <li class="scroll"><a href="{{ route('service.index') }}"><i class="mdi mdi-format-list-bulleted"></i><span>Daftar Layanan</span></a></li>
                @auth
                <li class="scroll"><a href="{{ route('order.history') }}"><i class="mdi mdi-cart-outline"></i><span>Riwayat Pesanan</span></a></li>
                <li class="scroll dropdown">
                    <a href="javascript:void();" class="dropdown-toggle" data-toggle="dropdown"><i class="mdi mdi-credit-card"></i><span>Deposit</span></a>
                    <ul class="dropdown-menu">
                        <li><a href="{{ route('deposit.index') }}"><i class="mdi mdi-circle"></i>Permintaan</a></li>
                        <li><a href="{{ route('deposit.history') }}"><i class="mdi mdi-circle"></i>Riwayat</a></li>
                    </ul>
                </li>
                <li class="scroll"><a href="{{ route('account.mutation') }}"><i class="mdi mdi-scale-balance"></i><span>Mutasi Saldo</span></a></li>
                <li class="scroll"><a href="{{ route('account.index') }}"><i class="mdi mdi-account-outline"></i><span>Profil</span></a></li>
                <li class="scroll"><a href="javascript:void(0)" onclick="logout()"><i class="mdi mdi-logout"></i><span>Logout</span></a></li>
                @endauth

              </ul>
            </div>
        </nav>
        <!-- End Horizontal Nav -->
    </div>
    <!-- End container-fluid -->
</div>
<!-- End Navigationbar -->
