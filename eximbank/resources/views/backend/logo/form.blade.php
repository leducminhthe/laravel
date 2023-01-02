@extends('layouts.backend')

@section('page_title', $page_title)

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lasetting.logo'),
                'url' => route('backend.logo')
            ],
            [
                'name' => $page_title,
                'url' => ''
            ],
        ]
    @endphp
    @include('layouts.backend.menu_breadcum',$breadcum)
@endsection

@section('content')

    <div role="main">
        <div class="tPanel">
            <ul class="nav nav-pills mb-4" role="tablist" id="mTab">
                {{--<li class="nav-item"><a href="#web" class="nav-link active" role="tab" data-toggle="tab">Web</a></li>--}}
                {{-- <li class="nav-item"><a href="#outside" class="nav-link" role="tab" data-toggle="tab">outside</a></li> --}}
            </ul>
            <div class="tab-content">
                <div id="web" class="tab-pane active">
                    @include('backend.logo.form.web')
                </div>
                <div id="outside" class="tab-pane">
                    @include('backend.logo.form.outside')
                </div>
            </div>
        </div>
    </div>
@stop
