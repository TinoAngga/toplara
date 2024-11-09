<div class="table-responsive">
    <table class="table table-bordered">
        <tr>
            <th>ID</th>
            <td>{{ $deposit->id }}</td>
        </tr>
        <tr>
            <th>Pengguna</th>
            <td>{{ $deposit->user->username }}</td>
        </tr>
        <tr>
            <th>Pembayaran</th>
            <td>{{ $deposit->payment->name }}</td>
        </tr>
        <tr>
            <th>Invoice</th>
            <td>{{ $deposit->invoice }}</td>
        </tr>
        <tr>
            <th>Pembayaran</th>
            <td>{{ $deposit->payment->name }} ({{ strtoupper($deposit->payment->payment_gateway ?? '-') }})</td>
        </tr>
        <tr>
            <th>Jumlah</th>
            <td>{{ 'Rp ' . currency($deposit->amount) }}</td>
        </tr>
        <tr>
            <th>Saldo</th>
            <td>{{ 'Rp ' . currency($deposit->balance) }}</td>
        </tr>
        <tr>
            <th>Kode Unik</th>
            <td>{{ 'Rp ' . currency($deposit->unique_code) }}</td>
        </tr>
        <tr>
            <th>Fee</th>
            <td>{{ 'Rp ' . currency($deposit->fee - $deposit->unique_code) }}</td>
        </tr>
        <tr>
            <th>Lunas</th>
            <td>{!! isPaid($deposit->is_paid) !!}</td>
        </tr>
        <tr>
            <th>Status</th>
            <td>{!! badgeStatus($deposit->status, $deposit->is_paid) !!}</td>
        </tr>
        <tr>
			<th width="50%">DIBUAT</th>
			<td>
				{{ parseCarbon($deposit->created_at)->translatedFormat('d F Y - H:i') }}
				({{ parseCarbon($deposit->created_at)->diffForHumans() }})
			</td>
		</tr>
		<tr>
			<th width="50%">DIPERBARUI</th>
			<td>
				{{ parseCarbon($deposit->updated_at)->translatedFormat('d F Y - H:i') }}
				({{ parseCarbon($deposit->updated_at)->diffForHumans() }})
			</td>
		</tr>
    </table>
</div>
