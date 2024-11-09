@if ($is_active == 1)
<div class="custom-control custom-switch">
    <input type="checkbox" class="custom-control-input" id="switch-active-{{ $id }}" value="0" onclick="switchStatus(this, {{ $id }}, '{{ url('admin/'.request()->segment(2).'/switch/'.$id.'?type=status&value=0') }}')" checked>
    <label class="custom-control-label" for="switch-active-{{ $id }}">Aktif</label>
</div>
@elseif ($is_active == 0)
<div class="custom-control custom-switch">
    <input type="checkbox" class="custom-control-input" id="switch-active-{{ $id }}" value="1" onclick="switchStatus(this, {{ $id }}, '{{ url('admin/'.request()->segment(2).'/switch/'.$id.'?type=status&value=1') }}')">
    <label class="custom-control-label" for="switch-active-{{ $id }}">Nonaktif</label>
</div>
@else
<span class="badge badge-info badge-sm">ERROR</span>
@endif
