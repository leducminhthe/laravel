<div class="modal fade" id="modal-schedule" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content py-3 px-1">
            <form id="form-schedule" action="{{ route('backend.category.training_teacher.save_register_schedule', ['courseId' => $courseId]) }}" method="post" class="form-ajax" data-success="submit_success_schedule">
                <input type="hidden" name="schedule_parent_id" value="{{ $schedule_parent_id }}">
                <div class="row m-0">
                    <div class="col-md-4">
                        <label>{{ trans('latraining.start_time') }}</label>
                        <input type="text" name="schedule_parent_start_time" class="form-control" autocomplete="off" value="{{ $start_time }}" readonly>
                    </div>
                    <div class="col-md-4">
                        <label>{{ trans('latraining.end_time') }}</label>
                        <input type="text" name="schedule_parent_end_time" class="form-control" autocomplete="off"  value="{{ $end_time }}" readonly>
                    </div>
                    <div class="col-md-4">
                        <label>{{ trans('latraining.lesson_date') }}</label>
                        <input name="lesson_date" type="text" class="form-control" autocomplete="off" value="{{ $lesson_date }}" readonly>
                    </div>
                </div>
                <br>
                @if (!$trainingTeacherRegister->isEmpty())
                    <div class="wrraped_time_register mb-3">
                        @foreach ($trainingTeacherRegister as $key => $item)
                            <input type="hidden" name="id" value="{{ $item->id }}">
                            <div class="form-group row mx-0 form_add_time item_{{ $item->id }} form_add_{{ $key + 1 }}">
                                <div class="col-md-2 pr-0 control-label">
                                    <label>{{ trans('latraining.registration_class_time') }} <span class="text-danger">*</span></label>
                                </div>
                                <div class="col-md-4 p-1 d_flex_align">
                                    <span><input name="start_time[]" type="text" required class="form-control timepicker d-inline-block" placeholder="{{ trans('latraining.start_time') }}" autocomplete="off" value="{{ $item->start_time }}"></span>
                                    <span class="fa fa-arrow-right mx-1" style="padding: 0 10px;"></span>
                                    <span><input name="end_time[]" type="text" required class="form-control timepicker d-inline-block" placeholder="{{ trans('latraining.end_time') }}" autocomplete="off" value="{{ $item->end_time }}"></span>
                                </div>
                                <div class="col-md-5 p-1">
                                    <textarea readonly class="w-100 form-control" name="" id="" rows="1">{{ $item->note }}</textarea>
                                </div>
                                @if ($item->approve == 1)
                                    <div class="col-md-1 text-center icon_check_approve">
                                        <img src="{{ asset('images/approve_daily.png') }}" alt="" width="20px">
                                    </div>
                                @else
                                    <div class="col-md-1 text-center icon_check_approve">
                                        <img src="{{ asset('images/deny_daily.png') }}" alt="" width="20px">
                                    </div>
                                @endif
                                {{-- @if (($key + 1) == 2)
                                    <div class="col-md-1 text-center">
                                        <div class="delete_time cursor_pointer" onclick="deleteTimeAjax({{ $item->id }})">
                                            <i class="fas fa-trash"></i>
                                        </div>
                                    </div>
                                @endif --}}
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="wrraped_time_register mb-3">
                        <div class="form-group row mx-0 form_add_time">
                            <div class="col-md-2 pr-0 control-label">
                                <label>{{ trans('latraining.registration_class_time') }} <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-4 p-1 d_flex_align">
                                <span><input name="start_time[]" type="text" required class="form-control timepicker d-inline-block" placeholder="{{ trans('latraining.start_time') }}" autocomplete="off" value=""></span>
                                <span class="fa fa-arrow-right mx-1" style="padding: 0 10px;"></span>
                                <span><input name="end_time[]" type="text" required class="form-control timepicker d-inline-block" placeholder="{{ trans('latraining.end_time') }}" autocomplete="off" value=""></span>
                            </div>
                            <div class="col-md-5 p-1"></div>
                        </div>
                    </div>
                @endif
                <div class="modal-footer pb-0">
                    <button type="button" class="btn" onclick="addMoreTimeRegister()">{{ trans('latraining.more_time_register') }}</button>
                    <button type="submit" class="btn" id="btn-save-copy">{{trans('labutton.save')}}</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
    $('.timepicker').datetimepicker({
        locale:'vi',
        format: 'HH:mm'
    });

    function addMoreTimeRegister() {
        var count = $('.form_add_time').length + 1;
        var html = `<div class="form-group row mx-0 form_add_time form_add_`+ count +`">
                        <div class="col-md-2 pr-0 control-label">
                            <label>{{ trans('latraining.registration_class_time') }} <span class="text-danger">*</span></label>
                        </div>
                        <div class="col-md-4 p-1 d_flex_align">
                            <span><input name="start_time[]" type="text" required class="form-control timepicker d-inline-block" placeholder="{{ trans('latraining.start_time') }}" autocomplete="off" value=""></span>
                            <span class="fa fa-arrow-right mx-1" style="padding: 0 10px;"></span>
                            <span><input name="end_time[]" type="text" required class="form-control timepicker d-inline-block" placeholder="{{ trans('latraining.end_time') }}" autocomplete="off" value=""></span>
                        </div>
                        <div class="col-md-5 p-1"></div>
                        <div class="col-md-1 text-center">
                            <div class="delete_time cursor_pointer" onclick="deleteTime(`+ count +`)">
                                <i class="fas fa-trash"></i>
                            </div>
                        </div>
                    </div>`;
        $('.wrraped_time_register').append(html);
        $('.timepicker').datetimepicker({
            locale:'vi',
            format: 'HH:mm'
        });
    }

    function deleteTime(count) {
        $('.form_add_'+ count).remove();
    }

    function submit_success_schedule(form) {
        $("#modal-schedule").modal('hide');
    }

    function deleteTimeAjax(id) {
        Swal.fire({
            title: '',
            text: '{{ trans("laother.want_to_delete") }} ?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: '{{ trans("laother.agree") }}!',
            cancelButtonText: '{{ trans("lacore.cancel") }}!',
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    type: "POST",
                    url: '{{ route('backend.category.training_teacher.delete_register_schedule', ['courseId' => $courseId]) }}',
                    dataType: 'json',
                    data: {
                        'id': id
                    },
                    success: function (result) {
                        $('.item_' + id).remove();
                        return false;
                    }
                });
            }
        });
    }
</script>
