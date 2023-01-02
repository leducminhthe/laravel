<form method="post" action="{{ route('module.online.edit.save_prerequisite_condition', ['id' => $model->id]) }}" class="form-ajax" id="form-prerequisite">
    <div class="row">
        <div class="col-12 text-right">
            <div class="form-group row">
                <div class="col-sm-4 control-label"></div>
                <div class="col-md-8 pl-1">
                    <button type="submit" class="btn" data-must-checked="false">
                        <i class="fa fa-save"></i> &nbsp;{{ trans('labutton.save') }}
                    </button>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            @if($permission_save)
                <div class="wrapped_1">
                    <div class="form-group row">
                        <div class="col-sm-4 control-label">
                            <label>Khóa học cần hoàn thành <span class="text-danger">*</span></label>
                        </div>
                        <div class="col-md-8 pl-1">
                            <div class="row">
                                <div class="col-12 p-1">
                                    <select name="subject_prerequisite" id="subject_prerequisite_id" class="form-control load-subject" data-placeholder="Chọn chuyên đề">
                                        <option value=""></option>
                                        @if(isset($subject_prerequisite))
                                            <option value="{{ $subject_prerequisite->id }}" selected> {{ $subject_prerequisite->name }} </option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-4 control-label">
                            <label>Hoàn thành sau thời gian (ngày)/ Điểm >=</label>
                        </div>
                        <div class="col-md-8 pl-1">
                            <div class="row d_flex_align">
                                <div class="col-4 p-1">
                                    <input type="number" class="form-control date_finish_prerequisite" name="date_finish_prerequisite" id="date_finish_prerequisite" placeholder="Nhập số ngày" value="{{ $prerequisite_course ? $prerequisite_course->date_finish_prerequisite : '' }}">
                                </div>
                                <div class="col-4 p-1">
                                    <select class="form-control" name="finish_and_score" id="finish_and_score">
                                        <option value="1" {{ $prerequisite_course && $prerequisite_course->finish_and_score == 1 ? 'selected' : '' }}>Và</option>
                                        <option value="2" {{ $prerequisite_course && $prerequisite_course->finish_and_score == 2 ? 'selected' : '' }}>Hoặc</option>
                                    </select>
                                </div>
                                <div class="col-4 p-1">
                                    <input type="number" class="form-control score_prerequisite" name="score_prerequisite" id="score_prerequisite" placeholder="Nhập điểm" value="{{ $prerequisite_course ? $prerequisite_course->score_prerequisite : '' }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-7 control-label">
                    </div>
                    <div class="col-md-2">
                        <select class="form-control text-center" name="select_subject_prerequisite" id="select_subject_prerequisite" data-placeholder="Chọn hình thức">
                            <option value="1" {{ $prerequisite_course && $prerequisite_course->select_subject_prerequisite == 1 ? 'selected' : '' }}>Và</option>
                            <option value="2" {{ $prerequisite_course && $prerequisite_course->select_subject_prerequisite == 2 ? 'selected' : '' }}>Hoặc</option>
                        </select>
                    </div>
                </div>
                <div class="wrapped_2">
                    <div class="form-group row">
                        <div class="col-sm-4 control-label">
                            <label>Chức danh</label>
                        </div>
                        <div class="col-md-8 pl-1">
                            <div class="row d_flex_align">
                                <div class="col-1 p-1">
                                    <input type="checkbox" name="status_title" id="status_title" {{ $prerequisite_course && $prerequisite_course->status_title == 1 ? 'checked' : '' }}>
                                </div>
                                <div class="col-11 p-1">
                                    <select name="title_id" id="title_id" class="load-title form-control" data-placeholder="Chọn chức danh">
                                        <option value=""></option>
                                        @if(isset($title_prerequisite))
                                            <option value="{{ $title_prerequisite->id }}" selected> {{ $title_prerequisite->name }} </option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-7 control-label">
                    </div>
                    <div class="col-md-2">
                        <select class="form-control text-center" name="select_title" id="select_title" data-placeholder="Chọn hình thức">
                            <option value="1" {{ $prerequisite_course && $prerequisite_course->select_title == 1 ? 'selected' : '' }}>Và</option>
                            <option value="2" {{ $prerequisite_course && $prerequisite_course->select_title == 2 ? 'selected' : '' }}>Hoặc</option>
                        </select>
                    </div>
                </div>
                <div class="wrapped_3">
                    <div class="form-group row">
                        <div class="col-sm-4 control-label">
                            <label>Ngày bổ nhiệm chức danh >=</label>
                        </div>
                        <div class="col-md-8 pl-1">
                            <div class="row d_flex_align">
                                <div class="col-1 p-1">
                                    <input type="checkbox" name="status_date_title_appointment" id="status_date_title_appointment" {{ $prerequisite_course && $prerequisite_course->status_date_title_appointment == 1 ? 'checked' : '' }}>
                                </div>
                                <div class="col-11 p-1">
                                    <input type="number" class="form-control" name="date_title_appointment" id="date_title_appointment" placeholder="Nhập số ngày" value="{{ $prerequisite_course ? $prerequisite_course->date_title_appointment : '' }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-7 control-label">
                    </div>
                    <div class="col-md-2">
                        <select class="form-control text-center" name="select_date_title_appointment" id="select_date_title_appointment" data-placeholder="Chọn hình thức">
                            <option value="1" {{ $prerequisite_course && $prerequisite_course->select_date_title_appointment == 1 ? 'selected' : '' }}>Và</option>
                            <option value="2" {{ $prerequisite_course && $prerequisite_course->select_date_title_appointment == 2 ? 'selected' : '' }}>Hoặc</option>
                        </select>
                    </div>
                </div>
                <div class="wrapped_4">
                    <div class="form-group row">
                        <div class="col-sm-4 control-label">
                            <label>Ngày vào làm >=</label>
                        </div>
                        <div class="col-md-8 pl-1">
                            <div class="row d_flex_align">
                                <div class="col-1 p-1">
                                    <input type="checkbox" name="status_join_company" id="status_join_company" {{ $prerequisite_course && $prerequisite_course->status_join_company == 1 ? 'checked' : '' }}>
                                </div>
                                <div class="col-11 p-1">
                                    <input type="number" class="form-control" name="join_company" id="join_company" placeholder="Nhập số ngày" value="{{ $prerequisite_course ? $prerequisite_course->join_company : '' }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</form>

<script type="text/javascript">
    var subject_prerequisite = '{{ $prerequisite_course->subject_prerequisite }}'
    var status_title = '{{ $prerequisite_course->status_title }}'
    var status_date_title_appointment = '{{ $prerequisite_course->status_date_title_appointment }}'
    var status_join_company = '{{ $prerequisite_course->status_join_company }}'
    console.log(subject_prerequisite);
    if(status_title == 1) {
        $('#title_id').removeAttr("disabled")
        $('#select_title').removeAttr("disabled")
    } else {
        $('#title_id').attr('disabled', 'disabled')
        $('#select_title').attr('disabled', 'disabled')
    }

    if(status_date_title_appointment == 1) {
        $('#date_title_appointment').removeAttr("disabled")
        $('#select_date_title_appointment').removeAttr("disabled")
    } else {
        $('#date_title_appointment').attr('disabled', 'disabled')
        $('#select_date_title_appointment').attr('disabled', 'disabled')
    }

    if(status_join_company == 1) {
        $('#join_company').removeAttr("disabled")
    } else {
        $('#join_company').attr('disabled', 'disabled')
    }

    if(subject_prerequisite) {
        $('#finish_and_score').removeAttr("disabled")
        $('.date_finish_prerequisite').removeAttr("disabled")
        $('.score_prerequisite').removeAttr("disabled")
        $('#select_subject_prerequisite').removeAttr("disabled")
    } else {
        $('#date_finish_prerequisite').val('');
        $('#score_prerequisite').val('')

        $('#finish_and_score').attr('disabled', 'disabled')
        $('.date_finish_prerequisite').attr('disabled', 'disabled')
        $('.score_prerequisite').attr('disabled', 'disabled')
        $('#select_subject_prerequisite').attr('disabled', 'disabled')
    }
        
    $('#subject_prerequisite_id').on('change', function() {
        if($(this).val()) {
            $('#finish_and_score').removeAttr("disabled")
            $('.date_finish_prerequisite').removeAttr("disabled")
            $('.score_prerequisite').removeAttr("disabled")
            $('#select_subject_prerequisite').removeAttr("disabled")
        } else {
            $('#date_finish_prerequisite').val('');
            $('#score_prerequisite').val('')

            $('#finish_and_score').attr('disabled', 'disabled')
            $('.date_finish_prerequisite').attr('disabled', 'disabled')
            $('.score_prerequisite').attr('disabled', 'disabled')
            $('#select_subject_prerequisite').attr('disabled', 'disabled')
        }
    })

    $('#status_title').on('change', function () {
        if($(this).is(":checked")) {
            $('#title_id').removeAttr("disabled")
            $('#select_title').removeAttr("disabled")
        } else {
            $('#title_id').attr('disabled', 'disabled')
            $('#select_title').attr('disabled', 'disabled')
        }
    })

    $('#status_date_title_appointment').on('change', function () {
        if($(this).is(":checked")) {
            $('#date_title_appointment').removeAttr("disabled")
            $('#select_date_title_appointment').removeAttr("disabled")
        } else {
            $('#date_title_appointment').attr('disabled', 'disabled')
            $('#select_date_title_appointment').attr('disabled', 'disabled')
        }
    })

    $('#status_join_company').on('change', function () {
        if($(this).is(":checked")) {
            $('#join_company').removeAttr("disabled")
        } else {
            $('#join_company').attr('disabled', 'disabled')
        }
    })
</script>
