<div class="table-responsive">
    <table id="history" class="table bootstrap-table">
        <thead class="thead-s">
        <tr class="tbl-heading">
            <th width="5%" data-formatter="index_formatter">#</th>
            <th data-field="name">@lang('laprofile.course')</th>
            <th data-field="point">@lang('laprofile.score')</th>
            <th  data-field="type" data-formatter="course_type" data-align="center">@lang('laprofile.type')</th>
            <th  data-field="createdat" data-align="center" >@lang('laprofile.time')</th>
        </tr>
        </thead>
    </table>
</div>
<script type="text/javascript">
    function index_formatter(value, row, index) {
        return (index + 1);
    }
    function course_type(value, row, index) {
        return value == 1 ? trans('lamenu.online_course') : trans('lamenu.offline_course');
    }
    var table = new LoadBootstrapTable({
        locale: '{{ \App::getLocale() }}',
        url: '{{ route('module.frontend.user.my_promotion.history') }}',
        locale: '{{ App::getLocale() }}',
    });
</script>
