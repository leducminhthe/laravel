@extends('layouts.backend')

@section('page_title', $page_title)

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lamenu.experience_directed'),
                'url' => route('backend.experience_navigate')
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
    <div class="tPanel">
        <ul class="nav nav-pills mb-4" role="tablist" id="mTab">
            <li class="nav-item"><a href="#base" class="nav-link active" role="tab" data-toggle="tab">{{ trans('latraining.info') }}</a></li>
            @if ($model->id)
                <li class="nav-item"><a href="#object" class="nav-link" data-toggle="tab">{{trans('latraining.object')}}</a></li>
            @endif
        </ul>
        <div class="tab-content">
            <div id="base" class="tab-pane active">
                @include('backend.setting_experience_navigate.form.info')
            </div>
            @if ($model->id)
                <div id="object" class="tab-pane">
                    @include('backend.setting_experience_navigate.form.object')
                </div>
            @endif
        </div>
    </div>
@stop
