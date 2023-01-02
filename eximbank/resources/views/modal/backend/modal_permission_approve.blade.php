<div class="modal fade modal-show-permission-approve" id="modal-show-permission-approve">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{ trans('latraining.approve_detail') }}</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                <div>
                    <table class="tDefault table table-hover table-show-permission-approve">
                        <thead>
                            <tr>
                                <th  data-field="object_name" data-width="200" data-class="text-center">{{ trans('latraining.approve_level') }}</th>
                                <th  data-field="full_name"  data-width="200">{{ trans('latraining.user') }}</th>
                                <th  data-field="title_name"  data-width="200">{{ trans('latraining.title') }}</th>
                                <th data-field="level" data-width="200" data-formatter="level_formatter" >{{ trans('latraining.approve_order') }}</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    function level_formatter(value, row, index) {
        return 'Phê duyệt cấp '+ value;
    }
</script>
