@php
    $tab = Request::segment(3);
    $type = $tab == 'course-for-offline' ? 1 : 0;
@endphp
<div role="main" class="quiz_course_online">
    <div class="row">
        <div class="col-md-8 form-inline">
            @include('online::backend.quiz.filter')
        </div>
        <div class="col-md-4 text-right act-btns" id="btn-quiz">
            <div class="pull-right">
                <div class="btn-group">
                    @if ($course->lock_course == 0)
                        <a href="{{ route('module.online.quiz.create', ['course_id' => $model->id]) }}" class="btn">
                            <i class="fa fa-plus-circle"></i> {{ trans('labutton.add_new') }}
                        </a>
                    @endif
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
                <th data-field="code" data-width="5%" data-align="center">{{trans('latraining.quiz_code')}}</th>
                <th data-field="name" data-width="20%" data-formatter="name_formatter">{{trans('latraining.quiz_name')}}</th>
                <th data-field="quiz_type" data-width="5%" data-align="center">{{ trans('lacategory.form') }}</th>
                <th data-field="quiz_time" data-width="20%" data-formatter="quiz_time_formatter">{{trans('latraining.time')}}</th>
                <th data-field="limit_time" data-align="center" data-formatter="limit_time_formatter" data-width="10%">{{trans('latraining.time_quiz')}}</th>
                <th data-field="view_result" data-formatter="view_result_formatter" data-align="center" data-width="7%">{{trans('latraining.see_result')}}</th>
                <th data-field="status" data-align="center" data-formatter="status_formatter" data-width="5%">{{trans('latraining.approved')}}</th>
                <th data-field="regist" data-width="150"  data-align="center" data-formatter="register_formatter">{{trans('latraining.action')}}</th>
                <th data-field="quantity_quiz_attempts" data-width="10%" data-align="center" data-formatter="number_candidates_submission">
                    {{trans('latraining.number_candidates_submission')}}
                </th>
                <th data-formatter="report_formatter" data-align="center">{{trans('latraining.report')}}</th>
                <th data-field="created_at2" data-align="center">{{trans('latraining.create_time')}}</th>
                <th data-field="user" data-align="center" data-formatter="created_formatter">{{trans('latraining.user_create')}}</th>
                <th data-field="time_approved" data-align="center">{{trans('latraining.time_approved')}}</th>
                <th data-field="user_approved" data-align="center" data-formatter="user_approved_formatter">{{trans('latraining.user_approved')}}</th>
            </tr>
        </thead>
    </table>
</div>

<script type="text/javascript">
    function name_formatter(value, row, index) {
        return '<a href="'+ row.edit_url +'">'+ value +'</a>';
    }
    function report_formatter(value, row, index) {
        return '<a href="'+row.report_url+'" class="text-warning">Lần thử</a>';
    }
    function quiz_time_formatter(value, row, index) {
        return row.start_date + (row.end_date ? ' <i class="fa fa-arrow-right"></i> ' + row.end_date : '');
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
        return value == 1 ? '<span class="text-success">{{ trans("backend.viewed") }}</span>' : '<span class="text-danger">{{ trans("backend.not_seen") }}</span>';
    }

    function is_open_formatter(value, row, index) {
        return value == 1 ? '<span class="text-success">{{trans("backend.enable")}}</span>' : '<spanclass="text-danger">{{trans("backend.disable")}}</span>';
    }

    function created_formatter(value, row, index) {
        return '<a href="javascript:void(0)" class="load-modal" data-url="'+row.user_url+'"><i class="fa fa-user"></i></a>';
    }

    function user_approved_formatter(value, row, index) {
        if (row.user_approved_url){
            return '<a href="javascript:void(0)" class="load-modal" data-url="'+row.user_approved_url+'"><i class="fa fa-user"></i></a>';
        }
        return '';
    }

    function register_formatter(value, row, index) {
        let str = '';
        if (row.question) {
            str += '<a href="'+ row.question +'" class="btn"><i class="fa fa-question-circle"></i> {{ trans("backend.question") }}</a> ';
        }

        return str;
    }

    var table = new LoadBootstrapTable({
        locale: '{{ \App::getLocale() }}',
        url: '{{ route('module.online.get_quiz', ['course_id' => $model->id]) }}',
    });
</script>