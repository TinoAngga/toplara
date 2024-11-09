<div>
    <style>
        .icon-service {
            bottom: 0%;
            right: 5%;
            position: absolute;
        }
    </style>
    <!--<div class="position-relative" wire:init="loadServices">-->
    <!--    <div class="container">-->
    <!--        <div class="row">-->
    <!--            <div class="col-md-12">-->
    <!--                <div class="relative h-full mt-5">-->
    <!--                    <img-->
    <!--                        fetchpriority="high"-->
    <!--                        decoding="async"-->
    <!--                        class="banner-games blur-games lazyload"-->
    <!--                        src="{{ asset('cdn/dummy-banner.webp') }}"-->
    <!--                        loading="lazy"-->
    <!--                        data-src="{{ asset(config('constants.options.asset_img_service_category') . $category->img) }}"-->
    <!--                        alt="Top Up {{ $category->name }}"-->
    <!--                        aria-label="Top Up {{ $category->name }}"-->
    <!--                    >-->
    <!--                </div>-->
    <!--            </div>-->
    <!--            <div class="col-12">-->
    <!--                <div class="row" style="margin-top: -1rem">-->
    <!--                    <div class="col-md-4 mb-2">-->
    <!--                        <div class="card card-custom-single-game align-items-center" style="border: none; box-shadow:none; margin-bottom: -1rem; background-color: transparent;">-->
    <!--                            <img-->
    <!--                                fetchpriority="high" decoding="async" class="card-img-top lazyload"-->
    <!--                                src="{{ asset('cdn/dummy-img.webp') }}"-->
    <!--                                loading="lazy"-->
    <!--                                data-src="{{ asset(config('constants.options.asset_img_service_category') . $category->img) }}"-->
    <!--                                aria-label="Top Up {{ $category->name }}" height="auto" width="auto" />-->
    <!--                            <div class="card-body">-->
    <!--                                <h6>{{ $category->name }}</h6>-->
    <!--                            </div>-->
    <!--                        </div>-->
    <!--                    </div>-->
    <!--                    <div class="col-md-8 mt-0 mt-lg-5" x-data="{ showMore: false }">-->
    <!--                        <div class="mt-2" x-show="!showMore">-->
    <!--                            {!! Str::limit($category->description, 50) !!}-->
    <!--                        </div>-->
    <!--                        <div class="mt-2" x-show="showMore" >-->
    <!--                            {!! $category->description !!}-->
    <!--                        </div>-->
    <!--                        <span style="cursor:pointer; color: var(--flat-color-1)" @click="showMore = !showMore">-->
    <!--                            <span x-show="!showMore">Lihat lebih banyak <i class="fa fa-arrow-down"></i></span>-->
    <!--                            <span x-show="showMore">Lihat lebih sedikit <i class="fa fa-arrow-up"></i></span>-->
    <!--                        </span>-->
    <!--                        <div class="text-lg-start text-center my-4">-->
    <!--                            <a href="javascript:void()" data-bs-toggle="modal" data-bs-target="#orderInfo" style="text-decoration: none;" class="btn btn-primary">-->
    <!--                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-help-circle">-->
    <!--                                    <circle cx="12" cy="12" r="10"></circle>-->
    <!--                                    <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>-->
    <!--                                    <path d="M12 17h.01"></path>-->
    <!--                                </svg>-->
    <!--                                Cara Pembelian-->
    <!--                            </a>-->
    <!--                        </div>-->
    <!--                        <div class="modal fade" id="orderInfo" tabindex="-1" aria-labelledby="orderInfoLabel" aria-hidden="true">-->
    <!--                            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">-->
    <!--                                <div class="modal-content">-->
    <!--                                    <div class="modal-header">-->
    <!--                                        <h5 class="modal-title">Cara Pembelian</h5>-->
    <!--                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>-->
    <!--                                    </div>-->
    <!--                                    <div class="modal-body">-->
    <!--                                        {!! $category->information !!}-->
    <!--                                    </div>-->
    <!--                                    <div class="modal-footer">-->
    <!--                                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Tutup</button>-->
    <!--                                    </div>-->
    <!--                                </div>-->
    <!--                            </div>-->
    <!--                        </div>-->
    <!--                    </div>-->
    <!--                </div>-->
    <!--            </div>-->
    <!--        </div>-->
    <!--    </div>-->
    <!--</div>-->
    
   <div class="position-relative" wire:init="loadServices">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="relative h-full mt-5">
                    <img
                        fetchpriority="high"
                        decoding="async"
                        class="banner-games blur-games lazyload"
                        src="{{ asset('cdn/dummy-banner.webp') }}"
                        loading="lazy"
                        data-src="{{ asset(config('constants.options.asset_img_service_category') . $category->img) }}"
                        alt="Top Up {{ $category->name }}"
                        aria-label="Top Up {{ $category->name }}"
                    >
                </div>
            </div>
            <div class="col-12">
                <div class="row" style="margin-top: -1rem">
                    <div class="col-md-4 mb-2">
                        <div class="card card-custom-single-game align-items-center" style="border: none; box-shadow:none; margin-bottom: -1rem; background-color: transparent;">
                            <img
                                fetchpriority="high" decoding="async" class="card-img-top lazyload"
                                src="{{ asset('cdn/dummy-img.webp') }}"
                                loading="lazy"
                                data-src="{{ asset(config('constants.options.asset_img_service_category') . $category->img) }}"
                                aria-label="Top Up {{ $category->name }}" height="auto" width="auto" />
                            <div class="card-body">
                            @if($category->slug == 'royal-dream')
                                <h6>ROYAL DREAM</h6>
                            @elseif($category->slug == 'higgs-global-island')
                                <h6>HIGGS GAME ISLAND</h6>
                            @else
                                <h6>{{ $category->name }}</h6>
                            @endif
                        </div>
                        </div>
                    </div>
                    <div class="col-md-8 mt-0 mt-lg-5" x-data="{ showMore: false }">
                        <div class="mt-2" x-show="!showMore">
                            {!! Str::limit($category->description, 50) !!}
                        </div>
                        <div class="mt-2" x-show="showMore" >
                            {!! $category->description !!}
                        </div>
                        <span style="cursor:pointer; color: var(--flat-color-1)" @click="showMore = !showMore">
                            <span x-show="!showMore">Lihat lebih banyak <i class="fa fa-arrow-down"></i></span>
                            <span x-show="showMore">Lihat lebih sedikit <i class="fa fa-arrow-up"></i></span>
                        </span>
                        @if($category->slug == 'royal-dream')
                            <div class="text-lg-start text-center my-4">
                                <a href="javascript:void()" data-bs-toggle="modal" data-bs-target="#orderInfo" style="text-decoration: none;" class="btn btn-primary">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-help-circle">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>
                                        <path d="M12 17h.01"></path>
                                    </svg>
                                    Cara Pembelian
                                </a>
                                <!-- Tombol bongkar untuk produk royal-dream-domino -->
                                <a href="https://wa.me/{{ $whatsappNumber }}?text=BONGKAR%20ROYAL%20KAK" target="_blank" class="btn btn-success">Bongkar RD</a>
                            </div>
                        @elseif($category->slug == 'higgs-global-island')
                            <div class="text-lg-start text-center my-4">
                                <a href="javascript:void()" data-bs-toggle="modal" data-bs-target="#orderInfo" style="text-decoration: none;" class="btn btn-primary">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-help-circle">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>
                                        <path d="M12 17h.01"></path>
                                    </svg>
                                    Cara Pembelian
                                </a>
                                <!-- Tombol bongkar untuk produk higgs-domino-emas -->
<a href="https://wa.me/{{ $whatsappNumber1 }}?text=BONGKAR%20HDI%20KAK" target="_blank" class="btn btn-success">BONGKAR HIGGS</a>
                            </div>
                        @else
                            <div class="text-lg-start text-center my-4">
                                <a href="javascript:void()" data-bs-toggle="modal" data-bs-target="#orderInfo" style="text-decoration: none;" class="btn btn-primary">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-help-circle">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>
                                        <path d="M12 17h.01"></path>
                                    </svg>
                                    Cara Pembelian
                                </a>
                            </div>
                        @endif
                        <div class="modal fade" id="orderInfo" tabindex="-1" aria-labelledby="orderInfoLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Cara Pembelian</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        {!! $category->information !!}
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Tutup</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


    
    
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <form method="POST" id="order-form">
                    @csrf
                    @method('POST')
                    <input type="hidden" name="category" value="{{ $category->id }}" />
                    <input type="hidden" name="is_check_id" value="{{ $category->is_check_id }}">
                    <div class="row">
                        <div class="col-md-4 review">
                            <div class="num-page">
                                <div>
                                    <i class="mdi mdi-star"></i>
                                </div>
                                <h5>
                                    Ulasan
                                </h5>
                            </div>
                            <div class="card shadow">
                                <div class="card-body">
                                    @forelse ($reviews as $key => $value)
                                    <article class="d-flex flex-column mt-3"> <!-- Loop -->
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <p class="fw-bold">{{ hideString($value['order']['whatsapp_order']) }}</p>
                                                <small>{{ $value['service']['name'] }}</small>
                                                <p>
                                                    <i>{{ $value['comment'] }}</i>
                                                </p>
                                            </div>
                                            <div>
                                                <div class="d-flex justify-content-end fs-5">
                                                    @for ($i = 0; $i < $value['rating']; $i++)
                                                    <span class="mdi mdi-star text-warning"></span>
                                                    @endfor
                                                </div>
                                                <small>{{ format_datetime($value['created_at']) }}</small>
                                            </div>
                                        </div>
                                        <hr class="border-3" style="opacity: 10%">
                                    </article> <!-- Loop End -->
                                    @empty
                                    <p class="text-center">Belum ada ulasan</p>
                                    @endforelse
                                    <a href="{{ route('review.index') }}" class="text-decoration-none">Tampilkan Selengkapnya <i class="fa fa-arrow-right"></i></a>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-12 mb-2">
                                    <div class="num-page">
                                        <div>1</div>
                                        <h5>
                                            Lengkapi Data
                                            <a href="javasript:void()" data-bs-toggle="modal" data-bs-target="#exampleModal" style="text-decoration: none">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-help-circle">
                                                    <circle cx="12" cy="12" r="10"></circle>
                                                    <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>
                                                    <path d="M12 17h.01"></path>
                                                </svg>
                                            </a>
                                        </h5>
                                    </div>
                                    <div class="card shadow">
                                        <div class="card-body">
                                            <div class="row">
                                                <!--<div class="form-group col-md-12 col-12 mt-2">-->
                                                <!--    <label for="data" style="font-size: 14px">{{ $category->form_setting['placeholder_data'] ?? 'Masukan User ID' }}</label>-->
                                                <!--    <input type="text" class="form-control shadow" name="data" id="data"-->
                                                <!--        placeholder="{{ $category->form_setting['placeholder_data'] ?? 'Masukan User ID' }}"-->
                                                <!--        style="border-radius: 10px;">-->
                                                <!--    <small class="text-danger data-invalid"></small>-->
                                                <!--</div>-->
                                                <div class="form-group col-md-12 col-12 mt-2">
                                                    <label for="data" style="font-size: 14px">{{ $category->form_setting['placeholder_data'] ?? 'Masukan User ID' }}</label>
                                                    <input type="number" class="form-control shadow" name="data" id="data"
                                                        placeholder="{{ $category->form_setting['placeholder_data'] ?? 'Masukan User ID' }}"
                                                        style="border-radius: 10px;">
                                                    <small class="text-danger data-invalid"></small>
                                                </div>
                                                @if ($category->is_additional_data == true)
                                                <div class="form-group col-md-12 col-12 mt-2">
                                                    @if (isset($category->form_setting['form_additional_data']) AND $category->form_setting['form_additional_data'] <> '')
                                                        @php
                                                            $getListServer = explode(',',
                                                            $category->form_setting['form_additional_data']);
                                                        @endphp
                                                        <label for="additional_data" style="font-size: 14px">{{ $category->form_setting['placeholder_additional_data'] ?? 'Masukan Zone ID' }}</label>
                                                        <select class="form-control shadow" name="additional_data"
                                                            id="additional_data" style="border-radius: 10px;">
                                                                <option value=" 0" selected disabled>Pilih Salah Satu</option>
                                                            @foreach ($getListServer as $server)
                                                                @php $server = explode('|', $server) @endphp
                                                                <option value="{{ $server[0] }}">{{ $server[1] }}</option>
                                                            @endforeach
                                                        </select>
                                                        @else
                                                        <label for="additional_data" style="font-size: 14px">{{ $category->form_setting['placeholder_additional_data'] ?? 'Masukan Zone ID' }}</label>
                                                        <input type="text" class="form-control shadow" name="additional_data"
                                                            id="additional_data"
                                                            placeholder="{{ $category->form_setting['placeholder_additional_data'] ?? 'Masukan Zone ID' }}"
                                                            style="border-radius: 10px;">
                                                        @endif
                                                        <small class="text-danger additional_data-invalid"></small>
                                                        <input type="hidden" name="additional_data_is_true" value="1">
                                                </div>
                                                @endif
                                            </div>
                                            @if (preg_match("/joki-mobile-legend/i", $category->slug))
                                            <div class="row mt-2">
                                                <div class="col-md-6 col-12 mt-2">
                                                    <label class="text-white">Tipe Login</label>
                                                    <select class="form-control shadow" name="login" id="login"  style="border-radius: 10px;">
                                                        <option value="">Pilih tipe login</option>
                                                            @foreach (config('constants.options.joki.mobile-legends.login') as $value)
                                                                <option value="{{ $value }}">{{ $value }}</option>
                                                            @endforeach
                                                    </select>
                                                    <small class="text-danger login-invalid"></small>
                                                </div>
                                                <div class="col-md-6 col-12 mt-2">
                                                    <label class="text-white">Hero</label>
                                                    <input type="text" class="form-control shadow" name="hero" id="hero"
                                                        placeholder="Contoh: Hanzo, Fanny, Gusion, dll"
                                                        style="border-radius: 10px;">
                                                    <small class="text-danger hero-invalid"></small>
                                                </div>
                                                <div class="col-md-6 col-12 mt-2">
                                                    <label class="text-white">Catatan untuk penjoki</label>
                                                    <input type="text" class="form-control shadow" name="note" id="note"
                                                        placeholder="Ketik catatan untuk penjoki" style="border-radius: 10px;">
                                                    <small class="text-danger note-invalid"></small>
                                                </div>
                                                <div class="col-md-6 col-12 mt-2">
                                                    <label class="text-white">User ID & Nickname</label>
                                                    <input type="text" class="form-control shadow" name="user_nickname"
                                                        id="user_nickname" placeholder="User Id & Nickname"
                                                        style="border-radius: 10px;">
                                                    <small class="text-danger user_nickname-invalid"></small>
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                    <!-- Modal -->
                                    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                @if (!is_null($category->guide_img))
                                                <img src="{{ asset(config('constants.options.asset_img_service_category_guide') . $category->guide_img) }}" class="img-fluid" style="width: 100%; height: auto;">
                                                @else
                                                -
                                                @endif
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i class="fa fa-times"></i> TUTUP </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div
                                    class="col-12 mb-2"
                                    x-data="{
                                        showAll: {},
                                        openTab: 'all',
                                    }"
                                >
                                    <div class="num-page">
                                        <div>2</div>
                                        <h5>
                                            Pilih Layanan
                                        </h5>
                                    </div>
                                    <div class="card shadow">
                                        <div class="card-body">
                                            <div class="mt-1">
                                                <div class="row  @if($readyToLoadServices) d-none @endif my-2">
                                                    <div class="progress">
                                                        <div class="progress-bar progress-bar-striped bg-flat-primary" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                </div>
                                                <div class="row service @if($readyToLoadServices == false) d-none @endif">
                                                    <div class="col-md-12">
                                                        <ul class="nav nav-pills d-inline-block py-3">
                                                            <li
                                                                class="nav-item d-inline-block nav-category nav-link"
                                                                style="padding-left: .2rem; padding-right: .2rem;"
                                                                x-on:click="openTab = 'all'" :class="{ 'nav-active': openTab === 'all' }"
                                                            >
                                                                Semua
                                                            </li>
                                                            @foreach ($services as $key => $value)
                                                                @if (count($value['service']) == 0) @continue @endif
                                                                <li
                                                                    x-on:click="openTab = '{{ $value['slug'] }}'" :class="{ 'nav-active': openTab === '{{ $value['slug'] }}' }"
                                                                    class="nav-item d-inline-block nav-category nav-link"
                                                                    style="padding-left: .2rem; padding-right: .2rem; cursor: pointer;"
                                                                >
                                                                    {{ ucwords(str_replace('-', ' ', $value['name'])) }}
                                                                </li>
                                                            @endforeach
                                                        </ul>

                                                        @foreach ($services as $key => $value)
                                                        <div class="row row-sub-category service" x-show="openTab === 'all' || openTab === '{{ $value['slug'] }}'">
                                                            @if (count($value['service']) > 0)
                                                                <div class="col-md-12">
                                                                    <h5 class="my-3">{{ $value['name'] }}</h5>
                                                                </div>
                                                                @php
                                                                    $count = 0;
                                                                @endphp
                                                                @foreach ($value['service'] as $service)
                                                                    @php
                                                                        $count++;
                                                                    @endphp
                                                                    <div
                                                                        class="col-md-4 col-6  col-product"
                                                                        x-show="showAll['{{ $value['name'] }}'] || {{ $count }} < 12"
                                                                    >
                                                                        <input
                                                                            type="radio"
                                                                            id="service_{{ $service['id'] }}"
                                                                            class="radio-service" name="service"
                                                                            value="{{ $service['id'] }}"
                                                                            onchange="selectService()"
                                                                        >
                                                                        <label for="service_{{ $service['id'] }}" class="list-service">
                                                                            <div class="row">
                                                                                <div class="col-12">
                                                                                    <p class="text-list-service">
                                                                                        {{ ucwords(strtolower($service['name'])) }}
                                                                                    </p>
                                                                                </div>
                                                                                <div class="col-12">
                                                                                    <p class="text-price float-start">Rp
                                                                                        {{ currency(getServicePriceByLevel($service)) }}
                                                                                    </p>
                                                                                    <img src="{{ get_icon($category->name, $service['name']) }}" width="32" class="float-end">
                                                                                </div>
                                                                            </div>
                                                                        </label>
                                                                    </div>
                                                                @endforeach
                                                            @endif
                                                        </div>
                                                        <div class="row"
                                                            x-show="{{ count($value['service']) }} > 12 && (openTab === '{{ $value['slug'] }}' || openTab === 'all')"
                                                        >
                                                            <div class="col-md-12 my-3 text-center">
                                                                <span
                                                                    @click="showAll['{{ $value['name'] }}'] = !showAll['{{ $value['name'] }}']"
                                                                    style="cursor:pointer; color: var(--flat-color-1)"
                                                                >
                                                                    <span class="text-white" x-show="!showAll['{{ $value['name'] }}']"><i class="fa fa-arrow-down"></i> Klik Ini Untuk Melihat Pilihan Lainnya </span>
                                                                    <span class="text-white" x-show="showAll['{{ $value['name'] }}']"><i class="fa fa-arrow-up"></i> Show Less</span>
                                                                </span>
                                                            </div>
                                                        </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                                @if ($category->slug == 'joki-mobile-legends-ranked')
                                                <div class="row my-3">
                                                    <div class="col-md-12 col-12 mt-2">
                                                        <label class="text-white">Jumlah Bintang</label>
                                                        <input type="text" class="form-control shadow" name="star" id="star"
                                                            placeholder="star" style="border-radius: 10px;" value="1">
                                                        <small class="text-danger star-invalid"></small>
                                                    </div>
                                                </div>
                                                @endif
                                            </div>
                                            <small class="text-danger service-invalid"></small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 mb-2">
                                    <div class="num-page">
                                        <div>3</div>
                                        <h5>
                                            Pilih Pembayaran
                                        </h5>
                                    </div>
                                    <div class="card shadow" id="payment-card">
                                        <div class="card-body">
                                            <!-- <span class="strip-primary" style="margin-left: 54px"></span> -->
                                            <style>
                                                input[type="radio"]:disabled+label {
                                                    background: var(--theme-color-3);
                                                }

                                            </style>
                                            <div class="mt-3">
                                                @include('primary.product.payment-template')
                                            </div>
                                            <small class="text-danger payment-invalid"></small>
                                        </div>
                                    </div>
                                </div>
                                <!--<div class="col-12">-->
                                <!--    <div class="num-page">-->
                                <!--        <div>4</div>-->
                                <!--        <h5>-->
                                <!--            Masukkan No Whatsapp Anda<br>-->
                                <!--            (contoh: 628123xxxxx)-->
                                <!--        </h5>-->
                                <!--    </div>-->
                                    
                                <!--    <div class="card shadow">-->
                                <!--        <div class="card-body">-->
                                <!--            <div class="form-group mt-3">-->
                                <!--                <input type="text" name="whatsapp" id="whatsapp" class="form-control" placeholder="Masukan Nomor Whatsapp Aktif 628xxxxxxxx" aria-describedby="helpId" style="border-radius: 10px;">-->
                                <!--                <small class="text-danger whatsapp-invalid"></small>-->
                                <!--            </div>-->
                                <!--        </div>-->
                                <!--    </div>-->
                                <!--</div>-->
                                
                                <!--<div class="col-12">-->
                                <!--    <div class="num-page">-->
                                <!--        <div>5</div>-->
                                <!--        <h5>-->
                                <!--            Masukkan Email Anda<br>-->
                                <!--        </h5>-->
                                <!--    </div>-->
                                    
                                <!--    <div class="card shadow">-->
                                <!--        <div class="card-body">-->
                                <!--            <div class="form-group mt-3">-->
                                <!--                <input type="text" name="email" id="email" class="form-control" placeholder="Masukan Email Anda" aria-describedby="helpId" style="border-radius: 10px;">-->
                                <!--                <small class="text-danger mail-invalid"></small>-->
                                <!--            </div>-->
                                <!--        </div>-->
                                <!--    </div>-->
                                <!--</div>-->
                                <div class="col-12">
    <div class="num-page">
        <div>4</div>
        <h5>Masukkan Kontak</h5>
    </div>
    
    <div class="card shadow">
        <div class="card-body">
            <div class="form-group mt-3">
                <label for="whatsapp" class="font-weight-bold fs-5">Nomor Whatsapp</label>
                <input type="text" name="whatsapp" id="whatsapp" class="form-control" placeholder="Masukkan Nomor Whatsapp Aktif 628XXXXXXX" aria-describedby="helpId" style="border-radius: 10px;">
                <small class="text-danger whatsapp-invalid"></small>
                <small class="form-text text-danger">* Format Nomor yang digunakan adalah 628XXXXXXX</small>
            </div>
            

        </div>
    </div>
</div>

                                <div class="col-md-12 mb-5">
                                    <div class="form-group mt-3 text-center">
                                        <button type="button" class="btn btn-md btn-primary btn-block shadow font-weight-bold" id="order-button"><i class="mdi mdi-cart"></i> Beli Sekarang</button>
                                    </div>
                                </div>

                                <div class="col-md-12 review-mobile">
                                    <div class="num-page">
                                        <div>
                                            <i class="mdi mdi-star"></i>
                                        </div>
                                        <h5>
                                            Ulasan
                                        </h5>
                                    </div>
                                    <div class="card shadow">
                                        <div class="card-body">
                                            @forelse ($reviews as $key => $value)
                                            <article class="d-flex flex-column mt-3"> <!-- Loop -->
                                                <div class="d-flex justify-content-between">
                                                    <div>
                                                        <p class="fw-bold">{{ hideString($value['order']['whatsapp_order']) }}</p>
                                                        <small>{{ $value['service']['name'] }}</small>
                                                        <p>
                                                            <i>{{ $value['comment'] }}</i>
                                                        </p>
                                                    </div>
                                                    <div>
                                                        <div class="d-flex justify-content-end fs-5">
                                                            @for ($i = 0; $i < $value['rating']; $i++)
                                                            <span class="mdi mdi-star text-warning"></span>
                                                            @endfor
                                                        </div>
                                                        <small>{{ format_datetime($value['created_at']) }}</small>
                                                    </div>
                                                </div>
                                                <hr class="border-3" style="opacity: 10%">
                                            </article> <!-- Loop End -->
                                            @empty
                                            <p class="text-center">Belum ada ulasan</p>
                                            @endforelse
                                            <a href="{{ route('review.index') }}" class="text-decoration-none">Show more <i class="fa fa-arrow-right"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <!-- Modal -->
                    <div class="modal fade modal-order" id="modal-order" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title fw-bold" id="modal-order-title"></h5>
                                </div>
                                <div class="modal-body" id="modal-order-body">

                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-md btn-secondary fw-bold"
                                        data-bs-dismiss="modal">Tutup</button>
                                    <button type="button" class="btn btn-md btn-primary fw-bold"
                                        id="modal-order-button"><i class="mdi mdi-cart"></i> Beli Sekarang</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('script')
    <script>
        // Listen for the Livewire event
        Livewire.on('loadServices', (data) => {
            // Update the Alpine.js variable with the data
        });
    </script>
    <script>

    </script>
    @endpush
</div>
