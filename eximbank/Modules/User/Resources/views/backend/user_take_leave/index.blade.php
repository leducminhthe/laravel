{{-- @extends('layouts.backend')

@section('page_title', 'Nhân viên nghỉ phép')

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            {{ trans('backend.management') }} <i class="uil uil-angle-right"></i>
            <span class="font-weight-bold"> Nhân viên nghỉ phép</span>
        </h2>
    </div>
@endsection --}}

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

        <div class="row">
            <div class="col-5">
                @include('user::backend.user_take_leave.filter_user_take_leave')
            </div>
            <div class="col-md-7 text-right act-btns">
                <div class="pull-right">
                    <div class="btn-group">
                        <a class="btn" href="{{ route('module.backend.user_take_leave.export') }}"><i class="fa fa-download"></i> {{ trans('labutton.export') }}</a>
                    </div>
                    <div class="btn-group">
                        <a class="btn" href="{{ download_template('mau_import_nhan_vien_nghi_phep.xlsx') }}"><i class="fa fa-download"></i> {{ trans('labutton.import_template') }}</a>
                        <button class="btn" id="import_user_take_leave" type="button" name="task" value="import">
                            <i class="fa fa-upload"></i> {{ trans('labutton.import') }}
                        </button>
                    </div>
                    <div class="btn-group">
                        @can('user-take-leave-create')
                            <button type="button" class="btn btn-demo" onclick="create()">
                                <i class="fa fa-plus-circle"></i> {{ trans('labutton.add_new') }}
                            </button>
                        @endcan
                        @can('user-take-leave-delete')
                            <button class="btn" id="delete-item"><i class="fa fa-trash"></i> {{ trans('labutton.delete') }}</button>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
        <br>
        <table class="tDefault table table-hover bootstrap-table">
            <thead>
            <tr>
                <th data-field="state" data-checkbox="true"></th>
                <th data-field="code" data-width="5%">{{ trans('laprofile.employee_code') }}</th>
                <th data-field="full_name" data-formatter="fullname_formatter">{{ trans('laprofile.employee_name') }}</th>
                <th data-field="email">{{ trans('laprofile.email') }}</th>
                <th data-field="unit_name" data-formatter="unit_formatter">{{ trans('laprofile.work_unit') }}</th>
                <th data-field="unit_manager" data-with="5%">{{ trans('laprofile.unit_manager') }}</th>
                <th data-field="title_name">{{ trans('laprofile.title') }}</th>
                <th data-field="position_name">{{ trans('laprofile.position') }}</th>
                <th data-field="absent">{{ trans('laprofile.reason') }}</th>
                <th data-field="date_take_leave">{{ trans('laprofile.date') }}</th>
            </tr>
            </thead>
        </table>
    </div>

    <div class="modal fade" id="modal-import" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabelImport" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog" role="document">
            <form action="{{ route('module.backend.user_take_leave.import') }}" method="post" class="form-ajax">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabelImport">
                            {{ trans('laprofile.import_user_take_leave') }}
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="unit_id" value=" ">
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

    <div class="modal right fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
                <form action="" method="post" class="form-ajax" id="form_save" onsubmit="return false;">
                    <input type="hidden" name="id" value="">
                    <div class="modal-header">
                        <div class="btn-group">
                            <h5 class="modal-title" id="exampleModalLabel"></h5>
                        </div>
                        <div class="btn-group act-btns">
                            @can(['user-take-leave-create','user-take-leave-edit'])
                                <button type="button" onclick="save(event)" class="btn" data-must-checked="false"><i class="fa fa-save"></i> {{ trans('labutton.save') }}</button>
                            @endcan
                            <a href="{{ route('module.backend.user_take_leave') }}" class="btn"><i class="fa fa-times-circle"></i> {{ trans('labutton.cancel') }}</a>
                        </div>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-11">
                                <div class="form-group row">
                                    <div class="col-sm-3 control-label">
                                        <label for="user_id">{{ trans('laprofile.user') }} <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-md-9">
                                        <select name="user_id" id="user_id" class="form-control load-user" data-placeholder="-- {{ trans('laprofile.user') }} --" required>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-sm-3 control-label">
                                        <label for="absent_code">{{ trans('laprofile.reason') }} <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-md-9">
                                        <select name="absent_code" id="absent_code" class="form-control select2" data-placeholder="-- {{ trans('laprofile.reason') }} --">
                                            <option value=""></option>
                                            @foreach($absents as $absent)
                                                <option value="{{ $absent->code }}"> {{ $absent->name }}</option>
                                            @endforeach
                                        </select>
                                        <input type="checkbox" id="absent_other" value="0"> {{ trans('laprofile.other') }}
                                        <textarea name="absent_name" id="absent_name" class="form-control" rows="5"></textarea>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-sm-3 control-label">
                                        <label>{{ trans('laprofile.date_off') }} <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="row d_flex_align">
                                            <div class="col-5 pr-0">
                                                <input type="text" name="start_date" class="form-control d-inline-block datepicker" placeholder="{{trans('laprofile.start_date')}}" autocomplete="off" value="" required>
                                            </div>
                                            <div class="col-2">
                                                <span class="fa fa-arrow-right" style="padding: 0 10px;"></span>
                                            </div>
                                            <div class="col-5 pl-0">
                                                <input type="text" name="end_date" class="form-control d-inline-block datepicker" placeholder="{{trans('laprofile.end_date')}}" autocomplete="off" value="" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
			</div>
		</div>
	</div>

    <script type="text/javascript">
        function fullname_formatter(value, row, index) {
            return '<a class="edit" id="edit_'+ row.id +'" style="cursor: pointer;" onclick="edit('+ row.id +')">'+ row.full_name +'</a>' ;
        }

        function unit_formatter(value, row, index) {
            return row.unit_name + ' <a href="javascript:void(0)" class="load-modal" data-url="'+row.unit_url+'"> <i class="fa fa-info-circle"></i></a>';
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.backend.user_take_leave.getdata') }}',
            remove_url: '{{ route('module.backend.user_take_leave.remove') }}',
            // field_id: 'user_id'
        });

        $('#import_user_take_leave').on('click', function() {
            $('#modal-import').modal();
        });

        function edit(id){
            let item = $('#edit_'+id);
            let oldtext = item.html();
            item.html('<i class="fa fa-spinner fa-spin"></i> {{ trans("laother.please_wait") }}');
            var level =  $("input[name=level]").val();
            $.ajax({
                url: "{{ route('module.backend.user_take_leave.edit') }}",
                type: 'post',
                data: {
                    id: id,
                }
            }).done(function(data) {
                item.html(oldtext);
                $('#exampleModalLabel').html('{{ trans('labutton.edit') }}');
                $("input[name=id]").val(data.model.id);
                $("input[name=start_date]").val(data.start_date);
                $("input[name=end_date]").val(data.end_date);

                $("#user_id").html('<option value="'+ data.model.user_id +'">'+ data.model.full_name +'</option>');

                $("#absent_code").val('').trigger('change');
                if (data.model.absent_code) {
                    $("#absent_code").val(data.model.absent_code);
                    $("#absent_code").val(data.model.absent_code).change();
                }

                if (data.model.absent_name) {
                    $("#absent_other").val(1);
                    $('#absent_name').val(data.model.absent_name);
                } else {
                    $("#absent_other").val(0);
                    $('#absent_name').val('');
                }
                checkOption();
                $('#myModal2').modal();
                return false;
            }).fail(function(data) {
                show_message('Lỗi dữ liệu', 'error');
                return false;
            });
        }

        function save(event) {
            let item = $('.save');
            let oldtext = item.html();
            item.html('<i class="fa fa-spinner fa-spin"></i> {{ trans("laother.processing") }}');
            $('.save').attr('disabled',true);
            event.preventDefault();
            $.ajax({
                url: "{{ route('module.backend.user_take_leave.save') }}",
                type: 'post',
                data: $("#form_save").serialize(),

            }).done(function(data) {
                item.html(oldtext);
                $('.save').attr('disabled',false);
                if (data && data.status == 'success') {
                    $('#myModal2').modal('hide');
                    show_message(data.message, data.status);
                    $(table.table).bootstrapTable('refresh');
                } else {
                    show_message(data.message, data.status);
                }
                return false;
            }).fail(function(data) {
                show_message('Lỗi dữ liệu', 'error');
                return false;
            });
        }

        function create() {
            $('#exampleModalLabel').html('{{ trans('labutton.add_new') }}');
            $('#form_save').trigger("reset");
            $("#absent_other").val(0);
            $("#absent_code").val('').trigger('change');
            $("#user_id").html('');
            $("input[name=id]").val('');
            checkOption();
            $('#myModal2').modal();
        }

        window.onload = checkOption;
        function checkOption() {
            if ($('#absent_other').val() == 1){
                $('#absent_other').prop('checked', true);
                $('#absent_name').prop('disabled', false);
                $('#absent_code').val('').trigger('change');
                $('#absent_code').prop('disabled', true);
            }else {
                $('#absent_other').prop('checked', false);
                $('#absent_name').val('').trigger('change');
                $('#absent_name').prop('disabled', true);
                $('#absent_code').prop('disabled', false);
            }
        }

        $('#absent_other').on('click', function () {
        if($(this).is(':checked')){
            $('#absent_name').prop('disabled', false);
            $('#absent_code').prop('disabled', true);
            $('#absent_code').val('').trigger('change');
        }else{
            $('#absent_name').val('').trigger('change');
            $('#absent_name').prop('disabled', true);
            $('#absent_code').prop('disabled', false);
        }
        });
    </script>
{{-- @endsection --}}
