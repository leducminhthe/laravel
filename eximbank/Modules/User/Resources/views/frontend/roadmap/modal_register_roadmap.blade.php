<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header" >
                <h5 class="modal-title text-center" id="exampleModalLabel">{{trans('backend.register_course_by_subject')}} {{ $subject->name }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive ">
                    <table  class="table table-bordered bootstrap-table-modal table-striped" style="table-layout: fixed">
                        <thead>
                            <tr class="tbl-heading">
                                <th data-field="index" data-formatter="index_formatter" data-align="center" data-width="40"  style="vertical-align: middle;">#</th>
                                <th data-field="code" data-width="120" style="vertical-align: middle;">{{ trans('backend.code') }}</th>
                                <th data-field="name" data-width="300" style="vertical-align: middle;">{{ trans('latraining.course_name') }}</th>
                                <th data-field="type" data-width="150" style="vertical-align: middle;">{{ trans('backend.type_course') }}</th>
                                <th data-width="120" data-field="start_date" data-align="center" style="text-align: center; vertical-align: middle;">{{ trans('latraining.start_date') }}</th>
                                <th data-width="120" data-field="end_date" data-align="center">{{ trans('latraining.end_date') }}</th>
                                <th data-width="100" data-align="center" data-formatter="register_formatter">{{ trans('backend.register') }}</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <input type="hidden" value="{{$subject_id}}" id="subject_id"/>
                <button type="button" class="btn" data-dismiss="modal">{{ trans('labutton.close') }}</button>
            </div>
        </div>
    </div>
</div>
<script>
    function index_formatter(value, row, index) {
        return (index + 1);
    }
    function register_formatter(value, row, index) {
        if(row.check_register) {
            value = parseInt(row.check_register);
            if(value == 0) {
                return '<span class="text-danger">{{ trans("latraining.deny") }}</span>';
            } else if (value == 1) {
                if(row.course_type == 1) {
                    return '<a href="'+ row.online_detail +'" class="btn" disabled>{{ trans("latraining.go_course") }}</a>';
                } else {
                    return '<a href="'+ row.offline_detail +'" class="btn" disabled>{{ trans("latraining.go_course") }}</a>';
                }
            } else {
                return '<span class="text-warning">{{ trans("latraining.not_approved") }}</span>';
            }
        } else {
            return '<button class="btn btn_register_'+ row.id +'" onclick="registerCourse('+ row.course_type +','+ row.id +')">{{ trans("laother.register") }}</button>';
        }
    }

    function registerCourse(course_type, courseid) {
        var subject_id = $('#subject_id').val();
        let item = $('.btn_register_' +courseid );
        let oldtext = item.html();
        item.html('<i class="fa fa-spinner fa-spin"></i> Đang chờ');
        $.ajax({
            type: 'PUT',
            url: '{{ route('module.frontend.user.roadmap.register') }}',
            dataType: 'json',
            data: {
                course_id: courseid,
                course_type: course_type,
                subject_id: subject_id
            }
        }).done(function(data) {
            item.html(oldtext);
            if(data.status == 'success') {
                $('.btn_register_' + courseid ).html('Đã đăng ký')
                $('.btn_register_' + courseid ).prop('disabled', true)
            }
            show_message(data.message,data.status);
        }).fail(function(data) {
            item.html(oldtext);
            show_message('{{ trans('laother.data_error') }}','error');
            return false;
        });
    }

    $(function () {
        var table = new LoadBootstrapTable({
            table: '.bootstrap-table-modal',
            locale: '{{ \App::getLocale() }}',
            url: '{{ route("module.frontend.user.roadmap.getCourseBySubject",[$subject_id]) }}',
            locale: '{{ App::getLocale() }}',
        });
    })
</script>
