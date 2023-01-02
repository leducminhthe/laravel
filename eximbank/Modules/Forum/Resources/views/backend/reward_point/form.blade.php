@extends('layouts.backend')

@section('page_title', trans('latraining.reward_points'))

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lamenu.forum'),
                'url' => route('module.forum.category')
            ],
            [
                'name' => $category->name,
                'url' => route('module.forum', ['cate_id' => $cate_id])
            ],
            [
                'name' => trans('latraining.reward_points'),
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
                <li class="nav-item"><a href="#another" class="nav-link active" data-toggle="tab">{{ trans('latraining.reward_points') }}</a></li>
                <li class="nav-item"><a href="#comment_point" class="nav-link" data-toggle="tab">{{ trans('latraining.comments_landmark') }}</a></li>
            </ul>
            <div class="tab-content">
                <div id="another" class="tab-pane active">
                    @include('forum::backend.reward_point.reward_point')
                </div>
                <div id="comment_point" class="tab-pane">
                    @include('forum::backend.reward_point.comment_point')
                </div>
            </div>
        </div>
    </div>
@endsection
