@extends('layouts.backend')

@section('page_title', $page_title)

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => 'Sales Kit',
                'url' => route('module.saleskit.category')
            ],
            [
                'name' => $categories->name,
                'url' => route('module.saleskit',[$categories->id])
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
            @if($model->id && userCan(['saleskit-create', 'saleskit-edit']))
                <li class="nav-item"><a href="#object" class="nav-link" data-toggle="tab">{{trans('backend.object')}}</a></li>
            @endif
        </ul>
        <div class="tab-content">
            <div id="base" class="tab-pane active">
                @include('saleskit::backend.salekit.form.info')
            </div>
            @if($model->id)
                <div id="object" class="tab-pane">
                    @include('saleskit::backend.salekit.form.object')
                </div>
            @endif
        </div>
    </div>
</div>
@stop
