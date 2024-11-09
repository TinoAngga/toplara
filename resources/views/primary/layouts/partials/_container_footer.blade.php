<footer id="aboutus" class="bg-footer">
    <div class="custom-shape-divider-top-1686901712">
        <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
            <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z" class="shape-fill"></path>
        </svg>
    </div>
    <div style="margin-top: -4px;">
        <div class="pt-5 pb-5">
            {{-- FIRST FOOTER:DANANG --}}
            <section id="first-footer" class="px-md-10rem px-lg-17rem overflow-hidden">
                <div class="text-center px-md-5">
                    <h5>Tentang Topup Legal</h5>
                    <p class="mt-3 px-5">Topup Legal adalah platform penjualan produk hiburan digital yang menawarkan harga dan layanan paling menarik untuk semua pelanggan. Pedagang dan pihak yang bekerja sama dengan kami semuanya berasal dari perusahaan terpercaya. Selain itu, metode pembayaran yang kami dukung mudah digunakan.</p>
                </div>
                <div class="row gap-4 gap-md-0 text-center p-3 mt-5">
                    @php
                        $aboutSection = (getConfig('about_section') == null) ? [] : json_decode(getConfig('about_section'), true);
                    @endphp
                    @foreach ($aboutSection as $key => $value)
                    <article class="col-md-6">
                        <div class="d-flex gap-3">
                            <div>
                                <div class="p-3" style  ="background: {{ $value['bg_color'] }}">
                                    {!! $value['icon'] !!}
                                </div>
                            </div>
                            <div class="text-start">
                                <h5>{{ $value['title'] }}</h5>
                                <p>{{ $value['description'] }}</p>
                            </div>
                        </div>
                    </article>
                    @endforeach

                </div>
            </section>
            <!--<section class="bg-theme1 mb-5 mt-3 py-3 py-md-5 p-5 mx-md-10rem mx-lg-17rem rounded-md-1rem">-->
            <!--    <article class="row gap-3 gap-lg-0 justify-content-betwen align-items-center">-->
            <!--        <div class="col-lg-6 px-2 text-center text-lg-start">-->
            <!--            <h5>Mendaftarkan Akun</h5>-->
            <!--            <p class="mt-3">Yuk gabung! Untuk melihat catatan transaksi kamu serta memberi ulasan pada produk yang telah kamu beli.</p>-->
            <!--        </div>-->
            <!--        <div class="col-lg-6 px-2 px-md-5 d-flex justify-content-center">-->
            <!--            <a class="btn btn-flat-primary rounded-1 w-100 fw-bold py-3 d-flex justify-content-center align-items-center gap-3" href="{{ route('auth.register.get') }}">-->
            <!--                Buat Akun Baru-->
            <!--                <svg class="d-none d-md-block" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8"/></svg>-->
            <!--            </a>-->
            <!--        </div>-->
            <!--    </article>-->
            <!--</section>-->
            {{-- END FIRST FOOTER --}}

            <div class="container">
                <div class="row">
                    <div class="col-md-4 mb-5">
                        @if (getConfig('logo'))
                            <h5 class="pb-2">
                                <img data-src="{{ asset(config('constants.options.asset_img_website') . getConfig('logo')) }}" alt="{{ getConfig('bartitle') }}" height="33" width="33" class="rounded lazyload"> {{ getConfig('title') }}
                            </h5>
                        @else
                            <h5 class="pb-2">
                                <i class="mdi mdi-gamepad"></i> {{ getConfig('title') }}
                            </h5>
                        @endif

                        <span class="strip-primary mb-2"></span>
                        <p class="mt-4 text-white">{{ getConfig('meta_tags_description') ?? '-' }}</p>
                    </div>
                    <div class="col-md-4 mb-5">
                        <h5 class="pb-2">Produk Terpopuler</h5>
                        <span class="strip-primary mb-2"></span>
                        <div class="row mt-4" id="getPopularCategory">
                        </div>
                    </div>
                    <div class="col-md-4 mb-5">
                        <h5 class="pb-2">Hubungi Kami</h5>
                        <span class="strip-primary mb-2"></span>
                        <div class="mt-4">
                            <a href="https://instagram.com/{{ getConfig('social_media_instagram') }}" target="_blank" role="link"
                                style="font-size: 20px; text-decoration: none;">
                                <i class="mdi mdi-instagram mr-4"></i><font size="2"> {{ getConfig('social_media_instagram') }} </font>
                            </a>
                            <br>
                            <a href="https://wa.me/{{ getConfig('social_media_whatsapp') }}" target="_blank" role="link"  style="font-size: 20px; text-decoration: none;">
                                <i class="mdi mdi-whatsapp mr-4"></i><font size="2"> {{ getConfig('social_media_whatsapp') }} </font>
                            </a>
                            <br>
                            <a href="https://facebook.com/{{ getConfig('social_media_facebook_url') }}" target="_blank" role="link"
                                style="font-size: 20px; text-decoration: none;">
                                <i class="mdi mdi-facebook mr-4"></i><font size="2"> {{ getConfig('social_media_facebook_name') }} </font>
                            </a><br>
                            <a href="mailto:{{ getConfig('contact_email_address') }}" target="_blank" role="link"
                                style="font-size: 20px; text-decoration: none;">
                                <i class="mdi mdi-email mr-4"></i><font size="2"> {{ getConfig('contact_email_address') }} </font>
                            </a>
                        </div>
                    </div>
                    <div class="col-md-4 mb-5">
                        <h5 class="pb-2">Halaman</h5>
                        <span class="strip-primary mb-2"></span>
                        <ul class="menu-list mt-4">
                            @foreach (getSiteMap() as $key => $value)
                                <li>
                                    <a href="{{ url('page/sitemap/' . $value->slug) }}">{{ ucfirst(strtolower($value->title)) }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="col-md-4 mb-5">
                        <h5 class="pb-2">Metode Pembayaran</h5>
                        <span class="strip-primary mb-2"></span>
                        <div class="mt-4">
                        <marquee>
                          <img data-src="{{ url('cdn/payment-method/list/') }}/bca_footer.png" alt="BCA" width="80px" class="ml-3 bg-white p-1 lazyload">
                          <img data-src="{{ url('cdn/payment-method/list/') }}/linkaja_footer.png" alt="LINK AJA" width="80px" class="ml-3 bg-white p-1 lazyload">
                          <img data-src="{{ url('cdn/payment-method/list/') }}/shopay_footer.png" alt="SHOPEEPAY" width="80px" class="ml-3 bg-white p-1 lazyload">
                          <img data-src="{{ url('cdn/payment-method/list/') }}/ovo_footer.png" alt="OVO" width="80px" class="ml-3 bg-white p-1 lazyload">
                          <img data-src="{{ url('cdn/payment-method/list/') }}/gopay_footer.png" alt="GOPAY" width="80px" class="ml-3 bg-white p-1 lazyload">
                          <img data-src="{{ url('cdn/payment-method/list/') }}/dana_footer.png" alt="DANA" width="80px" class="ml-3 bg-white p-1 lazyload">
                          <img data-src="{{ url('cdn/payment-method/list/') }}/qris_footer.png" alt="QRIS" width="80px" class="ml-3 bg-white p-1 lazyload">
                          <img data-src="{{ url('cdn/payment-method/list/') }}/indomaret_footer.png" alt="INDOMARET" width="80px" class="ml-3 bg-white p-1 lazyload">
                      </marquee>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="bg-theme1 text-center pb-4 pt-4">
        {{ !is_null(getConfig('footer_description')) ? convertString(getConfig('footer_description')) : '© '.date('Y').' '.getConfig('title').' - All Rights Reserved.' }}
    </div>
</footer>
