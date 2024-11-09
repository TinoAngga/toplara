<div class="table-responsive">
    <table class="table table-bordered">
        <tr>
            <th>Tipe</th>
            <td>{{ strtoupper($paymentMethod->type) }}</td>
        </tr>
        <tr>
            <th>Nama Pembayaran</th>
            <td>{{ strtoupper($paymentMethod->name) }}</td>
        </tr>
        <tr>
            <th>Gambar</th>
            <td><img src="{{ asset(config('constants.options.asset_img_payment_method') . $paymentMethod->img) }}" class="img-fluid" alt=""></td>
        </tr>
        <tr>
            <th>Fee & Fee Persen</th>
            <td>{{ 'Rp ' . currency($paymentMethod->fee) . ' & ' . $paymentMethod->fee_percent }}</td>
        </tr>
        <tr>
            <th>Deskripsi</th>
            <td>{{ strip_tags($paymentMethod->description) }}</td>
        </tr>
        <tr>
            <th>Information</th>
            <td>{{ strip_tags($paymentMethod->information) }}</td>
        </tr>
        <tr>
            <th>QR Code</th>
            <td>@if(!is_null($paymentMethod->qrcode)) <img src="{{ asset(config('constants.options.asset_img_qr_code') . $paymentMethod->qrcode) }}" class="img-fluid" alt=""> @else - @endif</td>
        </tr>
        <tr>
            <th>Payment Gateway & Kode Payment Gateway</th>
            <td>{{ strtoupper($paymentMethod->payment_gateway) . ' & ' . strtoupper($paymentMethod->payment_gateway_code)}} </td>
        </tr>
        <tr>
            <th>Publik / Tamu</th>
            <td>{{ ($paymentMethod->is_public == 1) ? 'YA' : 'TIDAK' }}</td>
        </tr>
        <tr>
            <th>Manual</th>
            <td>{{ ($paymentMethod->is_manual == 1) ? 'YA' : 'TIDAK' }}</td>
        </tr>
        <tr>
            <th>Aktif</th>
            <td>{{ ($paymentMethod->is_active == 1) ? 'YA' : 'TIDAK' }}</td>
        </tr>
        <tr>
			<th width="50%">DIBUAT</th>
			<td>
				{{ parseCarbon($paymentMethod->created_at)->translatedFormat('d F Y - H:i') }}
				({{ parseCarbon($paymentMethod->created_at)->diffForHumans() }})
			</td>
		</tr>
		<tr>
			<th width="50%">DIPERBARUI</th>
			<td>
				{{ parseCarbon($paymentMethod->updated_at)->translatedFormat('d F Y - H:i') }}
				({{ parseCarbon($paymentMethod->updated_at)->diffForHumans() }})
			</td>
		</tr>
    </table>
</div>
