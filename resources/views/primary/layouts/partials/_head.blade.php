<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="{{ getConfig('meta_tags_description') }}">
    <meta name="keywords" content="{{ getConfig('meta_tags_keyword') }}">
    <meta name="author" content="DolananKode X Ahmad Andika">
    <meta property="og:locale" content="id_ID" />
    <meta property="og:type" content="website" />
    <meta name="og:site" content="{{ getConfig('title') }}">
    <meta property="og:title" content="{{ getConfig('title') }} - {{ $page['title'] }}" />
    <meta property="og:description" content="{{ getConfig('meta_tags_description') }}" />
    <meta property="og:site_name" content="{{ getConfig('title') }}" />
    <link rel="canonical" href="{{ url()->current() }}" />
    <link rel="alternate" hreflang="id-default" href="{{ config('app.url') }}" />
    <link rel="alternate" hreflang="en" href="{{ config('app.url') }}" />
    <meta property="og:image"
        content="{{ asset(config('constants.options.asset_img_website') . getConfig('meta_tags_image')) }}" />
    <meta property="og:url" content="{{ config('app.url') }}" />
    <meta property="og:image:secure_url"
        content="{{ asset(config('constants.options.asset_img_website') . getConfig('meta_tags_image')) }}" />
    <meta property="og:image:type" content="image/jpg, image/png, image/jpeg, image/ico, image/webp, image/svg" />
    <meta property="og:image:width" content="400" />
    <meta property="og:image:height" content="400" />

    <meta name="twitter:card" content="summary">
    <meta name="twitter:site" content="{{ getConfig('title') }}">
    <meta name="twitter:site_name" content="{{ getConfig('title') }}">
    <meta name="twitter:title" content="{{ getConfig('title') }} - Top up Game Termurah dan Terpecaya">
    <meta name="twitter:description" content="{{ getConfig('meta_tags_description') }}">
    <meta name="twitter:image"
        content="{{ asset(config('constants.options.asset_img_website') . getConfig('meta_tags_image')) }}">
    <meta name="twitter:image:alt"
        content="{{ asset(config('constants.options.asset_img_website') . getConfig('meta_tags_image')) }}">

    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title> @yield('title')</title>
    <!-- Fevicon -->
    <link rel="shortcut icon" href="{{ asset(config('constants.options.asset_img_website') . getConfig('favicon')) }}">
    <!-- Start CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/MaterialDesign-Webfont/6.7.96/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="{{ asset('assets/plugins/simplebar/css/simplebar.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css">
    <script src="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js"></script>


    <!-- Styles -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css">
    {{-- <link rel="stylesheet" href="{{ asset('assets/theme/app.css') }}"> --}}
    @vite([
        'public/assets/css/animate.min.css',
        'public/assets/css/horizontal-menu.min.css',
        'public/assets/css/app-style.css',
        'public/assets/css/main.css',
        'public/assets/theme/app.css',
        'resources/css/app.css',
    ])
    {{-- <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}"> --}}
    @yield('style')
    <style>
        table.dataTable thead th {
            color: #fff;
        }
        table.dataTable tbody td {
            color: #fff;
        }
        a.badge {
            text-decoration: none!important;
        }
        @media (min-width: 992px) {
            .px-md-10rem {
                padding-inline: 10rem
            }
            .mx-md-10rem {
                margin-inline: 10rem
            }
            .rounded-md-1rem {
                border-radius: 1rem;
            }
        }
        @media (min-width: 1200px) {
            .px-lg-17rem {
                padding-inline: 17rem
            }
            .mx-lg-17rem {
                margin-inline: 17rem
            }
        }
    </style>
    @livewireStyles

    {!! getConfig('additional_head_scripts') !!}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
