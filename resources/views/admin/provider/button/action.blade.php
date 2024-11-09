<a href="{{ route('admin.' .request()->segment(2). '.service', $id) }}" class="badge badge-primary badge-md" data-toggle="tooltip" data-placement="top" title="Layanan API">
    <i class="fa fa-fire fa-fw"></i>
</a>
<a href="javascript:;" onclick="updateBalance('{{ route('admin.provider.balance', $id) }}')" class="badge badge-success badge-md" data-toggle="tooltip" data-placement="top" title="Update Saldo API">
    <i class="mdi mdi-wallet fa-fw"></i>
</a>
<a href="javascript:;" onclick="syncServiceProvider('{{ route('admin.provider.service_sync', $id) }}')" class="badge badge-success badge-md" data-toggle="tooltip" data-placement="top" title="Sinkronisasi Layanan API">
    <i class="mdi mdi-sync fa-fw"></i>
</a>
<a href="javascript:;" onclick="modal('detail', '#{{ $id }}', '{{ route('admin.' .request()->segment(2). '.show', $id) }}')" class="badge badge-info badge-md" data-toggle="tooltip" data-placement="top" title="Detail">
    <i class="fa fa-search fa-fw"></i>
</a>
<a href="javascript:;" onclick="modal('edit', '#{{ $id }}', '{{ route('admin.' .request()->segment(2). '.edit', $id) }}')" class="badge badge-warning badge-md" data-toggle="tooltip" data-placement="top" title="Edit">
    <i class="fa fa-edit fa-fw"></i>
</a>
<a href="javascript:;"
    onclick="deleteData(this, {{ $id }}, '{{ $name }}',
    '{{ route('admin.' .request()->segment(2). '.destroy', $id) }}',
    '<br /><small>jika Anda menghapus <b>{{ $name }}</b>, maka data yang bersangkutan akan ikut terhapus.')"
    class="badge badge-danger badge-md" data-toggle="tooltip" title="Hapus">
    <i class="fa fa-trash"></i>
</a>
