<form method="post" action="{{ route('module.offline.save_new_teacher', ['courseId' => $course->id, 'classId' => $class->id, 'id' => $model->id]) }}" autocomplete="off"  class="form-ajax form-horizontal w-100" role="form" enctype="multipart/form-data" id="form_save_new_teacher">
    <div class="row">
        @if($course->lock_course == 0)
            <div class="col-md-12 act-btns mb-3">
                <div class="pull-right">
                    <div class="wrraped_register text-right">
                        @canany(['offline-course-create'])
                            <div class="btn-group">
                                <button type="button" id="add_more_teacher" onclick="addMoreTeacherHandle()" class="btn">
                                    <i class="fas fa-plus"></i> &nbsp;{{ trans('latraining.add_lecturers') }}
                                </button>
                                <button type="submit" id="btn_save_teacher" class="btn save" data-must-checked="false">
                                    <i class="fa fa-save"></i> &nbsp;{{ trans('labutton.save') }}
                                </button>
                                <a href="{{ route('module.offline.schedule', [$course->id,  $class->id]) }}" class="btn">
                                    <i class="fa fa-times-circle"></i> {{ trans('labutton.cancel') }}
                                </a>
                            </div>
                        @endcanany
                    </div>
                </div>
            </div>
            <div class="col-12" id='wrapped_teacher'>
                @foreach ($newTeachers as $newTeacher)
                    <input type="hidden" name="id_teacher[]" value="{{ $newTeacher->id }}">
                    <div class="wrapped_add new_teacher_{{ $newTeacher->id }}">
                        <div class="form-group row">
                            <div class="col-sm-3 pr-0 control-label">
                                <label class="mb-0">{{ trans('latraining.main_lecturer') }} <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-7">
                                <select name="new_teacher_id[]" class="form-control select2" required>
                                    <option value="">{{ trans('latraining.choose_teacher') }}</option>
                                    @foreach($teachers_offline_new as $teacher)
                                        <option value="{{ $teacher->id }}" {{ $newTeacher->new_teacher_id == $teacher->id ? 'selected' : '' }}>{{ $teacher->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-2 text-right">
                                <button type="button" class="btn btn-add" onclick="deleteNewTeacherHandle({{ $newTeacher->id }}, {{ $newTeacher->new_teacher_id }})" data-count=''>
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3 pr-0 control-label">
                                <label class="mb-0">
                                    {{ trans('latraining.cost') }} <br> ({{ trans('latraining.main_lecturer') }})
                                </label>
                            </div>
                            <div class="col-md-7">
                                <input type="text" name="cost_new_teacher[]" class="form-control is-number cost_new_teacher" value="{{ number_format($newTeacher->cost_new_teacher) }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3 pr-0 control-label">
                                <label class="mb-0">
                                    {{ trans('latraining.practical_teaching_hours') }}
                                </label>
                            </div>
                            <div class="col-md-7">
                                <input type="text" step="any" name="practical_teaching_new_teacher[]" class="form-control is-number" value="{{ $newTeacher->practical_teaching_new_teacher }}">
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</form>
<template>
    <div class="wrapped_add">
        <div class="form-group row">
            <div class="col-sm-3 pr-0 control-label">
                <label class="mb-0">{{ trans('latraining.main_lecturer') }} <span class="text-danger">*</span></label>
            </div>
            <div class="col-md-7">
                <select name="new_teacher_id[]" class="form-control select2 new_teacher_id" data-count='' onchange="chosseTeacherHandle(this)" required>
                    <option value="">{{ trans('latraining.choose_teacher') }}</option>
                    @foreach($teachers_offline_new as $teacher)
                        <option value="{{ $teacher->id }}"> {{ $teacher->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-2 text-right">
                <button type="button" class="btn btn-add" onclick="deleteAddTeacherHandle(this)" data-count=''>
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-3 pr-0 control-label">
                <label class="mb-0">
                    {{ trans('latraining.cost') }} <br> ({{ trans('latraining.main_lecturer') }})
                </label>
            </div>
            <div class="col-md-7">
                <input type="text" name="cost_new_teacher[]" value="" class="form-control is-number cost_new_teacher" value="">
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-3 pr-0 control-label">
                <label class="mb-0">
                    {{ trans('latraining.practical_teaching_hours') }}
                </label>
            </div>
            <div class="col-md-7">
                <input type="text" step="any" name="practical_teaching_new_teacher[]" value="" class="form-control is-number">
            </div>
        </div>
    </div>
</template>
<script>
    var teacherTNT = $('#teacherTNT').val();
    teacherTNT = teacherTNT.split(",");

    function chosseTeacherHandle(ele) {
        var teacherId = ele.value
        var count = ele.getAttribute('data-count');
        if(teacherTNT.includes(teacherId)) {
            var cost = $('.count_'+ count).find('.cost_new_teacher').val('130.000');
        } else {
            var cost = $('.count_'+ count).find('.cost_new_teacher').val('100.000');
        }
    }

    $('.cost_new_teacher').on( "keyup", function( event ) {
        var $this = $( this );
        var input = $this.val();
        var input = input.replace(/[\D\s\._\-]+/g, "");
        input = input ? parseInt( input, 10 ) : 0;

        $this.val( function() {
            return ( input === 0 ) ? "" : input.toLocaleString( "en-US" );
        } );
    });

    function addMoreTeacherHandle() {
        var count = $('.wrapped_add').length;
        var temp = document.getElementsByTagName("template")[0];
        var clon = temp.content.cloneNode(true);
        var wrapped = clon.querySelector(".wrapped_add");
        var btnAdd = clon.querySelector(".btn-add");
        var selectAdd = clon.querySelector(".new_teacher_id");
        btnAdd.setAttribute('data-count', count);
        selectAdd.setAttribute('data-count', count);
        wrapped.classList.add("count_"+ count);
        document.getElementById('wrapped_teacher').appendChild(clon);
    }

    function deleteAddTeacherHandle(ele) {
        var count = ele.getAttribute('data-count');
        $('.count_'+ count).remove()
    }

    function deleteNewTeacherHandle(id, teacherId) {
        Swal.fire({
            title: '',
            text: 'Bạn chắc chắn muốn xóa giảng viên?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Đồng ý!',
            cancelButtonText: 'Hủy!',
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    type: "POST",
                    url: "{{ route('module.offline.delete_new_teacher', ['courseId' => $course->id, 'classId' => $class->id, 'id' => $model->id]) }}",
                    dataType: 'json',
                    data: {
                        id: id,
                        teacherId: teacherId,
                    },
                    success: function (result) {
                        $('.new_teacher_'+ id).remove();
                    }
                });
            }
        });
    }
</script>
