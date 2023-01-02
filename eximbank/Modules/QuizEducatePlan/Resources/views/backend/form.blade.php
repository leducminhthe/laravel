@extends('layouts.backend')

@section('page_title', $page_title)

@section('header')
    <link rel="stylesheet" href="{{ asset('css/tree-folder.css') }}">
    <script src="{{asset('styles/vendor/jqueryplugin/printThis.js')}}"></script>
    <style>
        table tbody th {
            font-weight: normal !important;
        }
    </style>
@endsection

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('latraining.unit'),
                'url' => ''
            ],
            [
                'name' => trans('lamenu.quiz_plan_suggest'),
                'url' => route('module.quiz_educate_plan_suggest')
            ],
            [
                'name' => 'Đề xuất',
                'url' => route('module.quiz_educate_plan.index',["idsg"=>$idsg])
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
    <div class="row">
        @if($model->id)
            <div class="col-md-12 text-center">
                <a href="{{ route('module.quiz_plan.question', ["idsg"=>$idsg, 'id' => $model->id]) }}"
                   class="btn">
                    <div><i class="fa fa-inbox"></i></div>
                    <div>{{ trans('latraining.question') }}</div>
                </a>
            </div>
        @endif
    </div>
    <br>
    <div class="tPanel">
        <ul class="nav nav-pills mb-4" role="tablist" id="mTab">
            <li class="nav-item"><a href="#base" class="nav-link @if($tabs == 'base' || empty($tabs)) active @endif" role="tab" data-toggle="tab">{{ trans('latraining.info') }}</a></li>
            @if($model->id)
            <li class="nav-item"><a href="#part" class="nav-link" data-toggle="tab">Ca thi</a></li>
            <li class="nav-item"><a href="#rank" class="nav-link" data-toggle="tab">Xếp loại</a></li>
            <li class="nav-item"><a href="#teacher" class="nav-link" data-toggle="tab">{{ trans('lareport.teacher') }}</a></li>
            <li class="nav-item"><a href="#qsetting" class="nav-link" data-toggle="tab">Tùy chỉnh</a></li>
            @endif
        </ul>
        <div class="tab-content">
            <div id="base" class="tab-pane @if($tabs == 'base' || empty($tabs)) active @endif">
                @include('quizeducateplan::backend.form.info')
            </div>
            @if($model->id)
                <div id="part"
                     class="tab-pane">
                    @include('quizeducateplan::backend.form.part')
                </div>
                <div id="rank"
                     class="tab-pane">
                    @include('quizeducateplan::backend.form.rank')
                </div>
                <div id="teacher"
                     class="tab-pane">
                    @include('quizeducateplan::backend.form.teacher')
                </div>
                <div id="qsetting"
                     class="tab-pane">
                    @include('quizeducateplan::backend.form.setting')
                </div>
            @endif
        </div>
    </div>
    <script type="text/javascript">

        $("#select-image").on('click', function () {
            var lfm = function (options, cb) {
                var route_prefix = '/filemanager';
                window.open(route_prefix + '?type=' + options.type || 'file', 'FileManager', 'width=1100,height=600');
                window.SetUrl = cb;
            };

            lfm({type: 'image'}, function (url, path) {
                $("#image-review").html('<img src="'+ path +'">');
                $("#image-select").val(path);
            });
        });

        $('#action_plan').on('change', function() {
            if($(this).val() == 1) {
                $(".contain_plan_app_template").fadeIn();
                $('input[name=plan_app_day]').fadeIn();
            }
            else {
                $("select[name=plan_app_template]").val(0).trigger('change');
                $(".contain_plan_app_template").fadeOut();
                $("input[name=plan_app_day]").val('');
                $('input[name=plan_app_day]').fadeOut();
            }

        }).trigger('change');

    </script>
</div>
@stop
