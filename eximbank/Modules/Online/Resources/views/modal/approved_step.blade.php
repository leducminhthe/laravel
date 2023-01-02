<div class="modal fade modal-approved-step" id="modal-approved-step" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{ trans('latraining.approve_detail') }}</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                <div>
                    <table class="tDefault table table-hover table-approved-step ">
                        <thead>
                        <tr>
                            <th data-formatter="index_formatter" data-width="50px" data-align="center">#</th>
                            <th data-field="level" data-align="center" data-width="3%">{{ trans('latraining.approve_level') }}</th>
                            <th data-field="status" data-width="5%" data-formatter="status_formatter">{{ trans('latraining.status') }}</th>
                            <th data-field="note" data-width="400px">{{ trans('latraining.note') }}</th>
                            <th data-field="created_by_name" data-width="220px">{{ trans('latraining.approved_by') }}</th>
                            <th data-field="approved_date" data-align="center" data-width="160px">{{ trans('latraining.approved_date') }}</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="closed" class="btn" data-dismiss="modal"><i class="fa fa-times-circle"></i> {{ trans('labutton.close') }}</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    function index_formatter(value, row, index) {
        return (index+1);
    }

</script>
