<div class="table-responsive">
    <table class="table table-bordered">
        <tr>
            <th>Nama</th>
            <td>{{ $banner->name }}</td>
        </tr>
        <tr>
            <th>Url</th>
            <td><a href="{{ $banner->url }}" target="_blank">{{ $banner->url }}</a></td>
        </tr>
        <tr>
            <th>Gambar</th>
            <td><img src="{{ asset(config('constants.options.asset_img_banner') . $banner->value) }}" class="img-fluid" alt=""></td>
        </tr>
        <tr>
			<th width="50%">DIBUAT</th>
			<td>
				{{ parseCarbon($banner->created_at)->translatedFormat('d F Y - H:i') }}
				({{ parseCarbon($banner->created_at)->diffForHumans() }})
			</td>
		</tr>
		<tr>
			<th width="50%">DIPERBARUI</th>
			<td>
				{{ parseCarbon($banner->updated_at)->translatedFormat('d F Y - H:i') }}
				({{ parseCarbon($banner->updated_at)->diffForHumans() }})
			</td>
		</tr>
    </table>
</div>
