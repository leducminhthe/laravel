{{-- @extends('layouts.backend')

@section('page_title', 'Kế hoạch đào tạo tháng')

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            {{ trans('lamenu.training') }} <i class="uil uil-angle-right"></i>
            <span class="font-weight-bold">Kế hoạch đào tạo tháng</span>
        </h2>
    </div>
@endsection

@section('content') --}}
    <div role="main">
        @if(isset($errors))
            @foreach($errors as $error)
                <div class="alert alert-danger">{!! $error !!}</div>
            @endforeach
        @endif
        <div class="row">
            <div class="col-md-2">
                @include('courseplan::backend.filter')
            </div>
            <div class="col-md-10 text-right act-btns">
                <div class="pull-right">
                    <a href="{{ route('module.course_plan.view_register_training_plan') }}" class="btn">
                        <i class="fa fa-eye"></i> {{ trans('latraining.view_register_training_plan') }}
                    </a>

                    <div class="btn-group">
                        @if (App\Models\Permission::isAdmin())
                            <button class="btn approved" data-model="el_course_plan" data-status="1">
                                <i class="fa fa-check-circle"></i> {{ trans('labutton.approve') }}
                            </button>
                            <button class="btn approved" data-model="el_course_plan" data-status="0">
                                <i class="fa fa-exclamation-circle"></i> {{ trans('labutton.deny') }}
                            </button>
                        @endif
                    </div>

                    <div class="btn-group">
                        <button class="btn" id="model-list-template-import">
                            <i class="fa fa-download"></i> {{ trans('labutton.import_template') }}
                        </button>
                        <button class="btn" id="model-list-import">
                            <i class="fa fa-upload"></i> {{ trans('labutton.import') }}
                        </button>

                        @can('course-plan-create')
                            <a href="{{ route('module.course_plan.create', ['course_type' => 1]) }}" class="btn"><i class="fa fa-plus-circle"></i> {{ trans('labutton.add_new_online') }}</a>
                            <a href="{{ route('module.course_plan.create', ['course_type' => 2]) }}" class="btn"><i class="fa fa-plus-circle"></i> {{ trans('labutton.add_new_offline') }}</a>
                        @endcan
                        @can('course-plan-delete')
                            <button class="btn" id="delete-item"><i class="fa fa-trash"></i> {{ trans('labutton.delete') }}</button>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
        <br>

        <table class="tDefault table table-hover bootstrap-table text-nowrap" id="table-course-plan">
            <thead>
                <tr>
                    <th data-field="state" data-checkbox="true"></th>
                    <th data-field="name" data-sortable="true" data-formatter="name_formatter">{{ trans('backend.course') }}</th>
                    <th data-field="course_type" data-sortable="true" data-formatter="course_type_formatter">{{ trans('backend.type_course') }}</th>
                    <th data-field="course_belong_to" data-width="10%" data-align="center" data-formatter="course_belong_to_formatter">{{ trans('latraining.course_belong_to') }}</th>
                    <th data-field="subject_name">{{ trans('app.subject') }}</th>
                    <th data-field="register_deadline" data-sortable="true" data-align="center" data-width="5%">{{ trans('backend.register_deadline') }}</th>
                    <th data-formatter="created_by_formatter">{{ trans('backend.code_user_create') }}</th>
                    <th data-field="status" data-align="center" data-formatter="status_formatter" data-width="5%">{{ trans('latraining.status') }}</th>
                    <th data-align="center" data-formatter="convert_formatter">{{ trans('latraining.convert') }}</th>
                </tr>
            </thead>
        </table>

        <div class="modal fade" id="modal-import" tabindex="-1" role="dialog" aria-labelledby="modal-import" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modal-import">{{ trans('laprofile.import') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group row">
                            <div class="col-md-7 control-label"> {{ trans('lamenu.online_course') }}</div>
                            <div class="col-md-5">
                                <button class="btn" id="import-online_course" type="button" name="task" value="import">
                                    <i class="fa fa-upload"></i> {{ trans('labutton.import') }}
                                </button>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-7 control-label"> {{ trans('lamenu.offline_course') }}</div>
                            <div class="col-md-5">
                                <button class="btn" id="import-offline_course" type="button" name="task" value="import">
                                    <i class="fa fa-upload"></i> {{ trans('labutton.import') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modal-template-import" tabindex="-1" role="dialog" aria-labelledby="modal-template-import" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modal-template-import">{{ trans('laprofile.import_template') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group row">
                            <div class="col-md-7 control-label"> {{ trans('lamenu.online_course') }}</div>
                            <div class="col-md-5">
                                <a class="btn" href="{{ download_template('mau_import_khoa_truc_tuyen_dao_tao_thang.xlsx') }}"><i class="fa fa-download"></i> {{ trans('labutton.import_template') }}</a>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-7 control-label"> {{ trans('lamenu.offline_course') }}</div>
                            <div class="col-md-5">
                                <a class="btn" href="{{ download_template('mau_import_khoa_tap_trung_dao_tao_thang.xlsx') }}"><i class="fa fa-download"></i> {{ trans('labutton.import_template') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{--  Import khoá học Online --}}
        <div class="modal fade" id="modal-import-online_course" tabindex="-1" role="dialog" aria-labelledby="modal-import-register" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog" role="document">
                <form action="{{ route('module.course_plan.import_online_course') }}" method="post" class="form-ajax">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modal-import-online_course">{{ (trans('lamenu.online_course')) }}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <input type="file" name="import_file" id="import_file" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn">{{ trans('labutton.import') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{--  Import khoá học Offline --}}
        <div class="modal fade" id="modal-import-offline_course" tabindex="-1" role="dialog" aria-labelledby="modal-import-register" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog" role="document">
                <form action="{{ route('module.course_plan.import_offline_course') }}" method="post" class="form-ajax">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modal-import-offline_course">{{ (trans('lamenu.offline_course')) }}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <input type="file" name="import_file" id="import_file" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
                            <div class="form-group row mt-2">
                                <div class="col-md-4">
                                    <label for="">Chọn khóa chính <span class="text-danger">(*)</span></label>
                                </div>
                                <div class="col-md-8">
                                    <label class="radio-inline">
                                        <input type="radio" name="type_import" class="mr-1" value="1" checked>
                                        {{ trans('latraining.employee_code') }}
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="type_import" class="mr-1" value="2">
                                        Username
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="type_import" class="mr-1" value="3">
                                        Email
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn">{{ trans('labutton.import') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        function name_formatter(value, row, index) {
            return '<a href="'+ row.edit_url +'">'+ row.name +'</a> <br>' + row.start_date  + (row.end_date ? ' <i class="fa fa-arrow-right"></i> ' + row.end_date : ' ');
        }

        function isopen_formatter(value, row, index) {
            return row.isopen == 1 ? '<i class="fa fa-check text-success"></i>' : '<i class="fa fa-exclamation-triangle text-warning"></i> ';
        }

        function action_plan_formatter(value, row, index) {
            return (row.in_plan) ? '{{ trans("backend.yes") }}' : '{{ trans("backend.no") }}';
        }

        function created_by_formatter(value, row, index) {
            return row.full_name + '<br>' + row.unit_name + '<br>' + row.created_at2;
        }

        function status_formatter(value, row, index) {
            value = parseInt(value);
            var text_status = '';
            switch (value) {
                case 0: text_status = '<span class="text-danger">{{ trans("backend.deny") }}</span>'; break;
                case 1: text_status = '<span class="text-success">{{trans("backend.approve")}}</span>'; break;
                case 2 || null: text_status = '<span class="text-warning">{{ trans("backend.not_approved") }}</span>'; break;
            }

            return text_status + '<br>' + (row.approved_step ? `<a href="javascript:void(0)" data-id="${row.id}" data-model="el_course_plan" class="text-success font-weight-bold load-modal-approved-step">(${row.approved_step})</a>`:'');
        }

        function course_type_formatter(value, row, index) {
            return (row.course_type == 1) ? 'Trực tuyến' : '{{ trans("latraining.offline") }}';
        }

        function course_belong_to_formatter(value, row, index) {
            return row.course_belong_to ? (row.course_belong_to == 1) ? 'Đào tạo nội bộ' : 'Đào tạo chéo' : '';
        }

        function convert_formatter(value, row, index) {
            if (row.status == 1){
                if(row.status_convert == 1){
                    return 'Đã chuyển';
                }
                return '<a href="javascript::void(0)" class="form-control convert" data-course_id="'+ row.id +'" data-course_type="'+ row.course_type +'"> <i class="fa fa-exchange-alt"></i></a>';
            }
            return '';
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.course_plan.getdata') }}',
            remove_url: '{{ route('module.course_plan.remove') }}'
        });

        $('#training_program').on('change', function () {
            var training_program_id = $('#training_program option:selected').val();
            $("#level_subject").empty();
            $("#level_subject").data('training-program', training_program_id);
            $('#level_subject').trigger('change');

            $("#subject").empty();
            $("#subject").data('training-program', training_program_id);
            $("#subject").data('level-subject', '');
            $('#subject').trigger('change');
        });

        $('#level_subject').on('change', function () {
            var training_program_id = $('#training_program option:selected').val();
            var level_subject_id = $('#level_subject option:selected').val();
            $("#subject").empty();
            $("#subject").data('training-program', training_program_id);
            $("#subject").data('level-subject', level_subject_id);
            $('#subject').trigger('change');
        });

        $('.publish').on('click', function () {
            var ids = $("input[name=btSelectItem]:checked").map(function(){return $(this).val();}).get();
            var status = $(this).data('status');

            if (ids.length <= 0) {
                show_message('{{ trans('lacourse.min_one_course ') }}', 'error');
                return false;
            }

            $.ajax({
                url: '{{ route('module.course_plan.ajax_isopen_publish') }}',
                type: 'post',
                data: {
                    ids: ids,
                    status: status
                }
            }).done(function(data) {
                $(table.table).bootstrapTable('refresh');
                return false;
            }).fail(function(data) {
                show_message('Lỗi hệ thống', 'error');
                return false;
            });
        });

        $('.approve').on('click', function () {
            var ids = $("input[name=btSelectItem]:checked").map(function(){return $(this).val();}).get();
            var status = $(this).data('status');

            if (ids.length <= 0) {
                show_message('{{ trans('lacourse.min_one_course') }}', 'error');
                return false;
            }

            $.ajax({
                url: '{{ route('module.course_plan.approve') }}',
                type: 'post',
                data: {
                    ids: ids,
                    status: status
                }
            }).done(function(data) {
                $(table.table).bootstrapTable('refresh');
                return false;
            }).fail(function(data) {
                show_message('{{ trans('laother.data_error') }}', 'error');
                return false;
            });
        });

        $('#table-course-plan').on('click', '.convert', function () {
            var course_id = $(this).data('course_id');
            var course_type = $(this).data('course_type');

            $.ajax({
                url: '{{ route('module.course_plan.convert') }}',
                type: 'post',
                data: {
                    course_id: course_id,
                    course_type: course_type
                }
            }).done(function(data) {
                $(table.table).bootstrapTable('refresh');
                return false;
            }).fail(function(data) {
                show_message('{{ trans('laother.data_error') }}', 'error');
                return false;
            });
        });

        $('#model-list-import').on('click', function () {
            $('#modal-import').modal();
        });

        $('#model-list-template-import').on('click', function () {
            $('#modal-template-import').modal();
        });

        $('#import-online_course').on('click', function () {
            $('#modal-import').hide();
            $('#modal-import-online_course').modal();
        });

        $('#import-offline_course').on('click', function () {
            $('#modal-import').hide();
            $('#modal-import-offline_course').modal();
        });
    </script>
{{-- @endsection --}}
