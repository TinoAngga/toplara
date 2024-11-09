<div class="table-responsive">
    <table class="table table-bordered">
        <tr>
            <th>ID</th>
            <td>{{ $order->id }}</td>
        </tr>
        <tr>
            <th>Tipe Pesanan</th>
            <td>{{ strtoupper($order->order_type) }}</td>
        </tr>
        <tr>
            <th>Invoice</th>
            <td>{{ $order->invoice }}</td>
        </tr>
        <tr>
            <th>Pengguna</th>
            <td>{{ $order->user->username ?? 'PUBLIK' }}</td>
        </tr>
        <tr>
            <th>Pembayaran</th>
            <td>{{ strtoupper($order->payment->name) }} ({{ strtoupper($order->payment->payment_gateway ?? '-') }})</td>
        </tr>
        <tr>
            <th>Layanan</th>
            <td>{{ $order->service->name }} ({{ strtoupper($order->service->category->name ?? '-') }})</td>
        </tr>
        <tr>
            <th>Provider</th>
            <td>{{ $order->provider->name }}</td>
        </tr>
        <tr>
            <th>Data</th>
            <td>{!! clipboardCopy($order->data ?? '0', 'data-data-'.$order->id) !!}</td>
        </tr>
        <tr>
            <th>Data Tambahan</th>
            <td>{!! clipboardCopy($order->additional_data ?? '0', 'data-additional-data-'.$order->id) !!}</td>
        </tr>
        @if (preg_match("/joki-mobile-legend/i", $order->service->category->slug))
        <tr>
            <th>Hero</th>
            <td>
                @php
                    $getHero = str_replace('Hero=', '', explode('|', $order->additional_info)[0]);
                @endphp
                {!! clipboardCopy($getHero, 'data-additional-hero-'.$order->id) !!}
            </td>
        </tr>
        <tr>
            <th>Login</th>
            <td>
                @php
                    $getLogin = str_replace('Login=', '', explode('|', $order->additional_info)[1]);
                @endphp
                {!! clipboardCopy($getLogin, 'data-additional-login-'.$order->id) !!}
            </td>
        </tr>
        <tr>
            <th>Catatan</th>
            <td>
                @php
                    $getNote = str_replace('Catatan=', '', explode('|', $order->additional_info)[2]);
                @endphp
                {!! clipboardCopy($getNote, 'data-additional-note-'.$order->id) !!}
            </td>
        </tr>
        <tr>
            <th>User ID & Nickname</th>
            <td>
                @php
                    $getUserNickname = str_replace('User ID & Nickname=', '', explode('|', $order->additional_info)[3]);
                @endphp
                {!! clipboardCopy($getUserNickname, 'data-additional-nickname-'.$order->id) !!}
            </td>
        </tr>
            @if (isset(explode('|', $order->additional_info)[4]) AND explode('|', $order->additional_info)[4])
                <tr>
                    <th>Total Bintang</th>
                    <td>
                        @php
                            $getStar = str_replace('Star=', '', explode('|', $order->additional_info)[4]);
                        @endphp
                        {!! clipboardCopy($getStar, 'data-additional-star-'.$order->id) !!}
                    </td>
                </tr>
            @endif
        <tr>
            <th>Whatsapp</th>
            <td>
                {{ $order->whatsapp_order ?? '-'  }}
            </td>
        </tr>
        @endif
        <tr>
            <th>Email</th>
            <td>{{ $order->email_order ?? '-' }}</td>
        </tr>
        <tr>
            <th>Provider Order ID</th>
            <td>{{ $order->provider_order_id ?? '-' }}</td>
        </tr>
        <tr>
            <th>Provider SN / Deskripsi</th>
            <td>{{ $order->provider_order_description ?? '-' }}</td>
        </tr>
        <tr>
            <th>Harga</th>
            <td>{{ 'Rp ' . currency($order->price) }}</td>
        </tr>
        <tr>
            <th>Profit</th>
            <td>{{ 'Rp ' . currency($order->profit) }}</td>
        </tr>
        <tr>
            <th>Kode Unik</th>
            <td>{{ 'Rp ' . currency($order->unique_code) }}</td>
        </tr>
        <tr>
            <th>Fee</th>
            <td>{{ 'Rp ' . currency($order->fee - $order->unique_code) }}</td>
        </tr>
        <tr>
            <th>Lunas</th>
            <td>{!! isPaid($order->is_paid) !!}</td>
        </tr>
        <tr>
            <th>Status</th>
            <td>{!! badgeStatus($order->status, $order->is_paid) !!}</td>
        </tr>
                <tr>
            <th>Refund</th>
            <td>{!! isRefund($order->is_refund) !!}</td>
        </tr>
        <tr>
			<th width="50%">DIBUAT</th>
			<td>
				{{ parseCarbon($order->created_at)->translatedFormat('d F Y - H:i') }}
				({{ parseCarbon($order->created_at)->diffForHumans() }})
			</td>
		</tr>
		<tr>
			<th width="50%">DIPERBARUI</th>
			<td>
				{{ parseCarbon($order->updated_at)->translatedFormat('d F Y - H:i') }}
				({{ parseCarbon($order->updated_at)->diffForHumans() }})
			</td>
		</tr>
    </table>
</div>
