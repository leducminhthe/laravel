{{-- @extends('layouts.backend')

@section('page_title', trans('backend.user_management'))

{{-- @section('content') --}}
    <div role="main">
        @if(isset($errors))
            @foreach($errors as $error)
                <div class="alert alert-danger">{!! $error !!}</div>
            @endforeach
            @php
                session()->forget('errors');
            @endphp
        @endif
        <p></p>
        <div class="row">
            <div class="col-md-3">
                @include('user::backend.user.filter')
            </div>
            @if (!\App\Models\User::isRoleLeader())
                <div class="pull-right text-right col-md-9">
                    {{--  @can('user-approve-change-info')
                        <a href="{{ route('module.backend.user.approve_info') }}" class="btn"><i class="fa fa-check-circle"></i> {{ trans('labutton.approve') }}</a>
                    @endcan  --}}
                    <div class="btn-group">
                        @can('user-import')
                            <button class="btn" id="model-list-template-import"><i class="fa fa-download"></i> {{ trans('labutton.import_template') }}</button>
                            <button class="btn" id="model-list-import"><i class="fa fa-upload"></i> {{ trans('labutton.import') }}</button>
                        @endcan
                        @can('user-export')
                            <form action="{{ route('module.backend.user.export_user') }}" method="get">
                                <input type="hidden" name="export_search" value="">
                                <input type="hidden" name="export_unit" value="">
                                <input type="hidden" name="export_area" value="">
                                <input type="hidden" name="export_status" value="">
                                <input type="hidden" name="export_title" value="">
                                <button class="btn" id="btnExport" type="submit"><i class="fa fa-download"></i> {{ trans('labutton.export') }}</button>
                            </form>
                        @endcan
                    </div>
                    <div class="btn-group">
                        @can('user-create')
                            <a href="{{ route('module.backend.user.create') }}" class="btn"><i class="fa fa-plus-circle"></i> {{ trans('labutton.add_new') }}</a>
                        @endcan
                        @can('user-delete')
                            <button class="btn" id="delete-item"><i class="fa fa-trash"></i> {{ trans('labutton.delete') }}</button>
                        @endcan
                    </div>
                </div>
            @endif
        </div>
        <br>
        <table class="tDefault table table-hover bootstrap-table text-nowrap">
            <thead>
            <tr>
                <th data-field="state" data-checkbox="true"></th>
                <th data-field="avatar" data-formatter="avatar_formatter" data-width="5%">{{ trans('laprofile.avatar') }}</th>
                <th data-field="code" data-width="5%">{{ trans('laprofile.employee_code') }}</th>
                <th data-field="fullname" data-formatter="fullname_formatter" data-width="20%">
                    {{ trans('laprofile.employee_name') . ($total_model > 0 ? ' ('.$total_model_active.'/'.$total_model.')' : '') }}
                </th>
                <th data-field="email" >{{ trans('laprofile.employee_email') }}</th>
                <th data-field="title_name" >{{ trans('laprofile.title') }}</th>
                <th data-field="percent_roadmap" data-align="center" >{{ trans('laprofile.roadmap') }} (%)</th>
                <th data-field="unit_name" data-formatter="unit_formatter" data-with="5%">{{ trans('laprofile.work_unit') }}</th>
                <th data-field="parent_unit_name" data-with="5%">{{ trans('laprofile.unit_manager') }}</th>
                {{--<th data-field="area_name" data-formatter="area_formatter">{{ trans('backend.work_location') }}</th>--}}
                <th data-field="status_id" data-align="center" data-formatter="status_formatter" data-width="5%">{{ trans('laprofile.status') }}</th>
                @if (\App\Models\User::isRoleLeader())
                    <th data-field="dashboard" data-formatter="dashboard_formatter" data-align="center" data-with="5%">{{ trans('app.dashboard') }}</th>
                    <th data-field="total_time" data-align="center">{{ trans('lamenu.hours_learned_total') }}</th>
                @endif
            </tr>
            </thead>
        </table>
    </div>

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
                        <div class="col-md-7 control-label"> {{ trans('laprofile.user') }}</div>
                        <div class="col-md-5">
                            <button class="btn" id="import-user" type="submit" name="task" value="import"><i class="fa fa-upload"></i>{{ trans('labutton.import') }}</button>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-7 control-label"> {{ trans('laprofile.working_process') }}</div>
                        <div class="col-md-5">
                            <button class="btn" id="import-working-process" type="submit" name="task" value="import"><i class="fa fa-upload"></i> {{ trans('labutton.import') }}</button>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-7 control-label"> {{ trans('laprofile.training_program_learned') }}</div>
                        <div class="col-md-5">
                            <button class="btn" id="import-training-program-learned" type="submit" name="task" value="import"><i class="fa fa-upload"></i> {{ trans('labutton.import') }}</button>
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
                        <div class="col-md-7 control-label"> {{ trans('laprofile.user') }}</div>
                        <div class="col-md-5">
                            <a class="btn" href="{{ download_template('mau_import_nguoi_dung.xlsx') }}"><i class="fa fa-download"></i> {{ trans('labutton.import_template') }}</a>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-7 control-label"> {{ trans('laprofile.working_process') }}</div>
                        <div class="col-md-5">
                            <a class="btn" href="{{ download_template('mau_import_qua_trinh_cong_tac.xlsx') }}"><i class="fa fa-download"></i> {{ trans('labutton.import_template') }}</a>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-7 control-label"> {{ trans('laprofile.training_program_learned') }}</div>
                        <div class="col-md-5">
                            <a class="btn" href="{{ download_template('mau_import_chuong_trinh_dao_tao_da_hoc.xlsx') }}"><i class="fa fa-download"></i> {{ trans('labutton.import_template') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-import-user" tabindex="-1" role="dialog" aria-labelledby="modal-import-user" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog" role="document">
            <form action="{{ route('module.backend.user.import_user') }}" method="post" class="form-ajax">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modal-import-user">{{ (trans('laprofile.import_user')) }}</h5>
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

    <div class="modal fade" id="modal-import-working-process" tabindex="-1" role="dialog" aria-labelledby="modal-import-working-process" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog" role="document">
            <form action="{{ route('module.backend.user.import_working_process') }}" method="post" class="form-ajax">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modal-import-working-process">{{ (trans('laprofile.import_working_process')) }}</h5>
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

    <div class="modal fade" id="modal-import-training-program-learned" tabindex="-1" role="dialog" aria-labelledby="modal-import-training-program-learned" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog" role="document">
            <form action="{{ route('module.backend.user.import_training_program_learned') }}" method="post" class="form-ajax">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modal-import-training-program-learned">{{ (trans('laprofile.import_training_program_learned')) }}</h5>
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

    <script type="text/javascript">
        function fullname_formatter(value, row, index) {
            return '<a href="' + row.edit_url + '">' + row.full_name + '</a>';
        }
        function avatar_formatter(value, row, index) {

            var img = `<img src="${row.avatar}" />`;
            return `<a  class="opts_account" href="${row.edit_url}">${img}</a>`;
        }
        function unit_formatter(value, row, index) {
            return row.unit_name + ' <a href="javascript:void(0)" class="load-modal" data-url="'+row.unit_url+'"> <i class="fa fa-info-circle"></i></a>';
        }

        function area_formatter(value, row, index) {
            return row.area_name ? row.area_name + ' <a href="javascript:void(0)" class="load-modal" data-url="'+row.area_url+'"> <i class="fa fa-info-circle"></i></a>' : '';
        }

        function status_formatter(value, row, index) {
            value = parseInt(row.status_id);
            switch (value) {
                case 0:
                    return '<span>{{ trans('backend.inactivity') }}</span>';
                case 1:
                    return '<span>{{ trans('backend.doing') }}</span>';
                case 2:
                    return '<span>{{ trans('backend.probationary') }}</span>';
                case 3:
                    return '<span>{{ trans('backend.pause') }}</span>';
            }
        }

        function dashboard_formatter(value, row, index){
            return '<a href="'+row.dashboard_url+'" class=""> <i class="fa fa-chart-pie"></i></a>';
        }

        $('#model-list-import').on('click', function () {
            $('#modal-import').modal();
        });

        $('#model-list-template-import').on('click', function () {
            $('#modal-template-import').modal();
        });

        $('#import-user').on('click', function () {
            $('#modal-import').modal('hide');
            $('#modal-import-user').modal();
        });

        $('#import-working-process').on('click', function () {
            $('#modal-import').modal('hide');
            $('#modal-import-working-process').modal();
        });

        $('#import-training-program-learned').on('click', function () {
            $('#modal-import').modal('hide');
            $('#modal-import-training-program-learned').modal();
        });

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.backend.user.getdata') }}',
            remove_url: '{{ route('module.backend.user.remove') }}',
            field_id: 'user_id'
        });


    </script>
{{-- @endsection --}}
