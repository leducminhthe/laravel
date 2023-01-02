<div role="main">
    <div class="row">
        <div class="col-md-8">
            <form class="form-inline form-search mb-3" id="form-search-note">
                <input type="text" name="search_note" value="" class="form-control" placeholder="{{ trans('latraining.enter_name') }}">
                <button class="btn"><i class="fa fa-search"></i>&nbsp;{{ trans('labutton.search') }}</button>
            </form>
        </div>
        <div class="col-md-4 text-right act-btns">
            <div class="pull-right">
                <div class="btn-group">
                    {{-- <button class="btn" id="delete-item"><i class="fa fa-trash"></i> {{ trans('labutton.delete') }}</button> --}}
                </div>
            </div>
        </div>
    </div>
    <br>

    <table class="tDefault table table-hover text-nowrap" id="table-note">
        <thead>
            <tr>
                <th data-field="index" data-formatter="index_formatter" data-align="center" data-width="3%">#</th>
                <th data-field="state" data-checkbox="true"></th>
                <th data-field="fullname">{{ trans('latraining.fullname') }}</th>
                <th data-field="unit_name">{{ trans('latraining.work_unit') }}</th>
                <th data-field="unit_manager">{{ trans('latraining.unit_manager') }}</th>
                <th data-field="title_name">{{ trans('latraining.title') }}</th>
                <th data-field="view_note" data-formatter="note" data-align="center">{{ trans('latraining.take_notes') }}</th>
                <th data-field="view_evaluate" data-formatter="evaluate" data-align="center">{{ trans('latraining.evaluate') }}</th>
            </tr>
        </thead>
    </table>
</div>
<script type="text/javascript">
        function index_formatter(value, row, index) {
            return (index+1);
        }

        function note(value, row, index) {
            if (row.view_note) {
                return '<a target="_blank" href="'+ row.view_note +'"><i class="fa fa-eye"></a>';
            } else {
                return '-';
            }
        }

        function evaluate(value, row, index) {
            if (row.view_evaluate) {
                return '<a target="_blank" href="'+ row.view_evaluate +'"><i class="fa fa-eye"></a>';
            } else {
                return '-';
            }
        }

        var table_note = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.online.get_user_note_evaluate',['course_id' => $model->id]) }}',
            table: '#table-note',
            form_search: '#form-search-note',
        });
</script>
