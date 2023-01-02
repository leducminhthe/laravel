<div class="table-responsive">
    <table class="tDefault table table-hover bootstrap-table">
        <thead>
            <tr>
                <th data-field="code" data-width="5%">{{ trans('laprofile.employee_code') }}</th>
                <th data-field="fullname" data-width="20%" data-formatter="fullname_formatter">{{ trans('laprofile.employee_name') }}</th>
                <th data-field="email">{{ trans('laprofile.employee_email') }}</th>
                <th data-field="title_name">{{ trans('laprofile.title') }}</th>
                <th data-field="unit_name">{{ trans('laprofile.work_unit') }}</th>
                <th data-field="unit_manager">{{ trans('laprofile.unit_manager') }}</th>
                <th data-formatter="time_formatter" data-align="center">{{ trans('laprofile.time') }}</th>
                <th data-field="note">{{ trans('laprofile.note') }}</th>
            </tr>
        </thead>
    </table>
</div>
<script type="text/javascript">
    function fullname_formatter(value, row, index) {
        return '<span>' + row.fullname + '</span>';
    }

    function time_formatter(value, row, index) {
        return row.start_date + '<i class="uil uil-arrow-right"></i>' + row.end_date;
    }

    var table = new LoadBootstrapTable({
        locale: '{{ \App::getLocale() }}',
        url: '{{ route('module.frontend.user.working_process.getData') }}',
    });
</script>
