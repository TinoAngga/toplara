<!DOCTYPE html>
<html lang="en">

@include('primary.layouts.partials._head')

<body class="min-vh-100 h-100">
    @include('primary.layouts.partials._navbar_header')
    <div class="content pt-5">
        <section class="w-100">
            @include('alert')
            @yield('content')
        </section>

    </div>
    @include('primary.layouts.partials._container_footer')
    @include('primary.layouts.partials._footer')

    {!! getConfig('additional_body_scripts') !!}

</body>

</html>
