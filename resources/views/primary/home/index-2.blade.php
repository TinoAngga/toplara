@extends('primary.layouts.app')
@section('title')
{{ getConfig('title') }} - {{ $page['title'] }}
@endsection
@section('style')
<style>
.row {
  margin-right: -12.5px;
  margin-left: -12.5px;
}

</style>
@endsection
@section('content')

<!-- Start row -->
<div class="row mt-5">
    <div class="col-md-12">
        <div id="carouselExampleIndicators3" class="carousel slide shadow shadow-md" data-ride="carousel">
            <ol class="carousel-indicators">
                @forelse ($banner as $key => $value)
                <li data-target="#carouselExampleIndicators" data-slide-to="{{ $key }}"
                    class="{{ $key == 0 ? 'active' : '' }}"></li>
                @empty

                @endforelse
            </ol>
            <div class="carousel-inner">
                @forelse ($banner as $key => $value)
                <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                    <a href="{{ $value->url }}">
                        <img class="d-block w-100 lazy"
                            data-src="{{ asset(config('constants.options.asset_img_banner') . $value->value) }}" alt="{{ $value->name }}
                        " height="325px">
                    </a>
                </div>
                @empty

                @endforelse
            </div>
            <a class="carousel-control-prev" href="#carouselExampleIndicators3" role="button" data-slide="prev"> <span
                    class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#carouselExampleIndicators3" role="button" data-slide="next"> <span
                    class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>
    </div>
</div>
<!-- End row -->
<div class="row justify-content-center">
    <h2 class="row-title font-weight-bold"> TOP UP GAME </h2>
</div>
<div class="row mb-5 mt-5">
    @forelse ($serviceCategory as $key => $value)
    <div class="col-sm-3 col-lg-3 col-6">
        <div class="card mb-4">
            <div class="card-body">
                <div class="row" style="justify-content: center; margin-bottom: 20px">
                    <img src="https://topupgame.my.id/upload/be_the_king.png" class="img-category-product img-fluid" style="border-radius: 10px; display: block; margin">
                </div>
            </div>
        </div>
    </div>
    @empty
    @endforelse
</div>
@endsection
@section('script')

@endsection
