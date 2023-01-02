<button class="btn" id="btnFilter"><i class="fas fa-filter"></i> {{ trans('labutton.filter') }}</button>
<div class="modal left fade" id="modalFilter" tabindex="-1" role="dialog" aria-labelledby="myModalFilter">
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
                            {{-- @for($i = 1; $i <= 5; $i++)
                                <select name="unit_id" id="unit-{{ $i }}"
                                    class="form-control load-unit"
                                    data-placeholder="-- {{ trans('lacategory.unit_level', ['i'=> $i]) }} --"
                                    data-level="{{ $i }}" data-loadchild="unit-{{ $i+1 }}" data-parent="0">
                                </select>
                            @endfor --}}
                            <div class="mb-2">
                                @include('backend.form_choose_unit')
                            </div>
                            <select name="area" id="area" class="form-control load-area" data-placeholder="--{{ trans('lasuggest.area') }}--"></select>
                            <select name="title" class="form-control load-title" data-placeholder="-- {{ trans('lasuggest.title') }} --"></select>
                            <select name="status" class="form-control select2" data-placeholder="-- {{ trans('lasuggest.status') }} --">
                                <option value=""></option>
                                <option value="0">{{ trans('lasuggest.inactivity') }}</option>
                                <option value="1">{{ trans('lasuggest.doing') }}</option>
                                <option value="2">{{ trans('lasuggest.probationary') }}</option>
                                <option value="3">{{ trans('lasuggest.pause') }}</option>
                            </select>
                            <input type="text" name="search" class="form-control w-100" value="" placeholder="{{ trans('lasuggest.enter_suggest') }}">
                            <input name="start_date" type="text" class="form-control w-100 datetimepicker" placeholder="{{ trans('lasuggest.start_date') }}" autocomplete="off">
                            <input name="end_date" type="text" class="form-control w-100 datetimepicker" placeholder="{{ trans('lasuggest.end_date') }}" autocomplete="off">
                            <input type="text" name="search_code_name" class="form-control w-100" placeholder="{{ trans('lasuggest.enter_code_name_user') }}">
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
        var title = $('#title').val();
        var status = $('#status').val();
        var search = $('input[name=search]').val();
    })
</script>
