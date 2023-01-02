@extends('layouts.backend')

@section('page_title', trans('lacategory.list_teacher'))

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lacategory.teacher'),
                'url' => ''
            ],
            [
                'name' => trans('lacategory.list_teacher'),
                'url' => ''
            ],
        ]
    @endphp
    @include('layouts.backend.menu_breadcum',$breadcum)
@endsection

@section('content')
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
            <div class="col-md-5">
                <form class="form-inline form-search mb-3" id="form-search">
                    <input type="text" name="search" value="" class="form-control" placeholder='{{trans("lacategory.enter_name")}}'>
                    <div class="w-auto">
                    <select name="type" class="form-control select2" data-placeholder="{{trans('latraining.choose_form')}}">
                        <option value=""></option>
                        <option value="1">{{trans("latraining.internal")}}</option>
                        <option value="2">{{trans("latraining.outside")}}</option>
                    </select>
                    </div>
                    <button type="submit" class="btn"><i class="fa fa-search"></i>&nbsp;{{ trans('labutton.search') }}</button>
                </form>
            </div>
            <div class="col-md-7 text-right act-btns">
                <div class="pull-right">
                    @can('category-teacher-create')
                    <div class="btn-group">
                        <a href="{{ download_template('mau_import_giang_vien_noi_bo_theo_username.xlsx') }}" class="btn"><i class="fa fa-download"></i> {{ trans('labutton.import_template') }}</a>
                        <a href="javascript:void(0)" class="btn" data-toggle="modal" data-target="#modal-import"><i class="fa fa-upload"></i> {{ trans('labutton.import') }}</a>
                        <a class="btn" href="{{ route('backend.category.training_teacher.export') }}"><i class="fa fa-download"></i> {{ trans('labutton.export') }}</a>
                    </div>
                    @endcan
                    <div class="btn-group">
                        @can('category-teacher-create')
                            <button type="button" class="btn btn-demo" onclick="create()">
                                <i class="fa fa-plus-circle"></i> {{ trans('labutton.add_new') }}
                            </button>
                        @endcan
                        @can('category-teacher-delete')
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
                    <th data-field="state" data-checkbox="true" data-width="5%"></th>
                    <th data-field="code" data-width="15%">{{ trans('lacategory.code') }}</th>
                    <th data-field="name" data-width="25%" data-formatter="name_formatter">{{ trans('lacategory.name') }}</th>
                    <th data-field="email" data-width="20%">{{ trans('lacategory.email') }}</th>
                    <th data-field="phone" data-width="5%">{{ trans('lacategory.phone') }}</th>
                    <th data-field="partner" data-width="10%">{{ trans('lacategory.partner') }}</th>
                    <th data-field="rank" data-sortable="true" data-width="7%" data-align="center">{{ trans('lacategory.rank') }}</th>
                    <th data-sortable="true" data-field="status" data-align="center" data-formatter="status_formatter" data-width="7%">
                        {{ trans('lacategory.status') }}
                    </th>
                    <th data-align="center" data-formatter="history_formatter" data-width="7%">
                        {{ trans('latraining.history_teaching') }}
                    </th>
                </tr>
            </thead>
        </table>

        <div class="modal fade" id="modal-import" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <form method="post" action="{{ route('backend.category.training_teacher.import') }}" class="form-ajax">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">{{ trans('labutton.import') }}</h5>
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
                            <button type="button" class="btn" data-dismiss="modal">{{ trans('labutton.close') }}</button>
                            <button type="submit" class="btn"><i class="fa fa-upload"></i> {{ trans('labutton.import') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal right fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
                <form method="post" action="" class="form-ajax" role="form" enctype="multipart/form-data" id="form_save" onsubmit="return false;">
                    <input type="hidden" name="id" value="">
                    <div class="modal-header">
                        <div class="btn-group">
                            <h5 class="modal-title" id="exampleModalLabel"></h5>
                        </div>
                        <div class="btn-group act-btns">
                            @canany(['category-teacher-create', 'category-teacher-edit'])
                                <button type="button" id="btn_save" onclick="saveForm(event)" class="btn save" data-must-checked="false"><i class="fa fa-save"></i> &nbsp;{{ trans('labutton.save') }}</button>
                            @endcanany
                            <button data-dismiss="modal" aria-label="Close" class="btn"><i class="fa fa-times-circle"></i> {{ trans('labutton.cancel') }}</button>
                        </div>
                    </div>
                    <div class="modal-body">
                        <div id="base" class="tab-pane active">

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group row">
                                        <div class="col-sm-4 pr-0 control-label">
                                            <label for="type">{{trans('lacategory.form')}} <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-md-8">
                                            <select name="type" id="type" class="form-control" required data-placeholder="-- {{trans('latraining.choose_form')}} --">
                                                <option value="1">{{trans("lacategory.internal")}}</option>
                                                <option value="2">{{trans("lacategory.outside")}}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12" id="form-internal">
                                    <div class="form-group row">
                                        <div class="col-sm-4 pr-0 control-label">
                                            <label>{{ trans('lacategory.choose_user') }}</label>
                                        </div>
                                        <div class="col-md-8">
                                            <select name="user_id" id="user_id" class="form-control select2">
                                                <option value="" disabled selected>--{{ trans('lacategory.choose_user') }}--</option>
                                                @foreach($get_users_not_regis as $user_not_regis)
                                                    <option value="{{ $user_not_regis->user_id }}">
                                                        {{ $user_not_regis->code . ' - ' . $user_not_regis->lastname . ' ' . $user_not_regis->firstname }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group row">
                                        <div class="col-sm-4 pr-0 control-label">
                                            <label>{{ trans('lacategory.code') }}<span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-md-8">
                                            <input name="code" id="code" type="text" class="form-control" value="" required readonly>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-4 pr-0 control-label">
                                            <label>{{ trans('lacategory.name') }}<span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-md-8">
                                            <input name="name" id="name" type="text" class="form-control" value="" required readonly>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-4 pr-0 control-label">
                                            <label>{{ trans('lacategory.email') }} <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-md-8">
                                            <input name="email" id="email" type="text" class="form-control" value="" required readonly>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-4 pr-0 control-label">
                                            <label>{{ trans('lacategory.phone') }}</label>
                                        </div>
                                        <div class="col-md-8">
                                            <input name="phone" id="phone" type="text" class="form-control" value="">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-4 pr-0 control-label">
                                            <label>{{ trans('lacategory.account_number') }}</label>
                                        </div>
                                        <div class="col-md-8">
                                            <input name="account_number" id="account_number" type="text" class="form-control" value="">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-4 pr-0 control-label">
                                            <label>{{ trans('lacategory.unit') }}</label>
                                        </div>
                                        <div class="col-md-8">
                                            <input id="unit" type="text" class="form-control" value="{{ isset($unit) ? $unit->code . ' - ' . $unit->name : ''
                                             }}" disabled>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-4 pr-0 control-label">
                                            <label>{{ trans('lacategory.title') }}</label>
                                        </div>
                                        <div class="col-md-8">
                                            <input id="title" type="text" class="form-control" value="{{ isset($title) ? $title->code . ' - ' . $title->name :
                                           ''}}" disabled>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-sm-4 pr-0 control-label">
                                            <label for="teacher_type_id">{{ trans('lacategory.teacher_type') }}</label>
                                        </div>
                                        <div class="col-md-8">
                                            <select name="teacher_type_id" id="teacher_type_id" class="form-control select2" data-placeholder="-- {{ trans('lacategory.teacher_type') }} --" >
                                                <option value=""></option>
                                                @foreach($teacher_types as $teacher_type)
                                                    <option value="{{ $teacher_type->id }}">{{ $teacher_type->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-md-4 control-label">
                                            <label for="training_partner_id">{{ trans('lacategory.partner') }} </label>
                                        </div>
                                        <div class="col-md-8">
                                            <select name="training_partner_id" id="training_partner" class="form-control select2" data-placeholder="-- {{ trans('lacategory.partner') }} --" >
                                                <option value=""></option>
                                                @foreach($training_partner as $item)
                                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-sm-4 pr-0 control-label">
                                            <label>{{ trans('latraining.cost') }} <br> ({{ trans('latraining.main_lecturer') }})</label>
                                        </div>
                                        <div class="col-md-8">
                                            <input name="cost_teacher_main" id="cost_teacher_main" type="text" class="form-control is-number" value="">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-sm-4 pr-0 control-label">
                                            <label>{{ trans('latraining.cost') }} <br> ({{ trans('latraining.tutors') }})</label>
                                        </div>
                                        <div class="col-md-8">
                                            <input name="cost_teach_type" id="cost_teach_type" type="text" class="form-control is-number" value="">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-sm-4 pr-0 control-label">
                                            <label>{{ trans('latraining.convention') }}</label>
                                        </div>
                                        <div class="col-md-8 form-inline">
                                            {{ trans('latraining.session') }} =
                                            <input name="num_hour" id="num_hour" type="text" class="form-control is-number w-5" value=""> {{ trans('latraining.hour') }}
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-sm-4 pr-0 control-label">
                                            <label>{{ trans('lacategory.status') }}<span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-md-8">
                                            <label class="radio-inline"><input id="enable" type="radio" required name="status" value="1" checked>{{ trans('lacategory.working') }}</label>
                                            <label class="radio-inline"><input id="disable" type="radio" required name="status" value="0">{{ trans('lacategory.lay_off') }}</label>
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
        var ajax_get_user = "{{ route('backend.category.ajax_get_user') }}";
        function name_formatter(value, row, index) {
            return '<a href="'+ row.edit_url +'" id="edit_'+ row.id +'" style="cursor: pointer;" class="a-color">'+ value +'</a>';
        }
        function status_formatter(value, row, index) {
            return value == 1 ? '<span style="color: #060;">{{ trans("lacategory.working") }}</span>' : '<span style="color: red;">{{ trans("lacategory.lay_off") }}</span>';
        }
        function history_formatter(value, row, index){
            return '<a href="'+ row.history_url +'" style="cursor: pointer;" class=""> <i class="fa fa-list"></i></a>';
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('backend.category.training_teacher.getdata') }}',
            remove_url: '{{ route('backend.category.training_teacher.remove') }}'
        });

        function saveForm(event) {
            let item = $('.save');
            let oldtext = item.html();
            item.html('<i class="fa fa-spinner fa-spin"></i> {{ trans("laother.processing") }}');
            $('.save').attr('disabled',true);
            event.preventDefault();
            $.ajax({
                url: "{{ route('backend.category.training_teacher.save') }}",
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
            $("input[name=id]").val('');
            $('#type').attr("disabled", false);
            $("#teacher_type_id").val('').trigger('change');
            $("#training_partner").val('').trigger('change');
            $("#user_id").val('').trigger('change');
            $("#unit").val('');
            $("#title").val('');
            $("#type").html(`<option value="1" selected>{{trans("backend.internal")}}</option>
                             <option value="2">{{trans("backend.outside")}}</option>`);
            $('#form-internal').show();
            $('#user_id').attr("disabled", false);
            $('#myModal2').modal();
        }
    </script>
    <script src="{{ asset('styles/module/training_teacher/js/training_teacher.js?v='.time()) }}"></script>
@endsection
