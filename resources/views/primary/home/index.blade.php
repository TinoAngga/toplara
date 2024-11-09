@extends('primary.layouts.app')
@section('title')
{{ $page['title'] }}
@endsection
@section('style')
<style>
    .swiper-banner>.swiper-wrapper>.swiper-slide> a > img {
        height: 400px !important;
    }

    @media (max-width: 375px) {
        .swiper-banner>.swiper-wrapper>.swiper-slide> a > img {
            height: 140px !important;
        }
    }

    @media (max-width: 576px) {
        .swiper-banner>.swiper-wrapper>.swiper-slide> a > img {
            height: 160px !important;
        }
    }

    @media (max-width: 768px) {
        .swiper-banner>.swiper-wrapper>.swiper-slide> a > img {
            height: 180px !important;
        }
    }

</style>
@endsection
@section('content')
<div class="position-relative" style="background-color: var(--theme-color-2);">
    <div class="container">
        <div class="row">
            <div class="col-md-12 mt-5">
                <div class="swiper swiper-banner mb-3">
                    <div class="swiper-wrapper">
                        @if (count($banners) > 0)
                            @foreach ($banners as $key => $value)
                            <div class="swiper-slide" lazy="true">
                                <a href="{{ $value->url }}" class="mb-3">
                                    <img
                                        fetchpriority="high"
                                        decoding="async"
                                        class="d-block lazyload img-fluid w-100"
                                        loading="lazy"
                                        src="{{ asset('cdn/dummy-banner.webp') }}"
                                        data-src="{{ asset(config('constants.options.asset_img_banner') . $value->value) }}"
                                        alt="{{ $value->name }}"
                                        height="1440"
                                        width="2560"
                                    >
                                </a>
                            </div>
                            @endforeach
                        @else

                        @endif
                    </div>
                    <div class="swiper-pagination"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container">
    <div class="row">
        <div class="col-md-12 my-3 row-populer-swiper">
            <h5 class="text-shadow text-uppercase mb-3">🔥 Populer</h5>
            <div class="swiper swiper-populer mb-3">
                <div class="swiper-wrapper">
                    @foreach ($populers as $key => $value)
                        <div class="swiper-slide my-5" lazy="true">
                            <div class="card card-custom-populer align-items-center my-2" onclick="window.location='{{ url('/product/' . $value->service_type . '/category/' . $value->slug) }}'">
                                <img
                                    fetchpriority="high"
                                    decoding="async"
                                    class="card-img-top lazyload"
                                    loading="lazy"
                                    src="{{ asset('cdn/dummy-img.webp') }}"
                                    data-src="{{ asset(config('constants.options.asset_img_service_category') . $value->img) }}"
                                    aria-label="Top Up {{ $value->name }}"
                                    height="1440"
                                    width="2560"
                                    alt="Top Up {{ $value->name }}"
                                />
                                <div class="card-body">
                                    <h6 class="text-uppercase">{{ $value->name }}</h6>
                                </div>
                            </div>

                        </div>
                    @endforeach
                </div>
                <div class="swiper-pagination"></div>
                <div class="swiper-button-prev scroll-btn"></div>
                <div class="swiper-button-next scroll-btn"></div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 my-3 row-populer-card">
            <h5 class="text-shadow">🔥  Populer</h5>
            <div class="tab-content pl-2 pr-2">
                <div class="row game">
                    @foreach ($populers as $key => $value)
                    <div class="col-sm-6 col-lg-3 col-6 text-center" style="display: grid;overflow: hidden;padding: 5px 5px;" onclick="window.location='{{ url('/product/' . $value->service_type . '/category/' . $value->slug) }}'">
                        <div class="game-populer-card" style="overflow: hidden;">
                            <div class="row">
                                <div class="col-4">
                                    <img
                                        fetchpriority="high"
                                        decoding="async"
                                        class="game-img-populer lazyload"
                                        data-src="{{ asset(config('constants.options.asset_img_service_category') . $value->img) }}"
                                        aria-label="Top Up {{ $value->name }}"
                                        alt="Top Up {{ $value->name }}"
                                        height="50%" width="300"
                                    />
                                </div>
                                <div class="col-8 text-end mt-4">
                                    <h6 class="card-title text-uppercase" style="position: relative; font-size: 10px"> {{ $value->name }} </h6>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<livewire:primary.home.home-index />
@endsection
@section('script')
<script>
    var swiper = new Swiper('.swiper.swiper-populer', {
        loop: true,
        grabCursor: true,
        slidesPerView: 6,
        speed: 1000,
        autoplay: {
            delay: 3000,
            disableOnInteraction: false,
        },
        rewind: true,
            navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        breakpoints: {
            340: {
                slidesPerView: 2,
            },
            640: {
                slidesPerView: 3,
            },
            992: {
                slidesPerView: 5,
            }
        },
        pagination: {
            el: ".swiper-pagination",
            dynamicBullets: true,
        },
    });
    $('.nav-pills li a').on('click', function () {
        $('.nav-pills li').find('a.nav-active').removeClass('nav-active');
        $(this).addClass('nav-active');
    });
    var swiper1 = new Swiper(".swiper-banner", {
        loop: true,
        centeredSlides: true,
        parallax: true,
        effect: "coverflow",
        grabCursor: true,
        spaceBetween: 30,
        slidesPerView: "auto",
        speed: 5000,
        autoplay: {
            delay: 3000,
            disableOnInteraction: false,
        },
        slidesPerView: "auto",
        coverflowEffect: {
            rotate: 50,
            stretch: 0,
            depth: 100,
            modifier: 1,
            slideShadows: true,
        },
        breakpoints: {
            // when window width is >= 320px
            320: {
                slidesPerView: 1,
            },
            // when window width is >= 480px
            480: {
                slidesPerView: 1,
            },
            // when window width is >= 640px
            640: {
                slidesPerView: "auto",
            }
        },
        pagination: {
            el: ".swiper-pagination",
            dynamicBullets: true,
        },
    });
</script>
@endsection
