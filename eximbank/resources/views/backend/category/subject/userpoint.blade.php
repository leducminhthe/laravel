@if(!empty($userPoint))
@foreach($userPoint as $v)
<div class="row">
    <div class="col-md-12">
        <div class="form-group row">
            <div class="col-sm-3 control-label">
                <label>{{ $v["name"] }}<span class="text-danger">*</span></label>
            </div>
            <div class="col-md-6">
                <input name="userpoint[{{ $v["ikey"] }}]" type="text" class="form-control" value="{{ isset($arrSetting[$v["ikey"]]) ? $arrSetting[$v["ikey"]] : '0' }}">
            </div>
        </div>
    </div>
</div>
@endforeach
@endif
