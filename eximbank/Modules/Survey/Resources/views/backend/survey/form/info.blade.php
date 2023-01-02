<form method="post" action="{{ route('module.survey.save') }}" class="form-horizontal form-ajax" role="form" enctype="multipart/form-data" id="form_save">
    <input type="hidden" name="id" value="{{ $model->id }}">
    <div class="row">
        <div class="col-md-8">
        </div>
        <div class="col-md-4 text-right">
            <div class="btn-group act-btns">
                @canany(['survey-create', 'survey-edit'])
                <button type="submit" class="btn" data-must-checked="false"><i class="fa fa-save"></i> &nbsp;{{trans('labutton.save')}}</button>
                @endcanany
                <a href="{{ route('module.survey.index') }}" class="btn"><i class="fa fa-times-circle"></i> {{trans('labutton.cancel')}}</a>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-9">
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>{{trans('lasurvey.survey_name')}} <span class="text-danger">*</span> </label>
                </div>
                <div class="col-md-9">
                    <input name="name" type="text" class="form-control" value="{{ $model->name }}" required>
                </div>
            </div>

            {{-- LOẠI KHẢO SÁT --}}
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>Loại khảo sát <span class="text-danger">*</span> </label>
                </div>
                <div class="col-md-9">
                    <select name="type" id="type" class="select2" data-placeholder="-- Loại khảo sát --">
                        <option value=""></option>
                        <option value="1" {{ $model->type == 1 ? 'selected' : '' }}>Khảo sát độc lập</option>
                        <option value="2" {{ $model->type == 2 ? 'selected' : '' }}>Khảo sát hoạt động online</option>
                        <option value="3" {{ $model->type == 3 ? 'selected' : '' }}>Khảo sát trước ghi danh</option>
                    </select>
                </div>
            </div>

            <div class="form-group row wrapped_image">
                <div class="col-sm-3 control-label">
                    <label>{{trans('lasurvey.picture')}} (300 x 200)</label>
                </div>
                <div class="col-md-4">
                    <a href="javascript:void(0)" id="select-image">{{trans('lasurvey.choose_picture')}}</a>
                    <div id="image-review" >
                        @if($model->image)
                            <img class="w-100" src="{{ image_file($model->image) }}" alt="">
                        @endif
                    </div>
                    <input name="image" id="image-select" type="text" class="d-none" value="{{ $model->image ? $model->image : '' }}">
                </div>
            </div>
            <div class="form-group row wrapped_time">
                <label class="col-sm-3 control-label">{{trans('lasurvey.time')}} <span class="text-danger">*</span></label>
                <div class="col-sm-6">
                    <div>
                        <input name="start_date" type="text" class="datepicker form-control w-25 d-inline-block date-custom" placeholder="{{trans('lasurvey.start')}}" autocomplete="off" value="{{ get_date($model->start_date) }}">
                        <select name="start_hour" id="start_hour" class="form-control d-inline-block w-25 date-custom">
                            @for($i = 0 ; $i <= 23 ; $i++)
                                @php $ii = $i < 10 ? '0'.$i : $i @endphp
                                <option value="{{$ii}}" {{ get_date($model->start_date, 'H') == $ii ? 'selected' : '' }}>{{$ii}}</option>
                            @endfor
                        </select>
                        giờ
                        <select name="start_min" id="start_min" class="form-control d-inline-block  w-25 date-custom">
                            @for($i = 0; $i <= 59; $i += 1)
                                @php $ii = $i < 10 ? '0'. $i : $i @endphp
                                <option value="{{$ii}}" {{ get_date($model->start_date, 'i') == $ii ? 'selected' : '' }}>{{$ii}}</option>
                            @endfor
                        </select>
                        phút
                    </div>
                    <div>
                        <input name="end_date" type="text" class="datepicker form-control w-25 d-inline-block date-custom" placeholder="{{trans('lasurvey.over')}}" autocomplete="off" value="{{ get_date($model->end_date) }}">
                        <select name="end_hour" id="end_hour" class="form-control d-inline-block w-25 date-custom">
                            @for($i = 0 ; $i <= 23 ; $i++)
                                @php $ii = $i < 10 ? '0'. $i : $i @endphp
                                <option value="{{$ii}}" {{ get_date($model->end_date, 'H') == $ii ? 'selected' : '' }}>{{$ii}}</option>
                            @endfor
                        </select>
                        giờ
                        <select name="end_min" id="end_min" class="form-control d-inline-block w-25 date-custom">
                            @for($i = 0; $i <= 59; $i += 1)
                                @php $ii = $i < 10 ? '0'. $i : $i @endphp
                                <option value="{{$ii}}" {{ get_date($model->end_date, 'i') == $ii ? 'selected' : '' }}>{{$ii}}</option>
                            @endfor
                        </select>
                        phút
                    </div>
                </div>
            </div>
            {{-- <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label for="template_id">{{trans('lasurvey.survey_template')}} <span class="text-danger">*</span> </label>
                </div>
                <div class="col-md-9">
                    @if($survey_templates)
                        @if(isset($surver_user))
                            <input type="hidden" name="template_id" value="{{ $model->template_id }}">
                        @endif

                    <select class="form-control select2" name="template_id" id="template_id" data-placeholder="-- {{trans('lasurvey.survey_template')}} --"  {{ isset($surver_user) ? 'disabled' : '' }}>
                        <option value=""></option>
                        @foreach($survey_templates as $survey_template)
                            <option value="{{ $survey_template->id }}" {{ $model->template_id == $survey_template->id ? 'selected' : '' }}>
                                {{ $survey_template->name }}
                            </option>
                        @endforeach
                    </select>
                    @endif
                </div>
            </div> --}}
            <div class="form-group row wrapped_suggest">
                <div class="col-sm-3 control-label">
                    <label>{{trans('lasurvey.another_suggestion')}}</label>
                </div>
                <div class="col-sm-6">
                    <label class="radio-inline"><input type="radio" name="more_suggestions" value="1" @if($model->more_suggestions == 1) checked @endif>{{trans('lasurvey.enable')}}</label>
                    <label class="radio-inline"><input type="radio" name="more_suggestions" value="0" @if($model->more_suggestions == 0) checked @endif>{{trans('lasurvey.disable')}}</label>
                </div>
            </div>
            <div class="form-group row wrapped_status">
                <div class="col-sm-3 control-label">
                    <label>{{trans('lasurvey.status')}}</label>
                </div>
                <div class="col-md-6">
                    <label class="radio-inline"><input type="radio" name="status" value="1" @if($model->status == 1) checked @endif>{{trans('lasurvey.enable')}}</label>
                    <label class="radio-inline"><input type="radio" name="status" value="0" @if($model->status == 0) checked @endif>{{trans('lasurvey.disable')}}</label>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label for="template_id">{{trans('lasurvey.survey_template')}} <span class="text-danger">*</span> </label>
                </div>
                <div class="col-md-6">
                    <button type="button" onclick="submitForm()" class="btn">{{trans('lasurvey.survey_template')}}</button>
                    <input type="hidden" name="template" class="template">
                </div>
            </div>
        </div>
    </div>
</form>
<script>
    $("#select-image").on('click', function () {
        var lfm = function (options, cb) {
            var route_prefix = '/filemanager';
            window.open(route_prefix + '?type=' + options.type || 'file', 'FileManager', 'width=1100,height=600');
            window.SetUrl = cb;
        };

        lfm({type: 'image'}, function (url, path) {
            $("#image-review").html('<img class="w-100" src="' + path + '">');
            $("#image-select").val(path);
        });
    });

    var type = '{{ $model->type }}';
    if(type == 1) {
        $('.wrapped_time').show()
        $('.wrapped_suggest').show()
        $('.wrapped_image').show()
        $('.wrapped_status').show()
    } else if (type == 2) {
        $('.wrapped_time').hide()
        $('.wrapped_suggest').hide()
        $('.wrapped_image').hide()
        $('.wrapped_status').hide()
    } else {
        $('.wrapped_time').hide()
        $('.wrapped_suggest').hide()
        $('.wrapped_image').hide()
        $('.wrapped_status').show()
    }

    $("#type").on('change', function () {
        if($(this).val() == 1) {
            $('.wrapped_time').show()
            $('.wrapped_suggest').show()
            $('.wrapped_image').show()
            $('.wrapped_status').show()
        } else if ($(this).val() == 2) {
            $('.wrapped_time').hide()
            $('.wrapped_suggest').hide()
            $('.wrapped_image').hide()
            $('.wrapped_status').hide()
        } else {
            $('.wrapped_time').hide()
            $('.wrapped_suggest').hide()
            $('.wrapped_image').hide()
            $('.wrapped_status').show()
        }
    });

    function submitForm() {
        $('.template').val(1);
        $('#form_save').submit();
    }
</script>
