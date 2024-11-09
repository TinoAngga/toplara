<div class="table-responsive">
    <table class="table table-bordered">
        <tr>
            <th>ORDER ID</th>
            <td>{{ $providerApiLog->order_id }}</td>
        </tr>
        <tr>
            <th>INVOICE</th>
            <td>{{ strtoupper($providerApiLog->order->invoice) }}</td>
        </tr>
        <tr>
            <th>PROVIDER</th>
            <td>{{ strtoupper($providerApiLog->provider->name) }}</td>
        </tr>
        <tr>
            <th>DESKRIPSI</th>
            <td>{{ $providerApiLog->description }}</td>
        </tr>
        <tr>
            <th>ORDER RESPONSE</th>
            <td><pre class="text-primary"> {{ $providerApiLog->order_response ?? '-'}} </pre></td>
        </tr>
        <tr>
            <th>STATUS RESPONSE</th>
            <td><pre class="text-primary"> {{ $providerApiLog->status_response ?? '-'}} </pre></td>
        </tr>
        <tr>
			<th width="50%">DIBUAT</th>
			<td>
				{{ parseCarbon($providerApiLog->created_at)->translatedFormat('d F Y - H:i') }}
				({{ parseCarbon($providerApiLog->created_at)->diffForHumans() }})
			</td>
		</tr>
		<tr>
			<th width="50%">DIPERBARUI</th>
			<td>
				{{ parseCarbon($providerApiLog->updated_at)->translatedFormat('d F Y - H:i') }}
				({{ parseCarbon($providerApiLog->updated_at)->diffForHumans() }})
			</td>
		</tr>
    </table>
</div>
