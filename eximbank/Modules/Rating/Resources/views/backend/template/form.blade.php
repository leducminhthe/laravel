@extends('layouts.backend')

@section('page_title', $page_title)

@section('header')
    <style>
        #input-category .item-category .input-group .btn-remove,
        #input-category .item-question .input-group .btn-remove,
        #input-category .item-answer .input-group .btn-remove,
        #input-category th .btn-remove-row-matrix{
            display: flex;
        }
    </style>
@endsection

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('latraining.training_evaluation'),
                'url' => ''
            ],
            [
                'name' => 'Mẫu đánh giá',
                'url' => route('module.rating.template')
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
            <li class="nav-item"><a href="#base" class="nav-link active" role="tab" data-toggle="tab">{{trans('latraining.info')}}</a></li>
            {{--  @if($model->id)
                <li class="nav-item"><a href="#statistic" class="nav-link" data-toggle="tab">{{trans('lamenu.statistic')}}</a></li>
            @endif  --}}
        </ul>
        <div class="tab-content">
            <div id="base" class="tab-pane active">
                @if ($teaching_organization == 1)
                    @include('rating::backend.template.info1')
                @else
                    @include('rating::backend.template.info')
                @endif

            </div>
            {{--  @if($model->id)
                <div id="statistic" class="tab-pane">
                    @include('rating::backend.template.statistic')
                </div>
            @endif  --}}
        </div>
    </div>
</div>
@stop
