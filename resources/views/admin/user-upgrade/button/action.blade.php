<a href="javascript:;" onclick="modal('detail', '#{{ $id }}', '{{ route('admin.' .request()->segment(2). '.show', $id) }}')" class="badge badge-info badge-md" data-toggle="tooltip" data-placement="top" title="Detail">
    <i class="fa fa-search fa-fw"></i>
</a>
<a href="javascript:;"
    onclick="deleteData(this, {{ $id }}, '{{ $id }}',
    '{{ route('admin.' .request()->segment(2). '.destroy', $id) }}',
    '<br /><small>jika Anda menghapus <b>{{ $id }}</b>, maka data yang bersangkutan akan ikut terhapus.')"
    class="badge badge-danger badge-md" data-toggle="tooltip" title="Hapus">
    <i class="fa fa-trash"></i>
</a>
