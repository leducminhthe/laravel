@extends('layouts.backend')

@section('page_title', trans('latraining.add_new'))

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('latraining.register'),
                'url' => route('module.training_unit.register_course')
            ],
            [
                'name' => $course->name,
                'url' => route('module.training_unit.register_course.register', ['course_id' => $course_id, 'course_type' => $course_type])
            ],
            [
                'name' => trans('labutton.add_new'),
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
                @include('trainingunit::backend.register.filter')
            </div>
            <div class="col-md-6 text-right act-btns">
                <div class="pull-right">
                    <div class="btn-group">
                        <button type="submit" id="button-register" class="btn"><i class="fa fa-plus-circle"></i> {{ trans('labutton.register') }}</button>
                        <a href="{{ route('module.training_unit.register_course.register', ['course_id' => $course_id, 'course_type' => $course_type]) }}" class="btn"><i class="fa fa-times-circle"></i> {{ trans('labutton.cancel') }}</a>
                    </div>
                </div>
            </div>
        </div>
        <br>

        <table class="tDefault table table-hover bootstrap-table" data-page-list="[10, 50, 100, 200, 500]">
            <thead>
                <tr>
                    <th data-field="state" data-checkbox="true"></th>
                    <th data-sortable="true" data-field="code">{{ trans('latraining.employee_code') }}</th>
                    <th data-field="name" data-formatter="name_formatter">{{ trans('latraining.employee_name') }}</th>
                    <th data-field="title_name">{{ trans('latraining.title') }}</th>
                    <th data-field="unit_name" data-width="20%">{{ trans('latraining.work_unit') }}</th>
                    <th data-field="parent_unit_name" data-width="20%">{{ trans('latraining.unit_manager') }}</th>
                </tr>
            </thead>
        </table>
    </div>

    <script type="text/javascript">
        function name_formatter(value, row, index) {
            return row.lastname +' ' + row.firstname;
        }
        var ajax_get_user = "{{ route('module.training_unit.register_course.save', ['course_id' => $course_id, 'course_type' => $course_type]) }}";
        
        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.training_unit.register_course.register.get_data_not_register', ['course_id' => $course_id, 'course_type' => $course_type]) }}',
            field_id: 'user_id'
        });

        $('#button-register').on('click', function() {
            var ids = $("input[name=btSelectItem]:checked").map(function(){return $(this).val();}).get();

            if (ids.length <= 0) {
                show_message('Vui lòng chọn ít nhất 1 nhân viên', 'error');
                return false;
            }

            $.ajax({
                type: 'POST',
                url: ajax_get_user,
                dataType: 'json',
                data: {
                    ids: ids
                },
            }).done(function(data) {
                show_message(
                    'Ghi danh thành công',
                    'success'
                );
                $(table.table).bootstrapTable('refresh');
                return false;
            }).fail(function(data) {
                
                show_message(
                    'Lỗi hệ thống',
                    'error'
                );
                return false;
            });
        });
    </script>
@stop
