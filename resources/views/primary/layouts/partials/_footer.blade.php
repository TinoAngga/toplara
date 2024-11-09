    <div href="#" class="act-btn-top back-to-top" onclick="toTop()" style="display: none;">
        <i class="fa fa-angle-double-up"></i>
    </div>
    <!--<div class="fab-container">-->
    <!--    <div class="fab fab-icon-holder" style="background-color:#FFF; padding:5px">-->
    <!--        <img data-src="{{ asset('assets/img/call-center.png') }}" class="img-fluid lazyload" alt="Call Center {{ getConfig('title') }}">-->
    <!--    </div>-->
    <!--    <ul class="fab-options">-->
            <!--<li>-->
            <!--    <a href="https://wa.me/{{ getConfig('social_media_whatsapp') }}" class="text-decoration-none" target="_blank" role="link" aria-labelledby="Whatsapp {{ getConfig('title') }}" aria-label="Whatsapp {{ getConfig('title') }}">-->
            <!--        <div class="fab-icon-holder" style="background-color: #25D366;">-->
            <!--            <i class="fab fa-whatsapp"></i>-->
            <!--        </div>-->
            <!--    </a>-->
            <!--</li>-->
            <!--<li>-->
            <!--    <a href="https://instagram.com/{{ getConfig('social_media_instagram') }}" class="text-decoration-none" target="_blank" role="link" aria-labelledby="{{ getConfig('title') }}" aria-label="Instagram {{ getConfig('title') }}">-->
            <!--        <div class="fab-icon-holder" style="background: radial-gradient(circle farthest-corner at 35% 90%, #fec564, transparent 50%), radial-gradient(circle farthest-corner at 0 140%, #fec564, transparent 50%), radial-gradient(ellipse farthest-corner at 0 -25%, #5258cf, transparent 50%), radial-gradient(ellipse farthest-corner at 20% -50%, #5258cf, transparent 50%), radial-gradient(ellipse farthest-corner at 100% 0, #893dc2, transparent 50%), radial-gradient(ellipse farthest-corner at 60% -20%, #893dc2, transparent 50%), radial-gradient(ellipse farthest-corner at 100% 100%, #d9317a, transparent), linear-gradient(#6559ca, #bc318f 30%, #e33f5f 50%, #f77638 70%, #fec66d 100%);">-->
            <!--            <i class="fab fa-instagram"></i>-->
            <!--        </div>-->
            <!--    </a>-->
            <!--</li>-->
            <!--<li>-->
            <!--    <a href="https://facebook.com/{{ getConfig('social_media_facebook_url') }}" class="text-decoration-none" target="_blank" role="link" aria-labelledby="Facebook {{ getConfig('title') }}" aria-label="Facebook {{ getConfig('title') }}">-->
            <!--        <div class="fab-icon-holder" style="background-color: #0F92F3;">-->
            <!--            <i class="fab fa-facebook-f"></i>-->
            <!--        </div>-->
            <!--    </a>-->
            <!--</li>-->
            <!--<li>-->
            <!--    <a href="mailto:{{ getConfig('contact_email_address') }}" class="text-decoration-none" target="_blank" role="link" aria-labelledby="Email Address {{ getConfig('title') }}" aria-label="Email Address {{ getConfig('title') }}">-->
            <!--        <div class="fab-icon-holder" style="background-color: #2FA6DE;">-->
            <!--            <i class="fa fa-envelope"></i>-->
            <!--        </div>-->
            <!--    </a>-->
            <!--</li>-->
                    <!-- <li>
    <!--            <a href="https://tiktok.com/" class="text-decoration-none" target="_blank">-->
    <!--                <div class="fab-icon-holder" style="background-color: #000000;">-->
    <!--                    <i class="fab fa-tiktok"></i>-->
    <!--                </div>-->
    <!--            </a>-->
    <!--        </li> -->-->
    <!--    </ul>-->
    <!--    <a href="#" class="act-btn-top text-decoration-none" onclick="toTop()" style="display: none; background-color: #bd4cae; bottom: 19px;">-->
    <!--        <i class="fas fa-angle-up mt-2"></i>-->
    <!--    </a>-->
    <!--</div>-->
    <!--<a href="https://api.whatsapp.com/send?phone={{ getConfig('social_media_whatsapp') }}">
        <img src="https://hantamo.com/free/whatsapp.svg" class="whatsapp-button" alt="Whatsapp-Button"/>
    </a> -->
    <style>
        .whatsapp-button{
            width:50px;
            height:50px;
            position:fixed;
            bottom:20px;
            right:20px;
            z-index:100;
        }
    </style>
    <div class="toast-container position-fixed bottom-0 start-0 p-3" style="z-index: 999999">
        <div id="notif-toast" class="toast" role="alert" aria-live="assertive" aria-atomic="true" style="border-radius: 0.7rem !important">
          <div class="toast-header-custom bg-flat-primary text-white">
            <img src="" class="rounded me-2 notif-img" alt="...">
            <strong class="me-auto notif-phone"></strong>
            <button type="button" class="btn-close text-white" data-bs-dismiss="toast" aria-label="Close"></button>
          </div>
          <div class="toast-body bg-flat-primary notif-text">

          </div>
        </div>
    </div>
    <!--End wrapper-->

    <!--End wrapper-->
    <!-- Bootstrap core JavaScript-->
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <!-- Bootstrap core JavaScript-->
    <script defer src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <!--Data Tables js -->
    <script defer src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script defer src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>
    <script src="{{ asset('js/lazyload.min.js') }}"></script>
    <script src="{{ asset('js/main.custom.js') }}"></script>

    @livewireScripts
    @vite([
        'resources/js/app.js',
    ])
    <script>
        actBtnTop = document.querySelector(".act-btn-top");
        window.onscroll = function() {
            scrollFunc();
        };

        function scrollFunc() {
            if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
                actBtnTop.style.display = "block";
            } else {
                actBtnTop.style.display = "none";
            }
        }

        function toTop() {
            document.body.scrollTop = 0;
            document.documentElement.scrollTop = 0;
        }
        $(document).ready(function () {
            function close_result(){
                $('.modal-result-search-product').addClass('d-none');
            };
            $(document).ready(function() {
                $('.form-search-product, .btn-form-search-product, .form-search-product-mobile').on('click', function(){
                    $('#modal-search-product').modal('show');
                    $.ajax({
                        url: '{{ route('product.search') }}',
                        method: 'GET',
                        processData: false,
                        dataType: 'json',
                        contentType: false,
                        beforeSend: function() {
                            $('#search-product').val('');
                            $('.modal-result-search-product').empty();
                        },
                        success: function(data) {
                            if (data.status == false) {
                                $('.modal-result-search-product').append(data.html);
                                $('#search-product').focus();
                            }
                        },
                        error: function() {

                        },
                    });
                });
                $('#search-product').on('input', function(e){
                    e.preventDefault();
                    var keyword = $('#search-product').val();
                    if(keyword.length > 1){
                        $.get('{{ route('product.search') }}', {
                        'q': keyword
                    }, function(data, textStatus, xhr) {
                        if (data.html) {
                            $('.modal-result-search-product').empty();
                            // $('.modal-result-search-product').append('<span><button class="btn p-0 mr-auto" onclick="close_result()" style="position: absolute; right: 20px;background: transparent;">x</button></span>');
                            $('.modal-result-search-product').append(data.html);
                        } else {
                            $('.modal-result-search-product').empty();
                            $('.modal-result-search-product').append('<span class="d-flex text-muted"><i class="fi fi-rr-triangle-warning mr-2 align-middle"></i> Maaf, Game tidak ditemukan!</span>');
                        }
                    }, 'json');
                        // $('.search-result').removeClass('d-none');
                    }
                });
            });
            window.onclick = function(event) {
                if (event.target != $('.modal-result-search-product')) {
                    // $('.modal-result-search-product').addClass('d-none');
                }
            }
        });

    </script>
    <script>
        getPopularCategory();
        async function getPopularCategory(){
            fetch('{{ url('/') }}?type=getPopularCategory').then(function (response) {
                return response.text();
            }).then(function (html) {
                document.getElementById('getPopularCategory').innerHTML = html;
            }).catch(function (err) {
                console.warn('Something went wrong.', err);
            });
        }
    </script>
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
        });

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
    <!--<script>-->
    <!--    setInterval(function() {-->
    <!--        $.get('{{ route('order.pop-up-notification') }}', function(order) {-->
    <!--            const notifToast = document.getElementById('notif-toast')-->
    <!--            const toast = new bootstrap.Toast(notifToast)-->

    <!--            if (order.status == true) {-->
    <!--                toast.show();-->
    <!--                $('.notif-img').attr('src', order.data.img);-->
    <!--                $('.notif-phone').text(order.data.phone);-->
    <!--                $('.notif-text').text(order.data.text);-->
    <!--            } else {-->
    <!--                toast.hide()-->
    <!--            }-->
    <!--        });-->
    <!--    }, 10000);-->
    <!--</script>-->
    
    <!--Start of Tawk.to Script-->
    <!--<script type="text/javascript">-->
    <!--var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();-->
    <!--(function(){-->
    <!--var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];-->
    <!--s1.async=true;-->
    <!--s1.src='https://embed.tawk.to/670361f937379df10df314bc/1i9ijdebs';-->
    <!--s1.charset='UTF-8';-->
    <!--s1.setAttribute('crossorigin','*');-->
    <!--s0.parentNode.insertBefore(s1,s0);-->
    <!--})();-->
    <!--</script>-->
    <!--End of Tawk.to Script-->

<script type="text/javascript">window.$crisp=[];window.CRISP_WEBSITE_ID="6ebb646d-5616-4cd3-9cb8-3b2ce248af69";(function(){d=document;s=d.createElement("script");s.src="https://client.crisp.chat/l.js";s.async=1;d.getElementsByTagName("head")[0].appendChild(s);})();</script>
    
    
    
    @yield('script')
    @stack('script')
    <div class="modal fade" id="modal-form" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
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
    <div class="modal fade" id="modal-search-product" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content p-2">
                <div class="input-group mb-0">
                    <span class="input-group-text text-white" style="border: none!important;background: none!important">
                        <i class="fa fa-search"></i>
                    </span>
                    <input class="form-control" id="search-product" placeholder="Cari produk..." type="search" style="border: none !important" autocomplete="off">
                </div>
                <div class="modal-body" id="modal-search-product-body">
                    <div class="row modal-search-product mb-2">
                        <div class="modal-result-search-product">
                        </div>
                    </div>
                    <div class="float-end">
                        <button type="button" class="btn btn-md btn-danger" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
