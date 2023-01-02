@extends('layouts.backend')

@section('page_title', trans('latraining.result'))

@section('breadcrumb')
    @php
        if($quiz_name->course_type == 1){
            $route_edit = route('module.online.edit', ['id' => $course_id]);
            $route_quiz = route('module.online.quiz', ['course_id' => $course_id]);

            $breadcum= [
                [
                    'name' => trans('lamenu.training_organizations'),
                    'url' => ''
                ],
                [
                    'name' => trans('lamenu.online_course'),
                    'url' => route('module.online.management')
                ],
                [
                    'name' => $course->name,
                    'url' => $route_edit
                ],
                [
                    'name' => trans('latraining.quiz_list'),
                    'url' => $route_quiz
                ],
                [
                    'name' => $quiz_name->name,
                    'url' => route('module.online.quiz.edit', ['course_id' => $course_id, 'id' => $quiz_name->id])
                ],
                [
                    'name' => trans('backend.result'),
                    'url' => ''
                ],
            ];
        }elseif($quiz_name->course_type == 2){
            $route_edit = route('module.offline.edit', ['id' => $course_id]);
            $route_quiz = route('module.offline.quiz', ['course_id' => $course_id]);

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
                    'url' => $route_edit
                ],
                [
                    'name' => trans('latraining.quiz_list'),
                    'url' => $route_quiz
                ],
                [
                    'name' => $quiz_name->name,
                    'url' => route('module.offline.quiz.edit', ['course_id' => $course_id, 'id' => $quiz_name->id])
                ],
                [
                    'name' => trans('backend.result'),
                    'url' => ''
                ],
            ];
        }else{
            $breadcum= [
                [
                    'name' => trans('latraining.quiz_list'),
                    'url' => route('module.quiz.manager')
                ],
                [
                    'name' => $quiz_name->name,
                    'url' => route('module.quiz.edit', ['id' => $quiz_id])
                ],
                [
                    'name' => trans('backend.result'),
                    'url' => ''
                ],
            ];
        }
    @endphp
    @include('layouts.backend.menu_breadcum',$breadcum)
@endsection

@section('content')
<div role="main" id="quiz-result">
    @if(isset($errors))

    @foreach($errors as $error)
        <div class="alert alert-danger">{!! $error !!}</div>
    @endforeach

    @endif
        <div class="row">
            <div class="col-md-6">
                @include('quiz::backend.result.filter')
            </div>
            <div class="col-md-6 text-right act-btns">
                <div class="pull-right">
                    <div class="btn-group">
                        @if($quiz_name->paper_exam == 1)
                            <a class="btn" href="{{ download_template('mau_import_diem_ky_thi.xlsx') }}"><i class="fa fa-download"></i> {{ trans('backend.import_template') }}</a>

                            <button class="btn" id="import-plan" type="submit" name="task" value="import">
                                <i class="fa fa-upload"></i> Import
                            </button>
                        @endif
                        @if($export_result)
                            <a class="btn" href="javascript:void(0)" id="export-result">
                                <i class="fa fa-download"></i> Export
                            </a>
                        @endif
                        <a class="btn" href="javascript:void(0)" onclick="changeStatus(0)">
                            <i class="fa fa-check"></i> {{ trans('labutton.open') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <table class="tDefault table table-hover bootstrap-table" data-page-list="[10, 50, 100, 200, 500]">
            <thead>
                <tr>
                    <th data-field="state" data-checkbox="true"></th>
                    <th data-sortable="true" data-field="name" data-formatter="name_formatter" class="text-nowrap">{{ trans('backend.employee_name') }}</th>
                    <th data-field="type" data-formatter="type_formatter" data-width="10%">{{ trans('backend.examinee') }}</th>
                    <th data-field="title_name" data-width="20%">{{ trans('latraining.title') }}</th>
                    <th data-field="unit_name" data-width="20%">{{ trans('backend.work_unit') }}</th>
                    <th data-field="part_name" data-align="center" data-width="5%">{{trans('backend.exams')}}</th>
                    <th data-field="grade" data-formatter="grade_formatter" data-width="10%">{{ trans('backend.score') }}</th>
                    <th data-field="reexamine" data-align="center" data-formatter="reexamine_formatter" data-width="5%">{{ trans('backend.references') }}</th>
                    <th data-field="res" data-align="center" data-formatter="res_formatter" data-width="5%">{{ trans('backend.result') }}</th>
                    <th data-field="file" data-align="center" data-formatter="file_formatter" data-width="5%">{{ trans('backend.attach_file') }}</th>
                    <th data-field="view_quiz" data-align="center" data-formatter="view_quiz_formatter" data-width="5%">{{ trans('backend.task') }}</th>
                    <th data-field="view_image" data-align="center" data-formatter="view_image_formatter" data-width="5%">{{trans('backend.picture')}}</th>
                    <th data-field="user_locked" data-align="center" data-width="5%">{{trans('latraining.lock')}}</th>
                </tr>
            </thead>
        </table>
    </div>
    <div class="modal fade" id="modal-import" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog" role="document">
            <form action="{{ route('module.quiz.result.import_result', ['id' => $quiz_id]) }}" method="post" class="form-ajax">
                <input type="hidden" name="unit" value="{{ $quiz_name->unit_id }}">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">IMPORT {{ trans('backend.score') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="file" name="import_file" id="import_file" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn" data-dismiss="modal">{{ trans('labutton.close') }}</button>
                        <button type="submit" class="btn">Import</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script type="text/javascript">
        function name_formatter(value, row, index) {
            return `<span class="mb-0">`+ (row.type == 1 ? row.lastname + ' ' + row.firstname : row.secondary_name) +`</span>
                    <br/>
                    <span>(`+ (row.type == 1 ? row.profile_code : row.user_secon_code) +`)</span> <br/>` + (row.type == 1 ? row.profile_email : row.user_secon_email)
        }

        function email_formatter(value, row, index) {
            return row.type == 1 ? row.profile_email : row.user_secon_email;
        }

        function grade_formatter(value, row, index) {
            return '<input style="width:100%;" type="text" {{ $save_grade ? '' : 'disabled' }} name="grade" data-regid="'+row.regid+'" data-id="'+ row.result_id +'" value="'+row.grade+'" class="form-control is-number change-grade" '+ ((row.paper_exam == 1) ? '' : 'readonly') +' >';
        }

        function reexamine_formatter(value, row, index) {
            return '<input type="text" {{ $save_reexamine ? '' : 'disabled' }} name="reference" data-regid="'+row.regid+'" data-id="'+ row.result_id +'" value="'+row.reexamine+'" class="form-control is-number change-reexamine" >';
        }

        function file_formatter(value, row, index) {
            return '<div class="attemp btn-group" {{ ($save_grade || $save_reexamine )? '' : 'disabled' }}><a href="javascript:void(0)" class="select-file btn"><i class="fa fa-upload"></i></a> <input type="hidden" data-regid="'+row.regid+'" data-id="'+ row.result_id +'" value="'+row.file+'" name="file" class="file-select"> <a href="'+ row.link_download +'" title="'+ row.file_name +'" class="btn '+ (row.link_download ? '' : 'disabled') +'"><i class="fa fa-download"></i></a></div>';
        }

        function res_formatter(value, row, index) {
            return row.res == 1 ? '<span class="text-success">Đậu</span>' : row.res == 0 ? '<span class="text-danger">Rớt</span>' : '';
        }

        function type_formatter(value, row, index) {
            return value == 1 ? '{{trans("backend.internal")}}' : '{{trans("backend.outside")}}';
        }

        function view_quiz_formatter(value, row, index){
            if (row.status != 0){
                return '<a href="'+row.review_link+'"><i class="fa fa-eye"></i></a>';
            }
            return '';
        }

        function view_image_formatter(value, row, index){
            if (row.status != 0){
                return '<a href="'+row.url_image+'"><i class="fa fa-eye"></i></a>';
            }
            return '';
        }

        $('#import-plan').on('click', function() {
            $('#modal-import').modal();
        });
        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.quiz.result.getdata', ['id' => $quiz_id]) }}',
        });

        // BẬT/TẮT
        function changeStatus(status) {
            var ids = $("input[name=btSelectItem]:checked").map(function(){return $(this).val();}).get();
            if (ids.length <= 0) {
                show_message('Vui lòng chọn 1 kỳ thi', 'error');
                return false;
            }
            $.ajax({
                url: "{{ route('module.quiz.result.update_user_locked', ['id' => $quiz_id]) }}",
                type: 'post',
                data: {
                    ids: ids,
                    status: status
                }
            }).done(function(data) {

                show_message(data.message, data.status);
                $(table.table).bootstrapTable('refresh');

                return false;
            }).fail(function(data) {
                show_message('Lỗi hệ thống', 'error');
                return false;
            });
        };

    </script>

<script type="text/javascript">
    $('#quiz-result').on('click', '.select-file', function () {
        let item = $(this);
        var lfm = function (options, cb) {
            var route_prefix = '/filemanager';
            window.open(route_prefix + '?type=' + options.type || 'file', 'FileManager', 'width=1100,height=600');
            window.SetUrl = cb;
        };

        lfm({type: 'files'}, function (url, path) {
            var path2 =  path.split("/");
            item.closest(".attemp").find('.file-review').html(path2[path2.length - 1]);
            item.closest(".attemp").find('.file-select').val(path);
            var result_id = item.closest(".attemp").find('.file-select').data('id');
            var regid = item.closest(".attemp").find('.file-select').data('regid');

            $.ajax({
                url: "{{ route('module.quiz.result.save_file', ['id' => $quiz_id]) }}",
                type: 'post',
                data: {
                    result_id: result_id,
                    regid : regid,
                    path : path,
                }
            }).done(function(data) {
                $(table.table).bootstrapTable('refresh');
                return false;
            }).fail(function(data) {
                show_message('Lỗi hệ thống', 'error');
                return false;
            });

        });
    });

    $('#quiz-result').on('change', '.change-grade', function () {
        var result_id = $(this).data('id');
        var regid = $(this).data('regid');
        var grade = $(this).val();

        $.ajax({
            url: "{{ route('module.quiz.result.save_grade', ['id' => $quiz_id]) }}",
            type: 'post',
            data: {
                result_id: result_id,
                regid : regid,
                grade : grade,
            }
        }).done(function(data) {
            if(data.status == 'error'){
                show_message(data.message, 'error');
            }
            $(table.table).bootstrapTable('refresh');
            return false;
        }).fail(function(data) {
            show_message('Lỗi hệ thống', 'error');
            return false;
        });
    });

    $('#quiz-result').on('change', '.change-reexamine', function () {
        var result_id = $(this).data('id');
        var regid = $(this).data('regid');
        var reexamine = $(this).val();

        $.ajax({
            url: "{{ route('module.quiz.result.save_reexamine', ['id' => $quiz_id]) }}",
            type: 'post',
            data: {
                result_id: result_id,
                regid : regid,
                reexamine : reexamine,
            }
        }).done(function(data) {
            if(data.status == 'error'){
                show_message(data.message, 'error');
            }
            $(table.table).bootstrapTable('refresh');
            return false;
        }).fail(function(data) {
            show_message('Lỗi hệ thống', 'error');
            return false;
        });
    });

    $('#export-result').on('click', function () {
        let form_search = $("#form-search").serialize();
        window.location = '{{ route('module.quiz.result.export_result', ['id' => $quiz_id]) }}?'+form_search;
    })
</script>
@endsection
