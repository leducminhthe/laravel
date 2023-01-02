@extends('layouts.backend')

@section('page_title', $page_title)
@section('header')
    <style>
        .select2-container--default .error {
            background-color: #fff;
            border-color: #80bdff !important;
            outline: 0;
            box-shadow: 0 0 0 0.2rem rgb(0 123 255 / 25%);
        }
    </style>
    <script src="{{asset('styles/vendor/jqueryplugin/printThis.js')}}"></script>
    <script src="{{asset('modules/quiz/js/quiz.userpoint.js')}}"></script>
@endsection

@section('breadcrumb')
    @php
        if($course_type == 1){
            $route_edit = route('module.online.edit', ['id' => $course_id]);
            $route_quiz = route('module.online.quiz', ['course_id' => $course_id]);

            $breadcum= [
                [
                    'name' => trans('lamenu.training_organizations'),
                    'url' => ''
                ],
                [
                    'name' => trans('lamenu.online_course'),
                    'url' => route('module.online.management')
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
            ];
        }elseif($course_type == 2){
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
            ];
        }else{
            $breadcum= [
                [
                    'name' => trans('latraining.quiz_list'),
                    'url' => route('module.quiz.manager')
                ],
                [
                    'name' => $page_title,
                    'url' => ''
                ],
            ];
        }

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
                @canany(['training-unit-quiz-add-question', 'quiz-add-question'])
                    <a href="{{ $is_unit > 0 ? route('module.training_unit.quiz.question', ['id' => $model->id]) : route('module.quiz.question', ['id' => $model->id]) }}" class="btn"> <i class="fa fa-question-circle"></i> {{ trans('latraining.question') }}</a>
                @endcanany
                @canany(['training-unit-quiz-result', 'quiz-result'])
                    <a href="{{ $is_unit > 0 ? route('module.training_unit.quiz.result', ['id' => $model->id]) : route('module.quiz.result', ['id' => $model->id]) }}" class="btn"><i class="fa fa-eye"></i> {{ trans('backend.result') }}</a>
                @endcanany
                @canany(['training-unit-quiz-register', 'quiz-register'])
                    <a href="{{ $is_unit > 0 ? route('module.training_unit.quiz.register', ['id' => $model->id]) : route('module.quiz.register', ['id' => $model->id]) }}" class="btn"> <i class="fa fa-users"></i> {{ trans('backend.internal_contestant') }}</a>
                @endcanany
                @if ($model->quiz_type == 3)
                    @can('quiz-register-user-secondary')
                        <a href="{{ route('module.quiz.register.user_secondary', ['id' => $model->id]) }}" class="btn"><i class="fa fa-users"></i> {{ trans('backend.user_secondary') }}</a>
                    @endcan
                    @canany(['quiz-create', 'quiz-edit'])
                        <button type="button" id="text_quiz" onclick="modalTextQuiz({{ $model->id }})" class="btn"><i class="fas fa-pen-square"></i> Thi thử</button>
                    @endcan
                @endif
            </div>
        </div>
        <p></p>
    @endif
    <div class="tPanel">
        <ul class="nav nav-pills mb-4" role="tablist" id="mTab">
            <li class="nav-item"><a href="#base" class="nav-link @if($tabs == 'base' || empty($tabs)) active @endif" role="tab" data-toggle="tab">{{ trans('latraining.info') }}</a></li>
            @if($model->id)
                @canany(['quiz-create', 'quiz-edit'])
                <li class="nav-item">
                    <a href="#part" class="nav-link" role="tab" data-toggle="tab">
                        {{trans('backend.exams') .' ('. $model->parts->count() .')' }}
                    </a>
                </li>
                {{--  <li class="nav-item">
                    <a href="#rank" class="nav-link" role="tab" data-toggle="tab">
                        {{ trans("backend.classification") }}
                    </a>
                </li>  --}}
                <li class="nav-item">
                    <a href="#teacher1" class="nav-link" role="tab" data-toggle="tab">
                        {{ trans('backend.teacher') .' ('. $model->teachers->count() .')' }}
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#setting1" class="nav-link" role="tab" data-toggle="tab">
                        {{trans('backend.custom')}}
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#userpoint" class="nav-link @if($tabs == 'userpoint') active @endif" role="tab" data-toggle="tab">
                        {{ trans('backend.reward_points') .' ('. $userpoint->count() .')' }}
                    </a>
                </li>

                <li class="nav-item">
                    <a href="#suggestions" class="nav-link @if($tabs == 'suggestions') active @endif" role="tab" data-toggle="tab">
                        {{ trans('lamenu.suggestion') }} ({{ $model->user_reviews->count() }})
                    </a>
                </li>
                @endcanany
            @endif
        </ul>

        <div class="tab-content">
            <div id="base" class="tab-pane @if($tabs == 'base' || empty($tabs)) active @endif">
                @include('quiz::backend.quiz.form.info')
            </div>

            @if($model->id)
                <div id="part" class="tab-pane">
                    @include('quiz::backend.quiz.form.part')
                </div>

                <div id="rank" class="tab-pane">
                    @include('quiz::backend.quiz.form.rank')
                </div>

                <div id="teacher1" class="tab-pane">
                    @include('quiz::backend.quiz.form.teacher')
                </div>

                <div id="setting1" class="tab-pane">
                    @include('quiz::backend.quiz.form.setting')
                </div>

                <div id="userpoint" class="tab-pane @if($tabs == 'userpoint') active @endif">
                    @include('quiz::backend.quiz.form.userpoint')
                </div>

                <div id="suggestions" class="tab-pane @if($tabs == 'suggestions') active @endif">
                    @include('quiz::backend.quiz.form.suggestions')
                </div>
            @endif
        </div>
    </div>
</div>

<div class="modal fade" id="modal-text-quiz" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Thi thử</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" method="post" id="form_text_quiz">
                    {{ csrf_field() }}
                    <input type="hidden" name="text_quiz" value="1">
                    <table class="tDefault table table-hover">
                        <thead>
                            <tr>
                                <th style="width: 80%;">Ca thi</th>
                                <th class="text-center" style="width: 20%;">Thi thử</th>
                            </tr>
                        </thead>
                        <tbody class="tbody_table">
                            
                        </tbody>
                    </table>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('footer')
    <script type="text/javascript">
        $(document).ready(function() {
            $('.load-unit-quiz').select2({
                allowClear: true,
                dropdownAutoWidth: true,
                width: '100%',
                placeholder: function (params) {
                    return {
                        id: null,
                        text: params.placeholder,
                    }
                },
                ajax: {
                    method: 'GET',
                    url: '{{ route('module.quiz.edit.getunit', ['id' => $model->id]) }}',
                    dataType: 'json',
                    data: function (params) {

                        var query = {
                            search: $.trim(params.term),
                            page: params.page,
                        };

                        return query;
                    }
                }
            });

        });

        function modalTextQuiz(quizId) {
            let item = $('#text_quiz');
            let oldtext = item.html();
            item.html('<i class="fa fa-spinner fa-spin"></i> Vui lòng chờ');
            item.prop("disabled", true);
            $.ajax({
                url: "{{ route('module.quiz.ajax_text_quiz_part') }}",
                type: 'post',
                data: {
                    quizId: quizId,
                }
            }).done(function(data) {
                if(data.status == 'error') {
                    show_message(data.message, data.status)
                }
                item.html(oldtext);
                item.prop("disabled", false);
                let html = '';
                data.forEach(item => {
                    html += `<tr>
                                <th>`+ item.name +`</th>
                                <th class="text-center">
                                    <button type="button" class="btn btn-go-quiz" onclick="submitFormText(`+ quizId +`,`+ item.id +`)">Thi thử</button>
                                </th>
                            </tr>`
                });
                $('.tbody_table').html(html)
                $('#modal-text-quiz').modal()
                return false;
            }).fail(function(data) {
                show_message('Lỗi hệ thống', 'error');
                return false;
            });
        }

        function submitFormText(quizId, partId) {
            let url = "{{ route('module.quiz.doquiz.create_quiz', ['quiz_id' => ':id', 'part_id' => ':partId']) }}";
            url = url.replace(':id', quizId);
            url = url.replace(':partId', partId);
            $('#form_text_quiz').attr('action', url)
            $('#form_text_quiz').submit();
        }
    </script>
@endsection
