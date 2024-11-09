<nav class="navbar fixed-top navbar-expand-lg px-lg-3 navbar-dark shadow-sm navbar-custom">
    <div class="container">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
            data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
            aria-expanded="false" aria-label="Toggle navigation">
            <svg viewBox="0 0 100 80" width="30" height="30">
                <rect width="100" height="10" rx="10" fill="rgba(255,255,255,.1)"></rect>
                <rect y="30" width="100" height="10" rx="10" fill="rgba(255,255,255,.1)"></rect>
                <rect y="60" width="100" height="10" rx="10" fill="rgba(255,255,255,.1)"></rect>
            </svg>
        </button>
        <a class="navbar-brand" href="{{ url('/') }}">
            @if (getConfig('logo'))
                <img data-src="{{ asset(config('constants.options.asset_img_website') . getConfig('logo')) }}" alt="{{ getConfig('title') }}" class="lazyload" height="45px">
            @else
            <h4 class=""><i class="mdi mdi-gamepad"></i> {{ getConfig('title') ?? 'TopUpGame' }}</h4>
            @endif
        </a>
        <button class="btn btn-primary form-search-product-mobile" id="formSearchProductMobile" name="{{ rand() }}" type="button" aria-labelledby="labeldiv" title="Cari Produk.." aria-label="Cari Produk..">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
            </svg>
        </button>
        <div class="collapse navbar-collapse menu-utama justify-content-end" id="navbarSupportedContent">
            <ul class="navbar-nav ms-auto px-0 mb-2 mb-lg-0">
                <li class="nav-item p-0 @if (request()->segment(1) == null || !in_array(request()->segment(1), ['service', 'account', 'auth', 'register', 'order', 'tools', 'page']) ) active @endif">
                    <a class="nav-link @if (request()->segment(1) == null || !in_array(request()->segment(1), ['service', 'account', 'auth', 'register', 'order', 'tools', 'page']) ) active @endif" aria-current="page" href="{{ url('/') }}">
                        Home
                    </a>
                </li>
                <li class="nav-item p-0 @if (request()->segment(2) == 'search') active @endif">
                    <a class="nav-link @if (request()->segment(2) == 'search') active @endif" aria-current="page" href="{{ route('order.search.get') }}">
                        Cari Pesanan
                    </a>
                </li>
                <li class="nav-item p-0 @if (request()->segment(1) == 'service') active @endif">
                    <a class="nav-link @if (request()->segment(1) == 'service') active @endif" aria-current="page" href="{{ route('service.index') }}">
                        Daftar Harga
                    </a>
                </li>
                @if (user() == false)
                <!--<li class="nav-item dropdown p-0 @if (request()->segment(1) == 'auth') active @endif">-->
                <!--    <a class="nav-link dropdown-toggle @if (request()->segment(1) == 'auth')active  @endif" href="#" id="navbarDropdown1" role="button" data-bs-toggle="dropdown" aria-expanded="false">-->
                <!--         Login / Register-->
                <!--    </a>-->
                <!--    <ul class="dropdown-menu shadow" aria-labelledby="navbarDropdown1">-->
                <!--        <li><a class="dropdown-item @if (request()->segment(2) == 'login') active @endif" href="{{ route('auth.login.get') }}">Login</a></li>-->
                <!--        <li><a class="dropdown-item @if (request()->segment(2) == 'register') active @endif" href="{{ route('auth.register.get') }}">Register</a></li>-->
                <!--    </ul>-->
                <!--</li>-->
                @endif
                @if (user())
                <a class="nav-item nav-link @if (request()->segment(1) == 'account') active @endif" href="{{ url('account') }}"> Dashboard </a>
                @endif
                <li class="nav-item dropdown p-0 @if (request()->segment(1) == 'tools') active @endif">
                    <a class="nav-link dropdown-toggle @if (request()->segment(1) == 'tools') active @endif" href="#" id="navbarDropdown2" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Kalkulator ML
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown2">
                        <li><a class="dropdown-item  @if (request()->segment(2) == 'mobile-legends-win-rate') active @endif" href="{{ url('tools/mobile-legends-win-rate') }}"><i class="fas fa-calculator fs-6 me-2"></i>Hitung Win Rate</a></li>
                        <li><a class="dropdown-item @if (request()->segment(2) == 'mobile-legends-win-lose') active @endif" href="{{ url('tools/mobile-legends-win-lose') }}"><i class="fas fa-calculator fs-6 me-2"></i>Hitung Win Lose</a></li>
                        <li><a class="dropdown-item @if (request()->segment(2) == 'mobile-legends-magic-wheel') active @endif" href="{{ url('tools/mobile-legends-magic-wheel') }}"><i class="fas fa-calculator fs-6 me-2"></i>Hitung Magic Wheel</a></li>
                        <li><a class="dropdown-item @if (request()->segment(2) == 'mobile-legends-point-zodiac') active @endif" href="{{ url('tools/mobile-legends-point-zodiac') }}"><i class="fas fa-calculator fs-6 me-2"></i>Hitung Point Zodiac</a></li>
                    </ul>
                </li>
                <li>
                    <div class="search-wrapper d-flex">
                        <div class="input-group mb-0">
                            <input class="form-control form-search-product" id="formSearchProduct" name="{{ rand() }}" placeholder="Cari produk..." type="search" readonly>
                            <span class="input-group-text text-white bg-flat-primary btn-search-product">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                                    <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
                                </svg>
                            </span>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>
