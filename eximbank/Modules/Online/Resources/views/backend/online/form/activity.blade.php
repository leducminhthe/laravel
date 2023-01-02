@php
    $tab = Request::segment(3);
    $type = $tab == 'course-for-offline' ? 1 : 0;
@endphp
<div role="main">
    <div class="row wrapped_activity m-0">
        <div class="pl-0 col-md-3 text-center image_activity">
            {{--  <div class="control-label">
                <label>{{ trans('latraining.image_activity') }}</label>
            </div>
            <div class="choose_image" @if($model->lock_course == 0) id="select_image_activity" @endif>
                @if($model->image_activity)
                    <img src="{{ image_file($model->image_activity) }}" alt="" width="100px" height="100px">
                @else
                    {{ trans('latraining.choose_file') }}
                @endif
            </div>  --}}
            {{-- <input name="image_activity" id="image-activity-select" type="text" class="d-none" value="{{ $model->image_activity }}"> --}}
        </div>
        {{--  <div class="col-md-9 p-0">
            @if($permission_save)
                <form method="post" action="{{ route('module.online.save_lesson', ['course_id' => $model->id, 'type' => $type]) }}" class="form-ajax" id="form-lesson" data-success="submit_success_object">
                    <div class="box-title">
                        <div class="form-group row mt-2">
                            <div class="col-sm-2 control-label">
                                <label>{{ trans('latraining.lesson_name') }}</label><span style="color:red"> * </span>
                            </div>
                            <div class="col-md-10">
                                <div class="row m-0">
                                    <div class="col-9 p-0">
                                        <input id="lesson_name" name="lesson_name" type="text" class="form-control" value="" required>
                                    </div>
                                    <div class="col-3 p-0 text-right">
                                        @if($model->lock_course == 0)
                                            <button type="submit" class="btn" data-must-checked="false"><i class="fa fa-save"></i> &nbsp;{{ trans('labutton.add_lesson') }}</button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            @endif
        </div>  --}}
    </div>
</div>
<div class="row">
    {{--<div class="col-5 wrraped_search_activity">
        <div class="search_activity">
            <input id="search_acitivty" name="search_acitivty" type="text" class="form-control" value="" placeholder="Tìm kiếm hoạt động">
            <button type="button" id="button_search" class="btn" onclick="searchActivity()"><i class="fas fa-search"></i></button>
        </div>
    </div>--}}
    <div class="col-12 text-right">
        @if($permission_save)
            <form method="post" action="{{ route('module.online.save_lesson', ['course_id' => $model->id, 'type' => $type]) }}" class="form-ajax" id="form-lesson" data-success="submit_success_object">
                @if($model->lock_course == 0)
                    <button type="submit" class="btn" data-must-checked="false">
                        <i class="fa fa-plus-circle"></i> Thêm chủ đề
                    </button>
                @endif
            </form>
        @endif
    </div>
</div>
<div class="row mt-3">
    <div class="col-md-12 course-content">
        <form action="" method="post" id="form-activity">
            <div id="accordion">
                @foreach ($lesson as $key => $item)
                    <div class="card card_{{ $item->id }}">
                        <div class="card-header" id="heading_{{ $item->id }}">
                            <div class="mb-0 row">
                                <div class="col-md-9 d-flex align-items-center">
                                    <span>{{ $item->lesson_name }}
                                        <i class="fa fa-pen edit-lesson-name cursor_pointer" data-id="{{ $item->id }}" data-name="{{ $item->lesson_name }}"></i>
                                    </span>
                                </div>
                                <div class="pl-2 pr-2 col-md-3 pull-right">
                                @if($permission_save && $model->lock_course == 0)
                                    <a href="#" class="delete_lesson float-right delete_lesson_{{ $item->id }}  mr-2" onclick="deleteLesson({{ $item->id }})">
                                        <i class="fa fa-trash"></i> {{trans('latraining.delete')}}
                                    </a>
                                    <a href="javascript:void(0)" class="float-right load-modal mr-2"
                                        data-lesson_id="{{ $item->id }}"
                                        data-url="{{ route('module.online.modal_add_activity', ['id' => $model->id, 'type' => $type]) }}"
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
                                        @foreach($item->activities as $keyActivity => $activity)
                                            @php
                                                $check_history = \Modules\Online\Entities\OnlineCourseActivityHistory::where('course_id', '=', $model->id)->where('course_activity_id', '=', $activity->id)->first();
                                            @endphp
                                            <li class="my-2 li_activity activity_{{ $activity->id }}" data-id="{{ $activity->id }}">
                                                <div class="row">
                                                    <input type="hidden" name="num_order[]" class="num-order" value="{{ $activity->id }}">
                                                    <div class="col-md-7 d-flex" title="{{ $activity->activity_name .': '. $activity->name }}">
                                                        <span class="editing_move moodle-core-dragdrop-draghandle" title="Move resource" tabindex="0" data-draggroups="resource" role="button" data-sectionreturn="0">
                                                            <i class="icon fas fa-arrows fa-fw  iconsmall" aria-hidden="true"></i>
                                                        </span>
                                                        <img src="{{ $activity->icon }}" class="iconlarge activityicon" role="presentation" aria-hidden="true">
                                                        <input type="text" class="instancename form-control ml-1 activity_name_{{ $activity->id }}" onblur="editName({{ $activity->id }})" value="{{ $activity->name }}">
                                                    </div>

                                                    <div class="col-md-5 pt-1">
                                                        @if($permission_save)
                                                            <input type="hidden" id="edit_activity_{{ $activity->id }}" value="{{ $item->id }}">
                                                            <a href="javascript:void(0)"
                                                                class="editing-update menu-action cm-edit-action"
                                                                data-action="update" role="menuitem"
                                                                aria-labelledby="actionmenuaction-9"
                                                                data-id="{{ $activity->id }}"
                                                                data-activity-code="{{ $activity->activity_code }}"
                                                                data-subject="{{ $activity->subject_id }}"
                                                            >
                                                                <i class="fas fa-cog fa-fw text-primary" aria-hidden="true"></i>
                                                                <span class="menu-action-text"> {{trans('latraining.setting')}}</span>
                                                            </a>
                                                            @if($model->lock_course == 0)
                                                            <a href="javascript:void(0)"
                                                                class="editing-delete menu-action cm-edit-action ml-2"
                                                                role="menuitem"
                                                                aria-labelledby="actionmenuaction-15"
                                                                data-activity="{{ $activity->id }}">
                                                                <i class="fa fa-trash fa-fw text-primary" aria-hidden="true"></i>
                                                                <span class="menu-action-text" id="actionmenuaction-{{ $keyActivity }}"> {{trans('latraining.delete')}} </span>
                                                            </a>

                                                            <a href="javascript:void(0)" class="editing-status menu-action cm-edit-action ml-2" role="menuitem"
                                                                aria-labelledby="actionmenuaction-15" data-activity="{{ $activity->id }}" data-status="{{ $activity->status == 1 ? 0 : 1 }}">
                                                                @if ($activity->status == 1)
                                                                    <i class="fas fa-eye-slash text-primary" aria-hidden="true"></i><span class="menu-action-text" id="actionmenuaction-{{ $keyActivity }}"> {{trans('latraining.hide')}} </span>
                                                                @else
                                                                    <i class="fas fa-eye text-primary" aria-hidden="true"></i><span class="menu-action-text" id="actionmenuaction-{{ $keyActivity }}"> {{trans('latraining.show')}} </span>
                                                                @endif
                                                            </a>
                                                            @endif
                                                        @endif
                                                        @if(!in_array($activity->activity_id, [2,8]))
                                                            @php
                                                                $url = $activity->activity_id == 9? $zoomLink($activity->subject_id) :route('module.online.goactivity', ['id' => $model->id, 'aid' => $activity->id, 'lesson' => $activity->lesson_id])
                                                            @endphp
                                                            <a target="_blank" href="{{$url}}" class="menu-action cm-edit-action ml-2" role="menuitem" aria-labelledby="actionmenuaction-15" data-turbolinks="false">
                                                                <i class="fa fa-eye fa-fw text-primary" aria-hidden="true"></i>
                                                                <span class="menu-action-text" id="actionmenuaction-15"> {{ trans("laother.preview") }}</span>
                                                            </a>
                                                        @endif
                                                        @if($activity->activity_id == 1)
                                                            <a  href="{{route('module.online.scorm.report',['id'=>$model->id,'sid'=>$activity->subject_id])}}" class="menu-action cm-edit-action ml-2" role="menuitem" aria-labelledby="actionmenuaction-15" data-turbolinks="false">
                                                                <i class="fa fa-bar-chart text-primary" aria-hidden="true"></i>
                                                                <span class="menu-action-text" id="actionmenuaction-15"> {{ trans('latraining.report') }}</span>
                                                            </a>
                                                        @endif
                                                        @if($activity->activity_id == 8)
                                                            <a href="{{ route('module.online.survey.report', ['id' => $model->id, 'activityId' => $activity->id]) }}" class="menu-action cm-edit-action ml-2" role="menuitem" aria-labelledby="actionmenuaction-15" data-turbolinks="false">
                                                                <i class="fa fa-bar-chart text-primary" aria-hidden="true"></i>
                                                                <span class="menu-action-text" id="actionmenuaction-15"> {{ trans('latraining.report') }}</span>
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
            let lessonId = $('#edit_activity_' + id).val();
            $.ajax({
                type: 'POST',
                url: '{{ route('module.online.modal_activity', ['id' => $model->id]) }}',
                dataType: 'html',
                data: {
                    'id': id,
                    'activity': activity_code,
                    'subject_id': subject,
                    'lessonId': lessonId,
                    'edit': 1,
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
                        url: "{{ route('module.online.activity.remove', ['id' => $model->id]) }}",
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
                url: '{{ route('module.online.activity.update_status_activity', ['id' => $model->id]) }}',
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

        $(".droptrue").sortable({
            connectWith: "ul",
            update: function (event, ui) {
                update_num_order(ui.item[0].classList[2]);
                update_lesson(ui.item[0].classList[2])
            }
        }).disableSelection();

        function update_num_order(name_activity_id) {
            let id = $('.' + name_activity_id).data('id');
            var parent_id = $('.' + name_activity_id).parent().data('id');
            let qcount = $("input[name='num_order[]']").length;
            if (qcount <= 0) {
                return false;
            }

            $.ajax({
                type: 'POST',
                url: '{{ route('module.online.activity.update_numorder', ['id' => $model->id]) }}',
                dataType: 'json',
                data: $("#form-activity").serialize(),
            }).done(function (data) {
                if (data.status !== "success") {
                    show_message('Không thể cập nhật thứ tự', 'error');
                    return false;
                }
                return false;
            }).fail(function (data) {
                return false;
            });
        }

        // ĐỔI HOẠT ĐỘNG SANG BÀI HỌC KHÁC
        function update_lesson(name_activity_id) {
            let id = $('.' + name_activity_id).data('id');
            var lesson_id = $('.' + name_activity_id).parent().data('id');
            $('.ul_activity_' + lesson_id).addClass('opacity_activity');
            $('.card_body_lesson_' + lesson_id).find('.load_change_lesson').show();
            $.ajax({
                type: 'POST',
                url: '{{ route('module.online.activity.update_lesson', ['id' => $model->id]) }}',
                dataType: 'json',
                data: {
                    'id': id,
                    'lesson_id': lesson_id,
                },
            }).done(function (data) {
                if (data.status !== "success") {
                    show_message('Không thể cấp nhật', 'error');
                } else {
                    $('.card_body_lesson_' + lesson_id).find('.load_change_lesson').hide();
                    $('.ul_activity_' + lesson_id).removeClass('opacity_activity');
                    $('#edit_activity_' + id).val(lesson_id)
                }
                return false;
            }).fail(function (data) {
                return false;
            });
        }
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
            url: '{{ route('module.online.activity.edit_lesson_name', ['id' => $model->id]) }}',
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

    function submit_success_object(form) {
        $("#form-lesson #lesson_name").val(null).trigger('change');
        table_lesson.refresh();
    }

    //Chỉnh sửa tên bài học
    function editLessonName(id) {
        var name = $('.lesson_name_'+id).val();
        $.ajax({
            type: 'POST',
            url: '{{ route('module.online.activity.edit_lesson_name', ['id' => $model->id]) }}',
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

    // CHỈNH SỬA TÊN HOẠT ĐỘNG TRỰC TIẾP
    function editName(id) {
        var name = $('.activity_name_'+id).val();
        $.ajax({
            type: 'POST',
            url: '{{ route('module.online.activity.edit_name', ['id' => $model->id]) }}',
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
            url: '{{ route('module.online.remove_lesson') }}',
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

    // TÌM KIẾM HOẠT ĐỘNG THEO BÀI HỌC
    function searchActivity() {
        let item = $('#button_search');
        let oldtext = item.html();
        item.html('<i class="fa fa-spinner fa-spin"></i>');
        var search = $('#search_acitivty').val()
        $.ajax({
            type: 'POST',
            url: '{{ route('module.online.activity.search', ['id' => $model->id]) }}',
            dataType: 'json',
            data: {
                'search': search,
            }
        }).done(function (data) {
            item.html(oldtext);
            $('.collapse').removeClass('show');
            $('.li_activity').hide();
            $.each(data.lesson_id, function (i, item){
                $('#collapse_' + item).addClass('show');
            });
            $.each(data.activity_id, function (i, item){
                $('.activity_' + item).show();
            });
            return false;
        }).fail(function (data) {
            return false;
        });
    }

    $("#select_image_activity").on('click', function () {
        var lfm = function (options, cb) {
            var route_prefix = '/filemanager';
            window.open(route_prefix + '?type=' + options.type || 'file', 'FileManager', 'width=1100,height=600');
            window.SetUrl = cb;
        };

        lfm({type: 'image'}, function (url, path) {
            $("#select_image_activity").html('<img src="'+ path +'" width="100px" height="100px">');
            saveImageActivity(path)
        });
    });

    function saveImageActivity(path) {
        $.ajax({
            type: 'POST',
            url: '{{ route('module.online.image_activity.save', ['id' => $model->id, 'type' => $type]) }}',
            dataType: 'json',
            data: {
                'image_activity': path,
            }
        }).done(function (data) {
            show_message(data.message, data.status);
            return false;
        }).fail(function (data) {
            return false;
        });
    }
</script>
