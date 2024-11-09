@if ($is_auto_update == 1)
<div class="custom-control custom-switch">
    <input type="checkbox" class="custom-control-input" id="switch-check-{{ $id }}" value="0" onclick="switchStatus(this, {{ $id }}, '{{ url('admin/'.request()->segment(2).'/switch/'.$id.'?type=auto_update&value=0') }}')" checked>
    <label class="custom-control-label" for="switch-check-{{ $id }}">Ya</label>
</div>
@elseif ($is_auto_update == 0)
<div class="custom-control custom-switch">
    <input type="checkbox" class="custom-control-input" id="switch-check-{{ $id }}" value="1" onclick="switchStatus(this, {{ $id }}, '{{ url('admin/'.request()->segment(2).'/switch/'.$id.'?type=auto_update&value=1') }}')">
    <label class="custom-control-label" for="switch-check-{{ $id }}">Tidak</label>
</div>
@else
<span class="badge badge-info badge-sm">ERROR</span>
@endif
