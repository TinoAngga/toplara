@if (admin())
<div class="main-menu-content">
    <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
        <li class=" navigation-header"><span data-i18n="Main Menu">Main Menu</span><i data-feather="more-horizontal"></i>
        </li>
        <li class="@if (request()->segment(2) == 'dashboard') active @endif nav-item">
            <a class="d-flex align-items-center" href="{{ url('admin/dashboard') }}">
                <i data-feather="home"></i>
                <span class="menu-title text-truncate" data-i18n="Dashboard">Dashboard</span>
            </a>
        </li>
        <li class="@if (request()->segment(2) == 'Live Preview') active @endif nav-item">
             <a class="d-flex align-items-center" href="{{ url('/') }}">
                <i data-feather="shopping-bag"></i>
                <span class="menu-title text-truncate" data-i18n="Live Preview">Live Preview</span>
            </a>
        </li>
        <li class=" navigation-header"><span data-i18n="Super Admin">Super Admin</span><i data-feather="more-horizontal"></i>
        </li>
        <li class="@if (request()->segment(2) == 'admin') active @endif nav-item">
            <a class="d-flex align-items-center" href="{{ url('admin/admin') }}">
                <i data-feather="user-check"></i>
                <span class="menu-title text-truncate" data-i18n="Admin">Admin</span>
            </a>
        </li>
        <li class="@if (request()->segment(2) == 'user') active @endif nav-item">
            <a class="d-flex align-items-center" href="{{ url('admin/user') }}">
                <i data-feather="users"></i>
                <span class="menu-title text-truncate" data-i18n="Pengguna">Pengguna</span>
            </a>
        </li>
        <li class="@if (request()->segment(2) == 'user-level') active @endif nav-item">
            <a class="d-flex align-items-center" href="{{ url('admin/user-level') }}">
                <i data-feather="user-plus"></i>
                <span class="menu-title text-truncate" data-i18n="Level Pengguna">Level Pengguna</span>
            </a>
        </li>
        <li class=" navigation-header"><span data-i18n="Layanan">Layanan</span><i data-feather="more-horizontal"></i>
        </li>
        <li class="@if (request()->segment(2) == 'service-category-type') active @endif nav-item">
            <a class="d-flex align-items-center" href="{{ url('admin/service-category-type') }}">
                <i data-feather="list"></i>
                <span class="menu-title text-truncate" data-i18n="Tipe Kategori">Tipe Kategori</span>
            </a>
        </li>
        <li class="@if (request()->segment(2) == 'service-category') active @endif nav-item">
            <a class="d-flex align-items-center" href="{{ url('admin/service-category') }}">
                <i data-feather="list"></i>
                <span class="menu-title text-truncate" data-i18n="Kategori Layanan">Kategori Layanan</span>
            </a>
        </li>
        <li class="@if (request()->segment(2) == 'service-sub-category') active @endif nav-item">
            <a class="d-flex align-items-center" href="{{ url('admin/service-sub-category') }}">
                <i data-feather="list"></i>
                <span class="menu-title text-truncate" data-i18n="Kategori Layanan">Sub Kategori Layanan</span>
            </a>
        </li>
        <li class="@if (request()->segment(2) == 'service') active @endif nav-item">
            <a class="d-flex align-items-center" href="{{ url('admin/service') }}">
                <i data-feather="server"></i>
                <span class="menu-title text-truncate" data-i18n="Layanan">Layanan</span>
            </a>
        </li>
        <li class="@if (request()->segment(2) == 'provider') active @endif nav-item">
            <a class="d-flex align-items-center" href="{{ url('admin/provider') }}">
                <i data-feather="server"></i>
                <span class="menu-title text-truncate" data-i18n="Provider">Provider</span>
            </a>
        </li>
        <li class=" navigation-header"><span data-i18n="Deposit & Pembayaran">Deposit & Pembayaran</span><i data-feather="more-horizontal"></i>
        </li>
        <li class="@if (request()->segment(2) == 'payment-method') active @endif nav-item">
            <a class="d-flex align-items-center" href="{{ url('admin/payment-method') }}">
                <i data-feather="list"></i>
                <span class="menu-title text-truncate" data-i18n="Metode Pembayaran">Metode Pembayaran</span>
            </a>
        </li>
        <li class="@if (request()->segment(2) == 'deposit') active @endif nav-item">
            <a class="d-flex align-items-center" href="{{ url('admin/deposit') }}">
                <i data-feather="credit-card"></i>
                <span class="menu-title text-truncate" data-i18n="Deposit">Deposit</span>
            </a>
        </li>
        <li class=" navigation-header"><span data-i18n="Pesanan">Pesanan</span><i data-feather="more-horizontal"></i>
        </li>
        <li class="@if (request()->segment(2) == 'order') active @endif nav-item">
            <a class="d-flex align-items-center" href="{{ url('admin/order') }}">
                <i data-feather="shopping-cart"></i>
                <span class="menu-title text-truncate" data-i18n="Pesanan">Pesanan</span>
            </a>
        </li>
        <li class=" navigation-header"><span data-i18n="Log & Laporan">Log & Laporan</span><i data-feather="more-horizontal"></i>
        </li>
        <li class="@if (request()->segment(2) == 'user-upgrade') active @endif nav-item">
            <a class="d-flex align-items-center" href="{{ url('admin/user-upgrade') }}">
                <i data-feather="user-plus"></i>
                <span class="menu-title text-truncate" data-i18n="Peningkatan Pengguna">Peningkatan Pengguna</span>
            </a>
        </li>
        <li class="@if (request()->segment(2) == 'balance-mutation') active @endif nav-item">
            <a class="d-flex align-items-center" href="{{ url('admin/balance-mutation') }}">
                <i data-feather="credit-card"></i>
                <span class="menu-title text-truncate" data-i18n="Mutasi Saldo">Mutasi Saldo</span>
            </a>
        </li>
        <li class="@if (request()->segment(2) == 'admin-log') active @endif nav-item">
            <a class="d-flex align-items-center" href="{{ url('admin/admin-log') }}">
                <i data-feather="cpu"></i>
                <span class="menu-title text-truncate" data-i18n="Log Admin">Log Admin</span>
            </a>
        </li>
        <li class="@if (request()->segment(2) == 'provider-api-log') active @endif nav-item">
            <a class="d-flex align-items-center" href="{{ url('admin/provider-api-log') }}">
                <i data-feather="cpu"></i>
                <span class="menu-title text-truncate" data-i18n="Log Provider API">Log Provider API</span>
            </a>
        </li>
        <li class=" navigation-header"><span data-i18n="Lainnya">Lainnya</span><i data-feather="more-horizontal"></i>
        </li>
        <li class="@if (request()->segment(2) == 'banner') active @endif nav-item">
            <a class="d-flex align-items-center" href="{{ url('admin/banner') }}">
                <i data-feather="image"></i>
                <span class="menu-title text-truncate" data-i18n="Banner">Banner</span>
            </a>
        </li>
        <li class="@if (request()->segment(2) == 'halaman') active @endif nav-item">
            <a class="d-flex align-items-center" href="{{ url('admin/page') }}">
                <i data-feather="file-text"></i>
                <span class="menu-title text-truncate" data-i18n="Halaman">Halaman</span>
            </a>
        </li>
        <li class=" navigation-header"><span data-i18n="Layanan">Pengaturan Website</span><i data-feather="more-horizontal"></i>
        </li>
        <li class="nav-item ">
            <a class="d-flex align-items-center" href="#">
                <i data-feather="sliders"></i>
                <span class="menu-title text-truncate" data-i18n="Pengaturan Website">Pengaturan Website</span>
            </a>
            <ul class="menu-content">
                <li class="@if (request()->segment(3) == 'primary') active @endif">
                    <a class="d-flex align-items-center" href="{{ url('admin/website-config/primary') }}">
                        <i data-feather="circle"></i>
                        <span class="menu-item text-truncate" data-i18n="Utama">Utama</span>
                    </a>
                </li>
                <li class="@if (request()->segment(3) == 'profit') active @endif">
                    <a class="d-flex align-items-center" href="{{ url('admin/website-config/profit') }}">
                        <i data-feather="circle"></i>
                        <span class="menu-item text-truncate" data-i18n="Profit">Profit</span>
                    </a>
                </li>
                <li class="@if (request()->segment(3) == 'invoice') active @endif">
                    <a class="d-flex align-items-center" href="{{ url('admin/website-config/invoice') }}">
                        <i data-feather="circle"></i>
                        <span class="menu-item text-truncate" data-i18n="Invoice">Invoice</span>
                    </a>
                </li>
                <li class="@if (request()->segment(3) == 'payment-gateway') active @endif">
                    <a class="d-flex align-items-center" href="{{ url('admin/website-config/payment-gateway') }}">
                        <i data-feather="circle"></i>
                        <span class="menu-item text-truncate" data-i18n="Payment Gateway">Payment Gateway</span>
                    </a>
                </li>
                <li class="@if (request()->segment(3) == 'mail') active @endif">
                    <a class="d-flex align-items-center" href="{{ url('admin/website-config/mail') }}">
                        <i data-feather="circle"></i>
                        <span class="menu-item text-truncate" data-i18n="SMTP Mail">SMTP Mail</span>
                    </a>
                </li>
                <li class="@if (request()->segment(3) == 'meta-tags') active @endif">
                    <a class="d-flex align-items-center" href="{{ url('admin/website-config/meta-tags') }}">
                        <i data-feather="circle"></i>
                        <span class="menu-item text-truncate" data-i18n="Meta Tags">Meta Tags</span>
                    </a>
                </li>
                <li class="@if (request()->segment(3) == 'additional-scripts') active @endif">
                    <a class="d-flex align-items-center" href="{{ url('admin/website-config/additional-scripts') }}">
                        <i data-feather="circle"></i>
                        <span class="menu-item text-truncate" data-i18n="Additional Scripts">Additional Scripts</span>
                    </a>
                </li>
                <li class="@if (request()->segment(3) == 'social-media') active @endif">
                    <a class="d-flex align-items-center" href="{{ url('admin/website-config/social-media') }}">
                        <i data-feather="circle"></i>
                        <span class="menu-item text-truncate" data-i18n="Social Media">Social Media</span>
                    </a>
                </li>
                <li class="@if (request()->segment(3) == 'about-section') active @endif">
                    <a class="d-flex align-items-center" href="{{ url('admin/website-config/about-section') }}">
                        <i data-feather="circle"></i>
                        <span class="menu-item text-truncate" data-i18n="About Section">About Section</span>
                    </a>
                </li>
                <li class="@if (request()->segment(3) == 'whatsapp-notification') active @endif">
                    <a class="d-flex align-items-center" href="{{ url('admin/website-config/whatsapp-notification') }}">
                        <i data-feather="circle"></i>
                        <span class="menu-item text-truncate" data-i18n="Notification Whatsapp">Notification Whatsapp</span>
                    </a>
                </li>

            </ul>
        </li>
    </ul>
</div>

@endif
