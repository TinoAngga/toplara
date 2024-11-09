<div>


    <div class="container" x-data="alpineJS">

        <div class="row">
            <div class="col-12 text-nowrap mb-2" style="background-color: var(--theme-color-2); border-bottom-left-radius: 0.5rem; border-bottom-right-radius: 0.5rem;">

                <div class="row">
                    <div class="col-md-12 product-category mt-0" style="overflow: auto;" >
                        <ul class="nav nav-pills d-inline-block py-3" >
                            <li
                                x-on:click="openTab = 'all'" :class="{ 'nav-active': openTab === 'all' }"
                                class="nav-item d-inline-block nav-category nav-link"
                                style="padding-left: .2rem; padding-right: .2rem; cursor: pointer;"
                            >
                                Semua
                            </li>

                            @foreach ($serviceCategoryTypeMenus as $key => $value)
                                <li
                                    x-on:click="openTab = '{{ $value->slug }}'" :class="{ 'nav-active': openTab === '{{ $value->slug }}' }"
                                    class="nav-item d-inline-block nav-category nav-link"
                                    style="padding-left: .2rem; padding-right: .2rem; cursor: pointer;"
                                >
                                    {{ ucwords(str_replace('-', ' ', $value->name)) }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <template x-for="serviceCategoryType in serviceCategoryTypes" :key="serviceCategoryType.id">
                <div class="row-games" x-show="openTab === 'all' || openTab === serviceCategoryType.slug">
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="text-shadow text-uppercase text-with-strip">
                                {{-- <i class="{{ $type->icon }} me-2"></i> --}}
                                <h5 x-text="serviceCategoryType.name"></h5>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <template  x-for="(category, index) in serviceCategoryType.categories" :key="category.id">

                            <div
                                class="col-sm-3 col-lg-2 col-4 text-center my-1"
                                style="padding: 0px 6px; display: grid;"
                                x-show="showAll[serviceCategoryType.name] || index < 12"
                            >

                                <a :href="`/product/${category.service_type}/category/${category.slug}`" class="text-decoration-none">
                                    <div
                                        class="card-custom w-hover img-hover-zoom rounded-4 overflow-hidden position-relative"
                                    >
                                        <img
                                            fetchpriority="high"
                                            decoding="async"
                                            src="{{ asset('cdn/dummy-img.webp') }}"
                                            :data-src="`/cdn/service-category/${category.img}`"
                                            class="card-img-custom w-100 h-100 object-fit-cover lazyload"
                                            :alt="`Top Up ${category.name}`"
                                            height="1440"
                                            width="1440"
                                        >
                                        <div class="hover-logo">
                                            <img data-src="{{ asset(config('constants.options.asset_img_website') . getConfig('logo')) }}" alt="{{ getConfig('title') }}" alt="DolananTopUp" class="" height="45px" src="{{ asset(config('constants.options.asset_img_website') . getConfig('logo')) }}" alt="{{ getConfig('title') }}">
                                        </div>
                                        <h6 class="truncate card-title-game text-center text-uppercase" x-text="category.name"></h6>
                                        <span aria-hidden="true" class="absolute h-full gradient-bg-card"></span>
                                    </div>
                                </a>
                                <div class="mt-4 d-none d-md-block"></div>
                            </div>
                        </template>
                    </div>
                    <div class="row">
                        <div class="col-md-12 my-3 text-center">
                            <span
                                @click="showAll[serviceCategoryType.name] = !showAll[serviceCategoryType.name]"
                                x-show="serviceCategoryType.categories.length > 12"
                                style="cursor:pointer; color: var(--flat-color-1)"
                            >
                                <span x-show="!showAll[serviceCategoryType.name]" class="text-white fw-bold" style="font-size: 15px"><i class="fa fa-arrow-down"></i> Show More</span>
                                <span x-show="showAll[serviceCategoryType.name]" class="text-white fw-bold" style="font-size: 15px"><i class="fa fa-arrow-up"></i> Show Less</span>
                            </span>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>
    <div class="pb-4">
        <div class="container">

        </div>
    </div>
    @push('script')
    <script>
        const alpineJS = () => {
            return {
                serviceCategoryTypes: @json($serviceCategoryTypes),
                showAll: {},
                openTab: 'all',
                init() {
                }
            }
        }

    </script>
    <script>
        function selectCategoryType(type) {
            $(".nav-category").removeClass('active');
            $("#category-type-" + type).addClass('active');
            if (type == 'all') {
                $(".row-games").removeClass('d-none');
            } else {
                $(".row-games").addClass('d-none');
                $("#category-type-" + type).removeClass('d-none');
            }
        }
        document.addEventListener('DOMContentLoaded', function() {
            // show_more();
        });
        // function show_more() {
        //     $ShowHideMore = $(".game");
        //     $ShowHideMore.each(function() {
        //         var $times = $(this).children('.col-product');
        //         if ($times.length > 12) {
        //             $ShowHideMore.children(':nth-of-type(n+13)').addClass('more-product').hide();
        //             $(this).find("span.message").addClass('more-times').html('Show more <i class="fa fa-arrow-down"></i>');
        //         }
        //     });
        // }

        // $(document).on('click', '.game > span', function() {
        //     var that = $(this);
        //     var thisParent = that.closest('.game');
        //     if (that.hasClass('more-times')) {
        //         thisParent.find('.more-product').show();
        //         that.toggleClass('more-times', 'less-times').html('Show less <i class="fa fa-arrow-up"></i>');
        //     } else {
        //         thisParent.find('.more-product').hide();
        //         that.toggleClass('more-times', 'less-times').html('Show more <i class="fa fa-arrow-down"></i>');
        //     }
        // });
        </script>
    @endpush

</div>
