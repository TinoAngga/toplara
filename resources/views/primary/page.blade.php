@section('title')
{{ getConfig('title') }} - {{ $page['title'] }}
@endsection
@extends('primary.layouts.app')
@section('style')
@endsection
@section('content')
<div class="container my-5 py-5">
    <div class="row">
        <div class="col-lg-12">
            <div class="card m-b-30">
                <div class="card-header bg-primary">
                    <h6 class="m-0 font-weight-bold text-white"><i class="mdi mdi-format-list-bulleted"></i> {{ $page['title'] }} </h6>
                </div>
                <div class="card-body">
                    @if (!is_null($pages->img))
                        <div class="row justify-content-center">
                            <img src="{{ asset(config('constants.options.asset_img_page') . $pages->img) }}" alt="" class="img-fluid mb-5 mt-3" style="height: 300px; width: 300px">
                        </div>
                    @endif
                    {!! $pages->content !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
