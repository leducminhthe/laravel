@extends('layouts.backend')

@section('page_title', trans('laother.sign_teach'))

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lamenu.teacher_permission'),
                'url' => route('backend.category.training_teacher.list_permission')
            ],
            [
                'name' => trans('laother.sign_teach'),
                'url' => ''
            ],
        ]
    @endphp
    @include('layouts.backend.menu_breadcum', $breadcum)
@endsection

@section('content')
    <div role="main">
        <div class="row">
            <div class="col-md-12">
                <form class="form-inline form-search mb-3" id="form-search">
                    <input type="text"
                        name="search"
                        value=""
                        class="form-control mr-1"
                        autocomplete="off"
                        placeholder="{{ trans('latraining.enter_code_name_course') }}"
                    >
                    <input name="start_date"
                        type="text"
                        class="datepicker form-control mr-1"
                        placeholder="{{ trans('latraining.start_date') }}"
                        autocomplete="off"
                    >
                    <input name="end_date"
                        type="text"
                        class="datepicker form-control mr-1"
                        placeholder="{{ trans('latraining.end_date') }}"
                        autocomplete="off"
                    >
                    <button type="submit" class="btn"><i class="fa fa-search"></i>&nbsp;{{ trans('labutton.search') }}</button>
                </form>
            </div>
        </div>

        <table class="tDefault table table-hover bootstrap-table">
            <thead>
                <tr>
                    <th data-formatter="index_formatter" data-width="5%" data-align="center">{{trans('latraining.stt')}}</th>
                    <th data-field="course_name" data-formatter="course_name_formatter" data-width="40%">{{trans('lacategory.course')}}</th>
                    <th data-field="note" data-formatter="manager_note_formatter">{{ trans('laother.manager_note') }}</th>
                    <th data-field="num_schedule" data-align="center" data-width="5%">{{trans('latraining.num_session')}}</th>
                    <th data-formatter="teacher_formatter" data-width="5%" data-align="center">{{ trans('laother.register') }}</th>
                </tr>
            </thead>
        </table>
    </div>
    <script type="text/javascript">
        function index_formatter(value, row, index){
            return (index + 1);
        }
        function course_name_formatter(value, row, index){
            return '<a href="'+ row.detail_register +'"><p class="mb-0">'+ row.course_name +'</p><p class="mb-0">'+ row.course_date +'</p></a>';
        }
        function teacher_formatter(value, row, index) {
            if(row.checkExistsTeacher) {
                return '<button type="button" class="btn"><i class="fas fa-info-circle" onclick="modalExists()"></i></button>';
            } else if (row.checkApprove == 1) {
                return '<button type="button" class="btn"><i class="far fa-check-circle"></i></button>';
            } else if (row.checkApprove == 0) {
                return '<button type="button" class="btn"><i class="far fa-times-circle"></i></button>';
            } else {
                return '<div class="save_'+ row.id +'"><button type="button" class="btn add-teacher" onclick="registerClass('+ row.id +')"><i class="fas fa-edit"></i></button></div>';
            }
        }
        function manager_note_formatter(value, row, index) {
            return '<textarea class="form-control w-100" rows="2" readonly>'+ row.note +'</textarea>';
        }
        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: "{{ route('backend.category.training_teacher.getdata_course_register') }}",
        });
        function registerClass(courseId) {
            let item = $('.save_' + courseId);
            let oldtext = item.html();
            item.html('<i class="fa fa-spinner fa-spin"></i>');
            $('.save_' + courseId).find('.btn').attr('disabled',true);

            $.ajax({
                url: "{{ route('backend.category.training_teacher.save_register_class') }}",
                type: 'post',
                data: {
                    courseId: courseId
                },
            }).done(function(data) {
                item.html(oldtext);
                if (data && data.status == 'success') {
                    show_message(data.message, data.status);
                    $('.save_' + courseId).html('<button type="button" class="btn"><i class="far fa-times-circle"></i></button>')
                }
                return false;
            }).fail(function(data) {
                show_message('Lỗi dữ liệu', 'error');
                return false;
            });
        }
        function modalExists() {
            show_message('Bạn đang giảng dạy lớp học này', 'warning');
        }
    </script>
@endsection
