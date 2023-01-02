@php
    $tab = Request::segment(3);
    $type = $tab == 'course-for-offline' ? 1 : 0;
@endphp
<div class="row mt-3">
    <div class="col-md-12">
        @if (($online_result))
            <p class="text-danger h5">
                LƯU Ý: <br>
                - Khi khóa học đã tổ chức nếu thay đổi điều kiện hoàn thành sẽ ảnh hưởng đến số liệu kết quả hoàn thành khóa học của học viên. <br>
                - Bấm nút "Cập nhật lại kết quả" để chạy lại kết quả Học viên theo điều kiện thay đổi hiện tại (nếu cần).
            </p>
        @endif

        <form action="{{ route('module.online.save_condition', ['id' => $model->id]) }}" method="post" class="form-ajax" data-success="form_condition">
            <input type="hidden" name="type" value="{{ $type }}">
            <div class="row">
                <div class="col-md-8"></div>
                @if($permission_save && $model->lock_course == 0)
                    <div class="col-md-4 text-right">
                        <button type="submit" class="btn"><i class="fa fa-save"></i> &nbsp {{ trans('labutton.save') }}</button>
                    </div>
                @endif
            </div>
            <br>
            <table class="tDefault table table-hover text-nowrap" id="table-condition">
                <thead>
                    <tr>
                        <th class="text-center w-5">{{ trans('latraining.completed') }}</th>
                        <th class="text-center">{{ trans('latraining.activity') }}</th>
                        <th class="text-center w-5">{{ trans('latraining.score') }}</th>
                        <th class="text-center w-5">{{ trans('latraining.weight_percent') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($activities) && $activities)
                        @php
                            $include = explode(',', $condition->activity);
                        @endphp
                        @foreach($activities as $activity)
                            @php
                                $setting_percent = Modules\Online\Entities\OnlineCourseSettingPercent::query()
                                    ->where('course_id', '=', $model->id)
                                    ->where('course_activity_id', '=', $activity->id)
                                    ->first();

                                $activity->score = $setting_percent && !is_null($setting_percent->score) ? $setting_percent->score : '';
                                $activity->percent = $setting_percent && !is_null($setting_percent->percent) ? $setting_percent->percent : '';

                                $activity->disabled_acti = '';
                                if ($activity->activity_id == 1 || $activity->activity_id == 2 || $activity->activity_id == 7){
                                    $activity->disabled_acti = 'readonly';
                                }

                                $activity->checked = in_array($activity->id, $include) ? 'checked' : '';
                                $activity->disabled = !in_array($activity->id, $include) ? 'readonly' : '';
                            @endphp
                            <tr>
                                <th class="text-center w-5">
                                    <input name="activity[]" type="checkbox" class="activity" id="activity-{{ $activity->id }}" data-activity_id="{{ $activity->activity_id }}" value="{{ $activity->id }}" {{ $activity->checked }} >
                                </th>
                                <th>
                                    <img src="{{ $activity->icon }}" class="iconlarge activityicon" role="presentation" aria-hidden="true">
                                    <span class="instancename text-muted">
                                        {{ $activity->name . ($activity->status == 0 ? '('.trans('latraining.hided').')' : '') }}
                                    </span>
                                </th>
                                <th class="text-center w-5">
                                    <input name="score[{{ $activity->id }}]" class="form-control" id="score-{{ $activity->id }}" value="{{ $activity->score }}" {{ $activity->disabled_acti }} {{ $activity->disabled }} />
                                </th>
                                <th class="text-center w-5">
                                    <input name="percent[{{ $activity->id }}]" class="form-control" id="percent-{{ $activity->id }}" value="{{ $activity->percent }}" {{ $activity->disabled }} />
                                </th>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </form>
        {{--  <form method="post" action="" id="form-condition">
            <input type="hidden" name="type" value="{{ $type }}">
            <div class="mt-3"></div>
            @if(isset($activities) && $activities)
                @php
                    $include = explode(',', $condition->activity);
                @endphp
                @foreach($activities as $activity)

                    <div class="custom-control custom-checkbox">
                        <input name="activity[]" type="checkbox" class="custom-control-input" id="activity-{{ $activity->id }}" value="{{ $activity->id }}" @if(!$permission_save || $model->lock_course == 1) disabled @endif @if(in_array($activity->id, $include)) checked @endif>
                        <label class="custom-control-label" for="activity-{{ $activity->id }}">
                            <h5>
                                <img src="{{ $activity->icon }}" class="iconlarge activityicon" role="presentation" aria-hidden="true">
                                <span class="instancename">{{ trans('latraining.complete_act') }} <b>{{ $activity->name . ($activity->status == 0 ? '('.trans('latraining.hided').')' : '') }}</b></span>
                            </h5>
                        </label>
                    </div>
                @endforeach
            @endif
        </form>  --}}

        <p></p>
        @if (($online_result))
            <form action="{{ route('module.online.update_result_by_condition', ['id' => $model->id]) }}" method="post" class="form-ajax">
                <button type="submit" class="btn">Cập nhật lại kết quả</button>
            </form>
        @endif
    </div>

    <script type="text/javascript">
        $('#table-condition').on('click', '.activity', function(){
            var id = $(this).val();
            var activity_id = $(this).data('activity_id');

            if($('#activity-'+id).is(":checked")){
                if(activity_id == 1 || activity_id == 2 || activity_id == 7){
                    $('#percent-'+id).attr("readonly", false);
                }else{
                    $('#score-'+id).attr("readonly", false);
                    $('#percent-'+id).attr("readonly", false);

                    $('#score-'+id).val('100');
                }
            }else{
                if(activity_id == 1 || activity_id == 2 || activity_id == 7){
                    $('#percent-'+id).attr("readonly", true);
                    $('#percent-'+id).val('');
                }else{
                    $('#score-'+id).attr("readonly", true);
                    $('#percent-'+id).attr("readonly", true);

                    $('#score-'+id).val('');
                    $('#percent-'+id).val('');
                }
            }
        });

        $('#form-condition').on('change', '#grade_methor', function () {
            var grade_methor = $('#grade_methor option:selected').val();
            $.ajax({
                type: 'POST',
                url: '{{ route('module.online.save_condition', ['id' => $model->id]) }}',
                dataType: 'json',
                data: {
                    grade_methor : grade_methor
                }
            }).done(function(data) {

                if (data.status !== "success") {
                    show_message('Không thể lưu cài đặt', 'error');
                    return false;
                }

                return false;
            }).fail(function(data) {
                return false;
            });
        });

        $("#form-condition input").on('change', function () {
            $.ajax({
                type: 'POST',
                url: '{{ route('module.online.save_condition', ['id' => $model->id]) }}',
                dataType: 'json',
                data: $("#form-condition").serialize()
            }).done(function(data) {

                if (data.status !== "success") {
                    show_message('Không thể lưu cài đặt', 'error');
                    return false;
                }

                return false;
            }).fail(function(data) {
                return false;
            });
        });
    </script>
</div>
