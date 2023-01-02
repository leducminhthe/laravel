@extends('layouts.backend')

@section('page_title', trans('latraining.reward_points'))

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lamenu.video_category'),
                'url' => route('module.daily_training')
            ],
            [
                'name' => trans('latraining.reward_points') .': '. $category->name,
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
                <li class="nav-item"><a href="#score_views" class="nav-link active" role="tab" data-toggle="tab">{{ trans('lavideo_training_materials.view') }}</a></li>
                <li class="nav-item"><a href="#score_like" class="nav-link" data-toggle="tab">{{ trans('lahandle_situations.likes') }}</a></li>
                <li class="nav-item"><a href="#socre_comment" class="nav-link" data-toggle="tab">{{ trans('latraining.comment') }}</a></li>
                <li class="nav-item"><a href="#another" class="nav-link" data-toggle="tab">{{ trans('latraining.other') }}</a></li>
            </ul>
            <div class="tab-content">
                <div id="score_views" class="tab-pane active">
                    @include('dailytraining::backend.category.form.score_views')
                </div>
                <div id="score_like" class="tab-pane">
                    @include('dailytraining::backend.category.form.score_like')
                </div>
                <div id="socre_comment" class="tab-pane">
                    @include('dailytraining::backend.category.form.score_comment')
                </div>
                <div id="another" class="tab-pane">
                    @include('dailytraining::backend.category.form.another')
                </div>
            </div>
        </div>
    </div>
@endsection
