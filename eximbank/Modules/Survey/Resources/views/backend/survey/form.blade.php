@extends('layouts.backend')

@section('page_title', $page_title)
@section('header')
    <link rel="stylesheet" href="{{ asset('css/tree-folder.css') }}">
@endsection
@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lamenu.survey'),
                'url' => route('module.survey.index')
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
    @if(isset($errors))

        @foreach($errors as $error)
            <div class="alert alert-danger">{!! $error !!}</div>
        @endforeach

    @endif
    <div class="clear"></div>
    <br>
    <div class="tPanel">
        <ul class="nav nav-pills mb-4" role="tablist" id="mTab">
            <li class="nav-item"><a href="#base" class="nav-link active" role="tab" data-toggle="tab">{{trans('lasurvey.info')}}</a></li>
            @if($model->id && $model->type == 1)
                <li class="nav-item"><a href="#object" class="nav-link" data-toggle="tab">{{trans('lasurvey.object')}}</a></li>
                <li class="nav-item"><a href="#popup" class="nav-link" data-toggle="tab">Popup</a></li>
            @endif
        </ul>
        <div class="tab-content">
            <div id="base" class="tab-pane active">
                @include('survey::backend.survey.form.info')
            </div>
            @if($model->id && $model->type == 1)
                <div id="object" class="tab-pane">
                    @include('survey::backend.survey.form.object')
                </div>
                <div id="popup" class="tab-pane">
                    @include('survey::backend.survey.form.popup')
                </div>
            @endif
        </div>
    </div>
</div>
@stop
