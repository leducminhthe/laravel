{{-- @extends('layouts.backend')

@section('page_title', 'Lịch sử thi tuyển thí sinh nội bộ')

{{-- @section('content') --}}
    <div role="main">
        <div class="row">
            <div class="col-md-6">
                @include('quiz::backend.history_user.filter')
            </div>
            <div class="col-md-6 text-right">
                <a class="btn" href="javascript:void(0)" id="export-history-user">
                    <i class="fa fa-download"></i> {{ trans('labutton.export') }}
                </a>
            </div>
        </div>
        <p></p>
        <table class="tDefault table table-hover bootstrap-table">
            <thead>
            <tr>
                <th data-field="state" data-checkbox="true"></th>
                <th data-field="code" data-width="5%">{{ trans('backend.employee_code') }}</th>
                <th data-field="fullname" data-formatter="fullname_formatter" data-width="20%">{{ trans('backend.employee_name') }}</th>
                <th data-field="email" >{{ trans('backend.employee_email') }}</th>
                <th data-field="title_name">{{ trans('latraining.title') }}</th>
                <th data-field="unit_name" data-formatter="unit_formatter" data-with="5%">{{ trans('backend.work_unit') }}</th>
                <th data-field="unit_manager">{{ trans('backend.unit_manager') }}</th>
                <th data-sortable="true" data-field="status" data-align="center" data-formatter="status_formatter" data-width="5%">{{ trans('latraining.status') }}</th>
            </tr>
            </thead>
        </table>
    </div>

    <script type="text/javascript">
        function fullname_formatter(value, row, index) {
            return '<a href="' + row.quiz_history_url + '">' + row.lastname + ' ' + row.firstname + '</a>';
        }

        function unit_formatter(value, row, index) {
            return row.unit_name + ' <a href="javascript:void(0)" class="load-modal" data-url="'+row.unit_url+'"> <i class="fa fa-info-circle"></i></a>';
        }

        function status_formatter(value, row, index) {
            value = parseInt(value);
            switch (value) {
                case 0:
                    return '<span>{{ trans('backend.inactivity') }}</span>';
                case 1:
                    return '<span>{{ trans('backend.doing') }}</span>';
                case 2:
                    return '<span>{{ trans('backend.probationary') }}</span>';
                case 3:
                    return '<span>{{ trans('backend.pause') }}</span>';
            }
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.quiz.history_user.getdata') }}',
            field_id: 'user_id'
        });

        $('#export-history-user').on('click', function () {
            let form_search = $("#form-search").serialize();
            window.location = '{{ route('module.quiz.history_user.export') }}?'+form_search;
        });
    </script>
{{-- @endsection --}}
