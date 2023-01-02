
@extends('layouts.backend')

@section('page_title', $page_title)
@section('header')
    <script src="{{asset('styles/vendor/bootstrap/js/bs-custom-file-input.min.js')}}"></script>
    <script src="{{asset('styles/module/plansuggest/js/plan_suggest.js')}}"></script>
    {{--<link  rel="stylesheet" href="{{asset('styles/css/backend/styles/css/customs.css')}}">--}}
@endsection
@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lamenu.plan_suggest'),
                'url' => route('module.plan_suggest')
            ],
            [
                'name' => $page_title,
                'url' => ''
            ],
        ]
    @endphp
    @include('layouts.backend.menu_breadcum',$breadcum)
@endsection
@php
    $tabs = request()->get('tabs', null);
@endphp
@section('content')
<div role="main">
    <form method="post" action="{{ route('module.plan_suggest.save') }}" name="frm" enctype="multipart/form-data" class="form-ajax">
        <input type="hidden" name="id" value="{{$model->id}}">
        <div class="form-group row">
            <div class="col-md-8">
            </div>
            <div class="col-md-4 text-right">
                @if($approved == 1 && userCan('plan-suggest-approve'))
                    <button type="submit" class="btn approve"><i class="fa fa-check"></i> {{trans('labutton.approve')}}</button>
                    <button type="submit" class="btn unapprove"><i class="fa fa-times"></i> {{trans('labutton.deny')}}</button>
                @else
                    @if(!$model->status || $model->status == 3)
                        @if(\App\Models\Permission::isUnitManager() || userCan('plan-suggest-create') || userCan('plan-suggest-edit'))
                            <button type="submit" class="btn save" ><i class="fa fa-save"></i> &nbsp;{{ trans('labutton.save') }}</button>
                            <button type="submit" class="btn send" ><i class="fa fa-paper-plane"></i> &nbsp;{{ trans('labutton.save_send') }}</button>
                        @endif
                    @endif
                @endif
                <a href="{{ route('module.plan_suggest') }}" class="btn"><i class="fa fa-times-circle"></i> {{trans('labutton.back')}}</a>
                <input type="hidden" name="save" value="{{ $model->status }}">
            </div>
        </div>

        <div class="tPanel">
        <ul class="nav nav-pills mb-4" role="tablist" id="mTab">
            <li class="nav-item"><a href="#base" class="nav-link @if($tabs == 'base' || empty($tabs)) active @endif" role="tab" data-toggle="tab">{{ trans('lasuggest_plan.info') }}</a></li>
            <li class="nav-item"><a href="#report" class="nav-link @if($tabs == 'object') active @endif" data-toggle="tab">{{trans("lasuggest_plan.report")}}</a></li>
        </ul>
        <div class="tab-content">
            <div id="base" class="tab-pane @if($tabs == 'base' || empty($tabs)) active @endif">
                @include('plansuggest::backend.form.infor')
            </div>
            <div id="report" class="tab-pane @if($tabs == 'report') active @endif">
                @include('plansuggest::backend.form.report')
            </div>
        </div>
    </div>
    </form>
    <script type="text/javascript">
        $('.save').on('click', function () {
           $("input[name=save]").val(0);
        });
        $('.send').on('click', function () {
            $("input[name=save]").val(1);
        });
        $('.approve').on('click', function () {
            $("input[name=save]").val(2);
        });
        $('.unapprove').on('click', function () {
            $("input[name=save]").val(3);
        });

        if ($('#check-subject').val() == 1){
            $('#check-subject').prop('checked', false);
            $('#subject_text').prop('disabled', true);
            $('#subject_select2').prop('disabled', false);
            $('#subject_text').val('');
        }else {
            $('#check-subject').prop('checked', true);
            $('#subject_text').prop('disabled', false);
            $('#subject_select2').prop('disabled', true);
        }

        $('#check-subject').on('change', function () {
             if ($(this).is(':checked')){
                 $('#subject_text').prop('disabled', false);
                 $('#subject_select2').prop('disabled', true);
             } else {
                 $('#subject_text').prop('disabled', true);
                 $('#subject_select2').prop('disabled', false);
             }
        });

        $("#select-attach").on('click', function () {
            var lfm = function (options, cb) {
                var route_prefix = '/filemanager';
                window.open(route_prefix + '?type=' + options.type || 'file', 'FileManager', 'width=1100,height=600');
                window.SetUrl = cb;
            };

            lfm({type: 'files'}, function (url, path) {
                var path2 =  path.split("/");
                $("#attach-review").html(path2[path2.length - 1]);
                $("#attach-select").val(path);
            });
        });

        $("#select-attach-report").on('click', function () {
            var lfm = function (options, cb) {
                var route_prefix = '/filemanager';
                window.open(route_prefix + '?type=' + options.type || 'file', 'FileManager', 'width=1100,height=600');
                window.SetUrl = cb;
            };

            lfm({type: 'files'}, function (url, path) {
                var path2 =  path.split("/");
                $("#attach-report-review").html(path2[path2.length - 1]);
                $("#attach-report-select").val(path);
            });
        });

        var ajax_user_by_title = "{{ route('module.plan_suggest.ajax_user_by_title') }}";
    </script>
</div>

@stop
