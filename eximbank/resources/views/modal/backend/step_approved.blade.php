<div class="modal fade modal-approved-step" id="modal-approved-step">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{ trans('latraining.approve_detail') }}</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true">
                            Thông tin phê duyệt
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false">
                            Thông tin cấp duyệt
                        </a>
                    </li>
                </ul>
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                        <div>
                            <table class="tDefault table table-hover table-approved-step">
                                <thead>
                                <tr>
                                    <th data-formatter="index_formatter" data-width="50px" data-align="center">STT</th>
                                    <th data-field="level" data-align="center" data-width="3%">{{ trans('latraining.approve_level') }}</th>
                                    <th data-field="status" data-width="10%" data-align="center" data-formatter="status_formatter_approve">{{ trans('latraining.status') }}</th>
                                    <th data-field="note" data-width="400px">{{ trans('lasetting.note') }}</th>
                                    <th data-field="created_by_name" data-width="220px">{{ trans('latraining.approved_by') }}</th>
                                    <th data-field="approved_date" data-align="center" data-width="160px">{{ trans('latraining.approved_date') }}</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
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

    function status_formatter_approve(value, row, index) {
        value = parseInt(row.status);
        var text_status = '';
        switch (value) {
            case 0: text_status = '<span class="text-danger">{{ trans("latraining.deny") }}</span>'; break;
            case 1: text_status = '<span class="text-success">{{trans("latraining.approve")}}</span>'; break;
            case 2: text_status = '<span class="text-warning">{{ trans("latraining.not_approved") }}</span>'; break;
        }

        return text_status;
    }

    function level_formatter(value, row, index) {
        return 'Phê duyệt cấp '+ value;
    }
</script>
