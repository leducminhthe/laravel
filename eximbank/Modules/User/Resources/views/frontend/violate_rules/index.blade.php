<div class="tab-pane fade active show" id="nav-courses" role="tabpanel">
    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive">
                <table id="bootstraptable" class="tDefault table table-hover table-bordered bootstrap-table">
                    <thead>
                    <tr class="tbl-heading">
                        <th data-align="center" data-formatter="index_formatter">STT</th>
                        <th data-field="course_code">{{ trans('laprofile.course_code') }}</th>
                        <th data-field="course_name">{{ trans('laprofile.course_name') }}</th>
                        <th data-field="course_time" data-align="center">{{ trans('laprofile.course_time') }}</th>
                        <th data-field="start_date">{{ trans('laprofile.start_date') }}</th>
                        <th data-field="end_date">{{ trans('laprofile.end_date') }}</th>
                        <th data-field="time_schedule">{{ trans('laprofile.time') }}</th>
                        <th data-field="attendance" data-align="center">{{ trans('laprofile.total_course_time') }}</th>
                        <th data-field="schedule_discipline">{{ trans('laprofile.schedule_discipline') }}</th>
                        <th data-field="discipline">{{ trans('laprofile.discipline') }}</th>
                        <th data-field="absent">{{ trans('laprofile.absent') }}</th>
                        <th data-field="absent_reason">{{ trans('laprofile.absent_reason') }}</th>
                        <th data-field="status_user">{{ trans('laprofile.status') }}</th>
                        <th data-field="note">{{ trans('laprofile.note') }}</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    function index_formatter(value, row, index) {
        return (index + 1);
    }

    var table = new LoadBootstrapTable({
        locale: '{{ \App::getLocale() }}',
        url: '{{ route('module.frontend.user.violate_rules.get_data') }}',
    });
</script>
