<form method="post" action="{{ route('module.online.course_for_offline.save') }}" class="form-horizontal form-ajax" role="form" enctype="multipart/form-data">
    <input type="hidden" name="id" value="{{ $model->id }}">
    <div class="row row-acts-btn">
        <div class="col-sm-10 text-right mb-3">
            <div class="btn-group act-btns">
            @if($permission_save)
                <button type="submit" class="btn" data-must-checked="false"><i class="fa fa-save"></i> &nbsp;{{ trans('labutton.save') }}</button>
            @endif
            <a href="{{ route('module.online.management') }}" class="btn"><i class="fa fa-times-circle"></i> {{ trans('labutton.cancel') }}</a>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-10">
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>{{trans('latraining.training_program')}}<span style="color:red"> * </span></label>
                </div>
                <div class="col-md-9">
                    <select name="training_program_id" id="training_program_id" class="form-control load-training-program"
                            data-placeholder="-- {{trans('latraining.training_program')}} --" required>
                        @if(isset($training_program))
                            <option value="{{ $training_program->id }}" selected> {{ $training_program->name }} </option>
                        @endif
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label for="subject_id">{{ trans('latraining.subject') }}</label><span style="color:red"> * </span>
                </div>
                <div class="col-md-9">
                    <select name="subject_id" id="subject_id" class="form-control select2" data-training-program="{{ $model->training_program_id }}" data-placeholder="-- {{ trans('latraining.subject') }} --" required>
                        @if(isset($subject))
                        <option value="{{ $subject->id }}" selected> {{ $subject->name }} </option>
                        @endif
                    </select>
                </div>
            </div>

            {{-- Mã Khóa Học --}}
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>{{trans('latraining.course_code')}}</label><span style="color:red"> * </span>
                </div>
                <div class="col-md-9">
                    <input name="code" id="code" type="text" class="form-control" value="{{ $model->code }}" required readonly>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>{{trans('latraining.course_name')}}</label><span class="text-danger"> * </span>
                </div>
                <div class="col-md-9">
                    <input name="name" type="text" class="form-control" value="{{ $model->name }}" required>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>{{ trans('latraining.type_subject') }}</label>
                </div>
                <div class="col-md-9">
                    <input type="text" id="level_subject" class="form-control" value="{{ isset($level_subject) ? $level_subject->name : '' }}" readonly>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>{{trans('latraining.time')}}</label><span class="text-danger"> * </span>
                </div>
                <div class="col-md-9">
                    <span>
                        <input name="start_date" type="text" class="datepicker form-control d-inline-block w-25" placeholder="{{trans('latraining.start_date')}}" autocomplete="off" value="{{ $model->start_date ? get_date($model->start_date) : date('d/m/Y') }}">
                    </span>
                </div>
            </div>
        </div>
    </div>
</form>
<script>
    var ajax_get_course_code = "{{ route('module.online.course_for_offline.ajax_get_course_code') }}";

    $('#training_program_id').on('change', function () {
        var training_program_id = $('#training_program_id option:selected').val();
        $("#subject_id").empty();
        $.ajax({
            url: "{{ route('module.online.course_for_offline.ajax_get_subject') }}",
            type: 'post',
            data: {
                training_program_id: training_program_id,
            },
        }).done(function(data) {
            var html = ''
            html += '<option value="">Học phần</option>';
            data.forEach(element => {
                html += '<option value="'+ element.id +'">'+ element.code + ' - ' + element.name +'</option>'
            });
            $('#subject_id').html(html);
            return false;
        }).fail(function(data) {
            show_message('Lỗi hệ thống', 'error');
            return false;
        });
    });

    $('#subject_id').on('change', function() {
        var subject_id = $('#subject_id option:selected').val();
        var subject_name = $('#subject_id option:selected').text();
        var id = $('input[name=id]').val()
        $.ajax({
            url: ajax_get_course_code,
            type: 'post',
            data: {
                subject_id: subject_id,
                id: id,
            },
        }).done(function(data) {
            var d = new Date();
            if(subject_id != null){
                $('#code').val(data.course_code);
                $("input[name=name]").val(subject_name);
                $('#level_subject').val(data.level_subject_name).trigger('change');

                if(id.length <= 0){
                    $('#training_program_id').html($('<option>', {
                        value: data.training_program_id,
                        text: data.training_program_code +' - '+ data.training_program_name,
                    }));
                }
            }
            return false;
        }).fail(function(data) {
            show_message('Lỗi hệ thống', 'error');
            return false;
        });
    });
</script>