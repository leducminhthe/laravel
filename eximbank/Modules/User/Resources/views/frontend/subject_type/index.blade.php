<div class="table-responsive">
    <table id="dg" class="table bootstrap-table">
        <thead class="thead-s">
        <tr class="tbl-heading">
            <th data-width="40px" data-formatter="index_formatter">#</th>
            <th data-field="code" data-width="80px">@lang('latraining.subject_type_code')</th>
            <th data-field="name">@lang('latraining.subject_type')</th>
            <th data-field="start_date" data-width="180px" data-align="center">@lang('laprofile.start_date')</th>
            <th data-field="end_date" data-width="180px" data-align="center">@lang('laprofile.end_date')</th>
            <th data-field="date_complete" data-align="center">@lang('latraining.date_complete')</th>
            <th data-field="finished_total" data-width="150px" data-align="center">{{ trans('latraining.finish') }} / {{ trans('latraining.total') }}</th>
			<th data-field="percent" data-width="150px" data-align="center">{{ trans('ladashboard.completion_rate') }}</th>
            <th data-align="center" data-width="80px" data-field="cert" data-formatter="cert_formatter">@lang('latraining.cert')</th>
        </tr>
        </thead>
    </table>
</div>

<script type="text/javascript">
    function index_formatter(value, row, index) {
        return (index + 1);
    }
    function result_formatter(value, row, index) {
        return value == 1 ? '{{trans("latraining.completed")}}' : '{{trans("latraining.incomplete")}}';
    }
    function cert_formatter(value, row, index) {
        if(row.cert == 1) {
            return '<span onclick="notyCertificateExpiry()"><i class="far fa-times-circle"></i></span>';
        } else if(row.cert){
            return '<a href="'+ row.cert +'"><i class="uil uil-download-alt"></i></a>';
        }else{
            return '';
        }
    }
    var table = new LoadBootstrapTable({
        locale: '{{ \App::getLocale() }}',
        url: '{{ route('module.frontend.user.subject_type.getData') }}',
    });

    function notyCertificateExpiry() {
        show_message('{{ trans("latraining.expired_certificate") }}', 'warning')
    }
</script>
