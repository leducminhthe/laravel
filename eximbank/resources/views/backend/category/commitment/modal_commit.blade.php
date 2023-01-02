<div class="modal fade" id="myModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">{{ trans('lacategory.commitment_frame') }}</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <!-- Modal body -->
            <form action="{{route('backend.category.commit_month.save')}}" method="post"  id="form-move-training-process" enctype="multipart/form-data" data-success="success_submit">
                @csrf
            <div class="modal-body">
                <table class="tDefault table table-hover modal-bootstrap-table">
                    <thead>
                    <tr>
                        <th data-sortable="false" data-align="center" data-formatter="stt_formatter" data-width="25">{{ trans('lacategory.stt') }}</th>
                        <th data-field="min_cost" data-width="140px">{{ trans('lacategory.from') }}</th>
                        <th data-field="max_cost" data-width="140px">{{ trans('lacategory.to') }}</th>
                        <th data-field="month" data-align="center" data-width="140px">{{ trans('lacategory.time_day') }}</th>
                        <th data-width="140px" data-formatter="action_formatter">{{ trans('lacategory.action') }}</th>
                    </tr>
                    </thead>
                </table>
                <div class="row mt-5">
                    <div class="col-md-12">
                        <div class="form-group row">
                            <div class="col-sm-3 control-label">
                                <label>{{ trans('lacategory.from') }} <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-6">
                                <input name="min_cost"  required type="number" placeholder="{{ trans('lacategory.min_cost') }}" class="form-control" value=" " >
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3 control-label">
                                <label>{{ trans('lacategory.to') }} <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-6">
                                <input name="max_cost" required type="number" placeholder="{{ trans('lacategory.max_cost') }}" class="form-control" value=" " >
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3 control-label">
                                <label>{{ trans('lacategory.time_day') }} <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-6">
                                <input name="month" required type="number" placeholder="{{ trans('lacategory.enter_commit_day') }}" class="form-control" value=" " >
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3 control-label">

                            </div>
                            <div class="col-md-6">
                                <input type="hidden" name="group_id" value="{{$group_id}}">
                                <input type="hidden" name="id"  value="">
                                <button class="btn" id="btnSaveFrame"><i class="fa fa-save"></i> {{ trans('lacategory.update') }}</button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn" data-dismiss="modal"><i class="fa fa-times"></i> {{ trans('labutton.close') }}</button>
            </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
    function stt_formatter(value, row, index) {
        return (index + 1);
    }
    function success_submit(form) {
        $("#app-modal #myModal").modal('hide');
        table.refresh();
    }
    function action_formatter(value, row, index) {
        return '<button class="btn btnEditFrame" data-id='+row.id+'><i class="fa fa-edit"></i> {{ trans('lacategory.edit') }}</button> ' +
            ' <button class="btn btnDeleteFrame" data-id='+row.id+'><i class="fa fa-trash"></i> {{ trans('lacategory.delete') }}</button>';
    }

    var table = new LoadBootstrapTable({
        table: '.modal-bootstrap-table',
        locale: '{{ \App::getLocale() }}',
        url: '{{ route('backend.category.commit_month.getdataframe',['commit_group_id'=>$group_id]) }}',
    });
    $(document).on('click','.btnDeleteFrame',function (e) {
        e.preventDefault();
        let btn = $(this);
        let current_icon = btn.find('i').attr('class');
        let id = btn.data('id');
        btn.find('i').attr('class', 'fa fa-spinner fa-spin');
        btn.prop("disabled", true);
        $.ajax({
            type: 'POST',
            url: base_url+'/admin-cp/category/commit-group/frame/delete',
            dataType: 'json',
            data: {id:id},
        }).done(function(result) {
            btn.find('i').attr('class', current_icon);
            btn.prop("disabled", false);
            if (result.status=='success'){
                table.refresh();
            }
        }).fail(function(result) {
            btn.find('i').attr('class', current_icon);
            btn.prop("disabled", false);
            show_message('Lỗi dữ liệu', 'error');
            return false;
        });
    })
    $(document).on('click','.btnEditFrame',function (e) {
        e.preventDefault();
        var form = $(this).closest('form');
        var formData = new FormData(form[0]);
        let btn = $(this);
        let current_icon = btn.find('i').attr('class');
        let id = btn.data('id');
        btn.find('i').attr('class', 'fa fa-spinner fa-spin');
        btn.prop("disabled", true);

        $.ajax({
            type: 'GET',
            url: base_url+'/admin-cp/category/commit-group/frame/edit/'+id,
            dataType: 'json',
            data: {},
            processData: false,
            contentType: false
        }).done(function(result) {
            btn.find('i').attr('class', current_icon);
            btn.prop("disabled", false);

            if (result.status=='success'){
                $('input[name="id"]',form).val(result.result.id);
                $('input[name="min_cost"]',form).val(result.result.min_cost);
                $('input[name="max_cost"]',form).val(result.result.max_cost);
                $('input[name="month"]',form).val(result.result.month);
            }
        }).fail(function(result) {
            btn.find('i').attr('class', current_icon);
            btn.prop("disabled", false);
            show_message('Lỗi dữ liệu', 'error');
            return false;
        });
    })
    $(document).on('click','#btnSaveFrame',function (e) {
        e.preventDefault();
        var form = $(this).closest('form');
        var formData = new FormData(form[0]);
        let btn = $(this);
        let current_icon = btn.find('i').attr('class');
        btn.find('i').attr('class', 'fa fa-spinner fa-spin');
        btn.prop("disabled", true);

        $.ajax({
            type: 'POST',
            url: form.attr('action'),
            dataType: 'json',
            data: formData,
            processData: false,
            contentType: false
        }).done(function(result) {
            btn.find('i').attr('class', current_icon);
            btn.prop("disabled", false);
            show_message(result.message,result.status);
            if (result.status=='success'){
                form[0].reset();
                $('input[name="id"]',form).val(0);
                table.refresh();
            }
        }).fail(function(result) {
            btn.find('i').attr('class', current_icon);
            btn.prop("disabled", false);
            show_message('Lỗi dữ liệu', 'error');
            return false;
        });
    })
</script>

