<div class="table-responsive">
    <table class="table table-bordered">
        <tr>
			<td align="center" colspan="2">
				<strong>Informasi Pengguna</strong>
			</td>
		</tr>
		<tr>
			<th width="50%">Total Pemesanan</th>
			<td>Rp {{ currency($user->order()->sum('price')) }} ({{ $user->order()->count('id') }})</td>
		</tr>
		<tr>
			<th width="50%">Total Deposit</th>
			<td>Rp {{ currency($user->deposit()->sum('balance')) }} ({{ $user->deposit()->count('id') }})</td>
		</tr>
        <tr>
            <th>Nama</th>
            <td>{{ $user->full_name }}</td>
        </tr>
        <tr>
            <th>Email</th>
            <td>{{ $user->email }}</td>
        </tr>
        <tr>
            <th>Username</th>
            <td>{{ $user->username }}</td>
        </tr>
        <tr>
            <th>Nomor HP</th>
            <td>{{ $user->phone_number }}</td>
        </tr>
        <tr>
            <th>Sisa Saldo</th>
            <td>{{ 'Rp ' . currency($user->balance) }}</td>
        </tr>
        <tr>
            <th>Level</th>
            <td>{{ strtoupper($user->level) }}</td>
        </tr>

        <tr>
            <th>Status</th>
            <td>{{ ($user->is_active == 1) ? 'AKTIF' : 'NONAKTIF' }}</td>
        </tr>

        <tr>
			<th width="50%">BERGABUNG</th>
			<td>
				{{ parseCarbon($user->created_at)->translatedFormat('d F Y - H:i') }}
				({{ parseCarbon($user->created_at)->diffForHumans() }})
			</td>
		</tr>
		<tr>
			<th width="50%">DIPERBARUI</th>
			<td>
				{{ parseCarbon($user->updated_at)->translatedFormat('d F Y - H:i') }}
				({{ parseCarbon($user->updated_at)->diffForHumans() }})
			</td>
		</tr>
    </table>
</div>
