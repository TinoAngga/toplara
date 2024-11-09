<div class="table-responsive">
    <table class="table table-bordered">
        <tr>
			<td align="center" colspan="2">
				<strong>Informasi Admin</strong>
			</td>
		</tr>
		<tr>
            <th>NAMA</th>
            <td>{{ $adminLog->admin->full_name }}</td>
        </tr>
        <tr>
            <th>USERNAME</th>
            <td>{{ $adminLog->admin->username }}</td>
        </tr>
        <tr>
			<td align="center" colspan="2">
				<strong>Informasi Log Admin</strong>
			</td>
		</tr>
        <tr>
            <th>IP ADDRESS</th>
            <td>{{ $adminLog->ip_address }}</td>
        </tr>
        <tr>
            <th>USER AGENT</th>
            <td>{{ $adminLog->user_agent }}</td>
        </tr>
        <tr>
            <th>LOCATION</th>
            <td>
                <pre>{{ json_encode($adminLog->payload, JSON_PRETTY_PRINT) }}</pre>
            </td>
        </tr>

        <tr>
			<th width="50%">DIBUAT</th>
			<td>
				{{ parseCarbon($adminLog->created_at)->translatedFormat('d F Y - H:i') }}
				({{ parseCarbon($adminLog->created_at)->diffForHumans() }})
			</td>
		</tr>
		<tr>
			<th width="50%">DIPERBARUI</th>
			<td>
				{{ parseCarbon($adminLog->updated_at)->translatedFormat('d F Y - H:i') }}
				({{ parseCarbon($adminLog->updated_at)->diffForHumans() }})
			</td>
		</tr>
    </table>
</div>
