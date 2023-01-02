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
                'name' => trans('laprofile.list'). ' ' .trans('latraining.register'),
                'url' => ''
            ],
        ]
    @endphp
    @include('layouts.backend.menu_breadcum',$breadcum)
@endsection

@section('content')
    @php
        if($course_type == 1) {
            $name = 'online-course-register-approve';
            $model = 'el_online_register';
        } else {
            $name = 'offline-course-register-approve';
            $model = 'el_offline_register';
        }
    @endphp
    <div role="main">
        @if(isset($errors))
            @foreach($errors as $error)
                <div class="alert alert-danger">{!! $error !!}</div>
            @endforeach
        @endif

        <div class="row">
            <div class="col-md-12 act-btns">
                @include('trainingunit::backend.register.filter')
                <div class="wrraped_register text-right">
                    <div class="pull-right">
                        <div class="btn-group">
                            <a class="btn" href="{{ download_template('mau_import_nhan_vien_ghi_danh_khoa_hoc.xlsx') }}"><i class="fa fa-download"></i> {{ trans('labutton.import_template') }}</a>
                            <button class="btn" id="import-plan" type="submit" name="task" value="import">
                                <i class="fa fa-upload"></i> {{ trans('labutton.import') }}
                            </button>
                        </div>
                        @can($name)
                            <div class="btn-group">
                                <button type="button" class="btn approved" data-model="{{ $model }}" data-status="1">
                                    <i class="fa fa-check-circle"></i> {{ trans('labutton.approve') }}
                                </button>
                                <button type="button" class="btn approved" data-model="{{ $model }}" data-status="0">
                                    <i class="fa fa-times-circle"></i> {{ trans('labutton.deny') }}
                                </button>
                            </div>
                        @endcan
                        <div class="btn-group">
                            <a href="{{ route('module.training_unit.register_course.register.create', ['course_id' => $course_id, 'course_type' => $course_type]) }}" class="btn"><i class="fa fa-plus-circle"></i> {{ trans('labutton.add_new') }}</a>
                            <button class="btn" id="delete-item"><i class="fa fa-trash"></i> {{ trans('labutton.delete') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <table class="tDefault table table-hover bootstrap-table" data-page-list="[10, 50, 100, 200, 500]" id="list-user-registed">
            <thead>
                <tr>
                    <th data-field="state" data-checkbox="true"></th>
                    <th data-field="code" data-width="5px">{{ trans('latraining.employee_code') }}</th>
                    <th data-sortable="true" data-field="full_name" data-width="20%">{{ trans('latraining.employee_name') }}</th>
                    <th data-field="email">{{ trans('latraining.email') }}</th>
                    <th data-field="title_name">{{ trans('latraining.title') }}</th>
                    <th data-field="unit_name">{{ trans('latraining.work_unit') }}</th>
                    <th data-field="approved_step" data-align="center" data-formatter="approved_formatter" data-width="5%">{{ trans('latraining.approve') }}</th>
                    <th data-field="status" data-align="center" data-formatter="status_formatter" data-width="5%">{{ trans('latraining.status') }}</th>
                </tr>
            </thead>
        </table>
    </div>

    <div class="modal fade" id="modal-import" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog" role="document">
            <form action="{{ route('module.training_unit.register.import_register', ['course_id' => $course_id, 'course_type' => $course_type]) }}" method="post" class="form-ajax">
                <input type="hidden" name="unit" value="{{ $online->unit_id }}">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">{{ trans('latraining.import_student') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="file" name="import_file" id="import_file" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn" data-dismiss="modal">{{ trans('labutton.close') }}</button>
                        <button type="submit" class="btn">{{ trans('labutton.import') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script type="text/javascript">
        function approved_formatter(value, row, index) {
            return value? `<a href="javascript:void(0)" data-id="${row.id}" data-model="el_online_register" class="text-success font-weight-bold load-modal-approved-step">${value}</a>`:'-';
        }
        function status_formatter(value, row, index) {
            if (value == 0) {
                return '<span class="text-danger">{{ trans("backend.deny") }}</span>';
            }else if (value == 1) {
                return '<span class="text-success">{{ trans("backend.approved") }}</span>';
            }else{
                return '<span class="text-warning">{{ trans("backend.not_approved") }}</span>';
            }
        }
        
        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.training_unit.register_course.register.getdata', ['course_id' => $course_id, 'course_type' => $course_type]) }}',
            remove_url: '{{ route('module.training_unit.register_course.remove', ['course_id' => $course_id, 'course_type' => $course_type]) }}',
        });

        $('#import-plan').on('click', function() {
            $('#modal-import').modal();
        });
    </script>
@stop
