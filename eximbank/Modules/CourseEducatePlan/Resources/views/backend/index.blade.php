@extends('layouts.backend')

@section('page_title', trans('lamenu.training_seft_plan'))

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lamenu.training_seft_plan') ,
                'url' => ''
            ],
        ]
    @endphp
    @include('layouts.backend.menu_breadcum',$breadcum)
@endsection

@section('content')
    <div role="main">
        <div class="row">
            <div class="col-md-6">
                @include('courseeducateplan::backend.filter')
            </div>
            <div class="col-md-6 text-right act-btns">
                <div class="pull-right">
                    <div class="btn-group">
                        <button class="btn approve" data-status="1">
                            <i class="fa fa-check-circle"></i> {{ trans('labutton.approve') }}
                        </button>
                        <button class="btn approve" data-status="0">
                            <i class="fa fa-exclamation-circle"></i> {{ trans('labutton.deny') }}
                        </button>
                    </div>
                    <div class="btn-group">
                        <a href="{{ route('module.course_educate_plan.create') }}" class="btn"><i class="fa fa-plus-circle"></i> {{ trans('labutton.add_new_offline') }}</a>
                        <button class="btn" id="delete-item"><i class="fa fa-trash"></i> {{ trans('labutton.delete') }}</button>
                    </div>
                </div>
            </div>
        </div>
        <br>

        <table class="tDefault table
        table-hover bootstrap-table"
               id="table-course-educate-plan">
            <thead>
                <tr>
                    <th data-field="state" data-width="5%" data-checkbox="true"></th>
					<th data-width="5%" data-field="status" data-formatter="status_formatter">{{ trans('latraining.status') }}</th>
                    <th data-field="name" data-sortable="true" data-formatter="name_formatter">{{ trans('latraining.course_name') }}</th>
                    <th data-width="15%" data-field="subject_name">{{ trans('laprofile.subject') }}</th>
                    <th data-width="15%" data-field="training_program_name">{{ trans('latraining.training_program') }}</th>
                    <th data-field="time" data-align="center" data-width="18%">{{ trans('backend.time') }}</th>
                    <th data-align="center" data-width="10%" data-formatter="convert_formatter">{{ trans('latraining.result') }}</th>
                    <th data-align="center" data-field="creat_course" data-width="8%">{{ trans('latraining.course_name') }}</th>
                    <th data-field="actions" data-align="center" data-width="15%">{{ trans('labutton.task') }}</th>
                </tr>
            </thead>
        </table>
    </div>

    <script type="text/javascript">
        function name_formatter(value, row, index) {
            return '<a href="'+ row.edit_url +'">'+ row.name +'</a>';
        }

        function action_plan_formatter(value, row, index) {
            return (row.in_plan) ? '{{ trans("backend.yes") }}' : '{{ trans("backend.no") }}';
        }


        function status_formatter(value, row, index) {
            value = parseInt(value);
            switch (value) {
                case 0: return '<span class="text-danger">{{ trans("backend.deny") }}</span>';
                case 1: return '<span class="text-success">{{trans("backend.approve")}}</span>';
                case 2: return '<span class="text-warning">{{ trans("backend.not_approved") }}</span>';
            }
        }

        function convert_formatter(value, row, index) {
            if(row.status_convert == 1){
                return 'Đã chuyển';
            }
            return '<a href="javascript::void(0)" class="form-control convert" data-course_id="'+ row.id +'" > <i class="fa fa-exchange-alt"></i></a>';
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.course_educate_plan.getdata') }}',
            remove_url: '{{ route('module.course_educate_plan.remove') }}'
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
                url: '{{ route('module.course_educate_plan.ajax_isopen_publish') }}',
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
                show_message('{{ trans('lacourse.min_one_course ') }}', 'error');
                return false;
            }

            $.ajax({
                url: '{{ route('module.course_educate_plan.approve') }}',
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

        $('#table-course-educate-plan').on
        ('click', '.convert', function () {
            var course_id = $(this).data('course_id');
            $.ajax({
                url: '{{ route('module.course_educate_plan.convert') }}',
                type: 'post',
                data: {
                    course_id: course_id,
                }
            }).done(function(data) {
                $(table.table).bootstrapTable('refresh');
                return false;
            }).fail(function(data) {
                show_message('{{ trans('laother.data_error') }}', 'error');
                return false;
            });
        });
    </script>
@endsection
