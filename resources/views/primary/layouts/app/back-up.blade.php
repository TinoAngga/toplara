<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="description" content="Theta is a bootstrap & laravel admin dashboard template">
        <meta name="keywords" content="admin, admin dashboard, admin panel, admin template, analytics, bootstrap 4, crm, laravel admin, responsive, sass support, ui kits">
        <meta name="author" content="Themesbox17">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title> @yield('title') </title>
        <!-- Fevicon -->
        <link rel="shortcut icon" href="{{ asset('assets/dark-horizontal/images/favicon.ico') }}">
        <!-- Start CSS -->
        @yield('style')
            <!-- DataTables css -->
        <link href="{{ asset('assets/dark-horizontal/plugins/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet"
        type="text/css" />
        <link href="{{ asset('assets/dark-horizontal/plugins/datatables/buttons.bootstrap4.min.css') }}" rel="stylesheet"
        type="text/css" />
        <!-- Responsive Datatable css -->
        <link href="{{ asset('assets/dark-horizontal/plugins/datatables/responsive.bootstrap4.min.css') }}" rel="stylesheet"
        type="text/css" />
        <link href="{{ asset('assets/dark-horizontal/plugins/switchery/switchery.min.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ asset('assets/dark-horizontal/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ asset('assets/dark-horizontal/css/icons.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ asset('assets/dark-horizontal/css/flag-icon.min.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ asset('assets/dark-horizontal/css/style.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ asset('assets/dark-horizontal/css/custom.css') }}" rel="stylesheet" type="text/css">
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <!-- End CSS -->
        <style>
            .navigationbar {
                z-index: 2 !important;
            }
        </style>
    </head>
    <body class="vertical-layout">
        <!-- Start Infobar Notifications Sidebar -->
        <!-- End Infobar Notifications Sidebar -->
        <!-- Start Infobar Setting Sidebar -->

        <!-- End Infobar Setting Sidebar -->
        <!-- Start Containerbar -->
        <div id="containerbar" class="container-fluid">
            <!-- Start Rightbar -->
            <div class="rightbar">
                <!-- Start Topbar Mobile -->
                <div class="topbar-mobile">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <div class="mobile-logobar">
                                <a href="{{url('/')}}" class="mobile-logo"><img src="{{ asset('assets/dark-horizontal/images/logo.svg') }}" class="img-fluid" alt="logo"></a>
                            </div>
                            <div class="mobile-togglebar">
                                <ul class="list-inline mb-0">
                                    <li class="list-inline-item">
                                        <div class="topbar-toggle-icon">
                                            <a class="topbar-toggle-hamburger" href="javascript:void();">
                                                <img src="{{ asset('assets/dark-horizontal/images/svg-icon/horizontal.svg') }}" class="img-fluid menu-hamburger-horizontal" alt="horizontal">
                                                <img src="{{ asset('assets/dark-horizontal/images/svg-icon/verticle.svg') }}" class="img-fluid menu-hamburger-vertical" alt="verticle">
                                             </a>
                                         </div>
                                    </li>
                                    <li class="list-inline-item">
                                        <div class="menubar">
                                            <a class="menu-hamburger navbar-toggle bg-transparent" href="javascript:void();" data-toggle="collapse" data-target="#navbar-menu" aria-expanded="true">
                                                <img src="{{ asset('assets/dark-horizontal/images/svg-icon/collapse.svg') }}" class="img-fluid menu-hamburger-collapse" alt="collapse">
                                                <img src="{{ asset('assets/dark-horizontal/images/svg-icon/close.svg') }}" class="img-fluid menu-hamburger-close" alt="close">
                                            </a>
                                         </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Start Topbar -->
                <div class="topbar">
                    <!-- Start container-fluid -->
                    <div class="container-fluid">
                        <!-- Start row -->
                        <div class="row align-items-center">
                            <!-- Start col -->
                            <div class="col-md-12 align-self-center">
                                <div class="togglebar">
                                    <ul class="list-inline mb-0">
                                        <li class="list-inline-item">
                                            <div class="logobar">
                                                <a href="{{url('/')}}" class="logo logo-large"><img src="{{ asset('assets/dark-horizontal/images/logo.svg') }}" class="img-fluid" alt="logo"></a>
                                            </div>
                                        </li>
                                        <li class="list-inline-item">
                                            <div class="searchbar">
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                                <div class="infobar">
                                    <ul class="list-inline mb-0">
                                        <li class="list-inline-item">
                                        </li>
                                        <li class="list-inline-item">
                                            <a href="javascript:void(0)" id="infobar-settings-open" class="infobar-icon">
                                                <img src="{{ asset('assets/dark-horizontal/images/svg-icon/settings.svg') }}" class="img-fluid" alt="settings">
                                            </a>
                                        </li>
                                        <li class="list-inline-item">
                                        </li>
                                        <li class="list-inline-item">
                                            <div class="profilebar">
                                                <div class="dropdown">
                                                  <a class="dropdown-toggle" href="#" role="button" id="profilelink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img src="{{ asset('assets/dark-horizontal/images/users/profile.svg') }}" class="img-fluid" alt="profile"><span class="feather icon-chevron-down live-icon"></span></a>
                                                  <div class="dropdown-menu dropdown-menu-right" aria-labelledby="profilelink">
                                                    <div class="dropdown-item">
                                                        <div class="profilename">
                                                          <h5>{{ Auth::check() == true ? Auth::user()->username : 'Tamu' }}</h5>
                                                          <p>{{ Auth::check() == true ? strtoupper(Auth::user()->level) : '-' }}</p>
                                                        </div>
                                                    </div>
                                                    <div class="dropdown-item">
                                                        <div class="userbox">
                                                            <ul class="list-inline mb-0">
                                                                @guest
                                                                <li class="list-inline-item"><a href="{{ route('auth.login') }}"><i class="mdi mdi-login-variant font-20" data-toggle="tooltip" data-placement="top" title="Login"></i></a></li>
                                                                <li class="list-inline-item"><a href="{{ route('auth.register') }}"><i class="mdi mdi-account-plus-outline font-20" data-toggle="tooltip" data-placement="top" title="Register"></i></a></li>
                                                                <li class="list-inline-item"><a href="#" data-toggle="tooltip" data-placement="top" title="Lupa password"><i class="mdi mdi-lock-outline font-20"></i></a></li>
                                                                @endguest
                                                                @auth
                                                                <li class="list-inline-item"><a href="{{ route('account.index') }}" class=""><i class="mdi mdi-account-outline font-20" data-toggle="tooltip" data-placement="top" title="Profil"></i></a></li>
                                                                <li class="list-inline-item"><a href="javascript:void(0)" onclick="logout()"><i class="mdi mdi-logout font-20" data-toggle="tooltip" data-placement="top" title="Logout"></i></a></li>
                                                                @endauth

                                                            </ul>
                                                        </div>
                                                      </div>
                                                  </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="list-inline-item menubar-toggle">
                                            <div class="menubar">
                                                <a class="menu-hamburger navbar-toggle bg-transparent" href="javascript:void();" data-toggle="collapse" data-target="#navbar-menu" aria-expanded="true">
                                                    <img src="{{ asset('assets/dark-horizontal/images/svg-icon/collapse.svg') }}" class="img-fluid menu-hamburger-collapse" alt="collapse">
                                                    <img src="{{ asset('assets/dark-horizontal/images/svg-icon/close.svg') }}" class="img-fluid menu-hamburger-close" alt="close">
                                                </a>
                                             </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <!-- End col -->
                        </div>
                        <!-- End row -->
                    </div>
                    <!-- End container-fluid -->
                </div>
                <!-- End Topbar -->
                <!-- Start Navigationbar -->
                <div class="navigationbar">
                    <!-- Start container-fluid -->
                    <div class="container-fluid">
                        <!-- Start Horizontal Nav -->
                        <nav class="horizontal-nav mobile-navbar fixed-navbar">
                            <div class="collapse navbar-collapse" id="navbar-menu">
                              <ul class="horizontal-menu">
                                <li class="scroll"><a href="{{ url('/') }}"><i class="mdi mdi-view-dashboard-outline"></i><span>Halaman Utama</span></a></li>
                                {{-- <li class="scroll dropdown">
                                    <a href="javaScript:void();" class="dropdown-toggle" data-toggle="dropdown"><img src="assets/images/svg-icon/dashboard.svg" class="img-fluid" alt="dashboard"><span>Dashboard</span></a>

                                    <ul class="dropdown-menu">
                                        <li><a href="{{url('/')}}"><i class="mdi mdi-circle"></i>Social Media</a></li>
                                        <li><a href="{{url('/dashboard-ecommerce')}}"><i class="mdi mdi-circle"></i>eCommerce</a></li>
                                        <li><a href="{{url('/dashboard-analytics')}}"><i class="mdi mdi-circle"></i>Analytics</a></li>
                                    </ul>
                                </li> --}}

                              </ul>
                            </div>
                        </nav>
                        <!-- End Horizontal Nav -->
                    </div>
                    <!-- End container-fluid -->
                </div>
                <!-- End Navigationbar -->
                @include('primary.layouts.app.menu.dark-horizontal')
                <!-- Start Breadcrumbbar -->
                <div class="breadcrumbbar">
                    <div class="row align-items-center">
                        <div class="col-md-8 col-lg-8">
                            <h4 class="page-title">{{ $page['title'] }}</h4>
                            <div class="breadcrumb-list">
                                @if (count($page['breadcrumb']) > 1)
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{url('/')}}">{{ getConfig('title') }}</a></li>
                                    <li class="breadcrumb-item"><a href="#">{{ $page['breadcrumb']['first'] }}</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">{{ $page['breadcrumb']['second'] }}</li>
                                </ol>
                                @else
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{url('/')}}">{{ getConfig('title') }}</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">{{ $page['breadcrumb']['first'] }}</li>
                                </ol>
                                @endif

                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Breadcrumbbar -->
                <!-- Start Contentbar -->
                <div class="contentbar" style="margin-bottom: 300px !important;">
                @include('alert')
                @yield('content')
                </div>
                <!-- End Contentbar -->

                <!-- Start Footerbar -->
                <div class="footerbar bg-primary text-white">
                    <div class="container">
                        <div class="row justify-content-start" style="text-align: left !important">
                            <div class="col-md-4 col-12 mb-3 justify-content-start">
                                <h2>BisaCash</h2>
                                <p> Nikmati pengalaman pembelian Voucher dan Kredit Game otomatis kapan pun di manapun kamu
                                    mau. </p>
                                <div class="row">
                                    <div class="col-12 mb-2">
                                        <a href="https://api.whatsapp.com/send/?phone=6285813059470&text&app_absent=0"
                                            class="text-white"><i class="fab fa-whatsapp mr-2"></i>+6285813059470</a>
                                    </div>
                                    <div class="col-12 mb-2">
                                        <a href="https://facebook.com/bisacashcom" class="text-white"><i
                                                class="fab fa-facebook-square"></i>&ensp;bisacashcom</a>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <a href="https://instagram.com/bisacash" class="text-white"><i
                                                class="fab fa-instagram mr-2"></i>bisacash</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-12 mb-3">
                                <h3>Peta Situs</h3>
                                <div class="row">
                                    <div class="col-12 mb-2">
                                        <a href="https://bisacash.com/about" class="text-white">Tentang</a>
                                    </div>
                                    <div class="col-12 mb-2">
                                        <a href="https://bisacash.com/privacy" class="text-white">Kebijakan Privasi</a>
                                    </div>
                                    <div class="col-12 mb-2">
                                        <a href="https://bisacash.com/faq" class="text-white">FAQ</a>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <a href="https://bisacash.com/terms" class="text-white">Ketentuan Layanan</a>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4 col-12 mb-3">
                                <h3>Pembayaran</h3>
                                <div
                                    style="width: 221px; height: 177px; background-image: url('https://bisacash.com/assets/img/payment.png');">
                                </div>
                            </div>
                        </div>
                    </div>
                    <footer class="footer" style="text-align: center !important">
                        <p class="mb-0" style="text-align: center !important">{{ !is_null(getConfig('footer_description')) ? convertString(getConfig('footer_description')) : '© '.date('Y').' '.getConfig('title').' - All Rights Reserved.' }}</p>
                    </footer>
                </div>
                <!-- End Footerbar -->
            </div>
            <!-- End Rightbar -->
        </div>
        <!-- End Containerbar -->
        <!-- Start JS -->
        <script src="{{ asset('assets/dark-horizontal/js/jquery.min.js') }}"></script>
        <script src="{{ asset('assets/dark-horizontal/js/popper.min.js') }}"></script>
        <script src="{{ asset('assets/dark-horizontal/js/bootstrap.min.js') }}"></script>
        <script src="{{ asset('assets/dark-horizontal/js/modernizr.min.js') }}"></script>
        <script src="{{ asset('assets/dark-horizontal/js/detect.js') }}"></script>
        <script src="{{ asset('assets/dark-horizontal/js/jquery.slimscroll.js') }}"></script>
        <script src="{{ asset('assets/dark-horizontal/js/horizontal-menu.js') }}"></script>
        <script src="{{ asset('assets/dark-horizontal/plugins/switchery/switchery.min.js') }}"></script>
        <!-- Datatable js -->
        <script src="{{ asset('assets/dark-vertical/plugins/datatables/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('assets/dark-vertical/plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>
        <script>
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        </script>
        <script>
            let Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            })
        </script>
        <div class="modal fade" id="modal-form" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true"
        style="display: none;">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title" id="modal-title"></h6>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <div class="modal-body" id="modal-detail-body">...</div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
        @yield('script')
        @stack('script')
        <!-- Core JS -->
        <script src="{{ asset('assets/dark-horizontal/js/core.js') }}"></script>
        <script src="{{ asset('custom/main.custom.js') }}"></script>
        <!-- End JS -->
    </body>
</html>
