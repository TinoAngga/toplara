<div>
    <div class="container">
        <div class="row">
            <div class="col-12 text-nowrap mb-2" style="background-color: var(--theme-color-2); border-bottom-left-radius: 0.5rem; border-bottom-right-radius: 0.5rem;">
                <div class="row">
                    <div class="col-md-12 product-category mt-0" style="overflow: auto;" >
                        <ul class="nav nav-pills d-inline-block pb-3 pt-md-3" >
                            <li class="nav-item d-inline-block" style="padding-left: .2rem; padding-right: .2rem;">
                                <a
                                    href="#"
                                    wire:click.prevent="selectServiceCategoryType('all')"
                                    class="nav-link @if($selectServiceCategoryType == 'all') nav-active @endif"
                                >
                                    Semua
                                </a>
                            </li>
                            @foreach ($serviceCategoryTypeMenus as $key => $value)
                                <li class="nav-item d-inline-block" style="padding-left: .2rem; padding-right: .2rem;">
                                    <a
                                        href="#"
                                        wire:click.prevent="selectServiceCategoryType('{{ $value->slug }}')"
                                        class="nav-link @if($value->slug == $selectServiceCategoryType) nav-active @endif"
                                    >
                                        {{ ucwords(str_replace('-', ' ', $value->name)) }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            @foreach ($serviceCategoryTypes as $type)
            <div class="row mt-3" id="{{ $type->slug }}">
                <div class="col-12">
                    <div class="text-shadow text-uppercase text-with-strip">
                        {{-- <i class="{{ $type->icon }} me-2"></i> --}}
                        <h5>{{ ucwords(str_replace('-', ' ', $type->name)) }}</h5>
                    </div>
                </div>
            </div>
            <div class="row game mt-3">
                @foreach ($serviceCategories as $key => $value)
                    @if ($value->service_type == $type->slug)
                        <div
                            class="col-sm-3 col-lg-2 col-4 text-center my-1"
                            style="padding: 0px 6px; display: grid;"
                        >
                            <a href="{{ url('/product/' . $value->service_type . '/category/' . $value->slug) }}" class="text-decoration-none">
                                <div
                                    class="card-custom w-hover img-hover-zoom rounded-4 overflow-hidden position-relative"

                                >
                                    <img
                                        fetchpriority="high"
                                        decoding="async"
                                        src="{{ asset(config('constants.options.asset_img_service_category') . $value->img) }}"
                                        {{-- data-src="{{ asset(config('constants.options.asset_img_service_category') . $value->img) }}" --}}
                                        class="card-img-custom w-100 h-100 object-fit-cover lazyload"
                                        alt="Top Up {{ $value->name }}"
                                        srcset="{{ asset(config('constants.options.asset_img_service_category') . $value->img) }} 500w, {{ asset(config('constants.options.asset_img_service_category') . $value->img) }} 1000w, {{ asset(config('constants.options.asset_img_service_category') . $value->img) }} 1500w"
                                        height="400"
                                        width="400"
                                    >
                                    <h6 class="card-title-game text-center">{{ $value->name }}</h6>
                                    <span aria-hidden="true" class="absolute h-full gradient-bg-card"></span>


                                </div>
                            </a>
                            <div class="mt-4 d-none d-md-block"></div>
                        </div>
                    @endif
                @endforeach

            </div>
            @endforeach
        </div>
    </div>
    <style>
        .btn-topup {
            width: 80%;
            max-width: 100px;
        }

        .btn-topup:hover {
            color: #fff3e2 !important;
            width: 90%;
        }
        .col-hp {
            flex: 0 0 auto;
            width: 100%;
            font-size: 12px;
        }
        .size-img-product {
            width: 65%;
        }
        .rounded-img-product {
            border-radius: 0.5rem !important;
        }
    </style>
    <div class="pb-4">
        <div class="container">

        </div>
    </div>
    @push('script')
    <script>
    </script>
    @endpush

</div>
