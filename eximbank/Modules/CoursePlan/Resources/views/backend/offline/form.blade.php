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
                'name' => trans('lamenu.training_organizations'),
                'url' => ''
            ],
            [
                'name' => trans('latraining.monthly_training_plan'),
                'url' => route('module.course_plan.management')
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
                <a href="{{ route('module.course_plan.teacher', ['course_type' => $course_type, 'id' => $model->id]) }}"
                   class="btn">
                    <div><i class="fa fa-inbox"></i></div>
                    <div>{{ trans('backend.teacher') }}</div>
                </a>
            </div>
        @endif
    </div>
    <br>
    <div class="tPanel">
        <ul class="nav nav-pills mb-4" role="tablist" id="mTab">
            <li class="nav-item"><a href="#base" class="nav-link @if($tabs == 'base' || empty($tabs)) active @endif" role="tab" data-toggle="tab">{{ trans('latraining.info') }}</a></li>
            @if($model->id)
            <li class="nav-item"><a href="#object" class="nav-link" data-toggle="tab">{{ trans('backend.object_join') }}</a></li>
            <li class="nav-item"><a href="#schedule" class="nav-link" data-toggle="tab">{{ trans('backend.schedule') }}</a></li>
            {{-- <li class="nav-item"><a href="#cost" class="nav-link" data-toggle="tab">{{ trans('backend.training_cost') }}</a></li> --}}
            <li class="nav-item"><a href="#condition" class="nav-link" data-toggle="tab">{{ trans('backend.conditions') }}</a></li>
            @endif
        </ul>
        <div class="tab-content">
            <div id="base" class="tab-pane @if($tabs == 'base' || empty($tabs)) active @endif">
                @include('courseplan::backend.offline.form.info')
            </div>
            @if($model->id)
                <div id="object"
                     class="tab-pane">
                    @include('courseplan::backend.offline.form.object')
                </div>
                {{-- <div id="cost"
                     class="tab-pane">
                    @include('courseplan::backend.offline.form.cost')
                </div> --}}
                <div id="condition"
                     class="tab-pane">
                    @include('courseplan::backend.offline.form.condition')
                </div>
                <div id="schedule"
                     class="tab-pane">
                    @include('courseplan::backend.offline.form.schedule')
                </div>
            @endif
        </div>
    </div>
    <script type="text/javascript">
        $('#training_program_id').on('change', function () {
            var training_program_id = $('#training_program_id option:selected').val();
            $("#level_subject_id").empty();
            $("#level_subject_id").data('training-program', training_program_id);
            $('#level_subject_id').trigger('change');

            $("#subject_id").empty();
            $("#subject_id").data('training-program', training_program_id);
            $("#subject_id").data('level-subject', '');
            $('#subject_id').trigger('change');
        });

        $('#level_subject_id').on('change', function () {
            var training_program_id = $('#training_program_id option:selected').val();
            var level_subject_id = $('#level_subject_id option:selected').val();
            $("#subject_id").empty();
            $("#subject_id").data('training-program', training_program_id);
            $("#subject_id").data('level-subject', level_subject_id);
            $('#subject_id').trigger('change');
        });

        $('#subject_id').on('change', function() {
            var subject_id = $('#subject_id option:selected').val();
            var subject_name = $('#subject_id option:selected').text();
            $.ajax({
                url: '{{ route('module.course_plan.ajax_get_course_code') }}',
                type: 'post',
                data: {
                    subject_id: subject_id,
                },
            }).done(function(data) {
                var d = new Date();
                if(subject_id != null){
                    //$('#code').val(data.subject_code + "_" + (d.getMonth() + 1) + "_" + d.getFullYear() + "_" + (data.id + 1));
                    $("input[name=name]").val(subject_name);
                    $('#description').text(data.description);
                    CKEDITOR.instances['content'].setData(data.content);
                }
                return false;
            }).fail(function(data) {
                show_message('Lỗi hệ thống', 'error');
                return false;
            });
        });

        $('#has_cert').on('change', function() {
            if($(this).is(':checked')) {
                $("#cert_code").prop('disabled', false);
                $("#has_cert").val(1);
            }
            else {
                $("#cert_code").prop('disabled', true);
                $("#has_cert").val(0);
            }
        });

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

        $("#select-document").on('click', function () {
            var lfm = function (options, cb) {
                var route_prefix = '/filemanager';
                window.open(route_prefix + '?type=' + options.type || 'file', 'FileManager', 'width=1100,height=600');
                window.SetUrl = cb;
            };

            lfm({type: 'file'}, function (url, path) {
                var path2 =  path.split("/");
                $("#document-review").html(path2[path2.length - 1]);
                $("#document-select").val(path);
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

        $('select[name=province]').on('change',function (e) {
            var url = $(this).data('url');
            $.get(url,{province_id:$(this).val()})
                .done(function(result){
                    if (result && result.length) {
                        var data = [{ id:'',text:'Chọn quận huyện'}];
                        $.each(result, function (index, obj) {
                            data.push({
                                id: obj.id,
                                text: obj.name,
                            });
                        });
                        $('select[name=district]').empty().select2({
                            data: data,
                            width: '100%',
                        });
                    }
                });
            loadTranginingLocation($(this).val(),0)
        });

        $('select[name=district]').on('change',function (e) {
            loadTranginingLocation($('select[name=province]').val(),$(this).val())
        });

        function loadTranginingLocation(province,district){
            data ={province_id:province,district_id:district};
            $.ajax({
                type: "GET",
                url: $('select[name=training_location_id]').data('url'),
                dataType: 'json',
                data: data,
                success: function (result) {
                    var data=[];
                    $.each(result, function (index, obj) {
                        data.push({
                            id: obj.id,
                            text: obj.name,
                        });
                    });
                    $('select[name=training_location_id]').empty().select2({
                        data: data,
                        width: '100%',
                    }).val('').trigger('change');
                }
            });
        }
    </script>
</div>
@stop
