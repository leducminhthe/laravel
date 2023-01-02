@extends('layouts.backend')

@section('page_title', 'Xóa bộ nhớ đệm')

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            <a href="{{ route('backend.setting') }}">{{ trans('lamenu.setting') }} </a>
            <i class="uil uil-angle-right"></i>
            <span class="font-weight-bold">Xóa bộ nhớ đệm</span>
        </h2>
    </div>
@endsection

@section('content')
    <div role="main">
        @if(session('success'))
            <div class="alert alert-success text-center" role="alert">
                <h2>{{ session('success') }}</h2>
            </div>
        @endif
        <div class="row">
            <div class="col-md-4">
                <div class="btn-group act-btns">
                    <a href="{{ route('backend.clear_cache') }}" class="btn"><i class="fa fa-trash"></i> Xóa bộ nhớ đệm</a>
                </div>
            </div>
        </div>
    </div>
@stop
