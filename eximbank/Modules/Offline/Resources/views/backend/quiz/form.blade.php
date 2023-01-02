@extends('layouts.backend')

@section('page_title', $page_title)
@section('header')
    <script src="{{asset('styles/vendor/jqueryplugin/printThis.js')}}"></script>
@endsection
@section('breadcrumb')
    @php
        $route_edit = route('module.offline.edit', ['id' => $course_id]);
        $route_quiz = route('module.offline.quiz', ['course_id' => $course_id]);

        $breadcum= [
            [
                'name' => trans('lamenu.training_organizations'),
                'url' => ''
            ],
            [
                'name' => trans('lamenu.offline_course'),
                'url' => route('module.offline.management')
            ],
            [
                'name' => $course->name,
                'url' => $route_edit
            ],
            [
                'name' => trans('latraining.quiz_list'),
                'url' => $route_quiz
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
    @if($model->id)
        <div class="row">
            <div class="col-md-12 text-right">
                <a href="{{ route('module.quiz.register', ['id' => $model->id, 'course_id' => $course_id, 'course_type' => 2]) }}" class="btn"><i class="fa fa-users"></i> {{ trans("latraining.register") }}</a>

                <a href="{{ route('module.offline.quiz.question', ['course_id' => $course_id, 'id' => $model->id]) }}" class="btn"> <i class="fa fa-question-circle"></i> {{ trans('latraining.question') }}</a>

                <a href="{{ route('module.quiz.result', ['id' => $model->id, 'course_id' => $course_id, 'course_type' => 2]) }}" class="btn" title="{{ trans("latraining.result") }}"><i class="fa fa-eye"></i> {{ trans('latraining.result') }}</a>
            </div>
        </div>
        <p></p>
    @endif
    <div class="tPanel">
        <ul class="nav nav-pills mb-4" role="tablist" id="mTab">
            <li class="nav-item"><a href="#base" class="nav-link @if($tabs == 'base' || empty($tabs)) active @endif" role="tab" data-toggle="tab">{{ trans('latraining.info') }}</a></li>
            @if($model->id)
                <li class="nav-item"><a href="#part" class="nav-link" role="tab" data-toggle="tab">{{trans('latraining.part')}}</a></li>
                <li class="nav-item"><a href="#rank" class="nav-link" role="tab" data-toggle="tab">{{ trans("latraining.classification") }}</a></li>
                <li class="nav-item"><a href="#teacher1" class="nav-link" role="tab" data-toggle="tab">{{ trans('latraining.teacher') }}</a></li>
                <li class="nav-item"><a href="#setting1" class="nav-link" role="tab" data-toggle="tab">{{trans('latraining.custom')}}</a></li>
            @endif
        </ul>

        <div class="tab-content">
            <div id="base" class="tab-pane @if($tabs == 'base' || empty($tabs)) active @endif">
                @include('offline::backend.quiz.form.info')
            </div>

            @if($model->id)
                <div id="part" class="tab-pane">
                    @include('offline::backend.quiz.form.part')
                </div>

                <div id="rank" class="tab-pane">
                    @include('offline::backend.quiz.form.rank')
                </div>

                <div id="teacher1" class="tab-pane">
                    @include('offline::backend.quiz.form.teacher')
                </div>

                <div id="setting1" class="tab-pane">
                    @include('offline::backend.quiz.form.setting')
                </div>
            @endif
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $('#full_screen').on('click', function () {
            if ($(this).is(':checked')) {
                $(this).closest('.form-check').find('.check-full-screen').val(1);
            } else {
                $(this).closest('.form-check').find('.check-full-screen').val(0);
            }
        });
        $('#paper_exam').on('click', function () {
            if ($(this).is(':checked')) {
                $(this).closest('.form-check').find('.check-paper-exam').val(1);
            } else {
                $(this).closest('.form-check').find('.check-paper-exam').val(0);
            }
        });

        $('input[name=webcam_require]').on('click', function () {
            if ($(this).is(':checked')) {
                $(this).val(1);
                $('input[name=times_shooting_webcam]').prop('disabled', false);
            } else {
                $(this).val(0);
                $('input[name=times_shooting_webcam]').prop('disabled', true);
            }
        });
        $('input[name=question_require]').on('click', function () {
            if ($(this).is(':checked')) {
                $(this).val(1);
                $('input[name=times_shooting_question]').prop('disabled', false);
            } else {
                $(this).val(0);
                $('input[name=times_shooting_question]').prop('disabled', true);
            }
        });
    });

    $('select[name=quiz_template_id]').on('change',function () {
        var $this = $(this);
        $.ajax({
            type: 'POST',
            url: '{{ route('module.quiz.load.exam.template') }}',
            dataType: 'json',
            data: {
                exam_template_id:$this.val()
            }
        }).done(function(result) {
            var data = result.data;
            var attemp = (data.attempts < 10 && data.attempts > 0) ? '0'+(data.attempts) : data.attempts;
            if (result.status=='success'){
                $('input[name=code]').val(data.code);
                $('input[name=name]').val(data.name);
                $('textarea[name=description]').val(data.description);
                $('input[name=limit_time]').val(data.limit_time);
                $('input[name=pass_score]').val(data.pass_score);
                $('input[name=max_score]').val(data.max_score);
                $('input[name=questions_perpage]').val(data.questions_perpage);
                $('select[name=quiz_type]').val(data.quiz_type).trigger('change');
                $('select[name=shuffle_question]').val(data.shuffle_question).trigger('change');
                $('select[name=shuffle_answers]').val(data.shuffle_answers).trigger('change');
                $('select[name=attempts]').val(attemp).trigger('change');
                $('select[name=grade_methor]').val(data.grade_methor).trigger('change');
                $('select[name=type_id]').val(data.type_id).trigger('change');
                $('input[name=paper_exam]').val(data.paper_exam);
                $('#paper_exam').attr('checked',data.paper_exam==1?true:false);
                $('input[name=img]').val(data.img);
                $("#image-review").html('<img src="'+ data.img_view +'" class="w-50">');
                $('input[name=webcam_require]').val(data.webcam_require);
                $('input[name=question_require]').val(data.question_require);
                $('input[name=times_shooting_webcam]').val(data.times_shooting_webcam);
                $('input[name=times_shooting_question]').val(data.times_shooting_question);
                $('input[name=webcam_require]').attr('checked',data.webcam_require==1?true:false);
                $('input[name=question_require]').attr('checked',data.question_require==1?true:false);
                $('input[name=times_shooting_webcam]').attr('disabled',data.webcam_require==1?false:true);
                $('input[name=times_shooting_question]').attr('disabled',data.question_require==1?false:true);
            }
        }).fail(function(data) {
            return false;
        });
    });

    $("#select-image").on('click', function () {
        var lfm = function (options, cb) {
            var route_prefix = '/filemanager';
            window.open(route_prefix + '?type=' + options.type || 'file', 'FileManager', 'width=1100,height=600');
            window.SetUrl = cb;
        };

        lfm({type: 'image'}, function (url, path) {
            $("#image-review").html('<img src="'+ path +'" class="w-50">');
            $("#image-select").val(path);
        });
    });
</script>
@endsection
