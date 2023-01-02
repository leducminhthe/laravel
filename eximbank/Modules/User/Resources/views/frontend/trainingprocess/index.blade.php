<div class="table-responsive">
    <table id="dg" class="table bootstrap-table text-nowrap">
        <thead class="thead-s">
        <tr class="tbl-heading">
            <th width="40px" data-formatter="index_formatter">#</th>
            <th data-field="course_code">{{ trans('laprofile.course_code') }}</th>
            <th data-field="course_name">{{ trans('laprofile.course_name') }}</th>
            <th data-field="course_type"  data-align="center">{{ trans('lacategory.form') }}</th>
            <th data-field="training_form" data-align="center">{{ trans('laprofile.training_type') }}</th>
            <th data-field="titles_name" data-width="260px">{{ trans('laprofile.title') }}</th>
            <th data-align="center" data-width="260px" data-formatter="training_date">{{ trans('laprofile.time_held') }}</th>
            <th data-field="score" data-align="right">{{ trans('laprofile.score') }}</th>
            <th data-field="result" data-width="150px" data-formatter="result" data-align="center">{{ trans('laprofile.result') }}</th>
            <th data-field="certificate" data-align="center" data-formatter="certificate" >{{ trans('laprofile.certificates') }}</th>
        </tr>
        </thead>
    </table>
</div>
<script type="text/javascript">
    function certificate(value, row, index) {
        if(row.image_cert){
            return '<a href="'+row.image_cert+'" target="_blank" class="gray-s"><i class="uil uil-download-alt"></i></a>' ;
        }
        return '-';
    }
    function index_formatter(value, row, index) {
        return (index + 1);
    }
    function result(value, row, index) {
        return value == 1 ? '{{trans("latraining.completed")}}' : '{{trans("latraining.incomplete")}}';
    }
    function training_date(value,row,index) {
        return row.start_date +' - '+row.end_date;
    }
    var table = new LoadBootstrapTable({
        locale: '{{ \App::getLocale() }}',
        url: '{{ route('module.frontend.user.trainingprocess.getData') }}',
    });

</script>
