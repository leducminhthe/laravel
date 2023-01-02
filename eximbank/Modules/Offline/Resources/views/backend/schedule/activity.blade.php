@extends('layouts.backend')

@section('page_title', trans('latraining.activiti'))

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lamenu.training_organizations'),
                'url' => ''
            ],
            [
                'name' => trans('lamenu.offline_course'),
                'url' => route('module.offline.management')
            ],
            [
                'name' => $course->name,
                'url' => route('module.offline.edit', ['id' => $course->id])
            ],
            /*[
                'name' => trans('latraining.classroom'),
                'url' => route('module.offline.class', ['id' => $course->id]),
                'drop-menu'=>$classArray
            ],*/
            [
                'name' => trans('latraining.schedule').": ".$class->name,
                'url' => route("module.offline.schedule", [$course->id, $class->id])
            ],
            [
                'name' => trans('latraining.activiti').": Buổi ".$schedule->session,
                'url' => ''
            ],
        ]
    @endphp
    @include('layouts.backend.menu_breadcum',$breadcum)
@endsection

@section('content')
<div class="row mt-3">
    <div class="col-12 text-right mb-2">
        <form method="post" action="{{ route('module.offline.save_lesson', ['course_id' => $course->id, 'class_id' => $class->id, 'schedule_id' => $schedule]) }}" class="form-ajax" id="form-lesson" data-success="submit_success_object">
            @if($course->lock_course == 0 && $check_save)
                <button type="submit" class="btn" data-must-checked="false">
                    <i class="fa fa-plus-circle"></i> Thêm chủ đề
                </button>
            @endif
        </form>
    </div>
    <div class="col-md-12 course-content">
        <form action="" method="post" id="form-activity">
            <div id="accordion">
                @foreach ($lessons as $item)
                    <div class="card card_{{ $item->id }}">
                        <div class="card-header" id="heading_{{ $item->id }}">
                            <div class="mb-0 row">
                                <div class="col-md-9">
                                    <span>{{ $item->lesson_name }}
                                        <i class="fa fa-pen edit-lesson-name cursor_pointer" data-id="{{ $item->id }}" data-name="{{ $item->lesson_name }}"></i>
                                    </span>
                                </div>
                                <div class="pl-2 pr-2 col-md-3 pull-right">
                                @if($course->lock_course == 0 && $check_save)
                                    <a href="#" class="delete_lesson float-right delete_lesson_{{ $item->id }}  mr-2" onclick="deleteLesson({{ $item->id }})">
                                        <i class="fa fa-trash"></i> {{trans('latraining.delete')}}
                                    </a>
                                    <a href="javascript:void(0)" class="float-right load-modal mr-2 cursor_pointer"
                                        data-lesson_id="{{ $item->id }}"
                                        data-url="{{ route('module.offline.modal_add_activity', [$course->id, 'class_id' => $class->id, 'schedule_id' => $schedule->id]) }}"
                                    >
                                        <i class="fa fa-plus-circle"></i> {{trans('labutton.add_activities')}}
                                    </a>
                                @endif
                                </div>
                            </div>
                        </div>
                        <div id="collapse_{{ $item->id }}" class="collapse show" aria-labelledby="heading_{{ $item->id }}">
                            <div class="card-body card_body_lesson_{{ $item->id }}">
                                <span class="ml-2 load_change_lesson"><i class="fa fa-spinner fa-spin"></i></span>
                                <ul class="section img-text yui3-dd-drop droptrue ul_activity_{{ $item->id }}" id="sortable_{{ $item->id }}" data-id="{{ $item->id }}">
                                    @if(isset($item->activities))
                                        @foreach($item->activities as $activityOnline)
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
                                                        <input type="checkbox"
                                                            id="activity-{{ $activityOnline->id }}"
                                                            title="{{ trans('backend.conditions') }}"
                                                            value="{{ $activityOnline->id }}"
                                                            class="activity_complete"
                                                            @if (in_array($activityOnline->id, $conditions)) checked @endif
                                                            @if($course->lock_course == 0 && $check_save) onclick="activityComplete({{ $activityOnline->id }})" @endif
                                                        >
                                                        <label class="" for="activity-{{ $activityOnline->id }}">{{ trans('backend.complete_act') }}</label>

                                                        <a href="javascript:void(0)"
                                                            class="editing-update menu-action cm-edit-action"
                                                            data-action="update" role="menuitem"
                                                            aria-labelledby="actionmenuaction-9"
                                                            data-id="{{ $activityOnline->id }}"
                                                            data-activity-code="{{ $activityOnline->activity_code }}"
                                                            data-subject="{{ $activityOnline->subject_id }}"
                                                            data-class_id="{{ $activityOnline->class_id }}"
                                                            data-schedule_id="{{ $activityOnline->schedule_id }}"
                                                            data-lesson_id="{{ $activityOnline->lesson_id }}"
                                                        >
                                                            <i class="fas fa-cog fa-fw text-primary" aria-hidden="true"></i>
                                                            <span class="menu-action-text"> {{trans('latraining.setting')}}</span>
                                                        </a>
                                                        @if($course->lock_course == 0 && $check_save)
                                                            <a href="javascript:void(0)"
                                                                class="editing-delete menu-action cm-edit-action ml-2"
                                                                role="menuitem"
                                                                data-activity="{{ $activityOnline->id }}"
                                                                data-class_id="{{ $activityOnline->class_id }}"
                                                                data-schedule_id="{{ $activityOnline->schedule_id }}"
                                                                data-lesson_id="{{ $activityOnline->lesson_id }}"
                                                            >
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
                                                    </div>
                                                </div>
                                            </li>
                                        @endforeach
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </form>
    </div>
</div>
<div class="modal fade modal-edit-lesson-name" id="modal-edit-lesson-name" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ trans('app.edit') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="id" value="" class="id-lesson">
                <input type="text" name="name" value="" class="form-control name-lesson">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn" id="update-lesson-name"><i class="fa fa-save"></i> {{ trans('labutton.save') }}</button>
                <button type="button" id="closed" class="btn" data-dismiss="modal"><i class="fa fa-times-circle"></i> {{ trans('labutton.close') }}</button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(function () {
        $(".droptrue").on('click', '.editing-update', function () {
            let id = $(this).data('id');
            let activity_code = $(this).data('activity-code');
            let subject = $(this).data('subject');
            let class_id = $(this).data('class_id');
            let schedule_id = $(this).data('schedule_id');
            let lesson_id = $(this).data('lesson_id');

            $.ajax({
                type: 'POST',
                url: '{{ route('module.offline.modal_activity', [$course->id]) }}',
                dataType: 'html',
                data: {
                    'id': id,
                    'activity': activity_code,
                    'subject_id': subject,
                    'class_id': class_id,
                    'schedule_id': schedule_id,
                    'lesson_id': lesson_id,
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
            let class_id = $(this).data('class_id');
            let schedule_id = $(this).data('schedule_id');
            let lesson_id = $(this).data('lesson_id');

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
                            'id': id,
                            'class_id': class_id,
                            'schedule_id': schedule_id,
                            'lesson_id': lesson_id,
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

    $('.edit-lesson-name').on('click', function(){
        var id = $(this).data('id');
        var name = $(this).data('name');
        $('#modal-edit-lesson-name .id-lesson').val(id);
        $('#modal-edit-lesson-name .name-lesson').val(name);

        let item = $(this);
        item.removeClass('fa fa-pen');
        item.addClass('fa fa-spinner fa-spin');
        item.prop("disabled", true);

        $('#modal-edit-lesson-name').modal();
    });

    $('#modal-edit-lesson-name #update-lesson-name').on('click', function(){
        var id = $('#modal-edit-lesson-name .id-lesson').val();
        var name = $('#modal-edit-lesson-name .name-lesson').val();

        let item = $(this);
        let oldtext = item.html();
        item.html('<i class="fa fa-spinner fa-spin"></i> Lưu');
        item.prop("disabled", true);

        $.ajax({
            type: 'POST',
            url: '{{ route('module.offline.activity.edit_lesson_name', [$course->id]) }}',
            dataType: 'json',
            data: {
                'id': id,
                'name': name,
            }
        }).done(function (data) {

            item.html(oldtext);
            item.prop("disabled", false);
            show_message('Cập nhật thành công', 'success');

            window.location = '';

            return false;
        }).fail(function (data) {

            item.html(oldtext);
            item.prop("disabled", false);
            show_message('Lỗi dữ liệu', 'error');

            return false;
        });
    });

    // Tick hoàn thành hoạt động
    function activityComplete(id) {
        $.ajax({
            type: 'POST',
            url: '{{ route('module.offline.activity.update_condition_activity', [$course->id, 'class_id' => $class->id, 'schedule_id' => $schedule->id]) }}',
            dataType: 'json',
            data: {
                'id': id,
            }
        }).done(function (data) {
            return false;
        }).fail(function (data) {
            return false;
        });
    }

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
        let item = $('.delete_lesson_'+id);
        let oldtext = item.html();
        item.html('<i class="fa fa-spinner fa-spin"></i> Xoá');
        item.prop("disabled", true);

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
@endsection
