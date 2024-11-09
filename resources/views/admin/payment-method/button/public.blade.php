@if ($is_public == 1)
<div class="custom-control custom-switch">
    <input type="checkbox" class="custom-control-input" id="switch-check-public{{ $id }}" value="0" onclick="switchStatus(this, {{ $id }}, '{{ url('admin/'.request()->segment(2).'/switch/'.$id.'?type=public&value=0') }}')" checked>
    <label class="custom-control-label" for="switch-check-public{{ $id }}">Ya</label>
</div>
@elseif ($is_public == 0)
<div class="custom-control custom-switch">
    <input type="checkbox" class="custom-control-input" id="switch-check-public{{ $id }}" value="1" onclick="switchStatus(this, {{ $id }}, '{{ url('admin/'.request()->segment(2).'/switch/'.$id.'?type=public&value=1') }}')">
    <label class="custom-control-label" for="switch-check-public{{ $id }}">Tidak</label>
</div>
@else
<span class="badge badge-info badge-sm">ERROR</span>
@endif
