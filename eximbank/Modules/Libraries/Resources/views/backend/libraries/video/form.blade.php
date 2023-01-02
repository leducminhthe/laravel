@extends('layouts.backend')

@section('page_title', $page_title)

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lamenu.video'),
                'url' => route('module.libraries.video')
            ],
            [
                'name' => $page_title,
                'url' => ''
            ]
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
        <div class="tPanel">
            <ul class="nav nav-pills mb-4" role="tablist" id="mTab">
                <li class="nav-item"><a href="#base" class="nav-link active" role="tab" data-toggle="tab">{{ trans('latraining.info') }}</a></li>
                @if($model->id && userCan(['libraries-video-create', 'libraries-video-edit']))
                    <li class="nav-item"><a href="#object" class="nav-link" data-toggle="tab">{{ trans('backend.object') }}</a></li>
                    <li class="nav-item"><a href="#reward-points" class="nav-link" data-toggle="tab">{{ trans('latraining.reward_points') }}</a></li>
                @endif
            </ul>
            <div class="tab-content">
                <div id="base" class="tab-pane active">
                    @include('libraries::backend.libraries.video.form.info')
                </div>
                @if($model->id)
                    <div id="object" class="tab-pane">
                        @include('libraries::backend.libraries.video.form.object')
                    </div>
                    <div id="reward-points" class="tab-pane">
                        @include('libraries::backend.libraries.reward_points',['type' => 4])
                    </div>
                @endif
            </div>
        </div>
</div>
@stop