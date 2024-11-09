<div class="table-responsive">
    <table class="table table-bordered">
        <tr>
            <th>Provider</th>
            <td>{{ $service->provider->name }}</td>
        </tr>

        <tr>
            <th>Kategori</th>
            <td>{{ $service->category->name }}</td>
        </tr>

        <tr>
            <th>Nama</th>
            <td>{{ $service->name }}</td>
        </tr>

        <tr>
            <th>Kode Provider Layanan</th>
            <td>{{ $service->provider_service_code }}</td>
        </tr>

        <tr>
            <th>Harga</th>
            <td>
                @foreach (config('constants.options.member_level') as $key => $value)
                    <li> {{ ucwords($key) }} : {{ 'Rp ' . currency($service->price->{$key}) }}</li>
                @endforeach
            </td>
        </tr>

        <tr>
            <th>Profit</th>
            <td>
                @foreach (config('constants.options.member_level') as $key => $value)
                <li> {{ ucwords($key) }} : {{ 'Rp ' . currency($service->profit->{$key}) }}</li>
                @endforeach
            </td>
        </tr>

        <tr>
            <th>Rate Koin</th>
            <td>
                {{
                ($service->is_rate_coin == 1)
                    ? 'YA (RATE KOIN : '.$service->rate_coin.' | HARGA RATE KOIN : '.$service->price_rate_coin.')'
                    : 'TIDAK'
                }}
            </td>
        </tr>

        <tr>
            <th>Status</th>
            <td>{{ ($service->is_active == 1) ? 'YA' : 'TIDAK' }}</td>
        </tr>

        <tr>
			<th width="50%">DIBUAT</th>
			<td>
				{{ parseCarbon($service->created_at)->translatedFormat('d F Y - H:i') }}
				({{ parseCarbon($service->created_at)->diffForHumans() }})
			</td>
		</tr>
		<tr>
			<th width="50%">DIPERBARUI</th>
			<td>
				{{ parseCarbon($service->updated_at)->translatedFormat('d F Y - H:i') }}
				({{ parseCarbon($service->updated_at)->diffForHumans() }})
			</td>
		</tr>
    </table>
</div>
