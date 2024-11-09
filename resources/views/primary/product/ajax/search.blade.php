@if ($search <> '')
    @foreach ($serviceCategories as $key => $value)
    <div class="col-12 text-center" style="display: grid;overflow: hidden;padding: 5px 5px;" onclick="window.location='{{ url('/product/' . $value->service_type . '/category/' . $value->slug) }}'">
        <div class="game-populer-card" style="overflow: hidden;">
            <div class="row">
                <div class="col-2">
                    <img
                        fetchpriority="high"
                        decoding="async"
                        class="game-img-populer lazy"
                        {{-- src="{{ asset(config('constants.options.asset_img_service_category') . $value->img) }}" --}}
                        data-src="{{ asset(config('constants.options.asset_img_service_category') . $value->img) }}"
                        srcset="{{ asset(config('constants.options.asset_img_service_category') . $value->img) }} 500w, {{ asset(config('constants.options.asset_img_service_category') . $value->img) }} 1000w, {{ asset(config('constants.options.asset_img_service_category') . $value->img) }} 1500w"
                        loading="lazy"
                        aria-label="Top Up {{ $value->name }}"
                        height="auto" width="auto"
                    />
                </div>
                <div class="col-10 text-end mt-4">
                    <h6 class="float-start ms-3 text-uppercase"> {{ $value->name }} </h6>
                </div>
            </div>
        </div>
    </div>
    @endforeach
@else
    @foreach (getPopularCategory() as $key => $value)
    <div class="col-12 text-center" style="display: grid;overflow: hidden;padding: 5px 5px;" onclick="window.location='{{ url('/product/' . $value->service_type . '/category/' . $value->slug) }}'">
        <div class="game-populer-card" style="overflow: hidden;">
            <div class="row">
                <div class="col-2">
                    <img
                        fetchpriority="high"
                        decoding="async"
                        class="game-img-populer lazy"
                        {{-- src="{{ asset(config('constants.options.asset_img_service_category') . $value->img) }}" --}}
                        data-src="{{ asset(config('constants.options.asset_img_service_category') . $value->img) }}"
                        srcset="{{ asset(config('constants.options.asset_img_service_category') . $value->img) }} 500w, {{ asset(config('constants.options.asset_img_service_category') . $value->img) }} 1000w, {{ asset(config('constants.options.asset_img_service_category') . $value->img) }} 1500w"
                        loading="lazy"
                        aria-label="Top Up {{ $value->name }}"
                        height="auto" width="auto"
                    />
                </div>
                <div class="col-10 text-end mt-4">
                    <h6 class="float-start ms-3 text-uppercase"> {{ $value->name }} </h6>
                </div>
            </div>
        </div>
    </div>
    @endforeach
@endif
