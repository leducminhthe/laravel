{{-- MOdal SHOW ĐỐI TƯỢNG --}}
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ trans('latraining.object') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="tDefault table table-hover bootstrap-table" id="table_object">
                    <thead>
                        <tr>
                            <th data-align="center" data-width="3%" data-formatter="stt_formatter">{{ trans('latraining.stt') }}</th>
                            <th data-field="title_name">{{trans('latraining.title')}}</th>
                            <th data-field="unit_name">{{trans('lamenu.unit')}}</th>
                            <th data-align="center" data-field="type" data-width="10%" data-formatter="type_formatter">{{trans('backend.type_object')}}</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn" data-dismiss="modal">{{ trans('labutton.close') }}</button>
            </div>
        </div>
    </div>
</div>
<script>
    // Đối tượng
    function type_formatter(value, row, index) {
        return value == 1 ? '{{ trans("latraining.obligatory") }}' : '{{ trans("backend.register") }}';
    }

    function stt_formatter(value, row, index) {
        return (index + 1);
    }

    var table_object = new LoadBootstrapTable({
        locale: '{{ \App::getLocale() }}',
        url: "{{ route('module.online.get_object', [$course_id]) }}",
        table: '#table_object',
    });
</script>