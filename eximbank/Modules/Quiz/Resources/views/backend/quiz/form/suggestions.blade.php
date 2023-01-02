<div role="main">
    <div class="row">
        <div class="col-md-12 mb-2">
            @include('quiz::backend.quiz.form.filter_suggest')
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <table class="tDefault table table-hover bootstrap-table text-nowrap" id="table-suggestions">
                <thead>
                <tr>
                    <th data-field="code">{{trans('backend.employee_code')}}</th>
                    <th data-field="full_name">{{ trans('backend.employee_name') }}</th>
                    <th data-field="title_name">{{ trans('latraining.title') }}</th>
                    <th data-field="unit_name">{{ trans('lareport.unit_direct') }}</th>
                    <th data-field="parent_unit_name">{{ trans('latraining.unit_manager') }}</th>
                    <th data-field="content">{{ trans('latraining.content') }}</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
<br>

<script type="text/javascript">
    var table_suggestions = new LoadBootstrapTable({
        locale: '{{ \App::getLocale() }}',
        url: '{{ route('module.quiz.edit.get_suggestions', ['id' => $model->id]) }}',
        table: '#table-suggestions'
    });
</script>
