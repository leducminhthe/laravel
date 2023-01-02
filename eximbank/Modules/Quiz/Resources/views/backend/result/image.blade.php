@extends('layouts.backend')

@section('page_title', trans('latraining.result'))

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
                    'name' => trans('latraining.result'),
                    'url' => route('module.quiz.result', ['id' => $quiz_name->id])
                ],
                [
                    'name' => trans('backend.picture_of') . ' ' . $fullname,
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
                    'name' => trans('latraining.result'),
                    'url' => route('module.quiz.result', ['id' => $quiz_name->id])
                ],
                [
                    'name' => trans('backend.picture_of') . ' ' . $fullname,
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
                    'url' => route('module.quiz.edit', ['id' => $quiz_name->id])
                ],
                [
                    'name' => trans('latraining.result'),
                    'url' => route('module.quiz.result', ['id' => $quiz_name->id])
                ],
                [
                    'name' => trans('backend.picture_of') . ' ' . $fullname,
                    'url' => ''
                ],
            ];
        }
    @endphp
    @include('layouts.backend.menu_breadcum', $breadcum)
@endsection

@section('content')
        <table class="tDefault table table-hover bootstrap-table text-nowrap">
            <thead>
                <tr>
                    <th data-sortable="true" data-formatter="stt_formatter" data-align="center" data-width="5%">{{ trans('latraining.stt') }}</th>
                    <th data-field="image" data-formatter="image_formatter" data-width="15%">{{trans('backend.picture')}}</th>
                    <th data-field="time" data-align="center" data-width="10%">{{trans('backend.time')}}</th>
                </tr>
            </thead>
        </table>
    </div>

    <script type="text/javascript">
        function stt_formatter(value, row, index){
            return index + 1;
        }

        function image_formatter(value, row, index){
            return '<img src="'+ row.url_image+'" class="w-50" />';
        }
        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.quiz.result.user.getdata_image', ['id' => $quiz_id, 'type' => $type, 'user_id' => $user_id]) }}',
        });

    </script>

@endsection
