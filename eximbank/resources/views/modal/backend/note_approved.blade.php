<div class="modal fade modal-note-approved" id="myModal" >
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Ghi chú từ chối phê duyệt</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="idapproved" id="idapproved" value="">
                <div>
                    <div class="form-group">
                        <label>{{ trans('latraining.note') }}</label>
                        <textarea class="form-control" id="txta-note-approved" rows="3" name="note"></textarea>
                        <input type="hidden" name="table" value="bootstrap-table">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button id="update-note-approved" data-model="{{$model}}" class="btn"><i class="fa fa-save"></i> {{trans('labutton.save')}}</button>
                <button type="button" id="closed" class="btn" data-dismiss="modal"><i class="fa fa-times-circle"></i> {{ trans('labutton.close') }}</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
</script>
