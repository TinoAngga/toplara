{{-- <style>.detail {position: relative !important; max-width: 100% !important;margin-top: -300px !important;padding: 0.8em 1em 0 !important;color: inherit !important;font-size: 1.875em !important;font-weight: 600 !important;text-align: left !important;text-transform: none !important;word-wrap: break-word !important;}</style> --}}
<div>
    <div class="text-center">
        <img src="{{ asset('assets/img/check.png') }}" alt="" width="100" class="mb-3">
        <h6 class="mb-3">Konfirmasi Pesanan</h6>
    </div>
    <div class="px-5-desktop">
        <div id="modal-order-body"><div>
            <div class="table-responsive">
                <table class="table" style="color: var(--theme-font-color-1) !important">
                    @if (!is_null($getNickname))
                    <tr>
                        <th>NICKNAME</th>
                        <td>{{ $getNickname['data'] }}</td>
                    </tr>
                    @endif
                    @if (preg_match("/joki-mobile-legend/i", $service->category->slug))
                        <tr>
                            <th>Email / No. HP</th>
                            <td>{{ $additionalInfo['data'] }}</td>
                        </tr>
                        <tr>
                            <th>PASSWORD</th>
                            <td>{{ $additionalInfo['additional_data'] }}</td>
                        </tr>
                        <tr>
                            <th>Login</th>
                            <td>{{ $additionalInfo['login'] }}</td>
                        </tr>
                        @if (isset($additionalInfo['hero']))
                            <tr>
                                <th>Hero</th>
                                <td>{{ $additionalInfo['hero'] }}</td>
                            </tr>
                        @endif
                        <tr>
                            <th>Catatan</th>
                            <td>{{ $additionalInfo['note'] }}</td>
                        </tr>
                        <tr>
                            <th>User ID & Nickname</th>
                            <td>{{ $additionalInfo['user_nickname'] }}</td>
                        </tr>
                        @if(isset( $additionalInfo['star']) AND  $additionalInfo['star'])
                        <tr>
                            <th>Total Bintang</th>
                            <td>{{ $additionalInfo['star'] }}</td>
                        </tr>
                        @endif

                        <tr>
                            <th>Whatsapp</th>
                            <td>{{ $additionalInfo['whatsapp'] }}</td>
                        </tr>
                    @else
                        <tr>
                            <th>DATA</th>
                            <td>{{ $target }}</td>
                        </tr>
                    @endif
                    <tr>
                        <th>PRODUK</th>
                        <td>{{ $service->category->name . ' - ' . $service->name }}</td>
                    </tr>
                    <tr>
                        <th>PEMBAYARAN</th>
                        <td>{{ strtoupper($payment->name) }}</td>
                    </tr>
                    <tr>
                        <th>HARGA</th>
                        <td>{{ 'Rp ' . currency($price['price']) }}</td>
                    </tr>
                    <tr>
                        <th>BIAYA ADMIN</th>
                        <td>{{ 'Rp ' . currency(ceil($price['fee'] + ($price['price'] * convertPercent($price['feePercent'])))) }}</td>
                    </tr>
                    <tr>
                        <th>TOTAL</th>
                        <td><b>{{ 'Rp ' . currency($price['totalPrice']) }}</b></td>
                    </tr>
                </table>
            </div>
        </div>
