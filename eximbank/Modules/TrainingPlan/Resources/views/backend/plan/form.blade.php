@extends('layouts.backend')

@section('page_title', $page_title)

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lamenu.training'),
                'url' => route('backend.category')
            ],
            [
                'name' => trans('backend.training_plan'),
                'url' => route('module.training_plan')
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
    $get_type_model_costs = json_decode($model->type_costs);
@endphp
<div role="main">
    <form method="post" action="{{ route('module.training_plan.save') }}" class="form-horizontal form-ajax" role="form" enctype="multipart/form-data" id="form">
        <input type="hidden" name="id" value="{{ $model->id }}">
        <div class="row">
            <div class="col-md-8">
            </div>
            <div class="col-md-4 text-right">
                <div class="btn-group act-btns">
                    @canany(['training-plan-create', 'training-plan-edit'])
                        <button type="submit" class="btn" data-must-checked="false"><i class="fa fa-save"></i> &nbsp;{{ trans('labutton.save') }}</button>
                    @endcanany
                    <a href="{{ route('module.training_plan') }}" class="btn"><i class="fa fa-times-circle"></i> {{ trans('labutton.cancel') }}</a>
                </div>
            </div>
        </div>
        <div class="clear"></div>
        <br>
        <div class="tPanel">
            <ul class="nav nav-pills mb-4" role="tablist" id="mTab">
                <li class="nav-item">
                    <a href="#base" class="nav-link @if($tabs == 'base' || empty($tabs)) active @endif" id="base_tab" role="tab" data-toggle="tab">
                        {{ trans('latraining.info') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link @if($tabs == 'cost') active @endif" href="#cost" id="cost_tab" role="tab" data-toggle="tab">{{ trans('latraining.cost_norms_class') }}</a>
                </li>
            </ul>
            <div class="tab-content">
                <div id="base" class="tab-pane @if($tabs == 'base' || empty($tabs)) active  @endif">
                    @include('trainingplan::backend.plan.info')
                </div>
                <div id="cost" class="tab-pane @if($tabs == 'cost') active  @endif">
                    @include('trainingplan::backend.plan.cost')
                </div>
            </div>
        </div>
    </form>

</div>

@stop
