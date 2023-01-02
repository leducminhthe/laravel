@extends('layouts.backend')

@section('page_title', trans('latraining.report'). ': '. $quiz_name)

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
                'name' => $course_name,
                'url' => route('module.online.edit', ['id' => $course_id])
            ],
            [
                'name' => trans('latraining.quiz_list'),
                'url' => route('module.online.quiz', ['course_id' => $course_id])
            ],
            [
                'name' => trans('latraining.report'). ': '. $quiz_name,
                'url' => ''
            ],
        ]
    @endphp
    @include('layouts.backend.menu_breadcum',$breadcum)
@endsection

@section('content')
    <div role="main" class="quiz_course_online_report">
        <div class="row">
            <div class="col-md-8 form-inline">
                <form class="form-inline form-search mb-3" id="form-search">
                    <input type="text" name="search" value="" class="form-control" placeholder="{{trans('latraining.enter_code_name_user')}}">
                    <button type="submit" class="btn"><i class="fa fa-search"></i>&nbsp;{{ trans('labutton.search') }}</button>
                </form>
            </div>
            <div class="col-md-4 text-right act-btns" id="btn-quiz">
                <div class="pull-right">
                    <div class="btn-group">
                        <button class="btn" id="delete-item"><i class="fa fa-trash"></i> {{ trans('labutton.delete') }}</button>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <table class="tDefault table table-hover bootstrap-table ">
            <thead>
                <tr>
                    <th data-field="state"  data-width="5%" data-checkbox="true"></th>
                    <th data-field="user.code" user.code data-width="10%" data-align="center">{{trans('laprofile.employee_code')}}</th>
                    <th data-field="user.full_name" user.full_name data-width="30%"  >{{trans('latraining.fullname')}}</th>
                    <th data-field="attempt" data-width="10%">{{trans('latraining.attemps')}}</th>
                    <th data-field="part.quiz_part" part.quiz_part data-width="10%">{{trans('latraining.part')}}</th>
                    <th data-field="time_start" data-width="15%" data-align="center">{{ trans('latraining.start') }}</th>
                    <th data-field="time_finish" data-width="15%" data-align="center">{{ trans('latraining.over') }}</th>
                    <th data-field="sumgrades" data-width="15%" data-align="center">{{trans('latraining.score')}}</th>
                </tr>
            </thead>
        </table>
    </div>

    <script type="text/javascript">
        function name_formatter(value, row, index) {
            return '<a href="'+ row.edit_url +'">'+ value +'</a>';
        }
        function attempt_formatter(value, row, index) {
            return '<a href="#">2</a>';
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

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.online.quiz.getreport', ['course_id' => $course_id,'id'=>$quiz_id]) }}',
            sort_name: 'full_name',
            remove_url: '{{route('module.online.quiz.attempt.remove', ['course_id' => $course_id,'id'=>$quiz_id])}}'
        });
    </script>
@endsection
