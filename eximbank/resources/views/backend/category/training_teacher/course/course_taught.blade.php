<div role="main">
    <div class="row">
        <div class="col-md-12">
            <form class="form-inline form-search mb-3" id="form-search-course-taught">
                <input type="text"
                    name="search"
                    value=""
                    class="form-control mr-1"
                    autocomplete="off"
                    placeholder="{{ trans('latraining.enter_code_name_course') }}"
                >
                <input name="start_date"
                    type="text"
                    class="datepicker form-control mr-1"
                    placeholder="{{ trans('latraining.start_date') }}"
                    autocomplete="off"
                >
                <input name="end_date"
                    type="text"
                    class="datepicker form-control mr-1"
                    placeholder="{{ trans('latraining.end_date') }}"
                    autocomplete="off"
                >
                <button type="submit" class="btn"><i class="fa fa-search"></i>&nbsp;{{ trans('labutton.search') }}</button>
            </form>
        </div>
    </div>

    <table class="tDefault table table-hover bootstrap-table" id="all_course_taught">
        <thead>
            <tr>
                <th data-formatter="index_formatter" data-width="5%" data-align="center">{{trans('latraining.stt')}}</th>
                <th data-field="course_name">{{trans('lacategory.course')}}</th>
                <th data-field="course_date" data-align="center" data-width="20%">{{trans('latraining.time')}}</th>
                <th data-field="num_schedule" data-align="center" data-width="5%" data-formatter="info_formatter">{{trans('lacore.info')}}</th>
            </tr>
        </thead>
    </table>
</div>
<script type="text/javascript">
    function index_formatter(value, row, index){
        return (index + 1);
    }

    function info_formatter(value, row, index) {
        return '<a href="javascript:void(0)" class="load-modal" data-url="'+row.info_url+'"> <i class="fa fa-info-circle"></i></a>';
    }

    var table = new LoadBootstrapTable({
        locale: '{{ \App::getLocale() }}',
        url: '{{ route('backend.category.training_teacher.list_course_teacher_getdata',[ 'type' => 2]) }}',
        table: "#all_course_taught",
        form_search: '#form-search-course-taught'
    });
</script>