<div class="row mt-3">
    <div class="col-md-12 course-content">
        <form action="" method="post" id="form-activity">
            <div id="accordion">
                <div class="card card_1">
                    <div class="card-header" id="heading_1">
                        <div class="mb-0 row">
                            <div class="col-md-9">
                                <h4>{{trans('latraining.activiti')}}</h4>
                            </div>
                            <div class="pl-2 pr-2 col-md-3 pull-right">
                            @if($permission_save && $course->lock_course == 0)
                                <a href="javascript:void(0)" class="float-right load-modal mr-2 cursor_pointer"
                                    data-url="{{ route('module.offline.modal_add_activity', [$course->id]) }}"
                                >
                                    <i class="fa fa-plus-circle"></i> {{trans('labutton.add_activities')}}
                                </a>
                            @endif
                            </div>
                        </div>
                    </div>
                    <div id="collapse_1" class="collapse show" aria-labelledby="heading_1">
                        <div class="card-body card_body_lesson_1">
                            <span class="ml-2 load_change_lesson"><i class="fa fa-spinner fa-spin"></i></span>
                            <ul class="section img-text yui3-dd-drop droptrue ul_activity_1" id="sortable_1" data-id="1">
                                @if(isset($activitieOnlines))
                                    @foreach($activitieOnlines as $activityOnline)
                                        <li class="my-2 li_activity activity_{{ $activityOnline->id }}" data-id="{{ $activityOnline->id }}">
                                            <div class="row">
                                                <input type="hidden" name="num_order[]" class="num-order" value="{{ $activityOnline->id }}">
                                                <div class="col-md-7 d-flex" title="{{ $activityOnline->activity_name .': '. $activityOnline->name }}">
                                                    <span class="editing_move moodle-core-dragdrop-draghandle" title="Move resource" tabindex="0" data-draggroups="resource" role="button" data-sectionreturn="0">
                                                        <i class="icon fas fa-arrows fa-fw  iconsmall" aria-hidden="true"></i>
                                                    </span>
                                                    <img src="{{ $activityOnline->icon }}" style="width: 24px; height: 24px;" class="iconlarge activityicon" role="presentation" aria-hidden="true">
                                                    <input type="text" class="instancename form-control ml-1 activity_name_{{ $activityOnline->id }}" onblur="editName({{ $activityOnline->id }})" value="{{ $activityOnline->name }}">
                                                </div>

                                                <div class="col-md-5 pt-1">
                                                    @if($permission_save)
                                                        <a href="javascript:void(0)"
                                                            class="editing-update menu-action cm-edit-action"
                                                            data-action="update" role="menuitem"
                                                            aria-labelledby="actionmenuaction-9"
                                                            data-id="{{ $activityOnline->id }}"
                                                            data-activity-code="{{ $activityOnline->activity_code }}"
                                                            data-subject="{{ $activityOnline->subject_id }}">
                                                            <i class="fas fa-cog fa-fw text-primary" aria-hidden="true"></i>
                                                            <span class="menu-action-text"> {{trans('latraining.setting')}}</span>
                                                        </a>
                                                        @if($course->lock_course == 0)
                                                            <a href="javascript:void(0)"
                                                                class="editing-delete menu-action cm-edit-action ml-2"
                                                                role="menuitem"
                                                                data-activity="{{ $activityOnline->id }}">
                                                                <i class="fa fa-trash fa-fw text-primary" aria-hidden="true"></i>
                                                                <span class="menu-action-text"> {{trans('latraining.delete')}} </span>
                                                            </a>

                                                            <a href="javascript:void(0)" class="editing-status menu-action cm-edit-action ml-2" role="menuitem"
                                                                data-activity="{{ $activityOnline->id }}" data-status="{{ $activityOnline->status == 1 ? 0 : 1 }}">
                                                                @if ($activityOnline->status == 1)
                                                                    <i class="fas fa-eye-slash text-primary" aria-hidden="true"></i>
                                                                    <span class="menu-action-text" > {{trans('latraining.hide')}} </span>
                                                                @else
                                                                    <i class="fas fa-eye text-primary" aria-hidden="true"></i>
                                                                    <span class="menu-action-text" > {{trans('latraining.show')}} </span>
                                                                @endif
                                                            </a>
                                                        @endif
                                                    @endif
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="card card_2 mt-2">
                    <div class="card-header" id="heading_2">
                        <div class="mb-0 row">
                            <div class="col-md-9">
                                <h4>{{trans('backend.activiti')}} Miscrosoft Teams</h4>
                            </div>
                            <div class="pl-2 pr-2 col-md-3 pull-right">
                            {{--@if($permission_save && $course->lock_course == 0)
                                <a class="float-right mr-2 cursor_pointer" onclick="addActivity(2)">
                                    <i class="fa fa-plus-circle"></i>
                                    {{trans('labutton.add_activities')}}
                                </a>
                            @endif--}}
                            </div>
                        </div>
                    </div>
                    <div id="collapse_2" class="collapse show" aria-labelledby="heading_2">
                        <div class="card-body card_body_lesson_1">
                            <span class="ml-2 load_change_lesson"><i class="fa fa-spinner fa-spin"></i></span>
                            <ul class="section img-text yui3-dd-drop droptrue ul_activity_1" id="sortable_2" data-id="1">
                                @if(isset($activitieTeams))
                                    @foreach($activitieTeams as $activityTeam)
                                        <li class="my-2 li_activity activity_{{ $activityTeam->id }}" data-id="{{ $activityTeam->id }}">
                                            <div class="row">
                                                <input type="hidden" name="num_order[]" class="num-order" value="{{ $activityTeam->id }}">
                                                <div class="col-md-7 d-flex" title="{{ $activityTeam->activity_name .': '. $activityTeam->name }}">
                                                    <span class="editing_move moodle-core-dragdrop-draghandle" title="Move resource" tabindex="0" data-draggroups="resource" role="button" data-sectionreturn="0">
                                                        <i class="icon fas fa-arrows fa-fw  iconsmall" aria-hidden="true"></i>
                                                    </span>
                                                    <input type="text" class="instancename form-control ml-1 activity_name_{{ $activityTeam->id }}" onblur="editName({{ $activityTeam->id }})" value="{{ $activityTeam->name }}">
                                                </div>

                                                <div class="col-md-5 pt-1">
                                                    @if($permission_save)
                                                        <a href="javascript:void(0)"
                                                            class="editing-update menu-action cm-edit-action"
                                                            data-action="update" role="menuitem"
                                                            aria-labelledby="actionmenuaction-9"
                                                            data-id="{{ $activityTeam->id }}"
                                                            data-activity-code="teams"
                                                            data-subject="{{ $activityTeam->subject_id }}">
                                                            <i class="fas fa-cog fa-fw text-primary" aria-hidden="true"></i>
                                                            <span class="menu-action-text"> {{trans('latraining.setting')}}</span>
                                                        </a>

                                                        @if($course->lock_course == 0)
                                                            <a href="javascript:void(0)"
                                                                class="editing-delete menu-action cm-edit-action ml-2"
                                                                role="menuitem"
                                                                data-activity="{{ $activityTeam->id }}">
                                                                <i class="fa fa-trash fa-fw text-primary" aria-hidden="true"></i>
                                                                <span class="menu-action-text"> {{trans('latraining.delete')}} </span>
                                                            </a>

                                                            <a href="javascript:void(0)" class="editing-status menu-action cm-edit-action ml-2" role="menuitem"
                                                                data-activity="{{ $activityTeam->id }}" data-status="{{ $activityTeam->status == 1 ? 0 : 1 }}">
                                                                @if ($activityTeam->status == 1)
                                                                    <i class="fas fa-eye-slash text-primary" aria-hidden="true"></i>
                                                                    <span class="menu-action-text" > {{trans('latraining.hide')}} </span>
                                                                @else
                                                                    <i class="fas fa-eye text-primary" aria-hidden="true"></i>
                                                                    <span class="menu-action-text"> {{trans('latraining.show')}} </span>
                                                                @endif
                                                            </a>
                                                            @if($activityTeam->activity_id==2)
                                                                @php
                                                                    $activity_data = $activity_teams($activityTeam->subject_id);
                                                                @endphp
                                                                <a href="{{route('module.offline.activity.report_teams',['course_id'=>$course->id,'class_id'=>(int)$activity_data->class_id,'schedule_id'=>(int)$activity_data->schedule_id])}}" class="report_teams menu-action ml-2" role="menuitem">
                                                                    <i class="far fa-chart-bar" aria-hidden="true"></i>
                                                                    <span class="menu-action-text">Report</span>
                                                                </a>
                                                            @endif
                                                        @endif
                                                    @endif
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script type="text/javascript">
    $(function () {
        $(".droptrue").on('click', '.editing-update', function () {
            let id = $(this).data('id');
            let activity_code = $(this).data('activity-code');
            let subject = $(this).data('subject');

            $.ajax({
                type: 'POST',
                url: '{{ route('module.offline.modal_activity', [$course->id]) }}',
                dataType: 'html',
                data: {
                    'id': id,
                    'activity': activity_code,
                    'subject_id': subject
                }
            }).done(function(data) {
                $("#app-modal").html(data);
                $("#app-modal #myModal").modal();
                return false;
            }).fail(function(data) {
                return false;
            });
        });

        $(".droptrue").on('click', '.editing-delete', function () {
            let item = $(this);
            let id = item.data('activity');
            Swal.fire({
                title: '',
                text: 'Bạn có chắc muốn xóa hoạt động này?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '{{ trans("laother.agree") }}!',
                cancelButtonText: '{{ trans("labutton.cancel") }}!',
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        type: "POST",
                        url: "{{ route('module.offline.activity.remove', [$course->id]) }}",
                        dataType: 'json',
                        data: {
                            'id': id
                        },
                        success: function (result) {
                            if (result.status === "success") {
                                item.closest('li').remove();
                                update_num_order();
                            }
                            if (result.status === "error") {
                                show_message(result.message, 'error');
                                return false;
                            }
                        }
                    });
                }
            });
        });

        $(".droptrue").on('click', '.editing-status', function () {
            let item = $(this);
            let id = item.data('activity');
            let status = item.data('status');
            $.ajax({
                type: 'POST',
                url: '{{ route('module.offline.activity.update_status_activity', [$course->id]) }}',
                dataType: 'html',
                data: {
                    'id': id,
                    'status': status
                }
            }).done(function(data) {
                window.location = '';
                return false;
            }).fail(function(data) {
                return false;
            });
        });
    });

    // CHỈNH SỬA TÊN HOẠT ĐỘNG TRỰC TIẾP
    function editName(id) {
        var name = $('.activity_name_'+id).val();
        $.ajax({
            type: 'POST',
            url: '{{ route('module.offline.activity.edit_name', [$course->id]) }}',
            dataType: 'json',
            data: {
                'id': id,
                'name': name,
            }
        }).done(function (data) {
            return false;
        }).fail(function (data) {
            return false;
        });
    }

    // XÓA BÀI HỌC
    function deleteLesson(id) {
        $.ajax({
            type: 'POST',
            url: '{{ route('module.offline.remove_lesson') }}',
            dataType: 'json',
            data: {
                'id': id,
            }
        }).done(function (data) {
            if (data.status == "success") {
                $('.card_'+id).remove();
            }
            show_message(data.message, data.status);
            return false;
        }).fail(function (data) {
            return false;
        });
    }

    function addActivity(type) {
        if(type == 1) {
            var activity = 'online';
        } else {
            var activity = 'teams';
        }
        $.ajax({
            type: 'POST',
            url: '{{ route('module.offline.modal_activity', [$course->id]) }}',
            dataType: 'html',
            data: {
                'activity': activity
            }
        }).done(function(data) {
            $("#app-modal").html(data);
            $("#app-modal #myModal").modal();

            return false;
        }).fail(function(data) {
            show_message('{{ trans('laother.data_error') }}', 'error');
            return false;
        });
    }
</script>
