<a href="javascript:;" onclick="modal('edit', '#{{ $id }}', '{{ route('admin.' .request()->segment(2). '.edit', $id) }}')" class="badge badge-warning badge-sm" data-toggle="tooltip" data-placement="top" title="Edit">
    <i class="fa fa-edit fa-fw"></i>
</a>
<a href="javascript:;" onclick="deleteData(this, {{ $id }}, '{{ $username }}', '{{ route('admin.' .request()->segment(2). '.destroy', $id) }}', '<br /><small>jika Anda menghapus <b>{{ $username }}</b>, maka data yang bersangkutan akan ikut terhapus.')" class="badge badge-danger badge-sm" data-toggle="tooltip" title="Hapus">
    <i class="fa fa-trash"></i>
</a>
