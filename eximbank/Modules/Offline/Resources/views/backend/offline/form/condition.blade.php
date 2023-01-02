@if (($offline_result))
    <p class="text-danger h5">
        LƯU Ý: <br>
        - Khi khóa học đã tổ chức nếu thay đổi điều kiện hoàn thành sẽ ảnh hưởng đến số liệu kết quả hoàn thành khóa học của học viên. <br>
        - Bấm nút "Cập nhật lại kết quả" để chạy lại kết quả Học viên theo điều kiện thay đổi hiện tại (nếu cần).
    </p>
@endif
<p></p>
<form action="{{ route('module.offline.save_condition', ['id' => $model->id]) }}" method="post" class="form-ajax">
    <div class="row">
        <div class="col-md-12">
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label for="ratio">{{ trans('latraining.ratio_join') }}</label>
                </div>
                <div class="col-md-9">
                    <input type="text" name="ratio" class="form-control is-number" value="{{ (isset($condition['id']) && $condition['id'] != 0) ? $condition->ratio : ''}}">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label for="minscore">{{ trans('latraining.pass_score') }}</label>
                </div>
                <div class="col-md-9">
                <input type="text" name="minscore" class="form-control is-number"  value="{{ (isset($condition['id']) && $condition['id'] != 0) ? $condition->minscore : ''}}">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label for="survey">Đánh giá sau đào tạo</label>
                </div>
                <div class="col-md-9">
                    <input type="checkbox" name="survey" id="survey" value="{{ (isset($condition['id']) && $condition['id'] != 0) ? $condition->survey : ''}}" {{ isset($condition) && $condition->survey == 1 ? 'checked' : '' }}>
                </div>
            </div>
            @if ($check_schedule_elearning)
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label for="online_activity">Hoàn thành buổi học elearning</label>
                </div>
                <div class="col-md-9">
                    <input type="checkbox" name="online_activity" id="online_activity" value="{{ (isset($condition['id']) && $condition['id'] != 0) ? $condition->online_activity : ''}}" {{ isset($condition) && $condition->online_activity == 1 ? 'checked' : '' }}>
                </div>
            </div>
            @endif

            <div class="form-group row">
                <div class="col-md-3">
                </div>
                <div class="col-md-9">
                    @if($model->lock_course == 0)
                    <button type="submit" class="btn"><i class="fa fa-save"></i> &nbsp;{{ trans('labutton.save') }}</button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</form>
@if (($offline_result))
    <form action="{{ route('module.offline.update_result_by_condition', ['id' => $model->id]) }}" method="post" class="form-ajax">
        <button type="submit" class="btn">Cập nhật lại kết quả</button>
    </form>
@endif
<script>
    $('#survey').on('click', function() {
        if($(this).is(':checked')) {
            $("#survey").val(1);
        }
        else {
            $("#survey").val(0);
        }
    });
    $('#online_activity').on('click', function() {
        if($(this).is(':checked')) {
            $("#online_activity").val(1);
        }
        else {
            $("#online_activity").val(0);
        }
    });
    $('#certificate').on('click', function() {
        if($(this).is(':checked')) {
            $("#certificate").val(1);
        }
        else {
            $("#certificate").val(0);
        }
    });
</script>
