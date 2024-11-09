@section('title')
{{ getConfig('title') }} - {{ $page['title'] }}
@endsection
@extends('primary.layouts.app')
@section('style')

@endsection
@section('content')
<div class="container my-5 py-5">
    <div class="row">
        <div class="col-md-12">
            <div class="pt-3 pb-4">
                <h5>{{ $page['title'] }}</h5>
                <span class="strip-primary"></span>
            </div>
        </div>
        <div class="col-md-12">
            <livewire:primary.review.review-index />
        </div>
    </div>
</div>
@push('scripts')
<script type="text/javascript">
    window.onscroll = function(ev) {
        if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight) {
            window.livewire.emit('load-more');
        }
    };
</script>

@endpush
@endsection
