<div class="row">
    <div class="col-md-12">
        <table class="tDefault table table-hover bootstrap-table text-nowrap" id="table-result">
            <thead>
                <tr>
                    <th data-field="profile_code"> {{ trans('laprofile.code') }}</th>
                    <th data-field="profile_name"> {{ trans('latraining.fullname') }}</th>
                    <th data-field="date_complete" data-align="center" data-width="10%">@lang('latraining.date_complete')</th>
                    <th data-field="finished_total" data-align="center" data-width="10%">{{ trans('latraining.finish') }} / {{ trans('latraining.total') }}</th>
                    <th data-field="percent" data-align="center" data-width="10%">{{ trans('ladashboard.completion_rate') }}</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
<script type="text/javascript">
    var table_result = new LoadBootstrapTable({
        locale: '{{ \App::getLocale() }}',
        url: '{{ route('module.subject-type.get_user_result', ['subject_type_id' => $model->id]) }}',
        table: '#table-result',
    });
</script>
