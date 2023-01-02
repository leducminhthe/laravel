@extends('layouts.backend')

@section('page_title', $page_title)

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lamenu.category'),
                'url' => route('backend.category')
            ],
            [
                'name' => $page_title,
                'url' => ''
            ],
        ]
    @endphp
    @include('layouts.backend.menu_breadcum',$breadcum)
@endsection

@section('content')
    <div role="main">
        <div class="row">
            <div class="col-md-5">
                <form class="form-inline form-search mb-3" id="form-search">
                    <input type="text" name="search" value="" class="form-control w-50" placeholder="{{ trans('latraining.enter_unit_name_search') }}">
                    <button type="submit" class="btn"><i class="fa fa-search"></i>&nbsp;{{ trans('labutton.search') }}</button>
                </form>
            </div>
            <div class="col-md-7 text-right act-btns mt-2">
                <div class="pull-right">
                    <div class="btn-group">
                        @can('category-unit-create')
                            <button type="button" class="btn btn-demo" onclick="create()">
                                <i class="fa fa-plus-circle"></i> {{ trans('labutton.add_new') }}
                            </button>
                        @endcan
                        @can('category-unit-delete')
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
                    <th data-sortable="true" data-field="model" data-width="20%" data-formatter="name_formatter">model</th>
                    <th data-field="description">{{ trans('latraining.description') }}</th>
                    <th data-field="type_name"  data-width="20%">Hình thức</th>
                </tr>
            </thead>
        </table>
    </div>

	<div class="modal right fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
                <form method="post" action=" " class="form-ajax" role="form" enctype="multipart/form-data" id="form_save" onsubmit="return false;">
                    <input type="hidden" name="id" value="">
                    <div class="modal-header">
                        <div class="btn-group">
                            <h5 class="modal-title" id="exampleModalLabel"></h5>
                        </div>
                        <div class="btn-group act-btns">
                            @canany(['category-unit-create', 'category-unit-edit'])
                                <button type="button" id="btn_save" onclick="saveForm(event)" class="btn save" data-must-checked="false"><i class="fa fa-save"></i> &nbsp;{{ trans('labutton.save') }}</button>
                            @endcanany
                            <button data-dismiss="modal" aria-label="Close" class="btn"><i class="fa fa-times-circle"></i> {{ trans('labutton.cancel') }}</button>
                        </div>
                    </div>
                    <div class="modal-body">
                        <div class="tPanel">
                            <ul class="nav nav-pills mb-4" role="tablist" id="mTab">
                                <li class="nav-item">
                                    <a href="#base" class="nav-link active" role="tab" data-toggle="tab">{{ trans('lacategory.info') }}</a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div id="base" class="tab-pane active">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group row">
                                                <div class="col-sm-3 pr-0 control-label">
                                                    <label>Tên model <span class="text-danger">*</span></label>
                                                </div>
                                                <div class="col-md-7">
                                                    <input name="model" type="text" class="form-control" value="" required>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-sm-3 pr-0 control-label">
                                                    <label>{{ trans('latraining.description') }} <span class="text-danger">*</span></label>
                                                </div>
                                                <div class="col-md-7">
                                                    <input name="description" type="text" class="form-control" value="" required>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-sm-3 pr-0 control-label">
                                                    <label>Hình thức <span class="text-danger">*</span></label>
                                                </div>
                                                <div class="col-md-7">
                                                    <select name="type" id="type" class="select2" data-placeholder="Hình thức">
                                                        <option value=""></option>
                                                        <option value="1">Tất cả</option>
                                                        <option value="2">Công ty</option>
                                                        <option value="3">Phân quyền</option>
                                                    </select>
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
        function name_formatter(value, row, index) {
            return '<a id="edit_'+ row.id +'" style="cursor: pointer;" onclick="edit('+ row.id +')" class="a-color">'+ row.model +'</a>' ;
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('backend.master_data.index') }}',
            remove_url: '{{ route('backend.master_data.remove' ) }}',
        });

        function saveForm(event) {
            let item = $('.save');
            let oldtext = item.html();
            item.html('<i class="fa fa-spinner fa-spin"></i> {{ trans("laother.processing") }}');
            $('.save').attr('disabled',true);
            event.preventDefault();
            $.ajax({
                url: "{{ route('backend.master_data.save') }}",
                type: 'post',
                data: $("#form_save").serialize(),
            }).done(function(data) {
                item.html(oldtext);
                $('.save').attr('disabled',false);
                if (data && data.status == 'success') {
                    $(table.table).bootstrapTable('refresh');
                } 
                show_message(data.message, data.status);
                return false;
            }).fail(function(data) {
                show_message('Lỗi dữ liệu', 'error');
                return false;
            });
        }

        function edit(id){
            let item = $('#edit_'+id);
            let oldtext = item.html();
            item.html('<i class="fa fa-spinner fa-spin"></i> {{ trans("laother.please_wait") }}');
            $.ajax({
                url: "{{ route('backend.master_data.edit' ) }}",
                type: 'post',
                data: {
                    id: id,
                }
            }).done(function(data) {
                item.html(oldtext);
                $('#exampleModalLabel').html('{{ trans('labutton.edit') }}');
                $("input[name=id]").val(data.model.id);
                $("input[name=model]").val(data.model.model);
                $("input[name=description]").val(data.model.description);
                $("#type").val(data.model.type).trigger('change');
                $('#myModal2').modal();
                return false;
            }).fail(function(data) {
                show_message('Lỗi dữ liệu', 'error');
                return false;
            });
        }

        function create() {
            $('#exampleModalLabel').html('{{ trans('labutton.add_new') }}');
            $("input[name=id]").val('');
            $("input[name=model]").val('');
            $("input[name=description]").val('');
            $("#type").val('').trigger('change');
            $('#myModal2').modal();
        }
    </script>
@endsection
