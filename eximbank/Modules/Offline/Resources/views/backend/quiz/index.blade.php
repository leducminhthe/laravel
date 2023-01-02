@extends('layouts.backend')

@section('page_title', trans('latraining.quiz_list'))

@section('breadcrumb')
    @php
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
                'name' => $page_title,
                'url' => route('module.offline.edit', ['id' => $course_id])
            ],
            [
                'name' => trans('latraining.quiz_list'),
                'url' => ''
            ],
        ]
    @endphp
    @include('layouts.backend.menu_breadcum',$breadcum)
@endsection

@section('content')
    <div role="main" class="quiz_course_offline">
        <div class="row">
            <div class="col-md-12 text-right act-btns" id="btn-quiz">
                <div class="pull-right">
                    <div class="btn-group">
                        @if ($course->lock_course == 0)
                            @if (!$course->register_quiz_id)
                            <a href="{{ route('module.offline.quiz.create', ['course_id' => $course_id, 'quiz_type_by_offline' => 'register_quiz_id']) }}" class="btn">
                                <i class="fa fa-plus-circle"></i> Thi trước ghi danh
                            </a>
                            @endif
                            @if (!$course->entrance_quiz_id)
                                <a href="{{ route('module.offline.quiz.create', ['course_id' => $course_id, 'quiz_type_by_offline' => 'entrance_quiz_id']) }}" class="btn">
                                    <i class="fa fa-plus-circle"></i> {{ trans("latraining.first_quiz") }}
                                </a>
                            @endif

                            @if (!$course->quiz_id)
                                <a href="{{ route('module.offline.quiz.create', ['course_id' => $course_id, 'quiz_type_by_offline' => 'quiz_id']) }}" class="btn">
                                    <i class="fa fa-plus-circle"></i> {{ trans("latraining.final_quiz") }}
                                </a>
                            @endif
                            <a href="{{ route('module.offline.quiz.create', ['course_id' => $course_id, 'quiz_type_by_offline' => 'activity_quiz_id']) }}" class="btn">
                                <i class="fa fa-plus-circle"></i> Hoạt động kỳ thi
                            </a>
                        @endif
                        <button class="btn" id="delete-item"><i class="fa fa-trash"></i> {{ trans('labutton.delete') }}</button>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <table class="tDefault table table-hover bootstrap-table">
            <thead>
                <tr>
                    <th data-field="state" data-width="1%" data-checkbox="true"></th>
                    <th data-field="is_open" data-width="3%" data-formatter="is_open_formatter" data-align="center">{{trans('latraining.status')}}</th>
                    <th data-field="name" data-formatter="name_formatter">{{trans('latraining.quiz_name')}}</th>
                    <th data-field="quiz_type_by_offline" data-width="10%" data-align="center">{{ trans('latraining.type') }}</th>
                    <th data-field="limit_time" data-align="center" data-formatter="limit_time_formatter" data-width="5%">
                        {{trans('backend.time')}} <br> {{trans('backend.do_quiz')}}
                    </th>
                    <th data-field="view_result" data-formatter="view_result_formatter" data-align="center" data-width="7%">{{trans('latraining.see_result')}}</th>
                    <th data-field="status" data-align="center" data-formatter="status_formatter" data-width="5%">{{trans('latraining.approved')}}</th>
                    <th data-field="quantity_quiz_attempts" data-width="10%" data-align="center" data-formatter="number_candidates_submission">
                        {{trans('latraining.number_candidates_submission')}}
                    </th>
                    <th data-field="regist" data-width="150" data-align="center" data-formatter="register_formatter">{{trans('latraining.action')}}</th>
                </tr>
            </thead>
        </table>
    </div>

    <script type="text/javascript">
        function name_formatter(value, row, index) {
            return '<a href="'+ row.edit_url +'">'+ value +'</a> <br> ('+ row.code +') <br>' + row.start_date + (row.end_date ? ' <i class="fa fa-arrow-right"></i> ' + row.end_date : '');
        }
        function report_formatter(value, row, index) {
            return '<a href="'+row.report_url+'" class="text-warning">Lần thử</a>';
        }

        function number_candidates_submission(value, row, index) {
            return row.quantity_quiz_attempts + ' / ' + row.quantity;
        }

        function limit_time_formatter(value, row, index) {
            return row.limit_time + " {{ trans('latraining.minute') }}";
        }

        function status_formatter(value, row, index) {
            return value == 1 ? '<span class="text-success">{{ trans("backend.approved") }}</span>' : (value == 2 ? '<span class="text-warning">Chưa ' +
                'duyệt</span>' : '<span class="text-danger">{{ trans("backend.deny") }}</span>');
        }

        function view_result_formatter(value, row, index) {
            return value == 1 ? '<i class="fa fa-eye text-success"></i>' : '<i class="fa fa-eye-slash text-danger"></i>';
        }

        function is_open_formatter(value, row, index) {
            return value == 1 ? '<span class="text-success">{{trans("backend.enable")}}</span>' : '<spanclass="text-danger">{{trans("backend.disable")}}</span>';
        }

        function created_formatter(value, row, index) {
            return '<a href="javascript:void(0)" class="load-modal" data-url="'+row.user_url+'"><i class="fa fa-user"></i></a> <br>' + row.created_at2;
        }

        function user_approved_formatter(value, row, index) {
            if (row.user_approved_url){
                return '<a href="javascript:void(0)" class="load-modal" data-url="'+row.user_approved_url+'"><i class="fa fa-user"></i></a> <br>' + row.time_approved;
            }
            return '';
        }

        function register_formatter(value, row, index) {
            let str = '';
            if (row.register_url){
                str += '<a href="'+ row.register_url +'" class="btn mb-1"><i class="fa fa-users"></i> {{ trans("latraining.register") }}</a>';
            }
            if (row.question) {
                str += ' <br> <a href="'+ row.question +'" class="btn" title="{{ trans("latraining.question") }}"><i class="fa fa-question-circle"></i></a>';
            }
            if (row.result){
                str += '<a href="'+ row.result +'" class="btn" title="{{ trans("latraining.result") }}"><i class="fa fa-eye"></i></a>';
            }

            str += '<a href="javascript:void(0)" class="btn load-modal" data-url="'+row.info_url+'" title="Thông tin"> <i class="fa fa-info-circle"></i></a>';

            return str;
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.offline.get_quiz', ['course_id' => $course_id]) }}',
            remove_url: '{{ route('module.offline.quiz.remove', ['course_id' => $course_id]) }}',
        });
    </script>
@endsection
