@extends('layouts.backend')

@section('page_title', $page_title)

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lamenu.training'),
                'url' => ''
            ],
            [
                'name' => trans('backend.training_plan'),
                'url' => route('module.training_plan')
            ],
            [
                'name' => trans('backend.detail_training_program'),
                'url' => route('module.training_plan.detail', ['id' => $plan_id])
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
@php
    $tabs = request()->get('tabs', null);
@endphp
<div role="main">
    @php
        $get_type_model_costs = json_decode($model->type_costs);
    @endphp
    <form id="form" method="post" action="{{ route('module.training_plan.detail.save', ['id' => $plan_id]) }}" class="form-validate form-ajax" role="form" enctype="multipart/form-data">
        <input type="hidden" name="id" value="{{ $model->id }}">
        <div class="row">
            <div class="col-md-8">
            </div>
            <div class="col-md-4 text-right">
                <div class="btn-group act-btns">
                    @canany(['training-plan-detail-create', 'training-plan-detail-edit'])
                    <button type="submit" class="btn" data-must-checked="false"><i class="fa fa-save"></i> &nbsp;{{ trans('labutton.save') }}</button>
                    @endcanany
                    <a href="{{ route('module.training_plan.detail', ['id' => $plan_id]) }}" class="btn"><i class="fa fa-times-circle"></i> {{ trans('labutton.cancel') }}</a>
                </div>
            </div>
        </div>
        <div class="clear"></div>
        <br>
        <div class="tPanel">
            <ul class="nav nav-pills mb-4" role="tablist" id="mTab">
                <li class="nav-item">
                    <a class="nav-link @if($tabs == 'base' || empty($tabs)) active @endif" href="#base" role="tab" data-toggle="tab">{{ trans('latraining.info') }}</a>
                </li>
            </ul>
            <div class="tab-content">
                <div id="base" class="tab-pane @if($tabs == 'base' || empty($tabs)) active  @endif">
                    @include('trainingplan::backend.plan-detail.info')
                </div>
            </div>
        </div>
    </form>
</div>

@stop
