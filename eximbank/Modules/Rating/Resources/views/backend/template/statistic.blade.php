<form method="post" action="{{ route('module.rating.template.save_statistic', ['template_id' => $model->id]) }}" class="form-validate form-ajax" role="form" enctype="multipart/form-data">
    <div class="row">
        <div class="col-md-8">
        </div>
        <div class="col-md-4 text-right">
            <div class="btn-group act-btns">
                @canany(['rating-template-create', 'rating-template-edit'])
                <button type="submit" class="btn" data-must-checked="false"><i class="fa fa-save"></i> &nbsp;{{ trans('labutton.save') }}</button>
                @endcanany
                <a href="{{ route('module.rating.template') }}" class="btn"><i class="fa fa-times-circle"></i> {{ trans('labutton.cancel') }}</a>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group row">
                <div class="col-sm-2 control-label">
                    <label>{{ trans('latraining.title_rating_lesson') }} <span class="text-danger">*</span></label>
                </div>
                <div class="col-md-7">
                    <input name="title_lesson" type="text" class="form-control" value="{{ $statistical->title_lesson }}" required>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group row">
                <div class="col-sm-2 control-label">
                    <label>{{ trans('latraining.title_rating_organization') }} <span class="text-danger">*</span></label>
                </div>
                <div class="col-md-7">
                    <input name="title_organization" type="text" class="form-control" value="{{ $statistical->title_organization }}" required>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group row">
                <div class="col-sm-2 control-label">
                    <label>{{ trans('latraining.title_rating_teacher') }}</label>
                </div>
                <div class="col-md-7">
                    <input name="title_teacher" type="text" class="form-control" value="{{ $statistical->title_teacher }}" required>
                </div>
            </div>
        </div>
    </div>
</form>
