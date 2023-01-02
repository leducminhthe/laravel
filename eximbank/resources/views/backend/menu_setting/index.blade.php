@extends('layouts.backend')

@section('page_title', trans('laother.menu_setting'))

@section('header')
    <style>
        .tree{
            --spacing : 1.5rem;
        }
        .tree li{
            display      : block;
            position     : relative;
            padding-left : 20px;
            margin-left: 5px
        }
        .tree ul li{
            border-left : 2px solid #ddd;
        }
        .tree ul li:last-child{
            border-color : transparent;
        }
        .tree ul li::before{
            content: '';
            display: block;
            position: absolute;
            top: calc(var(--spacing) / -2);
            left: -2px;
            width: calc(var(--spacing) + -5px);
            height: calc(var(--spacing) + -3px);
            border: solid #ddd;
            border-width: 0 0 2px 2px;
        }
    </style>
@endsection

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('laother.menu_setting'),
                'url' => ''
            ],
        ]
    @endphp
    @include('layouts.backend.menu_breadcum',$breadcum)
@endsection

@section('content')
    <div role="main">
        <div class="row">
            <div class="col-md-8">
                <form class="form-inline form-search mb-3" id="form-search">
                    <input type="text" name="search" class="form-control" placeholder="{{trans('lasetting.enter_name_title_code')}}">
                    <button type="submit" class="btn"><i class="fa fa-search"></i>&nbsp;{{ trans('labutton.search') }}</button>
                </form>
            </div>
            <div class="col-md-4 text-right act-btns">
                <div class="pull-right">
                    <div class="btn-group">
                        <a onclick="modalSaveSetting(0)" class="btn cursor_pointer"><i class="fa fa-plus-circle"></i> {{ trans('labutton.add_new') }}</a>
                    </div>
                </div>
            </div>
        </div>
        <table class="tDefault table table-hover bootstrap-table" id="table-mail-template">
            <thead>
                <tr>
                    <th data-field="state" data-checkbox="true"></th>
                    <th data-field="name" data-sortable="true" data-width="25%">{{ trans('lasetting.name') }}</th>
                    <th data-field="" data-formatter="setting_formatter">{{ trans('latraining.action') }}</th>
                    <th data-field="" data-formatter="edit_formatter" data-align="center" data-width="5%">{{ trans('labutton.edit') }}</th>
                </tr>
            </thead>
        </table>
    </div>

    <div class="modal fade" id="modal-setting" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel1" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel1">{{ trans('laother.menu_setting') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body body_menu_setting">
                    <input type="hidden" name="id" id="id">
                    <input type="hidden" name="ids" id="ids">
                    <form action="" id="form_menu_setting">
                        <ul>
                            <li>
                                <input type="checkbox" id="menu_news" name="menu_news" value="menu_news">
                                <label for="menu_news">{{ trans('lamenu.training_news') }}</label>
                            </li>
                            <li class="tree">
                                <input type="hidden" class="wrapped_menu_2 input_value_check" value="0">
                                <input type="checkbox" id="menu_2" name="menu_2" value="menu_2" onclick="chooseMenuChild('menu_2')">
                                <label for="menu_2">{{ trans('lamenu.course') }}</label>
                                <ul class="menu_child" id="wrapped_menu_2">
                                    <li>
                                        <input type="checkbox" id="course_4" name="course_4" value="course_4">
                                        <label for="course_4">Khóa học đánh dấu</label>
                                    </li>
                                    <li>
                                        <input type="checkbox" id="course_3" name="course_3" value="course_3">
                                        <label for="course_3">{{ trans('latraining.my_course') }}</label>
                                    </li>
                                    <li>
                                        <input type="checkbox" id="course_1" name="course_1" value="course_1">
                                        <label for="course_1">{{ trans('lamenu.online_course') }}</label>
                                    </li>
                                    <li>
                                        <input type="checkbox" id="course_2" name="course_2" value="course_2">
                                        <label for="course_2">{{ trans('lamenu.offline_course') }}</label>
                                    </li>
                                </ul>
                            </li>
                            <li class="tree">
                                <input type="hidden" class="wrapped_menu_3 input_value_check" value="0">
                                <input type="checkbox" id="menu_3" name="menu_3" value="menu_3" onclick="chooseMenuChild('menu_3')">
                                <label for="menu_3">{{ trans('lamenu.collaboration') }}</label>
                                <ul class="menu_child" id="wrapped_menu_3">
                                    <li>
                                        <input type="checkbox" id="daily_training" name="daily_training" value="daily_training">
                                        <label for="daily_training">Video sharing</label>
                                    </li>
                                    <li>
                                        <input type="checkbox" id="survey" name="survey" value="survey">
                                        <label for="survey">{{ trans('lamenu.survey') }}</label>
                                    </li>
                                    <li>
                                        <input type="checkbox" id="rating_level" name="rating_level" value="rating_level">
                                        <label for="rating_level">{{ trans('lamenu.kirkpatrick_model') }}</label>
                                    </li>
                                    <li>
                                        <input type="hidden" class="wrapped_sub_menu_1 input_value_sub_menu_check" value="0">
                                        <input type="checkbox" id="library" name="library" value="library" onclick="chooseSubMenuChild(1)">
                                        <label for="library">{{ trans('lamenu.library') }}</label>
                                        <ul class="sub_menu_child" id="wrapped_sub_menu_1">
                                            <li>
                                                <input type="checkbox" id="book" name="book" value="book">
                                                <label for="book">{{ trans('lalibrary.book') }}</label>
                                            </li>
                                            <li>
                                                <input type="checkbox" id="ebook" name="ebook" value="ebook">
                                                <label for="ebook">{{ trans('lamenu.ebook') }}</label>
                                            </li>
                                            <li>
                                                <input type="checkbox" id="document" name="document" value="document">
                                                <label for="document">{{ trans('lamenu.document') }}</label>
                                            </li>
                                            <li>
                                                <input type="checkbox" id="Video" name="Video" value="Video">
                                                <label for="Video">Video</label>
                                            </li>
                                            <li>
                                                <input type="checkbox" id="audio" name="audio" value="audio">
                                                <label for="audio">{{ trans('lamenu.audio') }}</label>
                                            </li>
                                        </ul>
                                    </li>
                                    <li>
                                        <input type="checkbox" id="forum" name="forum" value="forum">
                                        <label for="forum">{{ trans('lamenu.forum') }}</label>
                                    </li>
                                    <li>
                                        <input type="checkbox" id="suggest" name="suggest" value="suggest">
                                        <label for="suggest">{{ trans('lamenu.suggestion') }}</label>
                                    </li>
                                    <li>
                                        <input type="checkbox" id="topic_situation" name="topic_situation" value="topic_situation">
                                        <label for="topic_situation">{{ trans('lamenu.situations_proccessing') }}</label>
                                    </li>
                                    <li>
                                        <input type="checkbox" id="coaching_teacher" name="coaching_teacher" value="coaching_teacher">
                                        <label for="coaching_teacher">{{ trans('lamenu.coaching_teacher') }}</label>
                                    </li>
                                </ul>
                            </li>
                            <li class="tree">
                                <input type="hidden" class="wrapped_menu_4 input_value_check" value="0">
                                <input type="checkbox" id="menu_4" name="menu_4" value="menu_4" onclick="chooseMenuChild('menu_4')">
                                <label for="menu_4">{{ trans('lamenu.accumulated_points') }}</label>
                                <ul class="menu_child" id="wrapped_menu_4">
                                    <li>
                                        <input type="checkbox" id="promotion" name="promotion" value="promotion">
                                        <label for="promotion">{{ trans('lamenu.study_promotion_program') }}</label>
                                    </li>
                                    <li>
                                        <input type="checkbox" id="usermedal" name="usermedal" value="usermedal">
                                        <label for="usermedal">{{ trans('lamenu.emulation_program') }}</label>
                                    </li>
                                    <li>
                                        <input type="checkbox" id="usermedal_history" name="usermedal_history" value="usermedal_history">
                                        <label for="usermedal_history">{{ trans('latraining.medal_history') }}</label>
                                    </li>
                                    <li>
                                        <input type="checkbox" id="user_point_history" name="user_point_history" value="user_point_history">
                                        <label for="user_point_history">{{ trans('latraining.get_point_history') }}</label>
                                    </li>
                                    <li>
                                        <input type="checkbox" id="my_promotion" name="my_promotion" value="my_promotion">
                                        <label for="my_promotion">{{ trans('lamenu.gift_list') }}</label>
                                    </li>
                                </ul>
                            </li>
                            <li class="tree">
                                <input type="hidden" class="wrapped_menu_1 input_value_check" value="0">
                                <input type="checkbox" id="menu_1" name="menu_1" value="menu_1" onclick="chooseMenuChild('menu_1')">
                                <label for="menu_1">{{ trans('lamenu.my_plan') }}</label>
                                <ul class="menu_child" id="wrapped_menu_1">
                                    <li>
                                        <input type="checkbox" id="info" name="info" value="info">
                                        <label for="info">{{ trans('latraining.info') }}</label>
                                    </li>
                                    <li>
                                        <input type="checkbox" id="dashboard_by_user" name="dashboard_by_user" value="dashboard_by_user">
                                        <label for="dashboard_by_user">{{ trans('lamenu.summary') }}</label>
                                    </li>
                                    <li>
                                        <input type="checkbox" id="calendar" name="calendar" value="calendar">
                                        <label for="calendar">{{ trans('lamenu.training_calendar') }}</label>
                                    </li>
                                    <li>
                                        <input type="checkbox" id="quiz" name="quiz" value="quiz">
                                        <label for="quiz">{{ trans('lamenu.quiz_manager') }}</label>
                                    </li>
                                    <li>
                                        <input type="checkbox" id="note" name="note" value="note">
                                        <label for="note">{{ trans('latraining.my_note') }}</label>
                                    </li>
                                    <li>
                                        <input type="checkbox" id="interaction_history" name="interaction_history" value="interaction_history">
                                        <label for="interaction_history">{{ trans('lamenu.interaction_history') }}</label>
                                    </li>
                                </ul>
                            </li>
                            <li class="tree">
                                <input type="hidden" class="wrapped_menu_5 input_value_check" value="0">
                                <input type="checkbox" id="menu_5" name="menu_5" value="menu_5" onclick="chooseMenuChild('menu_5')">
                                <label for="menu_5">{{ trans('lamenu.support') }}</label>
                                <ul class="menu_child" id="wrapped_menu_5">
                                    <li>
                                        <input type="hidden" class="wrapped_sub_menu_2 input_value_sub_menu_check" value="0">
                                        <input type="checkbox" id="guide" name="guide" value="guide" onclick="chooseSubMenuChild(2)">
                                        <label for="guide">{{ trans('lamenu.guide') }}</label>
                                        <ul class="sub_menu_child" id="wrapped_sub_menu_2">
                                            <li>
                                                <input type="checkbox" id="pdf" name="pdf" value="pdf">
                                                <label for="pdf">PDF</label>
                                            </li>
                                            <li>
                                                <input type="checkbox" id="guide_video" name="guide_video" value="guide_video">
                                                <label for="guide_video">Video</label>
                                            </li>
                                            <li>
                                                <input type="checkbox" id="guide_post" name="guide_post" value="guide_post">
                                                <label for="guide_post">{{ trans('lamenu.post') }}</label>
                                            </li>
                                        </ul>
                                    </li>
                                    <li>
                                        <input type="checkbox" id="faq" name="faq" value="faq">
                                        <label for="faq">{{ trans('lamenu.faq') }}</label>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn" data-dismiss="modal">{{ trans('labutton.close') }}</button>
                    <button type="button" class="btn" id="btn_save_menu_setting" onclick="saveMenuSetting()">{{trans('labutton.save')}}</button>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        function name_formatter(value, row, index) {
            return '<a href="'+ row.edit_url +'">'+ row.name +' </a>';
        }

        function setting_formatter(value, row, index){
            return row.nameMenuSetting; 
        }

        function edit_formatter(value, row, index){
            return '<span id="edit_'+ row.id +'" class="cursor_pointer" onclick="modalSaveSetting('+ row.id +')"><i class="far fa-edit"></i></span>'; 
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('backend.menu_setting.getdata') }}',
        });

        function modalSaveSetting(id) {
            $('.input_value_check').val(0);
            if(id == 0) {
                var ids = $("input[name=btSelectItem]:checked").map(function(){return $(this).val();}).get();
                if (ids.length <= 0) {
                    show_message('Vui lòng chọn ít nhất 1 chức danh', 'error')
                    return false;
                }
                $('#ids').val(ids)
                $('#id').val('')
                $('input[type="checkbox"]').prop('checked', false)
                $('input[type="checkbox"]').prop('disabled', false)
                $('#modal-setting').modal();
            } else {
                $('input[type="checkbox"]').prop('checked', false)
                $('#id').val(id)
                let item = $('#edit_'+id)
                let oldtext = item.html()
                item.html('<i class="fa fa-spinner fa-spin"></i>');
                $.ajax({
                    url: "{{ route('backend.menu_setting.edit') }}",
                    type: 'post',
                    data: {
                        id: id,
                    }
                }).done(function(data) {
                    item.html(oldtext)
                    data.forEach(element => {
                        if(element.menu_value == 'menu_2' || element.menu_value == 'menu_1' || element.menu_value == 'menu_3' || element.menu_value == 'menu_4' || element.menu_value == 'menu_5' || element.menu_value == 'library' || element.menu_value == 'guide') {
                            $('#'+ element.menu_value).prop('checked', false)
                        } else {
                            $('#'+ element.menu_value).prop('checked', true)
                        }
                    });
                    $('#modal-setting').modal();
                    return false;
                }).fail(function(data) {
                    show_message('Lỗi dữ liệu', 'error');
                    return false;
                });
            }
        }

        function chooseMenuChild(name) {
            if($('.wrapped_'+ name).val() == 0) {
                $('#wrapped_'+ name).find('input[type="checkbox"]').prop('checked', true)
                $('.wrapped_'+ name).val(1)
            } else {
                $('#wrapped_'+ name).find('input[type="checkbox"]').prop('checked', false)
                $('.wrapped_'+ name).val(0)
            }
        }

        function chooseSubMenuChild(id) {
            if($('.wrapped_sub_menu_'+ id).val() == 0) {
                $('#wrapped_sub_menu_'+ id).find('input[type="checkbox"]').prop('checked', true)
                $('.wrapped_sub_menu_'+ id).val(1)
            } else {
                $('#wrapped_sub_menu_'+ id).find('input[type="checkbox"]').prop('checked', false)
                $('.wrapped_sub_menu_'+ id).val(0)
            }
        }

        function edit(id){
            let item = $('#edit_'+id);
            let oldtext = item.html();
            item.html('<i class="fa fa-spinner fa-spin"></i> {{ trans("laother.please_wait") }}');
            
        }

        function saveMenuSetting() {
            let item = $('#btn_save_menu_setting');
            let oldtext = item.html();
            item.html('<i class="fa fa-spinner fa-spin"></i> {{ trans("laother.please_wait") }}');
            var formValue = $('#form_menu_setting').serializeArray();
            var ids = $('#ids').val();
            var id = $('#id').val();
            $.ajax({
                url: "{{ route('backend.menu_setting.save') }}",
                type: 'post',
                data: {
                    formValue: formValue,
                    ids: ids,
                    id: id,
                },
            }).done(function(data) {
                item.html(oldtext)
                show_message(data.message, data.status)
                $('#modal-setting').modal('hide');
                $(table.table).bootstrapTable('refresh');
                return false;
            }).fail(function(data) {
                show_message('Lỗi hệ thống', 'error');
                return false;
            });
        }
    </script>
@endsection
