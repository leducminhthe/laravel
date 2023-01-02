@extends('layouts.backend')

@section('page_title', $profile->full_name)

@section('breadcrumb')
    @php
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
                'name' => $page_title,
                'url' => route('module.online.edit', ['id' => $course->id])
            ],
            [
                'name' => trans("latraining.training_result"),
                'url' => route('module.online.result', ['id' => $course->id])
            ],
            [
                'name' => $profile->full_name,
                'url' => ''
            ],
        ]
    @endphp
    @include('layouts.backend.menu_breadcum',$breadcum)

    <style>
        .history_name {
            padding: 10px;
            margin: 5px 0px;
            border: 1px solid #efefef;
            cursor: pointer;
        }
    </style>
@endsection

@section('content')

    <div role="main" id="result">
        <div class="row">
            <div class="col-12 col-md-4 mt-2">
                @foreach ($get_activity_courses as $key_history => $get_activity_quiz_scorm)
                    @if ($get_activity_quiz_scorm->activity_id == 1)
                        @php
                            $activity_history_scorm = \Modules\Online\Entities\OnlineCourseActivityScorm::findOrFail($get_activity_quiz_scorm->subject_id);
                        @endphp
                        <div class="history_name" onclick="opend_history_scorm({{ $activity_history_scorm->id }}, {{ $key_history }})">
                            <span>{{ $get_activity_quiz_scorm->name }} </span>
                            <i class="fas fa-caret-right float-right"></i>
                        </div>
                    @elseif ($get_activity_quiz_scorm->activity_id == 2)
                        @php
                            $user_type = getUserType();
                            $user_id = $profile->user_id;
                            $part =  \Modules\Quiz\Entities\QuizPart::where('quiz_id', '=', $get_activity_quiz_scorm->subject_id)
                            ->whereIn('id', function ($subquery) use ($user_id, $user_type, $get_activity_quiz_scorm) {
                                $subquery->select(['a.part_id'])
                                    ->from('el_quiz_register AS a')
                                    ->join('el_quiz_part AS b', 'b.id', '=', 'a.part_id')
                                    ->where('a.quiz_id', '=', $get_activity_quiz_scorm->subject_id)
                                    ->where('a.user_id', '=', $user_id)
                                    ->where('a.type', '=', $user_type)
                                    ->where(function ($where){
                                        $where->orWhere('b.end_date', '>', date('Y-m-d H:i:s'));
                                        $where->orWhereNull('b.end_date');
                                    });
                            })->first();
                        @endphp
                        <div class="history_name" onclick="opend_history_quiz({{ $get_activity_quiz_scorm->subject_id}}, {{ !empty($part) ? $part->id : 0 }}, {{ $key_history }})">
                            <span>{{ $get_activity_quiz_scorm->name }} </span>
                            <i class="fas fa-caret-right float-right"></i>
                        </div>
                    @endif
                @endforeach
            </div>
            <div class="col-12 col-md-8 mt-3">
                @foreach ($get_activity_courses as $key_history => $get_activity_quiz_scorm)
                    @if ($get_activity_quiz_scorm->activity_id == 1)
                        <div class="table_history" id="table_history_{{$key_history}}">
                            <table class="tDefault table table-hover bootstrap-table text-nowrap table-bordered" id="table-history-scrom-{{$get_activity_quiz_scorm->subject_id}}">
                                <thead>
                                <tr>
                                    <th data-formatter="index_formatter" data-align="center">#</th>
                                    <th data-field="start_date">{{ trans('latraining.start_date') }}</th>
                                    <th data-field="end_date">{{ trans('latraining.end_date') }}</th>
                                    <th data-field="grade" data-align="center">{{ trans('latraining.score') }}</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    @elseif ($get_activity_quiz_scorm->activity_id == 2)
                        <div class="table_history" id="table_history_{{$key_history}}">
                            <table class="tDefault table table-hover bootstrap-table text-nowrap table-bordered" id="table-history-quiz-{{$get_activity_quiz_scorm->subject_id}}">
                                <thead>
                                <tr>
                                    <th data-formatter="index_formatter" data-align="center">#</th>
                                    <th data-field="start_date">{{ trans('latraining.start_date') }}</th>
                                    <th data-field="end_date">{{ trans('latraining.end_date') }}</th>
                                    <th data-field="grade" data-align="center">{{ trans('latraining.score') }}</th>
                                    <th data-field="status" data-align="center">{{ trans('latraining.status') }}</th>
                                    <th data-field="review" data-align="center" data-formatter="review_formatter">{{ trans('latraining.review') }}</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    @endif

                @endforeach
            </div>
        </div>
    </div>

    <script type="text/javascript">
        function index_formatter(value, row, index) {
            return (index + 1);
        }

        $('.table_history').hide();
        // MỞ LỊCH SỬ SCORM
        function opend_history_scorm(id, key) {
            $('.table_history').hide();
            $('#table_history_'+key).show();
            var url = "{{ route('module.online.attempts', ':id') }}?user_id={{ $profile->user_id }}&user_type={{ getUserType() }}";
            url = url.replace(':id',id);
            var table_scrom = new LoadBootstrapTable({
                locale: '{{ \App::getLocale() }}',
                url: url,
                table: '#table-history-scrom-'+id
            });
        }

        // MỞ LỊCH SỬ KỲ THÌ

        function review_formatter(value, row, index) {
            if (row.after_review == 1 || row.closed_review == 1) {
                return '<a href="'+ row.review_link +'">{{ trans('latraining.review') }}</a>';
            }
            return '<span class="text-muted">{{ trans('latraining.no_review') }}</span>';
        }

        function opend_history_quiz(quizId, partId, key) {
            $('.table_history').hide();
            $('#table_history_'+key).show();
            var url = "{{ route('module.quiz.doquiz.attempt_history', ['quiz_id' => ':id', 'part_id' => ':partId']) }}?user_id={{ $profile->user_id }}&user_type={{ getUserType() }}";
            url = url.replace(':id',quizId);
            url = url.replace(':partId',partId);
            var table = new LoadBootstrapTable({
                locale: '{{ \App::getLocale() }}',
                url: url,
                table: '#table-history-quiz-'+quizId
            });
        }
    </script>
@endsection
