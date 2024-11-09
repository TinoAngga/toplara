@section('title')
{{ getConfig('title') }} - {{ $page['title'] }}
@endsection
@extends('primary.layouts.app')
@section('content')
<style>
    .table thead tr, .table thead tr th, .table thead th, .table tbody td{
        border: 1px solid rgb(255, 255, 255) !important;
    }
</style>
<livewire:primary.service.service-index />
@endsection
@section('script')

@endsection
