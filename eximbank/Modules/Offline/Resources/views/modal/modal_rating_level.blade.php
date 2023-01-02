<div class="modal fade" id="myModal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Thông tin</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <table class="tDefault table table-hover bootstrap-table text-nowrap" id="table-rating-level">
                    <thead>
                    <tr>
                        <th data-field="rating_url" data-formatter="rating_url_formatter" data-align="center">{{ trans('latraining.assessments') }}</th>
                        <th data-field="rating_name">{{ trans('latraining.rating_name') }}</th>
                        <th data-field="rating_time">{{ trans('latraining.time_rating') }}</th>
                        <th data-field="rating_status" data-align="center">{{ trans('latraining.status') }}</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
    function rating_url_formatter(value, row, index) {
        if(row.rating_level_url){
            return '<a href="'+ row.rating_level_url +'" class="btn">Đánh giá</a>';
        }
        return 'Đánh giá';
    }

    var table = new LoadBootstrapTable({
        locale: '{{ \App::getLocale() }}',
        url: '{{ route('module.offline.detail.rating_level.getdata', ['id' => $course_id]) }}',
        table: '#table-rating-level',
    });
</script>
