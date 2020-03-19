@extends('voyager::master')

@section('page_title', $title)

@section('page_header')
    <div class="container-fluid">
        <h1 class="page-title">
            <i class="voyager-settings"></i> {{ $title }}
        </h1>
    </div>
@stop

@section('content')

    <div class="container-fluid">
        @include('voyager::alerts')
    </div>

    <div class="page-content code container-fluid">
        <div class="panel panel-bordered">
            <div class="panel-body">
                @if($error)
                    <div class="send-test-mail-error">
                        {{ $error->getMessage() }}
                    </div>
                @else
                    <div class="send-test-mail-success">
                        {{ $content }}
                    </div>
                @endif
            </div>
        </div>

    </div>

@stop
