<div class="table-responsive">
    <table id="dg" class="table bootstrap-table">
        <thead class="thead-s">
        <tr class="tbl-heading">
            <th data-width="40px" data-formatter="index_formatter">#</th>
            <th data-field="code" data-width="80px">@lang('laprofile.quiz_code')</th>
            <th data-field="name">@lang('laprofile.quiz')</th>
            <th data-field="start_date" data-width="180px" data-align="center">@lang('laprofile.start_date')</th>
            <th data-field="end_date" data-width="180px" data-align="center">@lang('laprofile.end_date')</th>
            <th data-field="limit_time" data-width="150px" data-align="center">@lang('laprofile.exam_time_minutes')</th>
            <th data-align="center" data-width="80px" data-field="grade">@lang('laprofile.score')</th>
            <th data-align="center" data-width="160px" data-field="result" data-formatter="result_formatter">@lang('laprofile.result')</th>
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
    var table = new LoadBootstrapTable({
        locale: '{{ \App::getLocale() }}',
        url: '{{ route('module.frontend.user.quizresult.getData') }}',
        locale: '{{ App::getLocale() }}',
    });

</script>
