<div class="table-responsive">
    <table class="table table-bordered">
        <tr>
            <th>Nama</th>
            <td>{{ $serviceCategory->name }}</td>
        </tr>
        <tr>
            <th>Slug</th>
            <td>{{ $serviceCategory->slug }}</td>
        </tr>
        <tr>
            <th>Gambar</th>
            <td><img src="{{ asset(config('constants.options.asset_img_service_category') . $serviceCategory->img) }}" class="img-fluid" alt=""></td>
        </tr>
        <tr>
            <th>Gambar Petunjuk</th>
            <td><img src="{{ asset(config('constants.options.asset_img_service_category_guide') . $serviceCategory->img_guide) }}" class="img-fluid" alt=""></td>
        </tr>
        <tr>
            <th>Deskripsi</th>
            <td>{{ strip_tags($serviceCategory->description) }}</td>
        </tr>
        <tr>
            <th>Information</th>
            <td>{{ strip_tags($serviceCategory->information) }}</td>
        </tr>
        <tr>
            <th>Additional Data</th>
            <td>{{ ($serviceCategory->is_additional_data == 1) ? 'YA' : 'TIDAK' }}</td>
        </tr>
        <tr>
            <th>Check ID</th>
            <td>{{ ($serviceCategory->is_check_id == 1) ? 'YA' : 'TIDAK' }}</td>
        </tr>
        <tr>
            <th>Validasi Nickname Kode</th>
            <td>{{ ($serviceCategory->is_check_id == 1) ? $serviceCategory->get_nickname_code : '-' }}</td>
        </tr>
        <tr>
            <th>Status</th>
            <td>{{ ($serviceCategory->is_active == 1) ? 'YA' : 'TIDAK' }}</td>
        </tr>

        <tr>
			<th width="50%">DIBUAT</th>
			<td>
				{{ parseCarbon($serviceCategory->created_at)->translatedFormat('d F Y - H:i') }}
				({{ parseCarbon($serviceCategory->created_at)->diffForHumans() }})
			</td>
		</tr>
		<tr>
			<th width="50%">DIPERBARUI</th>
			<td>
				{{ parseCarbon($serviceCategory->updated_at)->translatedFormat('d F Y - H:i') }}
				({{ parseCarbon($serviceCategory->updated_at)->diffForHumans() }})
			</td>
		</tr>
    </table>
</div>
