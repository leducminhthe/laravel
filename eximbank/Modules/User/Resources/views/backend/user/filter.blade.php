<button class="btn" id="btnFilter"><i class="fas fa-filter"></i> {{ trans('labutton.filter') }}</button>
<div class="modal left fade" id="modalFilter" tabindex="-1" role="dialog" aria-labelledby="myModalFilter">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form role="form" enctype="multipart/form-data" id="form-search">
                <input type="hidden" name="export_unit">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <b>{{ trans('labutton.search') }}</b>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            {{-- @for($i = 1; $i <= 5; $i++)
                                <div class="form-group">
                                    <select name="unit_id" id="unit-{{ $i }}" class="form-control load-unit unit_search" data-placeholder="-- {{ trans('lacategory.unit_level', ['i' => $i]) }} --" data-level="{{ $i }}" data-loadchild="unit-{{ $i+1 }}" data-parent="0"></select>
                                </div>
                            @endfor --}}
                            <div class="form-group">
                                @include('backend.form_choose_unit')
                            </div>
                            <div class="form-group">
                                <select name="title" id="title" class="form-control load-title" data-placeholder="-- {{ trans('lacategory.title') }} --"></select>
                            </div>
                            <div class="form-group">
                                <select name="area" id="area" class="form-control load-area" data-placeholder="-- {{ trans('laprofile.area') }} --"></select>
                            </div>
                            <div class="form-group">
                                <select name="status" id="status" class="form-control select2" data-placeholder="-- {{ trans('laprofile.status') }} --">
                                    <option value=""></option>
                                    <option value="0">{{ trans('laprofile.inactivity') }}</option>
                                    <option value="1">{{ trans('laprofile.doing') }}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <input type="text" name="search" class="form-control w-24" placeholder="{{ trans('laprofile.enter_code_name_email_username') }}">
                            </div>
                            <div class="form-group">
                                <button  id="btnsearch" class="btn"><i class="fa fa-search"></i>&nbsp;{{ trans('labutton.search') }} </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    $('#btnFilter').on('click', function () {
        $('#modalFilter').modal();
    });
    $('#btnsearch').on('click', function() {
        var latest_value = $(".unit_search option:selected:last").val();
        if(latest_value) {
            $('input[name=export_unit]').val(latest_value);
        }
        var area = $('#area').val();
        $('input[name=export_area]').val(area);
        var title = $('#title').val();
        $('input[name=export_title]').val(title);
        var status = $('#status').val();
        $('input[name=export_status]').val(status);
        var search = $('input[name=search]').val();
        $('input[name=export_search]').val(search);
        console.log(55);
    })
</script>
