<form method="post" action="{{ route('module.offline.save_schedule', ['courseId' => $course->id, 'class_id' => $class->id]) }}" autocomplete="off"  class="form-ajax form-horizontal" role="form" enctype="multipart/form-data" id="form_save">
    <input type="hidden" name="id" value="{{ $model->id }}">
    <div class="row">
        <div class="col-12 text-right mb-3">
            <div class="btn-group act-btns">
                @canany(['category-unit-create', 'category-unit-edit'])
                    @if($course->lock_course == 0)
                        <button type="submit" id="btn_save" class="btn save" data-must-checked="false">
                            <i class="fa fa-save"></i> &nbsp;{{ trans('labutton.save') }}
                        </button>
                    @endif
                @endcanany
                <a href="{{ route('module.offline.schedule', [$course->id,  $class->id]) }}" class="btn">
                    <i class="fa fa-times-circle"></i> {{ trans('labutton.cancel') }}
                </a>
            </div>
        </div>
        <div class="col-12">
            <div class="form-group row">
                <div class="col-sm-3 pr-0 control-label">
                    <label>{{trans('latraining.type_study')}} <span class="text-danger">*</span></label>
                </div>
                <div class="form-group col-sm-7 m-0">
                    @if ($lockTeams || $model->id)
                        <input type="hidden" name="type_study" class="type_study_offline" value="{{ $model->type_study }}">
                    @endif
                    <div>
                        <label>
                            <input type="radio" name="type_study" id="type_study_3" {{$lockTeams || $model->id ? 'disabled' : ''}} class="type_study_offline" value="3" {{ !($model->id) || $model->type_study == 3 ? 'checked' : '' }}>
                            Đào tạo Elearning
                        </label>
                    </div>
                    <div>
                        <label>
                            <input type="radio" name="type_study" id="type_study_1" {{$lockTeams || $model->id ? 'disabled' : ''}} class="type_study_offline" value="1" {{ $model->type_study == 1 ? 'checked' : '' }}>
                            {{trans('latraining.type_study_class')}}
                        </label>
                    </div>
                    <div>
                        <label>
                            <input type="radio" name="type_study" id="type_study_2" {{$lockTeams || $model->id ? 'disabled' : ''}} class="type_study_teams" value="2" {{ $model->type_study == 2 ? 'checked' : '' }}>
                            <span>Online microsoft teams</span>
                        </label>
                        <br>
                        <div style="display:none" class="wrap-condition">
                            <label>{{trans('latraining.condition_complete')}}</label>
                            <input type="number" name="condition_complete_teams" {{$lockTeams?'disabled':''}} placeholder="{{trans('latraining.percent_duration_attendance')}}" class="form-control is-number" min="1" max="100" value="{{ $model->condition_complete_teams }}">
                        </div>
                    </div>
                </div>
            </div>
            <div id="form_time_type_3">
                <div class="form-group row">
                    <div class="col-sm-3 pr-0 control-label">
                        <label>{{ trans('latraining.training_time') }} <span class="text-danger">*</span></label>
                    </div>
                    <div class="col-md-7 d_flex_align" id="type_unit">
                        <span>
                            <input name="start_time_3" {{$lockTeams?'disabled':''}} type="text" class="form-control time_picker d-inline-block" placeholder="{{ trans('latraining.start_time') }}" autocomplete="off" value="{{ $model ? $model->start_time : '08:00' }}">
                        </span>
                        <span>
                            <input name="lesson_date_3" {{$lockTeams?'disabled':''}} type="text" placeholder="{{ trans('latraining.start_date') }}" id="datepicker_class" class="datepicker_class form-control d-inline-block" value="{{ $model->lesson_date }}">
                        </span>
                        <span class="fa fa-arrow-right" style="padding: 0 10px;"></span>
                        <span>
                            <input name="end_time_3" {{$lockTeams?'disabled':''}} type="text" class="form-control time_picker d-inline-block" placeholder="{{ trans('latraining.end_time') }}" autocomplete="off" value="{{ $model ? $model->end_time : '17:00' }}">
                        </span>
                        <span>
                            <input name="end_date" {{$lockTeams?'disabled':''}} type="text" placeholder="{{ trans('latraining.end_date') }}" id="datepicker_class_end" class="datepicker_class form-control d-inline-block" value="{{ $model->end_date }}">
                        </span>
                    </div>
                </div>
            </div>
            <div id="form_time_type_12">
                <div class="form-group row">
                    <div class="col-sm-3 pr-0 control-label">
                        <label>{{ trans('latraining.start_date') }} <span class="text-danger">*</span></label>
                    </div>
                    <div class="col-md-7">
                        <input name="lesson_date" {{$lockTeams?'disabled':''}} type="text" placeholder="{{ trans('latraining.start_date') }}" id="datepicker_class" class="datepicker_class form-control d-inline-block" value="{{ $model->lesson_date }}">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-3 pr-0 control-label">
                        <label>{{ trans('latraining.training_time') }} <span class="text-danger">*</span></label>
                    </div>
                    <div class="col-md-7 d_flex_align" id="type_unit">
                        <span><input name="start_time" {{$lockTeams?'disabled':''}} type="text" class="form-control time_picker d-inline-block" placeholder="{{ trans('latraining.start_time') }}" autocomplete="off" value="{{ $model ? $model->start_time : '08:00' }}"></span>
                        <span class="fa fa-arrow-right" style="padding: 0 10px;"></span>
                        <span><input name="end_time" {{$lockTeams?'disabled':''}} type="text" class="form-control time_picker d-inline-block" placeholder="{{ trans('latraining.end_time') }}" autocomplete="off" value="{{ $model ? $model->end_time : '17:00' }}"></span>
                    </div>
                </div>
            </div>

            @if ($model->id && $model->type_study == 3)
            <div class="form-group row">
                <div class="col-sm-3 pr-0 control-label">
                </div>
                <div class="form-group col-sm-7 m-0">
                    <a href="{{ route('module.offline.activity_by_schedule', ['course_id' => $course->id,'class_id'=>$class->id,'schedule_id'=>$model->id]) }}" class="btn">
                        {{ trans('latraining.lesson') }}
                    </a>
                </div>
            </div>
            @endif
            <div id="wrraped_teacher">
                <div class="form-group row">
                    <div class="col-sm-3 pr-0 control-label">
                        <label>{{ trans('latraining.main_lecturer') }} <span class="text-danger">*</span></label>
                    </div>
                    <div class="col-md-7">
                        <select name="teacher_id" id="teacher_id" class="form-control select2" {{$lockTeams?'disabled':''}}>
                            <option value="">{{ trans('latraining.choose_teacher') }}</option>
                            @foreach($teachers_offline as $teacher)
                                <option value="{{ $teacher->id }}" {{ $model->teacher_main_id == $teacher->id ? 'selected' : '' }}> {{ $teacher->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-3 pr-0 control-label">
                        <label for="cost_teacher_main">
                            {{ trans('latraining.cost') }} <span class="text-danger">*</span><br> ({{ trans('latraining.main_lecturer') }})
                        </label>
                    </div>
                    <div class="col-md-7">
                        <input type="text" name="cost_teacher_main" value="{{ $model->cost_teacher_main ? number_format($model->cost_teacher_main) : '' }}" class="form-control is-number cost_teacher_main">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-3 pr-0 control-label">
                        <label for="cost_teacher_main">
                            {{ trans('latraining.practical_teaching_hours') }}
                        </label>
                    </div>
                    <div class="col-md-7">
                        <input type="text" name="practical_teaching" value="{{ $model->practical_teaching ? $model->practical_teaching : '' }}" class="form-control is-number">
                    </div>
                </div>
            </div>
            <div id="wrraped_tutors">
                <div class="form-group row">
                    <div class="col-sm-3 pr-0 control-label">
                        <label>{{ trans('latraining.tutors') }}</label>
                    </div>
                    <div class="col-md-7">
                        <select name="tutors_id[]" id="tutors_id" class="form-control select2" multiple {{$lockTeams?'disabled':''}}>
                            @foreach($tutors_offline as $tutor)
                                <option value="{{ $tutor->id }}" {{ $model->teach_id == $tutor->id ? 'selected' : '' }}> {{ $tutor->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-3 pr-0 control-label">
                        <label for="cost_teach_type">
                            {{ trans('latraining.cost') }} <br> ({{ trans('latraining.tutors') }})
                        </label>
                    </div>
                    <div class="col-md-7">
                        <input type="text" {{$lockTeams?'disabled':''}} name="cost_teach_type" value="{{ $model->cost_teach_type }}" class="form-control is-number cost_teach_type">
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<script>
    var teacherTNT = $('#teacherTNT').val();
    teacherTNT = teacherTNT.split(",");

    $('#teacher_id').on('change', function () {
        var teacherId = $(this).val();
        if(teacherTNT.includes(teacherId)) {
            $('input[name=cost_teacher_main]').val('130.000')
        } else {
            $('input[name=cost_teacher_main]').val('100.000')
        }
    })

    $('#teacher_id').on('select2:select', function (e) {
        var teacherId = $('#teacher_id').val();
        $('#tutors_id').html('');
        $('input[name=cost_teach_type]').val('');

        $.ajax({
            url: "{{ route('module.offline.change_teacher', ['courseId' => $class->course_id]) }}",
            type: 'post',
            data: {
                teacherId: teacherId
            },
        }).done(function(data) {
            if(data.teacher) {
                let html = '';
                $.each(data.teacher, function (i, item){
                    html+='<option value='+ item.teacher_id +'>'+ item.name +'</option>';
                });
                $('#tutors_id').html(html);
            }
            return false;
        }).fail(function(data) {
            show_message('Lỗi dữ liệu', 'error');
            return false;
        });
    });

    $('#tutors_id').on('select2:select', function (e) {
        var tutors_id = $('#tutors_id').val();

        $.ajax({
            url: "{{ route('module.offline.change_teacher_tutors', ['courseId' => $course->id]) }}",
            type: 'post',
            data: {
                tutors_id: tutors_id
            },
        }).done(function(data) {
            $('input[name=cost_teach_type]').val(data.cost_teacher);
            return false;
        }).fail(function(data) {
            show_message('Lỗi dữ liệu', 'error');
            return false;
        });
    });

    var type_study = '{{ $model->type_study }}';
    if (type_study == 1) { //Học tại lớp
        $('.wrap-condition').hide();
        $('.wrap-location').attr('style',"display:flex");

        $('#wrraped_teacher').show();
        $('#wrraped_tutors').show();

        $('#form_time_type_3').hide();
        $('#form_time_type_12').show();

    } else if(type_study == 2) { //Ms Teams
        $('.wrap-condition').show();
        $('.wrap-location').hide();

        $('#wrraped_teacher').show();
        $('#wrraped_tutors').show();

        $('#form_time_type_3').hide();
        $('#form_time_type_12').show();

    } else { //Đào tạo Elearning
        $('.wrap-condition').hide();
        $('.wrap-location').hide();

        $('#wrraped_teacher').hide();
        $('#wrraped_tutors').hide();

        $('#form_time_type_3').show();
        $('#form_time_type_12').hide();
    }

    $('input[name="type_study"]').on('change',function () {
        if ($(this).val() == 1){ //Học tại lớp
            $('.wrap-condition').hide();
            $('.wrap-location').attr('style',"display:flex");
            $('#wrraped_teacher').show();
            $('#wrraped_tutors').show();
            $('input[name=condition_complete_teams]').val('');

            $('#form_time_type_3').hide();
            $('#form_time_type_12').show();

            $('#form_time_type_3 input[name=end_date]').val('');
            $('#form_time_type_3 input[name=lesson_date_3]').val('');
            $('#form_time_type_3 input[name=start_time_3]').val('');
            $('#form_time_type_3 input[name=end_time_3]').val('');

        } else if($(this).val()==2){ // MS Teams
            $('.wrap-condition').show();
            $('.wrap-location').hide();
            $('#wrraped_teacher').show();
            $('#wrraped_tutors').show();

            $('#form_time_type_3').hide();
            $('#form_time_type_12').show();

            $('#form_time_type_3 input[name=end_date]').val('');
            $('#form_time_type_3 input[name=lesson_date_3]').val('');
            $('#form_time_type_3 input[name=start_time_3]').val('');
            $('#form_time_type_3 input[name=end_time_3]').val('');

        } else { //Đào tạo Elearning
            $('.wrap-condition').hide();
            $('.wrap-location').hide();
            $('#wrraped_teacher').hide();
            $('#wrraped_tutors').hide();
            $('#teacher_id').val('').trigger('change');
            $('#tutors_id').val('').trigger('change');

            $('input[name=condition_complete_teams]').val('');
            $('input[name=cost_teacher_main]').val('');
            $('input[name=practical_teaching]').val('');
            $('input[name=cost_teach_type]').val('');

            $('#form_time_type_3').show();
            $('#form_time_type_12').hide();

            $('#form_time_type_12 input[name=lesson_date]').val('');
            $('#form_time_type_12 input[name=start_time]').val('');
            $('#form_time_type_12 input[name=end_time]').val('');
        }
    });

    $('.cost_teacher_main').on( "keyup", function( event ) {
        var $this = $( this );
        // Get the value.
        var input = $this.val();
        var input = input.replace(/[\D\s\._\-]+/g, "");
        input = input ? parseInt( input, 10 ) : 0;

        $this.val( function() {
            return ( input === 0 ) ? "" : input.toLocaleString( "en-US" );
        } );
    });
    $('.cost_teach_type').on( "keyup", function( event ) {
        var $this = $( this );
        // Get the value.
        var input = $this.val();
        var input = input.replace(/[\D\s\._\-]+/g, "");
        input = input ? parseInt( input, 10 ) : 0;

        $this.val( function() {
            return ( input === 0 ) ? "" : input.toLocaleString( "en-US" );
        } );
    });
    $('.time_picker').datetimepicker({
        locale:'vi',
        format: 'HH:mm'
    });
</script>
@section('footer')
    <script>
        var classStartDate = '{{ get_date($course->start_date, "d/m/Y") }}';
        var classEndDate = '{{ get_date($course->end_date, "d/m/Y") }}';
        $( ".datepicker_class" ).datepicker({
            "format": "dd/mm/yyyy",
            "startDate": classStartDate,
            "endDate": classEndDate,
        });
    </script>
@endsection
