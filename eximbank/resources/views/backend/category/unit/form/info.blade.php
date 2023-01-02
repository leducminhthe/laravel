<div class="row">
    <div class="col-md-12">
        <div class="form-group row">
            <div class="col-sm-3 control-label">
                <label>{{ trans('lacategory.unit_code') }} <span class="text-danger">*</span></label>
            </div>
            <div class="col-md-7">
                <input name="code" type="text" class="form-control" value="" required>
            </div>
        </div>

        <div class="form-group row">
            <div class="col-sm-3 control-label">
                <label>{{ trans('lacategory.unit') }} <span class="text-danger">*</span></label>
            </div>
            <div class="col-md-7">
                <input name="name" type="text" class="form-control" value="" required>
            </div>
        </div>

        @if($level == 1)
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>{{ trans('lacategory.email') }}</label>
                </div>
                <div class="col-md-7">
                    <input name="email" type="text" class="form-control" value="">
                </div>
            </div>
        @endif

        @if($level >= 1)
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label for="parent_id">{{ trans('lacategory.management_unit') }} <span class="text-danger">*</span></label>
                </div>
                <div class="col-md-7">
                    <select name="parent_id" id="parent_id" class="form-control load-unit" data-placeholder="-- {{ trans('lacategory.management_unit') }} --" data-level="{{ $level - 1 }}">
                        @if(isset($parent))
                            <option value="{{ $parent->id }}">{{ $parent->code .' - '. $parent->name }}</option>
                        @endif
                    </select>
                </div>
            </div>
        @endif

        <div class="form-group row">
            <div class="col-sm-3 control-label">
                <label>{{ trans('lacategory.unit_type') }}</label>
            </div>
            <div class="col-md-7" id="type_unit">
                <select name="type" id="type" class="form-control select2" data-placeholder="-- {{ trans('backend.unit_type') }} --">
                    <option value=""></option>
                    @foreach($type as $item)
                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-3 control-label">
                <label>{{trans('lacategory.area_type')}}</label>
            </div>
            <div class="col-md-7" >
                <select class="form-control load-area-level" id="area_level" data-width="100%" name="area_level" data-placeholder="{{trans('lacategory.area_type')}}">
                    <option value=""></option>
                </select>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-3 control-label">
                <label>{{ trans('lacategory.area') }}</label>
            </div>
            <div class="col-md-7" id="area-level">
                <select name="area_id" id="area_id" class="form-control load-area-by-level" data-placeholder="-- {{ trans('lacategory.area') }} --">
                    <option value=""></option>
                </select>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-3 control-label">
                <label>{{ trans('lacategory.note_1') }}</label>
            </div>
            <div class="col-md-7">
                <textarea id="note1" name="note1" type="text" class="form-control" rows="5"></textarea>
            </div>
        </div>

        <div class="form-group row">
            <div class="col-sm-3 control-label">
                <label>{{ trans('lacategory.note_2') }}</label>
            </div>
            <div class="col-md-7">
                <textarea id="note2" name="note2" type="text" class="form-control" rows="5"></textarea>
            </div>
        </div>

        <div class="form-group row">
            <div class="col-sm-3 control-label">
                <label>{{ trans('lacategory.status') }} <span class="text-danger">*</span></label>
            </div>
            <div class="col-md-7">
                <label class="radio-inline"><input id="enable" type="radio" required name="status" value="1" checked> {{ trans('lacategory.enable') }}</label>
                <label class="radio-inline"><input id="disable" type="radio" required name="status" value="0"> {{ trans('lacategory.disable') }}</label>
            </div>
        </div>
    </div>

</div>
<style>
    .bootstrap-select .dropdown-toggle{
        background: none;
        border: 1px solid #ced4da;
        margin-left:unset!important;
    }
    .bootstrap-select .dropdown-toggle:hover{
        color: unset;
        background: none;
    }
    .bootstrap-select .btn:hover{
        color: unset!important;
    }
    .show>.btn.dropdown-toggle, .btn:not(:disabled):not(.disabled):active{
        background: none;
    }
    .bootstrap-select .dropdown-item.active, bootstrap-select  .dropdown-item:active{
        background: #e7e7e7;
    }
</style>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">

<!-- Latest compiled and minified JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>
<script>
    $('select[name=area_level]').on('change',function () {
        $('select[name=area_id]').empty().trigger('change');
    })
</script>
