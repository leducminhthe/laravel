@extends('layouts.backend')

@section('page_title', trans("latraining.training_result"))

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
                'url' => ''
            ],
        ]
    @endphp
    @include('layouts.backend.menu_breadcum',$breadcum)
@endsection

@section('content')
    <div role="main" id="result">
        <div class="row">
            @if($course->id)
                <div class="col-md-12 text-center">
                    @canany(['online-course-create', 'online-course-edit'])
                    <a href="{{ route('module.online.edit', ['id' => $course->id]) }}" class="btn  btn-info"> <div><i class="fa fa-edit"></i></div>
                        <div>{{ trans("latraining.info") }}</div>
                    </a>
                    @endcanany
                    @can('online-course-register')
                        <a href="{{ route('module.online.register', ['id' => $course->id]) }}" class="btn">
                            <div><i class="fa fa-edit"></i></div>
                            <div>{{ trans('latraining.internal_registration') }}</div>
                        </a>
                        {{-- <a href="{{ route('module.online.register_secondary', ['id' => $course->id]) }}" class="btn
                        btn-info">
                            <div><i class="fa fa-edit"></i></div>
                            <div>{{ trans('latraining.external_enrollment') }}</div>
                        </a> --}}
                    @endcan
                    @can('online-course-rating-level-result')
                        <a href="{{ route('module.online.rating_level.list_report', [$course->id]) }}" class="btn">
                            <div><i class="fa fa-star"></i></div>
                            <div>{{ trans('latraining.rating_level_result') }}</div>
                        </a>
                    @endcan
                </div>
            @endif
        </div>
        <div class="row mt-3">
            <div class="col-md-6">
                @include('online::backend.result.filter_result')
            </div>
            <div class="col-md-6 text-right act-btns">
                <div class="pull-right">
                    <div class="btn-group">
                        <a class="btn" href="{{ route('module.online.export_result', ['id' => $course->id]) }}">
                            <i class="fa fa-download"></i> {{ trans('labutton.export') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <table class="tDefault table table-hover bootstrap-table text-nowrap">
            <thead>
                <tr>
                    <th data-field="index" data-formatter="index_formatter" data-align="center" data-width="3%">#</th>
                    <th data-field="code" data-width="5%">{{ trans("latraining.employee_code") }}</th>
                    <th data-field="email" data-width="25%">{{ trans("latraining.email") }}</th>
                    <th data-field="name" data-width="25%">{{ trans("latraining.fullname") }}</th>
                    @foreach($activities as $activity)
                        <th data-field="activity_{{$activity->id}}" data-width="10%" data-align="center">
                            {{ trans("latraining.activiti") }} {{ $activity->name }}
                        </th>

                        @if ($activity->activity_id == 1)
                            <th data-field="score_{{$activity->id}}" data-width="10%" data-align="center">{{ trans("latraining.score") }} {{ $activity->name }}</th>
                        @endif
                    @endforeach
                    <th data-field="score" data-width="7%" data-align="center">{{ trans("latraining.test_score") }}</th>
                    <th data-field="result" data-width="10%" data-align="center">{{ trans("latraining.result") }}</th>
                    <th data-align="center" data-formatter="view_history_learning_formatter" >{{ trans("latraining.history_learning") }}</th>
                </tr>
            </thead>
        </table>
    </div>

    <script type="text/javascript">
        function index_formatter(value, row, index) {
            return (index+1)
        }

        function result_formatter(value, row, index) {
            value = parseInt(value);
            switch (value) {
                case -1: return '<span class="text-muted">{{ trans("latraining.incomplete") }}</span>';
                case 0: return '<span class="text-danger">{{ trans("latraining.not_complete") }}</span>';
                case 1: return '<span class="text-success">{{ trans("latraining.completed") }}</span>';
            }
        }

        function survey_course_formatter(value, row, index) {
            return '<input name="survey_course" type="checkbox" disabled class="check-item" value="" '+ (row.rating_send == 1 ? "checked": "") +'>';
        }

        function view_history_learning_formatter(value, row, index) {
            if(row.view_history_learning){
                return '<a href="'+ row.view_history_learning +'" class="btn"> <i class="fa fa-eye"></i></a>';
            }
            return '';
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.online.get_result', ['id' => $course->id]) }}',
        });

        $('#result').on('click', '.check-complete', function () {
            var activity_id = $(this).data('activity_id');
            var user_id = $(this).data('user_id');
            var user_type = $(this).data('user_type');

            Swal.fire({
                type: 'warning',
                html: 'Bạn chắc muốn cập nhật hoàn thành?',
                focusConfirm: false,
                showCancelButton: true,
                confirmButtonText: "OK",
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: '{{ route('module.online.result.update_activity_complete', ['id' => $course->id]) }}',
                        type: 'post',
                        data: {
                            activity_id: activity_id,
                            user_id : user_id,
                            user_type: user_type
                        },

                    }).done(function(data) {
                        table.refresh();
                        return false;

                    }).fail(function(data) {

                        show_message(
                            'Lỗi hệ thống',
                            'error'
                        );
                        return false;
                    });

                    return false;
                }

                table.refresh();
                return false;
            });
            
        });

    </script>
@endsection
