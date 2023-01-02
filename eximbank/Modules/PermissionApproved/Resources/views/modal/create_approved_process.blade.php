<div class="modal fade"  id="modal-approved-process" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-md" >
        <div class="modal-content">
            <form id="frm-approved-process" class="form-horizontal" method="post" action="{{route('backend.approved.process.save')}}" >
                <div class="modal-header">
                    <h4 class="modal-title">Đơn vị áp dụng</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div>
                        <div class="form-group">
                            <label>Chọn đơn vị</label>
                            <select id="unit_id" name='unit_id' class="form-control load-unit-all" data-placeholder="Nhập mã hoặc tên đơn vị">
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{trans('labutton.close')}}</button>
                    <button id="save-approved-process" class="btn"><i class="fa fa-save"></i> {{trans('labutton.save')}}</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>

</script>
