<div class="table-responsive">
    <table class="table table-bordered">
        <tr>
            <th>ID</th>
            <td>{{ $order->id }}</td>
        </tr>
        <tr>
            <th>Invoice</th>
            <td>{{ $order->invoice }}</td>
        </tr>
        <tr>
            <th>Tipe Order</th>
            <td>{{ strtoupper($order->order_type) }}</td>
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
