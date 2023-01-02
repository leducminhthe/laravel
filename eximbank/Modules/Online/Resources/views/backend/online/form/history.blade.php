<table class="tDefault table table-hover bootstrap-table text-nowrap" id="tab-history">
    <thead>
    <tr>
        <th data-sortable="true" data-align="center" data-formatter="stt_formatter" data-width="3%">#</th>
        <th data-field="fullname">{{trans('latraining.fullname')}}</th>
        <th data-field="tab_edit">{{trans('latraining.tab_edit')}}</th>
        <th data-field="ip_address">{{trans('latraining.ip_address')}} ip</th>
        <th data-field="created_at2" data-align="center">{{trans('latraining.created_at')}}</th>
    </tr>
    </thead>
</table>

<script type="text/javascript">
    function stt_formatter(value, row, index) {
        return (index + 1);
    }
    var table_history = new LoadBootstrapTable({
        url: '{{ route('module.online.history.getdata', ['id' => $model->id]) }}',
        table: '#tab-history'
    });
</script>

