<div class="row">
    <div class="col-md-12">
        <div class="form-group row">
            <div class="col-sm-3 control-label">
                <label>{{ trans('lacategory.manager') }}</label>
            </div>
            <div class="col-md-7">
                <select name="manager[]" id="select_manager" class="form-control load-user" data-placeholder="-- {{ trans('lacategory.manager') }} --" multiple>
                </select>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-3 control-label">
                <label>{{ trans('app.type') }}</label>
            </div>
            <div class="col-md-7">
                <input type="radio" name="type_manager" id="type_manager_1" value="1"> <label for="type_manager_1"> Direct</label>
                <input type="radio" name="type_manager" id="type_manager_2" value="2"> <label for="type_manager_2"> Indirect</label>
            </div>
        </div>
    </div>
</div>
