<a href="javascript:;" onclick="modal('detail', '#{{ $id }}', '{{ route('admin.' .request()->segment(2). '.show', $id) }}')" class="badge badge-info badge-sm" data-toggle="tooltip" data-placement="top" title="Detail">
    <i class="fa fa-search fa-fw"></i>
</a>
<a href="javascript:;" onclick="deleteData(this, {{ $id }}, '{{ $name }}', '{{ route('admin.' .request()->segment(2). '.destroy', $id) }}', '<br /><small>jika Anda menghapus <b>{{ $name }}</b>, maka data yang bersangkutan akan ikut terhapus.')" class="badge badge-danger badge-sm" data-toggle="tooltip" title="Hapus">
    <i class="fa fa-trash"></i>
</a>
