@extends('layouts.backend')

@section('page_title', trans('backend.subject'))

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lamenu.category'),
                'url' => route('backend.category')
            ],
            [
                'name' => trans('backend.subject'),
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
            <div class="col-md-4">
                @include('backend.category.subject.filter')
            </div>
            <div class="col-md-8 text-right act-btns">
                <div class="pull-right">
                    @can('category-subject-create')
                        <div class="btn-group">
                            <a href="{{ download_template('mau_import_hoc_phan.xlsx') }}" class="btn"><i class="fa fa-download"></i> {{ trans('labutton.import_template') }}</a>
                            <a href="javascript:void(0)" class="btn" data-toggle="modal" data-target="#modal-import"><i class="fa fa-upload"></i>{{ trans('labutton.import') }}</a>
                            <a class="btn" href="{{ route('backend.category.subject.export') }}">
                                <i class="fa fa-download"></i> {{ trans('labutton.export') }}
                            </a>
                        </div>
                        <div class="btn-group">
                            <button class="btn" onclick="changeStatus(0,1)" data-status="1">
                                <i class="fa fa-check-circle"></i> &nbsp;{{ trans('labutton.enable') }}
                            </button>
                            <button class="btn" onclick="changeStatus(0,0)" data-status="0">
                                <i class="fa fa-exclamation-circle"></i> &nbsp;{{ trans('labutton.disable') }}
                            </button>
                        </div>
                    @endcan
                    <div class="btn-group">
                        @can('category-subject-create')
                            <button type="button" class="btn btn-demo" onclick="create()">
                                <i class="fa fa-plus-circle"></i> {{ trans('labutton.add_new') }}
                            </button>
                        @endcan
                        @can('category-subject-delete')
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
                    <th data-sortable="true" data-field="name" data-formatter="name_formatter" data-width="30%">{{ trans('lacategory.name') }}</th>
                    <th data-sortable="true" data-field="level_subject_name" data-width="20%">{{ trans('lacategory.type_subject') }}</th>
                    <th data-sortable="true" data-field="parent_name" data-width="20%">{{ trans('lacategory.training_program') }}</th>
                    <th data-field="regist" data-align="center" data-formatter="info_formatter" data-width="5%">{{ trans('latraining.info') }}</th>
                    <th data-sortable="true" data-field="status" data-align="center" data-formatter="status_formatter" data-width="5%">{{ trans('lacategory.status') }}</th>
                    {{--  <th data-field="subsection" data-align="center" data-formatter="subsection_formatter" data-width="5%">{{ trans('lacategory.subsection') }}</th>  --}}
                    <th data-align="center" data-formatter="edit_related_formatter">{{ trans('latraining.prerequisite_condition') }}</th>
                </tr>
            </thead>
        </table>
    </div>

    <div class="modal fade" id="modal-import" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabelImport" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form method="post" action="{{ route('backend.category.subject.import') }}" class="form-ajax">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabelImport">{{ trans('labutton.import') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="file" name="import_file" id="import_file" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn" data-dismiss="modal">{{ trans('labutton.close') }}</button>
                        <button type="submit" class="btn"><i class="fa fa-upload"></i> {{ trans('labutton.import') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="modal right fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
                <form method="post" action="{{ route('backend.category.subject.save') }}" class="form-ajax" role="form" enctype="multipart/form-data" id="form_save" onsubmit="return false;">
                    <input type="hidden" name="id" value="">
                    <div class="modal-header">
                        <div class="btn-group">
                            <h5 class="modal-title" id="exampleModalLabel"></h5>
                        </div>
                        <div class="btn-group act-btns">
                            @canany(['category-subject-create', 'category-subject-edit'])
                                <button type="button" id="btn_save" onclick="saveForm(event)" class="btn save" data-must-checked="false"><i class="fa fa-save"></i> &nbsp;{{ trans('labutton.save') }}</button>
                            @endcanany
                            <button data-dismiss="modal" aria-label="Close" class="btn"><i class="fa fa-times-circle"></i> {{ trans('labutton.cancel') }}</button>
                        </div>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <div class="col-sm-3 pr-0 control-label">
                                        <label>{{ trans('lacategory.code') }}<span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-md-9">
                                        <input name="code" type="text" class="form-control" value="" required>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-sm-3 pr-0 control-label">
                                        <label>{{ trans('lacategory.name') }}<span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-md-9">
                                        <input name="name" type="text" class="form-control" value="" required>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-sm-3 pr-0 control-label">
                                        <label>{{ trans('lacategory.training_program') }} <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-md-9" id="training_program_id">
                                        <select name="training_program_id" class="form-control load-training-program" data-placeholder="-- {{ trans('lacategory.training_program') }} --" required>
                                            <option value=""></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-3 pr-0 control-label">
                                        <label>{{ trans('lacategory.type_subject') }}<span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-md-9" id="level_subject_id">
                                        <select name="level_subject_id" class="form-control select2 load-level-subject" data-training-program="" data-placeholder="-- {{ trans('lacategory.type_subject') }} --" required>
                                            <option value=""></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-3 control-label">
                                        <label>{{trans('backend.picture')}} <br>(800 x 500)</label>
                                    </div>
                                    <div class="col-md-9">
                                        <a href="javascript:void(0)" id="select-image"> {{trans('latraining.choose_picture')}}</a>
                                        <div id="image-review">
                                        </div>
                                        <input name="image" id="image-select" type="hidden" value="">
                                    </div>
                                </div>
                                {{--  <div class="form-group row">
                                    <div class="col-sm-3 pr-0 control-label">
                                        <label for="subsection">{{ trans('lacategory.subsection') }}</label>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="checkbox" name="subsection" id="subsection" value="">
                                    </div>
                                </div>  --}}
                                <div class="form-group row">
                                    <div class="col-sm-3 pr-0 control-label">
                                        <label for="description">{{ trans('lacategory.brief') }}</label>
                                    </div>
                                    <div class="col-md-9">
                                        <textarea name="description" id="description" rows="4" class="form-control"></textarea>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-3 pr-0 control-label">
                                        <label>{{ trans('lacategory.description') }}</label>
                                    </div>
                                    <div class="col-md-9">
                                        <textarea name="content" id="content" class="form-control ckeditor"></textarea>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-sm-3 pr-0 control-label">
                                        <label>{{ trans('lacategory.status') }}<span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-md-9">
                                        <label class="radio-inline"><input type="radio" class="status" required name="status" value="1" checked>{{ trans('lacategory.enable') }}</label>
                                        <label class="radio-inline"><input type="radio" class="status" required name="status" value="0">{{ trans('lacategory.disable') }}</label>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-3 pr-0 control-label">
                                        <label>{{ trans('latraining.training_calendar_color') }}</label>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="color" class="avatar avatar-40 shadow-sm change_color" value="fff"> {{ trans('latraining.background') }}
                                        <input type="hidden" name="color" id="color" value="">
                                        <input type="checkbox" name="i_text" id="i_text" class="ml-1" value="0"> <label for="i_text">{{ trans('latraining.italic') }}</label>
                                        <input type="checkbox" name="b_text" id="b_text" class="ml-1" value="0"> <label for="b_text">{{ trans('latraining.bold') }}</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
			</div>
		</div>
	</div>

    {{-- MODAL KHÓA HỌC LIÊN QUAN --}}
    <div class="modal right fade" id="modalRelated" tabindex="-1" role="dialog" aria-labelledby="modalrelated">
		<div class="modal-dialog w-50" role="document">
			<div class="modal-content">
                <form method="post" action="{{ route('backend.category.subject.save_related_subject') }}" class="form-ajax" role="form" enctype="multipart/form-data" id="form_save_related" onsubmit="return false;">
                    <input type="hidden" name="id_subject" value="">
                    <div class="modal-header">
                        <div class="btn-group">
                            <h5 class="modal-title" id="exampleModalLabelRelated"></h5>
                        </div>
                        <div class="btn-group act-btns">
                            @canany(['category-subject-create', 'category-subject-edit'])
                                <button type="button" id="btn_save_related" onclick="saveFormRelated(event)" class="btn save" data-must-checked="false"><i class="fa fa-save"></i> &nbsp;{{ trans('labutton.save') }}</button>
                            @endcanany
                            <button data-dismiss="modal" aria-label="Close" class="btn"><i class="fa fa-times-circle"></i> {{ trans('labutton.cancel') }}</button>
                        </div>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="wrapped_1">
                                    <div class="form-group row">
                                        <div class="col-sm-4 control-label">
                                            <label>Khóa học cần hoàn thành</label>
                                        </div>
                                        <div class="col-md-8 pl-1">
                                            <div class="row">
                                                <div class="col-12 p-1">
                                                    <select name="subject_prerequisite" id="subject_prerequisite_id" class="form-control select2" data-placeholder="Chọn chuyên đề">
                                                        <option value=""></option>
                                                        @foreach ($subject as $item)
                                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-4 control-label">
                                            <label>Hoàn thành sau thời gian (ngày)/ Điểm >=</label>
                                        </div>
                                        <div class="col-md-8 pl-1">
                                            <div class="row d_flex_align">
                                                <div class="col-4 p-1">
                                                    <input type="number" class="form-control date_finish_prerequisite" name="date_finish_prerequisite" id="date_finish_prerequisite" placeholder="Nhập số ngày">
                                                </div>
                                                <div class="col-4 p-1">
                                                    <select class="form-control" name="finish_and_score" id="finish_and_score">
                                                        <option value="1">Và</option>
                                                        <option value="2">Hoặc</option>
                                                    </select>
                                                </div>
                                                <div class="col-4 p-1">
                                                    <input type="number" class="form-control score_prerequisite" name="score_prerequisite" id="score_prerequisite" placeholder="Nhập điểm">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-7 control-label">
                                    </div>
                                    <div class="col-md-2">
                                        <select class="form-control text-center" name="select_subject_prerequisite" id="select_subject_prerequisite" data-placeholder="Chọn hình thức">
                                            <option value="1">Và</option>
                                            <option value="2">Hoặc</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="wrapped_2">
                                    <div class="form-group row">
                                        <div class="col-sm-4 control-label">
                                            <label>Chức danh</label>
                                        </div>
                                        <div class="col-md-8 pl-1">
                                            <div class="row d_flex_align">
                                                <div class="col-1 p-1">
                                                    <input type="checkbox" name="status_title" id="status_title">
                                                </div>
                                                <div class="col-11 p-1">
                                                    <select name="title_id" id="title_id" class="load-title form-control" data-placeholder="Chọn chức danh">
                                                        <option value=""></option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-7 control-label">
                                    </div>
                                    <div class="col-md-2">
                                        <select class="form-control text-center" name="select_title" id="select_title" data-placeholder="Chọn hình thức">
                                            <option value="1">Và</option>
                                            <option value="2">Hoặc</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="wrapped_3">
                                    <div class="form-group row">
                                        <div class="col-sm-4 control-label">
                                            <label>Ngày bổ nhiệm chức danh >=</label>
                                        </div>
                                        <div class="col-md-8 pl-1">
                                            <div class="row d_flex_align">
                                                <div class="col-1 p-1">
                                                    <input type="checkbox" name="status_date_title_appointment" id="status_date_title_appointment">
                                                </div>
                                                <div class="col-11 p-1">
                                                    <input type="number" class="form-control" name="date_title_appointment" id="date_title_appointment" placeholder="Nhập số ngày">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-7 control-label">
                                    </div>
                                    <div class="col-md-2">
                                        <select class="form-control text-center" name="select_date_title_appointment" id="select_date_title_appointment" data-placeholder="Chọn hình thức">
                                            <option value="1">Và</option>
                                            <option value="2">Hoặc</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="wrapped_4">
                                    <div class="form-group row">
                                        <div class="col-sm-4 control-label">
                                            <label>Ngày vào làm >=</label>
                                        </div>
                                        <div class="col-md-8 pl-1">
                                            <div class="row d_flex_align">
                                                <div class="col-1 p-1">
                                                    <input type="checkbox" name="status_join_company" id="status_join_company">
                                                </div>
                                                <div class="col-11 p-1">
                                                    <input type="number" class="form-control" name="join_company" id="join_company" placeholder="Nhập số ngày">
                                                </div>
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
        function info_formatter(value, row, index) {
            return '<a href="javascript:void(0)" class="load-modal" data-url="'+row.info+'"><i class="fa fa-user"></i></a>';
        }

        function updated_formatter(value, row, index) {
            return '<a href="javascript:void(0)" class="load-modal" data-url="'+row.user_updated+'"><i class="fa fa-user"></i></a>';
        }

        function name_formatter(value, row, index) {
            return '<a id="edit_'+ row.id +'" style="cursor: pointer;" onclick="edit('+ row.id +')">'+ row.name +'</a>' ;
        }

        function subsection_formatter(value, row, index) {
            if(row.subsection == 1) {
                return '<i class="fas fa-check-circle"></i>'
            } else {
                return '-'
            }
        }

        function status_formatter(value, row, index) {
            var status = row.status == 1 ? 'checked' : '';
            var html = `<div class="custom-control custom-switch">
                            <input type="checkbox" `+ status +` onclick="changeStatus(`+row.id+`)" class="custom-control-input" id="customSwitch_`+row.id+`">
                            <label class="custom-control-label" for="customSwitch_`+row.id+`"></label>
                        </div>`;
            return html;
        }

        function edit_related_formatter(value, row, index) {
            if(row.subsection == 0) {
                if(row.check_isset) {
                    return '<a id="edit_related_'+ row.id +'" style="cursor: pointer;" onclick="editRelated('+ row.id +')"><i class="fas fa-edit"></i></a>' ;
                } else {
                    return '<a style="cursor: pointer;" onclick="createRelated('+ row.id +')"><i class="fas fa-plus-circle"></i></a>' ;
                }
            } else {
                return '-';
            }
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('backend.category.subject.getdata') }}',
            remove_url: '{{ route('backend.category.subject.remove') }}'
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
                url: "{{ route('backend.category.subject.ajax_isopen_publish') }}",
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

        function editRelated(id) {
            let item = $('#edit_related_'+id);
            let oldtext = item.html();
            item.html('<i class="fa fa-spinner fa-spin"></i> {{ trans("laother.please_wait") }}');
            $.ajax({
                url: "{{ route('backend.category.subject.edit_related') }}",
                type: 'post',
                data: {
                    id: id,
                }
            }).done(function(data) {
                item.html(oldtext);
                $('#exampleModalLabelRelated').html('Điều kiện tiên quyết');
                $("input[name=id_subject]").val(id);

                $('#subject_prerequisite_id').val(data.model.subject_prerequisite).trigger('change')
                $('#join_company').val(data.model.join_company)
                $('#date_title_appointment').val(data.model.date_title_appointment)
                $('#title_id').val(data.model.title_id).trigger('change')
                $('#score_prerequisite').val(data.model.score_prerequisite)
                $('#date_finish_prerequisite').val(data.model.date_finish_prerequisite)

                $('#select_title').val(data.model.select_title).trigger('change')
                $('#select_date_title_appointment').val(data.model.select_date_title_appointment).trigger('change')
                $('#select_join_company').val(data.model.select_join_company).trigger('change')

                if (data.model.title_id) {
                    $("#title_id").html('<option value="'+ data.title.id +'">'+ data.title.name +'</option>');
                }

                if(data.model.status_title == 1) {
                    $('#status_title').prop('checked', true).trigger('change')
                } else {
                    $('#status_title').prop('checked', false).trigger('change')
                }

                if(data.model.status_date_title_appointment == 1) {
                    $('#status_date_title_appointment').prop('checked', true).trigger('change')
                } else {
                    $('#status_date_title_appointment').prop('checked', false).trigger('change')
                }

                if(data.model.status_join_company == 1) {
                    $('#status_join_company').prop('checked', true).trigger('change')
                } else {
                    $('#status_join_company').prop('checked', false).trigger('change')
                }

                if(data.model.subject_prerequisite) {
                    $('#finish_and_score').val(data.model.finish_and_score).trigger('change')
                }

                $('#modalRelated').modal();
                return false;
            }).fail(function(data) {
                show_message('Lỗi dữ liệu', 'error');
                return false;
            });
        }

        function createRelated(id) {
            $('#exampleModalLabelRelated').html('Điều kiện tiên quyết');
            $("input[name=id_subject]").val(id);
            $('#subject_prerequisite_id').val('').trigger('change')
            $('#join_company').val('')
            $('#date_title_appointment').val('')
            $('#title_id').val('').trigger('change')
            $('#score_prerequisite').val('')
            $('#date_finish_prerequisite').val('')
            $('#status_title').prop('checked', true)
            $('#status_date_title_appointment').prop('checked', true)
            $('#status_join_company').prop('checked', true)
            $('#modalRelated').modal();
        }

        function saveFormRelated(event) {
            let item = $('.save');
            let oldtext = item.html();
            item.html('<i class="fa fa-spinner fa-spin"></i> {{ trans("laother.processing") }}');
            $('.save').attr('disabled',true);
            event.preventDefault();
            let formData = $("#form_save_related").serialize();
            $.ajax({
                url: "{{ route('backend.category.subject.save_related_subject') }}",
                type: 'post',
                data: formData,
            }).done(function(data) {
                item.html(oldtext);
                $('.save').attr('disabled',false);
                if (data && data.status == 'success') {
                    $('#modalRelated').modal('hide');
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

        function saveForm(event) {
            let item = $('.save');
            let oldtext = item.html();
            item.html('<i class="fa fa-spinner fa-spin"></i> {{ trans("laother.processing") }}');
            $('.save').attr('disabled',true);

            event.preventDefault();
            var content =  CKEDITOR.instances['content'].getData();
            var id = $("input[name=id]").val();
            var code = $("input[name=code]").val();
            var name = $("input[name=name]").val();
            var description = $("#description").val();
            var level_subject_id = $("#level_subject_id select").val();
            var training_program_id = $("#training_program_id select").val();
            var status = $('.status:checked').val();
            var color = $("input[name=color]").val();
            var i_text = $("input[name=i_text]").val();
            var b_text = $("input[name=b_text]").val();
            var image = $("input[name=image]").val();


            if($('#subsection').is(":checked")) {
                var subsection = 1;
            } else {
                var subsection = 0;
            }
            $.ajax({
                url: "{{ route('backend.category.subject.save') }}",
                type: 'post',
                data: {
                    'id' : id,
                    'code' : code,
                    'name' : name,
                    'description' : description,
                    'level_subject_id' : level_subject_id,
                    'training_program_id' : training_program_id,
                    'content' : content,
                    'status' : status,
                    'color' : color,
                    'i_text' : i_text,
                    'b_text' : b_text,
                    'image': image,
                    'subsection': subsection,
                },
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
                item.html(oldtext);
                $('.save').attr('disabled',false);

                show_message('Lỗi dữ liệu', 'error');
                return false;
            });
        }

        function edit(id){
            let item = $('#edit_'+id);
            let oldtext = item.html();
            item.html('<i class="fa fa-spinner fa-spin"></i> {{ trans("laother.please_wait") }}');
            $.ajax({
                url: "{{ route('backend.category.subject.edit') }}",
                type: 'post',
                data: {
                    id: id,
                }
            }).done(function(data) {
                item.html(oldtext);
                $('#exampleModalLabel').html('{{ trans('labutton.edit') }}');
                $("input[name=id]").val(data.model.id);
                $("input[name=code]").val(data.model.code);
                $("input[name=name]").val(data.model.name);
                $("#description").val(data.model.description);
                $("#color").val(data.model.color);
                $("#i_text").val(data.model.i_text);
                $("#b_text").val(data.model.b_text);

                $("input[name=image]").val(data.model.image);
                $("#image-review").html('<img class="w-100" src="'+ data.path_image +'" alt="">');

                $("#training_program_id select").html('<option value="'+ data.training_programs.id +'" selected>'+ data.training_programs.name + (data.training_programs.status==0?' (Đã tắt)':'') +'</option>');

                $("#level_subject_id select").attr('data-training-program', data.training_programs.id);
                $("#level_subject_id select").html('<option value="'+ data.level_subject.id +'" selected>'+ data.level_subject.name + (data.level_subject.status==0?' (Đã tắt)':'') +'</option>');

                CKEDITOR.instances.content.setData(data.model.content);

                if (data.model.status == 1) {
                    $('#enable').prop( 'checked', true )
                    $('#disable').prop( 'checked', false )
                } else {
                    $('#enable').prop( 'checked', false )
                    $('#disable').prop( 'checked', true )
                }

                if (data.model.subsection == 1) {
                    $('#subsection').prop( 'checked', true )
                } else {
                    $('#subsection').prop( 'checked', false )
                }

                if (data.model.i_text == 1) {
                    $('#i_text').prop( 'checked', true )
                } else {
                    $('#i_text').prop( 'checked', false )
                }

                if (data.model.b_text == 1) {
                    $('#b_text').prop( 'checked', true )
                } else {
                    $('#b_text').prop( 'checked', false )
                }

                $('#myModal2').modal();
                return false;
            }).fail(function(data) {
                show_message('Lỗi dữ liệu', 'error');
                return false;
            });
        }

        function create() {
            CKEDITOR.instances.content.setData('');
            $('#form_save').trigger("reset");
            $('#myModal2').modal();
            $('#exampleModalLabel').html('{{ trans('labutton.add_new') }}');
            $("input[name=id]").val('');
            $("input[name=code]").val('');
            $("input[name=name]").val('');
            $("#description").val('');
            $("#level_subject_id select").html('');
            $("#training_program_id select").html('');
            $("#level_subject_id select").val('').trigger('change');
            $("#training_program_id select").val('').trigger('change');
            $("input[name=color]").val('');
            $("input[name=i_text]").val('0');
            $("input[name=b_text]").val('0');
            $("input[name=image]").val('');
            $("#image-review").html('<img src="" alt="">');
            $("#level_subject_id select").attr('data-training-program', '');
        }

        $('#training_program_id').on('change', function () {
            var training_program_id = $('#training_program_id option:selected').val();
            console.log(training_program_id);
            $("#level_subject_id select").empty();
            $("#level_subject_id select").data('training-program', training_program_id);
            $("#level_subject_id select").trigger('change');
        });

        $('.change_color').on('change', function () {
            var set_color = $(this).val();
            $("#color").val(set_color);
        });

        $('#i_text').on('click', function () {
            if($(this).is(':checked')){
                $('#i_text').val(1);
            }else{
                $('#i_text').val(0);
            }
        });
        $('#b_text').on('click', function () {
            if($(this).is(':checked')){
                $('#b_text').val(1);
            }else{
                $('#b_text').val(0);
            }
        });

        $("#select-image").on('click', function () {
            var lfm = function (options, cb) {
                var route_prefix = '/filemanager';
                window.open(route_prefix + '?type=' + options.type || 'file', 'FileManager', 'width=1100,height=600');
                window.SetUrl = cb;
            };

            lfm({type: 'image'}, function (url, path) {
                $("#image-review").html('<img class="w-100" src="'+ path +'">');
                $("#image-select").val(path);
            });
        });

        $('#finish_and_score').attr('disabled', 'disabled')
        $('.date_finish_prerequisite').attr('disabled', 'disabled')
        $('.score_prerequisite').attr('disabled', 'disabled')
        $('#select_subject_prerequisite').attr('disabled', 'disabled')

        $('#subject_prerequisite_id').on('change', function() {
            if($(this).val()) {
                $('#finish_and_score').removeAttr("disabled")
                $('.date_finish_prerequisite').removeAttr("disabled")
                $('.score_prerequisite').removeAttr("disabled")
                $('#select_subject_prerequisite').removeAttr("disabled")
            } else {
                $('#date_finish_prerequisite').val('');
                $('#score_prerequisite').val('')

                $('#finish_and_score').attr('disabled', 'disabled')
                $('.date_finish_prerequisite').attr('disabled', 'disabled')
                $('.score_prerequisite').attr('disabled', 'disabled')
                $('#select_subject_prerequisite').attr('disabled', 'disabled')
            }
        })

        $('#status_title').on('change', function () {
            if($(this).is(":checked")) {
                $('#title_id').removeAttr("disabled")
                $('#select_title').removeAttr("disabled")
            } else {
                $('#title_id').attr('disabled', 'disabled')
                $('#select_title').attr('disabled', 'disabled')
            }
        })

        $('#status_date_title_appointment').on('change', function () {
            if($(this).is(":checked")) {
                $('#date_title_appointment').removeAttr("disabled")
                $('#select_date_title_appointment').removeAttr("disabled")
            } else {
                $('#date_title_appointment').attr('disabled', 'disabled')
                $('#select_date_title_appointment').attr('disabled', 'disabled')
            }
        })

        $('#status_join_company').on('change', function () {
            if($(this).is(":checked")) {
                $('#join_company').removeAttr("disabled")
            } else {
                $('#join_company').attr('disabled', 'disabled')
            }
        })
    </script>
@endsection
