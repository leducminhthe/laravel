@extends('layouts.backend')

@section('page_title', trans('lamenu.register_training_plan'))

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            <span class="font-weight-bold">{{ trans('lamenu.register_training_plan') }}</span>
        </h2>
    </div>
@endsection

@section('content')
    <div role="main">
        <div class="row">
            <div class="col-md-2">
                @include('registertrainingplan::backend.filter')
            </div>
            <div class="col-md-10 text-right act-btns">
                <div class="pull-right">
                    <div class="btn-group">
                        <button class="btn" id="send">
                            <i class="fa fa-paper-plane"></i> {{ trans('labutton.send') }}
                        </button>

                        <a class="btn" href="{{ route('module.register_training_plan.export_template') }}">
                            <i class="fa fa-download"></i> {{ trans('labutton.import_template') }}
                        </a>
                        <button class="btn" id="model-import">
                            <i class="fa fa-upload"></i> {{ trans('labutton.import') }}
                        </button>

                        <a href="{{ route('module.register_training_plan.create') }}" class="btn">
                            <i class="fa fa-plus-circle"></i> {{ trans('labutton.add_new') }}
                        </a>
                        <button class="btn" id="delete-item"><i class="fa fa-trash"></i> {{ trans('labutton.delete') }}</button>
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
                    <th data-field="course_type" data-width="10%" data-align="center" data-formatter="course_type_formatter">{{ trans('backend.type_course') }}</th>
                    <th data-field="date_time" data-width="15%" data-align="center" data-formatter="date_time_formatter">Ngày dự kiến</th>
                    <th data-field="course_time" data-width="10%" data-align="center">{{ trans('laother.timer') .' ('. trans('laother.hours') .')' }}</th>
                    <th data-field="max_student" data-width="10%" data-align="center">{{ trans('latraining.max_student') }}</th>
                    <th data-field="course_belong_to" data-width="10%" data-align="center" data-formatter="course_belong_to_formatter">{{ trans('latraining.course_belong_to') }}</th>
                    <th data-field="created_at2" data-width="10%" data-align="center">{{ trans('laapi.date_updated') }}</th>
                    <th data-field="send" data-width="10%" data-align="center">{{ trans('latraining.status') }}</th>
                    <th data-field="status" data-align="center" data-formatter="status_formatter" data-width="5%">{{ trans('latraining.result') .' TTĐT' }}</th>
                </tr>
            </thead>
        </table>

        <div class="modal" id="modal-info" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ trans('latraining.info') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body text-justify">

                    </div>
                </div>
            </div>
        </div>

        {{--  Import đăng ký kế hoạch --}}
        <div class="modal fade" id="modal-import" tabindex="-1" role="dialog" aria-labelledby="modal-import-register" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog" role="document">
                <form action="{{ route('module.register_training_plan.import') }}" method="post" class="form-ajax">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">{{ (trans('lamenu.register_training_plan')) }}</h5>
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
    </div>

    <script type="text/javascript">
        function name_formatter(value, row, index) {
            return '<a href="'+ row.edit_url +'">'+ row.name +'</a> <br>' + row.start_date  + (row.end_date ? ' <i class="fa fa-arrow-right"></i> ' + row.end_date : ' ');
        }

        function course_type_formatter(value, row, index) {
            return (row.course_type == 1) ? 'Trực tuyến' : '{{ trans("latraining.offline") }}';
        }

        function course_belong_to_formatter(value, row, index) {
            return row.course_belong_to ? (row.course_belong_to == 1) ? 'Đào tạo nội bộ' : 'Đào tạo chéo' : '';
        }

        function date_time_formatter(value, row, index) {
            return row.start_date + (row.end_date ? ' <i class="fa fa-arrow-right"></i> ' + row.end_date : '');
        }

        function status_formatter(value, row, index) {
            value = parseInt(value);
            var text_status = '';
            switch (value) {
                case 0: text_status = '<span class="text-danger">{{ trans("backend.deny") }}</span>' + ` <i class="fa fa-info-circle note_status_info" style="cursor: pointer;" data-value='${row.note_status}'></i>`; break;
                case 1: text_status = '<span class="text-success">{{trans("backend.approve")}}</span>'; break;
                case 2 || null: text_status = ''; break;
            }

            return text_status;
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.register_training_plan.getdata') }}',
            remove_url: '{{ route('module.register_training_plan.remove') }}'
        });


        $('#table-course-plan').on('click', '.note_status_info', function(){
            var item_val = $(this).data('value');

            $('#modal-info .modal-body').html(item_val);

            $('#modal-info').modal();
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

        $('#model-import').on('click', function () {
            $('#modal-import').modal();
        });

        $('#send').on('click', function(){
            let item = $(this);
            let current_icon = item.find('i').attr('class');

            item.find('i').attr('class', 'fa fa-spinner fa-spin');
            item.prop("disabled", true);

            var ids = $("input[name=btSelectItem]:checked").map(function(){return $(this).val();}).get();

            if (ids.length <= 0) {
                item.find('i').attr('class', current_icon);
                item.prop("disabled", false);

                show_message('Vui lòng chọn ít nhất dòng dữ liệu', 'error');
                return false;
            }

            $.ajax({
                url: '{{ route('module.register_training_plan.send') }}',
                type: 'post',
                data: {
                    ids: ids,
                }
            }).done(function(data) {
                item.find('i').attr('class', current_icon);
                item.prop("disabled", false);

                show_message(data.message, data.status);

                $(table.table).bootstrapTable('refresh');

                return false;
            }).fail(function(data) {
                item.find('i').attr('class', current_icon);
                item.prop("disabled", false);

                show_message('{{ trans('laother.data_error') }}', 'error');
                return false;
            });
        });
    </script>
@endsection
