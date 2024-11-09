<!--
## AUTHOR DOLANANKODE X AHMAD ANDIKA
## EMAIL : ahmdaka06@gmail.com
## FACEBOOK : AHMAD ANDIKA
## TELEGRAM : @ahmdandika
## WHATSAPP : 081363205735
## GITHUB : https://github.com/ahmdaka06
## LINKEDIN : SOON
## WEBSITE : SOON
-->
<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="description" content="{{ getConfig('meta_tags_description') }}">
        <meta name="keywords" content="{{ getConfig('meta_tags_keyword') }}">
        <meta name="author" content="DolananKode X Ahmad Andika">

        <meta property="og:locale" content="id_ID"/>
        <meta property="og:type" content="website"/>
        <meta name="og:site" content="{{ getConfig('title') }}">
        <meta property="og:title" content="{{ getConfig('title') }} - Halaman Utama"/>
        <meta property="og:description" content="{{ getConfig('meta_tags_description') }}"/>
        <meta property="og:site_name" content="{{ getConfig('title') }}"/>
        <link rel="canonical" href="{{ url()->current() }}"/>
        <link rel="alternate" hreflang="id-default" href="{{ config('app.url') }}"/>
        <link rel="alternate" hreflang="en" href="{{ config('app.url') }}"/>
        <meta property="og:image" content="{{ asset(config('constants.options.asset_img_website') . getConfig('meta_tags_image')) }}"/>
        <meta property="og:url" content="{{ config('app.url') }}"/>
        <meta property="og:image:secure_url" content="{{ asset(config('constants.options.asset_img_website') . getConfig('meta_tags_image')) }}"/>
        <meta property="og:image:type" content="image/jpg, image/png, image/jpeg, image/ico, image/webp, image/svg"/>
        <meta property="og:image:width" content="400"/>
        <meta property="og:image:height" content="400"/>

        <meta name="twitter:card" content="summary">
        <meta name="twitter:site" content="{{ getConfig('title') }}">
        <meta name="twitter:site_name" content="{{ getConfig('title') }}">
        <meta name="twitter:title" content="{{ getConfig('title') }} - Halaman Utama">
        <meta name="twitter:description" content="{{ getConfig('meta_tags_description') }}">
        <meta name="twitter:image" content="{{ asset(config('constants.options.asset_img_website') . getConfig('meta_tags_image')) }}">
        <meta name="twitter:image:alt" content="{{ asset(config('constants.options.asset_img_website') . getConfig('meta_tags_image')) }}">

        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
        <title> @yield('title') </title>
        <!-- Fevicon -->
        <link rel="shortcut icon" href="{{ asset(config('constants.options.asset_img_website') . getConfig('favicon')) }}">
        <!-- Start CSS -->

        <link href="{{ asset('assets/dark-horizontal/plugins/switchery/switchery.min.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ asset('assets/dark-horizontal/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ asset('assets/dark-horizontal/css/icons.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ asset('assets/dark-horizontal/css/flag-icon.min.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ asset('assets/dark-horizontal/css/style.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ asset('assets/dark-horizontal/css/custom.css') }}" rel="stylesheet" type="text/css">
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        @livewireStyles
        <!-- End CSS -->
        <style>
            .navigationbar {
                z-index: 3 !important;
            }
            .input-group-append .btn, .input-group-prepend .btn {
                position: relative;
                z-index: 1;
            }
        </style>
        {!! getConfig('additional_head_scripts') !!}
        @yield('style')
    </head>
    <body class="horizontal-layout">
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
                                @if (!is_null(getConfig('logo')))
                                    <a href="{{url('/')}}" class="mobile-logo"><img src="{{ asset(config('constants.options.asset_img_website') . getConfig('logo')) }}" class="img-fluid" alt="logo"></a>
                                @else
                                    <h4 class="text-primary"><i class="mdi mdi-gamepad-variant mr-2"></i>{{ getConfig('title') }}</h4>
                                @endif

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
                                                @if (!is_null(getConfig('logo')))
                                                    <a href="{{url('/')}}" class="logo logo-large"><img src="{{ asset(config('constants.options.asset_img_website') . getConfig('logo')) }}" class="img-fluid" alt="logo"></a>
                                                @else
                                                    <h4 class="text-primary"><i class="mdi mdi-gamepad-variant mr-2"></i>{{ getConfig('title') }}</h4>
                                                @endif

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
                                                                <li class="list-inline-item"><a href="{{ route('auth.login.get') }}"><i class="mdi mdi-login-variant font-20" data-toggle="tooltip" data-placement="top" title="Login"></i></a></li>
                                                                <li class="list-inline-item"><a href="{{ route('auth.register.get') }}"><i class="mdi mdi-account-plus-outline font-20" data-toggle="tooltip" data-placement="top" title="Register"></i></a></li>
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
                <div class="contentbar">
                @include('alert')
                @yield('content')
                    <hr>
                    <div class="row text-left mt-5 mb-5">
                        <div class="col-md-4 mb-2">
                            <h5>{{ getConfig('title') }}</h5>
                            <p> {{ getConfig('description') }} </p>
                            <hr>
                            <h5>Kontak Kami</h5>
                            <div class="row">
                                <div class="col-12 mb-2">
                                    <a href="https://api.whatsapp.com/send/?phone={{ getConfig('social_media_whatsapp') }}&text&app_absent=0"
                                        target="_blank" class="text-white"><i class="fa fa-whatsapp mr-2"></i>{{ getConfig('social_media_whatsapp') }}</a>
                                </div>
                                <div class="col-12 mb-2">
                                    <a href="{{ getConfig('social_media_facebook_url') }}" target="_blank" class="text-white"><i
                                            class="fa fa-facebook-square"></i>&ensp;{{ getConfig('social_media_facebook_name') }}</a>
                                </div>
                                <div class="col-12 mb-3">
                                    <a href="https://instagram.com/{{ getConfig('social_media_instagram') }}" target="_blank" class="text-white"><i
                                            class="fa fa-instagram mr-2"></i>{{ getConfig('social_media_instagram') }}</a>
                                </div>
                                <div class="col-12 mb-3">
                                    <a href="mailto:{{ getConfig('contact_email_address') }}" target="_blank" class="text-white"><i
                                            class="fa fa-envelope mr-2"></i>{{ getConfig('contact_email_address') }}</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-2">
                            <h5>Sitemap</h5>
                            <div class="row">
                                @foreach (\Db::table('pages')->orderBy('id', 'ASC')->get() as $key => $value)
                                <div class="col-12">
                                    <li><a href="{{ url('page/sitemap/' . $value->slug) }}" class="text-white">{{ ucwords($value->title) }}</a></li>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="col-md-4 mb-2">
                            <h5>Pembayaran</h5>
                            <img style="width: 221px; height: 177px;" alt="Pembayaran" class="lazy" data-src="{{ asset('public/cdn/website/payment.png') }}">
                        </div>
                    </div>
                </div>
                <!-- End Contentbar -->

                <!-- Start Footerbar -->
                <div class="footerbar bg-primary text-white">
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
        @livewireScripts
        <script src="{{ asset('assets/dark-horizontal/js/jquery.min.js') }}"></script>
        <script src="{{ asset('assets/dark-horizontal/js/popper.min.js') }}"></script>
        <script src="{{ asset('assets/dark-horizontal/js/bootstrap.min.js') }}"></script>
        <script src="{{ asset('assets/dark-horizontal/js/modernizr.min.js') }}"></script>
        <script src="{{ asset('assets/dark-horizontal/js/detect.js') }}"></script>
        <script src="{{ asset('assets/dark-horizontal/js/jquery.slimscroll.js') }}"></script>
        <script src="{{ asset('assets/dark-horizontal/js/horizontal-menu.js') }}"></script>
        <script src="{{ asset('assets/dark-horizontal/plugins/switchery/switchery.min.js') }}"></script>
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
            function logout() {
                swal.fire({
                    title: "Apakah anda yakin?",
                    html: '<b style="font-weight: bold;">Akan logout</b>?',
                    icon: "warning",
                    showCancelButton: !0,
                    confirmButtonText: "Ya, Logout!",
                    cancelButtonText: "Tidak, Batalkan!",
                    confirmButtonClass: "btn btn-success",
                    cancelButtonClass: "btn btn-danger",
                }).then(result => {
                    if (result.value) {
                        window.location = '{{ route('logout') }}';
                    } else {
                        swal.fire("Dibatalkan", "Logout di batalkan.", "error");
                    }
                });
            }
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
        <script src="https://cdn.jsdelivr.net/npm/vanilla-lazyload@17.7.0/dist/lazyload.min.js"></script>
        <!-- Core JS -->
        <script src="{{ asset('assets/dark-horizontal/js/core.js') }}"></script>
        <script src="{{ asset('custom/main.custom.js') }}"></script>
        <!-- End JS -->
        <script>
            let img = document.querySelectorAll(".lazy");
            new LazyLoad(img);
        </script>
        {!! getConfig('additional_body_scripts') !!}
    </body>
</html>
