<button class="btn" id="btnFilter"><i class="fas fa-filter"></i> {{ trans('labutton.filter') }}</button>
<div class="modal left fade" id="modalFilter" tabindex="-1" role="dialog" aria-labelledby="myModalFilter" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form   role="form" enctype="multipart/form-data" id="form-search">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <b>{{ trans('labutton.search') }}</b>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            {{-- @for($i = 1; $i <= $check_level; $i++)
                                <select name="unit" id="unit-{{ $i }}" class="form-control load-unit" data-placeholder="-- {{ trans('lacategory.unit_level', ['i' => $i]) }} --" data-level="{{ $i }}" data-loadchild="unit-{{ ($i+1) }}">
                                </select>
                            @endfor --}}
                            <div class="mb-2">
                                @include('backend.form_choose_unit')
                            </div>
                            <input type="text" name="search" value="" class="form-control w-100" placeholder="{{ trans('lacategory.enter_code_name') }}">
                            <input type="text" name="user_code" value="" class="form-control w-100" placeholder="{{ trans('lacategory.enter_unit_manager_code') }}">
                            <select name="unit_type" id="" class="form-control select2 w-100" data-placeholder="{{ trans('lacategory.choose_unit_type') }}">
                                <option value=""></option>
                                <option value="1">Hội sở</option>
                                <option value="2">Đơn vị kinh doanh</option>
                            </select>
                            <button id="btnsearch" class="btn">
                                <i class="fa fa-search"></i>&nbsp;{{ trans('labutton.search') }} 
                            </button>
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
        $('input[name=area]').val(area);
        var title = $('#title').val();
        $('input[name=title]').val(title);
        var search = $('input[name=search]').val();
        $('input[name=search]').val(search);
        console.log(55);
    })
</script>
