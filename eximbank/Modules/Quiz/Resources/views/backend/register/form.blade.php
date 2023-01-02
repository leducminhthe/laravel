@extends('layouts.backend')

@section('page_title', trans('labutton.add_new'))

@section('breadcrumb')
    @php
        if($quiz_name->course_type == 1){
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
                    'name' => $quiz_name->name,
                    'url' => route('module.online.quiz.edit', ['course_id' => $course_id, 'id' => $quiz_name->id])
                ],
                [
                    'name' => trans('laquiz.internal_user'),
                    'url' => route('module.quiz.register', ['id' => $quiz_id])
                ],
                [
                    'name' => trans('labutton.add_new'),
                    'url' => ''
                ],
            ];
        }elseif($quiz_name->course_type == 2){
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
                    'name' => $quiz_name->name,
                    'url' => route('module.offline.quiz.edit', ['course_id' => $course_id, 'id' => $quiz_name->id])
                ],
                [
                    'name' => trans('laquiz.internal_user'),
                    'url' => route('module.quiz.register', ['id' => $quiz_id])
                ],
                [
                    'name' => trans('labutton.add_new'),
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
                    'name' => $quiz_name->name,
                    'url' => route('module.quiz.edit', ['id' => $quiz_id])
                ],
                [
                    'name' => trans('laquiz.internal_user'),
                    'url' => route('module.quiz.register', ['id' => $quiz_id])
                ],
                [
                    'name' => trans('labutton.add_new'),
                    'url' => ''
                ],
            ];
        }
    @endphp
    @include('layouts.backend.menu_breadcum',$breadcum)
@endsection

@section('content')
<div role="main">
        <div class="row">
            <div class="col-md-6">
                @include('quiz::backend.register.filter_create')
            </div>
            <div class="col-md-6 text-right act-btns">
                <div class="pull-right">
                    <div class="btn-group">
                        <button type="submit" id="button-register" class="btn"><i class="fa fa-plus-circle"></i> {{ trans('backend.register') }}</button>
                        <a href="{{ route('module.quiz.register', ['id' => $quiz_id]) }}" class="btn"><i class="fa fa-times-circle"></i> {{ trans('labutton.cancel') }}</a>
                    </div>
                </div>
            </div>
        </div>
        <br>

        <table class="tDefault table table-hover bootstrap-table" data-page-list="[10, 50, 100, 200, 500]">
            <thead>
                <tr>
                    <th data-field="state" data-checkbox="true"></th>
                    <th data-sortable="true" data-field="code" data-width="10%">{{trans('backend.employee_code')}}</th>
                    <th data-field="name" data-formatter="name_formatter">{{ trans('backend.employee_name') }}</th>
                    <th data-field="title_name">{{ trans('latraining.title') }}</th>
                    <th data-field="unit_name">{{ trans('backend.work_unit') }}</th>
                    <th data-field="parent_name">{{ trans('backend.unit_manager') }}</th>
                </tr>
            </thead>
        </table>
    </div>
    <div class="modal fade" id="modal-part" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">{{trans('backend.choose_exams')}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="col-md-12">
                            <div class="form-group row">
                                <select name="part" id="part" class="form-control select2" data-placeholder="-- {{trans('backend.exams')}} --">
                                    <option value=""></option>
                                    @foreach ($quiz_part as $part)
                                        <option value="{{ $part->id }}" >{{ $part->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        {{-- <button type="button" class="btn" data-dismiss="modal">{{ trans('labutton.close') }}</button> --}}
                        <button type="button" class="btn" id="button-part">{{ trans('labutton.save') }}</button>
                    </div>
                </div>
            </div>
        </div>
    <script type="text/javascript">
        function name_formatter(value, row, index) {
            return row.lastname +' ' + row.firstname;
        }
        var ajax_get_user = "{{ route('module.quiz.register.save', ['id' => $quiz_id]) }}";

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.quiz.register.getDataNotRegister', ['id' => $quiz_id]) }}',
            field_id: 'user_id'
        });
    </script>
    <script type="text/javascript" src="{{ asset('styles/module/quiz/js/register.js?v='.time()) }}"></script>

@stop
