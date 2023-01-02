@extends('layouts.backend')

@section('page_title',trans('lamenu.approve_student_cost '))

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lamenu.training'),
                'url' => ''
            ],
            [
                'name' => trans('lamenu.approve_student_cost'),
                'url' => ''
            ],
            [
                'name' => trans('backend.all_course'),
                'url' => route('module.training_unit.approve_student_cost')
            ],
            [
                'name' => $course->name,
                'url' => ''
            ],
        ]
    @endphp
    @include('layouts.backend.menu_breadcum',$breadcum)
@endsection

@section('content')
    <div role="main" id="course">
        <div class="row">
            <div class="col-md-8 form-inline">
                <form class="form-inline" id="form-search">
                    <input type="text" name="search" class="form-control" placeholder="{{ trans('latraining.enter_code_name_user') }}">
                    <button type="submit" class="btn"><i class="fa fa-search"></i>&nbsp;{{trans('labutton.search')}}</button>
                </form>
            </div>
        </div>
        <br>

        <table class="tDefault table table-hover bootstrap-table">
            <thead>
                <tr>
                    <th data-field="code" data-width="10%">{{trans('backend.employee_code')}}</th>
                    <th data-field="name" data-formatter="name_formatter" data-width="20%">{{trans('backend.employee_name')}}</th>
                    <th data-field="email" >{{trans('backend.employee_email')}}</th>
                    <th data-field="title_name" >{{trans('latraining.title')}}</th>
                    <th data-field="unit_name" data-width="15%">{{ trans('backend.work_unit') }}</th>
                    <th data-field="unit_manager">{{ trans('backend.unit_manager') }}</th>
                    <th data-field="sum_cost" data-align="center">Tổng chi phí học viên</th>
                    <th data-field="student_cost_formatter" data-formatter="student_cost_formatter" data-align="center" data-width="20%">{{trans('latraining.status')}}</th>
                </tr>
            </thead>
        </table>
    </div>

    <script type="text/javascript">
        function name_formatter(value, row, index) {
            return row.lastname +' '+row.firstname;
        }

        function student_cost_formatter(value, row, index) {
            return '<button style:"cursor: pointer;" class="btn student_cost border-info p-2" data-register_id="'+ row.id +'" data-approved="'+ row.approved +'">'+ row.manager_approved +'</button>'
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.training_unit.approve_student_cost.getdata_register', ['id' => $course->id]) }}',
        });

        $('#course').on('click', '.student_cost', function() {
            $.ajax({
                type: 'POST',
                url: '{{ route('module.training_unit.approve_student_cost.modal', ['id' => $course->id]) }}',
                dataType: 'html',
                data: {
                    'regid': $(this).data('register_id'),
                    'approved': $(this).data('approved')
                },
            }).done(function(data) {
                $("#app-modal").html(data);
                $("#app-modal #modal-student-cost-by-user").modal();

                return false;
            }).fail(function(data) {

                Swal.fire(
                    '',
                    '{{ trans('laother.data_error') }}',
                    'error'
                );
                return false;
            });
        });

    </script>

@endsection
