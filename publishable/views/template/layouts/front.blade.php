@extends($template_master)

@section('header')
    @include('template.partials.header')
@endsection

@section('page-content')
    @include($template_page)
@endsection

@section('page-footer')
    @include('template.partials.footer')
@endsection

@push('javascript')
@endpush
