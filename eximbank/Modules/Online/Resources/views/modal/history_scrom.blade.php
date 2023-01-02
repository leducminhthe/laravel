{{-- MODAL LỊCH SỬ SCROM --}}
<div class="modal fade modal-add-activity" id="modal-scrom-{{$activity_scorm->id}}" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{ trans('latraining.history_scorm') }}</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body" id="modal-body-scrom-{{$activity_scorm->id}}">
                <table class="tDefault table table-hover bootstrap-table text-nowrap table-bordered" id="table-scrom-{{$activity_scorm->id}}">
                    <thead>
                        <tr>
                            <th data-formatter="index_formatter_scrom" data-align="center">#</th>
                            <th data-field="start_date">{{ trans('latraining.start_date') }}</th>
                            <th data-field="end_date">{{ trans('latraining.end_date') }}</th>
                            <th data-field="grade" data-align="center">{{ trans('latraining.score') }}</th>
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
