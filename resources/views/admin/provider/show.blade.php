<div class="table-responsive">
    <table class="table table-bordered">
        <tr>
            <th>Nama</th>
            <td>{{ $provider->name }}</td>
        </tr>
        <tr>
            <th>API Username / API ID</th>
            <td>{{ $provider->api_username ?? '-' }}</td>
        </tr>
        <tr>
            <th>API Key</th>
            <td>{{ $provider->api_key ?? '-' }}</td>
        </tr>
        <tr>
            <th>API Additional</th>
            <td>{{ $provider->api_additional ?? '-' }}</td>
        </tr>
        <tr>
            <th>API URL Order</th>
            <td>{{ $provider->api_url_order ?? '-' }}</td>
        </tr>
        <tr>
            <th>API URL Status</th>
            <td>{{ $provider->api_url_status ?? '-' }}</td>
        </tr>
        <tr>
            <th>API URL Service</th>
            <td>{{ $provider->api_url_service ?? '-' }}</td>
        </tr>
        <tr>
            <th>API URL Profile</th>
            <td>{{ $provider->api_url_profile ?? '-' }}</td>
        </tr>
        <tr>
            <th>Peringatan Saldo</th>
            <td>{{ $provider->api_alert_balance ?? '-' }}</td>
        </tr>
        <tr>
            <th>Auto Update Layanan</th>
            <td>{{ ($provider->is_auto_update == 1) ? 'YA' : 'TIDAK' }}</td>
        </tr>
        <tr>
            <th>Manual</th>
            <td>{{ ($provider->is_manual == 1) ? 'YA' : 'TIDAK' }}</td>
        </tr>
        <tr>
            <th>Aktif</th>
            <td>{{ ($provider->is_active == 1) ? 'YA' : 'TIDAK' }}</td>
        </tr>
        <tr>
			<th width="50%">DIBUAT</th>
			<td>
				{{ parseCarbon($provider->created_at)->translatedFormat('d F Y - H:i') }}
				({{ parseCarbon($provider->created_at)->diffForHumans() }})
			</td>
		</tr>
		<tr>
			<th width="50%">DIPERBARUI</th>
			<td>
				{{ parseCarbon($provider->updated_at)->translatedFormat('d F Y - H:i') }}
				({{ parseCarbon($provider->updated_at)->diffForHumans() }})
			</td>
		</tr>
    </table>
</div>
