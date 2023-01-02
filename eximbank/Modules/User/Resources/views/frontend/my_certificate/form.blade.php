<form method="POST" action="{{ route('module.frontend.user.save_my_certificate') }}" class="form-validate form-ajax bg-white p-2" role="form" enctype="multipart/form-data" id="add_my_certificate">
    <div class="row">
        <div class="col-md-8">
        </div>
        <div class="col-md-4 text-right">
            <div class="btn-group act-btns">
                <button id="add_certifivate" type="submit" class="btn" data-must-checked="false"><i class="fa fa-save"></i> &nbsp;{{ trans('labutton.save') }}</button>
                <a href="{{ route('module.frontend.user.my_certificate') }}" class="btn"><i class="fa fa-times-circle"></i> {{ trans('labutton.cancel') }}</a>
            </div>
        </div>
    </div>
    <input type="hidden" name="id" value="{{ $model_my_certificate->id }}">
    <input type="hidden" name="type" value="1">
    <div class="row">
        <div class="col-md-12">
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label for="name_certificate">{{ trans('laprofile.certificate_name') }} <span class="text-danger">*</span></label>
                </div>
                <div class="col-md-6">
                    <input name="name_certificate" type="text" class="form-control" value="{{ $model_my_certificate->name_certificate }}" required>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label for="name_school">{{ trans('laprofile.certificate_school') }} <span class="text-danger">*</span></label>
                </div>
                <div class="col-md-6">
                    <input name="name_school" type="text" class="form-control" value="{{ $model_my_certificate->name_school }}" required>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label for="rank">{{ trans('laprofile.rank') }}</label>
                </div>
                <div class="col-md-6">
                    <input name="rank" type="text" class="form-control" value="{{ $model_my_certificate->rank }}">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>{{ trans('laprofile.certificate_image') }} <span class="text-danger">*</span></label>
                </div>
                <div class="col-md-4">
                    <input type="file" id="file" placeholder="{{ trans('laprofile.select_certificate') }}" name="certificate">
                    @if ($model_my_certificate->certificate)
                        <input type="hidden" name="path_old" id="path_old" value="{{ $model_my_certificate->certificate }}">
                        <div class="name_path_old">
                            <span>{{ basename($model_my_certificate->certificate) }}</span>
                        </div>
                    @endif
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>{{ trans('laprofile.study_time') }}</label><span class="text-danger"> * </span>
                </div>
                <div class="col-md-9">
                    <span>
                        <input name="time_start"
                            type="text" class="datepicker form-control d-inline-block w-25"
                            placeholder="{{trans('laother.choose_start_date')}}"
                            autocomplete="off" value="{{ get_date($model_my_certificate->time_start) }}"
                            required
                        >
                    </span>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>{{ trans('laprofile.date_issue') }}</label><span class="text-danger"> * </span>
                </div>
                <div class="col-md-9">
                    <span>
                        <input name="date_license"
                            type="text" class="datepicker form-control d-inline-block w-25"
                            placeholder="{{trans('laother.choose_start_date')}}"
                            autocomplete="off" value="{{ get_date($model_my_certificate->date_license) }}"
                            required
                        >
                    </span>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label for="score">{{ trans('latraining.score') }} <span class="text-danger">*</span></label>
                </div>
                <div class="col-md-6">
                    <input name="score" type="text" class="form-control" value="{{ $model_my_certificate->score }}" required>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label for="result">{{ trans('latraining.result') }} <span class="text-danger">*</span></label>
                </div>
                <div class="col-md-6">
                    <input name="result" type="text" class="form-control" value="{{ $model_my_certificate->result }}" required>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label for="note">{{ trans('latraining.note') }}</label>
                </div>
                <div class="col-md-6">
                    <textarea name="note" id="note" placeholder="{{ trans('latraining.note') }}" class="form-control" value="">{{ $model_my_certificate->note }}</textarea>
                </div>
            </div>
        </div>
    </div>
</form>
<script>
    var fileUpload = document.getElementById("file");
    var addAcitivty = document.getElementById("add_certifivate");
    addAcitivty.addEventListener("click", function (event) {
        if (fileUpload.files.length != 0) {
            $('#path_old').val('');
            return;
        }
    })
</script>
