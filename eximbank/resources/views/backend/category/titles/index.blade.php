@extends('layouts.backend')

@section('page_title', trans('lacategory.title'))

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lamenu.category'),
                'url' => route('backend.category')
            ],
            [
                'name' => trans('lacategory.title'),
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
        @endif
            @php
                $max_level = \App\Models\Categories\Unit::getMaxUnitLevel();
            @endphp
        <div class="row">
            <div class="col-md-4 form-inline">
                @include('backend.category.titles.filter_tiltes')
            </div>
            <div class="col-md-8 text-right act-btns">
                <div class="pull-right">
                    @can('category-titles-create')
                    <div class="btn-group">
                        <button class="btn" onclick="changeStatus(0,1)" data-status="1">
                            <i class="fa fa-check-circle"></i> &nbsp;{{ trans('labutton.enable') }}
                        </button>
                        <button class="btn" onclick="changeStatus(0,0)" data-status="0">
                            <i class="fa fa-exclamation-circle"></i> &nbsp;{{ trans('labutton.disable') }}
                        </button>
                    </div>
                    <div class="btn-group">
                        <a class="btn" href="{{ download_template('mau_import_chuc_danh.xlsx') }}"><i class="fa fa-download"></i> {{ trans('labutton.import_template') }}</a>
                        <button class="btn" id="import-plan" type="submit" name="task" value="import">
                            <i class="fa fa-upload"></i> {{ trans('labutton.import') }}
                        </button>
                        <a class="btn" href="{{ route('backend.category.titles.export') }}"><i class="fa fa-download"></i> {{ trans('labutton.export') }}</a>
                        <a class="btn" href="{{ route('backend.category.titles.export_simple') }}"><i class="fa fa-download"></i> {{ trans('labutton.export') }} simple</a>
                    </div>
                    @endcan
                    <div class="btn-group">
                        @can('category-titles-create')
                            <button type="button" class="btn btn-demo" onclick="create()">
                                <i class="fa fa-plus-circle"></i> {{ trans('labutton.add_new') }}
                            </button>
                        @endcan
                        @can('category-titles-delete')
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
                    <th data-sortable="true" data-field="code" data-width="10%">{{ trans('lacategory.code') }}</th>
                    <th data-sortable="true" data-field="name" data-formatter="name_formatter">
                        {{ trans('lacategory.name') . ($total_model > 0 ? ' ('.$total_model_active.'/'.$total_model.')' : '') }}
                    </th>
                    <th data-field="title_rank_name">{{ trans('lacategory.title_level') }}</th>
                    <th data-field="unit_type_name">{{ trans('lacategory.unit_type') }}</th>
                    <th data-field="regist" data-align="center" data-formatter="info_formatter" data-width="5%">{{ trans('latraining.info') }}</th>
                    {{-- <th data-field="regist" data-align="center" data-formatter="kpi_formatter" data-width="5%">KPI</th> --}}
                    <th data-field="status" data-align="center" data-formatter="status_formatter" data-width="5%">{{ trans('lacategory.status') }}</th>
                </tr>
            </thead>
        </table>

        <div class="modal fade" id="modal-import" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabelImport" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <form action="{{ route('backend.category.titles.import') }}" method="post" class="form-ajax">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabelImport">{{ trans('lacategory.import_title') }}</h5>
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
    </div>

    <div class="modal right fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
                <form id="form_save" onsubmit="return false;" method="post" action="{{ route('backend.category.titles.save') }}" class="form-validate form-ajax" role="form" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="">
                    <div class="modal-header">
                        <div class="btn-group">
                            <h5 class="modal-title" id="exampleModalLabel"></h5>
                        </div>
                        <div class="btn-group act-btns">
                            @canany(['category-unit-create', 'category-unit-edit'])
                                <button type="button" id="btn_save" onclick="save(event)" class="btn save" data-must-checked="false"><i class="fa fa-save"></i> &nbsp;{{ trans('labutton.save') }}</button>
                            @endcanany
                            <button data-dismiss="modal" aria-label="Close" class="btn"><i class="fa fa-times-circle"></i> {{ trans('labutton.cancel') }}</button>
                        </div>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <div class="col-sm-4 control-label">
                                        <label>{{ trans('lacategory.code') }}<span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-md-7">
                                        <input name="code" type="text" class="form-control" value="" required>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-sm-4 control-label">
                                        <label>{{ trans('lacategory.name') }}<span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-md-7">
                                        <input name="name" type="text" class="form-control" value="" required>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-sm-4 control-label">
                                        <label for="group">{{ trans('lacategory.title_level') }} <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-md-7" id="group_modal">
                                        <select name="group" class="form-control select2" data-placeholder="--{{ trans('lacategory.title_level') }}--" required>
                                            <option value=""></option>
                                            @foreach ($title_ranks as $title_rank)
                                                <option value="{{ $title_rank->id }}"> {{ $title_rank->name }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-sm-4 control-label">
                                        <label for="unit_type">{{ trans('lacategory.unit_type') }}</label>
                                    </div>
                                    <div class="col-md-7" id="unit_type">
                                        <select name="unit_type" class="form-control select2" data-placeholder="--{{ trans('lacategory.unit_type') }}--" required>
                                            <option value=""></option>
                                            @foreach ($units_type as $unit_type)
                                                <option value="{{ $unit_type->id }}"> {{ $unit_type->name }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                {{-- <div class="form-group row">
                                    <div class="col-sm-4 control-label">
                                        <label>{{ trans('laother.total_time_learn_title') }} <br> ({{ trans('latraining.hour') }})</label>
                                    </div>
                                    <div class="col-md-7">
                                        <input name="title_time_kpi" type="number" class="form-control" value="" required>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-sm-4 control-label">
                                        <label>{{ trans('laother.total_time_learn_user') }} <br> ({{ trans('latraining.hour') }})</label>
                                    </div>
                                    <div class="col-md-7">
                                        <input name="user_time_kpi" type="number" class="form-control" value="" required>
                                    </div>
                                </div> --}}

                                @for($i = 1; $i <= 5; $i++)
                                    <div class="form-group row">
                                        <div class="col-sm-4 control-label">
                                            <label for="group">{{ trans('lacategory.unit_level', ['i' => $i]) }}</label>
                                        </div>
                                        <div class="col-md-7">
                                            <select name="unit_id" id="unit-modal-{{ $i }}" class="form-control load-unit" data-placeholder="-- {{ trans('lacategory.unit_level', ['i' => $i]) }} --" data-level="{{ $i }}" data-loadchild="unit-modal-{{ ($i+1) }}">
                                            </select>
                                        </div>
                                    </div>
                                @endfor

                                <div class="form-group row">
                                    <div class="col-sm-4 control-label">
                                        <label>{{ trans('lacategory.status') }} <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-md-7">
                                        <label class="radio-inline">
                                            <input id="enable" required type="radio" name="status" value="1" checked>{{ trans('lacategory.enable') }}
                                        </label>
                                        <label class="radio-inline">
                                            <input id="disable" required type="radio" name="status" value="0" >{{ trans('lacategory.disable') }}
                                        </label>
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
        function info_formatter(value, row, index) {
            return '<a href="javascript:void(0)" class="load-modal" data-url="'+row.info+'"><i class="fa fa-user"></i></a>';
        }

        // function kpi_formatter(value, row, index) {
        //     return '<a href="javascript:void(0)" class="load-modal" data-url="'+row.kpi+'"><i class="fas fa-info-circle"></i></a>';
        // }

        function name_formatter(value, row, index) {
            return '<a class="edit" id="edit_'+ row.id +'" style="cursor: pointer;" onclick="edit('+ row.id +')" class="a-color">'+ row.name +'</a>' ;
        }

        function status_formatter(value, row, index) {
            var status = row.status == 1 ? 'checked' : '';
            var html = `<div class="custom-control custom-switch">
                            <input type="checkbox" `+ status +` onclick="changeStatus(`+row.id+`)" class="custom-control-input" id="customSwitch_`+row.id+`">
                            <label class="custom-control-label" for="customSwitch_`+row.id+`"></label>
                        </div>`;
            return html;
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('backend.category.titles.getdata') }}',
            remove_url: '{{ route('backend.category.titles.remove') }}',
            sort_name: 'id'
        });

        $('#import-plan').on('click', function() {
            $('#modal-import').modal();
        });

        function changeStatus(id,status) {
            if (id && !status) {
                var ids = id;
                var checked = $('#customSwitch_' + id).is(":checked");
                var status = checked == true ? 1 : 0;
            } else {
                var ids = $("input[name=btSelectItem]:checked").map(function(){return $(this).val();}).get();
                if (ids.length <= 0) {
                    show_message('{{ trans("lacore.min_one_course") }}', 'error');
                    return false;
                }
            }
            $.ajax({
                url: "{{ route('backend.category.title.ajax_isopen_publish') }}",
                type: 'post',
                data: {
                    ids: ids,
                    status: status
                }
            }).done(function(data) {
                if (id == 0) {
                    show_message(data.message, data.status);
                }
                $(table.table).bootstrapTable('refresh');
                return false;
            }).fail(function(data) {
                show_message('Lỗi hệ thống', 'error');
                return false;
            });
        };

        function edit(id){
            let item = $('#edit_'+id);
            let oldtext = item.html();
            item.html('<i class="fa fa-spinner fa-spin"></i> {{ trans("laother.please_wait") }}');
            document.querySelector('.edit').style.pointerEvents = 'none';
            var level =  $("input[name=level]").val();
            $.ajax({
                url: "{{ route('backend.category.titles.edit') }}",
                type: 'post',
                data: {
                    id: id,
                }
            }).done(function(data) {
                item.html(oldtext);
                document.querySelector('.edit').style.pointerEvents = 'auto';
                $('#exampleModalLabel').html('{{ trans('labutton.edit') }}');
                $("input[name=id]").val(data.model.id);
                $("input[name=code]").val(data.model.code);
                $("input[name=name]").val(data.model.name);
                // $("input[name=title_time_kpi]").val(data.model.title_time_kpi);
                // $("input[name=user_time_kpi]").val(data.model.user_time_kpi);

				if(data.model.hgroup){
					 $('#group_modal select').prepend($("<option></option>").attr("value", data.model.group).attr("disabled", "disabled").text(data.model.hgroup)); 
				}
				
				$("#group_modal select").val(data.model.group);
				$("#group_modal select").val(data.model.group).change();

                $("#unit_type select").val(data.model.unit_type);
                $("#unit_type select").val(data.model.unit_type).change();

                for (var i = 1; i <= 5; i++) {
                    $("#unit-modal-"+i).html('');
                }
                if (data.unit) {
                    $.each(data.unit, function (index, value) {
                        $("#unit-modal-"+index).html('<option value="'+ value.id +'">'+ value.name + (value.status==0?' (Đã tắt)':'') +'</option>');
                    });
                }

                if (data.model.status == 1) {
                    $('#enable').prop( 'checked', true )
                    $('#disable').prop( 'checked', false )
                } else {
                    $('#enable').prop( 'checked', false )
                    $('#disable').prop( 'checked', true )
                }

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
                url: "{{ route('backend.category.titles.save') }}",
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
            $("#group_modal select").val('').trigger('change');
            $("#unit_type select").val('').trigger('change');
            $("input[name=id]").val('');
            // $("input[name=title_time_kpi]").val('');
            // $("input[name=user_time_kpi]").val('');
            $('#myModal2').modal();
            for (var i = 1; i <= 5; i++) {
                $("#unit-modal-"+i).html('');
            }
        }
    </script>
@endsection
