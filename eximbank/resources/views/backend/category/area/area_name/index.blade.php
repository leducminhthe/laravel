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
                    <input type="text" name="search" value="" class="form-control w-50" placeholder="{{ trans('latraining.enter_name') }}">
                    <button type="submit" class="btn"><i class="fa fa-search"></i>&nbsp;{{ trans('labutton.search') }}</button>
                </form>
            </div>
            <div class="col-md-7 text-right act-btns mt-2">
                <div class="pull-right">
                    <div class="btn-group">
                        @can('category-area-create')
                            <button type="button" class="btn btn-demo" onclick="create()">
                                <i class="fa fa-plus-circle"></i> {{ trans('labutton.add_new') }}
                            </button>
                        @endcan
                        @can('category-area-delete')
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
                    <th data-sortable="true" data-field="name" data-width="20%" data-formatter="name_formatter">
                        {{ trans('lacategory.area')}}
                    </th>
                    <th data-sortable="true" data-field="level" data-class="text-center" data-width="10%">{{ trans('laother.levels') }}</th>
                    <th data-field="description" >{{ trans('lacategory.description') }}</th>
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
                                                    <label>{{ trans('lacategory.area_name') }} <span class="text-danger">*</span></label>
                                                </div>
                                                <div class="col-md-7">
                                                    <input name="name" type="text" class="form-control" value="" required>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-sm-3 pr-0 control-label">
                                                    <label>{{ trans('lacategory.area_name') }}(EN) <span class="text-danger">*</span></label>
                                                </div>
                                                <div class="col-md-7">
                                                    <input name="name_en" type="text" class="form-control" value="" required>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-sm-3 pr-0 control-label">
                                                    <label>{{ trans('laother.levels') }} <span class="text-danger">*</span></label>
                                                </div>
                                                <div class="col-md-7">
                                                    <input name="level" id="level" type="number" class="form-control" value="" required>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-sm-3 pr-0 control-label">
                                                    <label>{{ trans('latraining.description') }}</label>
                                                </div>
                                                <div class="col-md-7">
                                                    <textarea id="description" name="description" type="text" class="form-control" rows="5"></textarea>
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
            return '<a id="edit_'+ row.id +'" style="cursor: pointer;" onclick="edit('+ row.id +')" class="a-color">'+ row.name +'</a>' ;
        }
        
        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('backend.category.area_name') }}',
            remove_url: '{{ route('backend.category.area_name.remove' ) }}',
        });

        function saveForm(event) {
            let item = $('.save');
            let oldtext = item.html();
            item.html('<i class="fa fa-spinner fa-spin"></i> {{ trans("laother.processing") }}');
            $('.save').attr('disabled',true);
            event.preventDefault();
            $.ajax({
                url: "{{ route('backend.category.area_name.save') }}",
                type: 'post',
                data: $("#form_save").serialize(),
            }).done(function(data) {
                item.html(oldtext);
                $('.save').attr('disabled',false);
                if (data && data.status == 'success') {
                    show_message(data.message, data.status);
                    location.reload();
                } else {
                    show_message(data.message, data.status);
                }
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
                url: "{{ route('backend.category.area_name.edit' ) }}",
                type: 'post',
                data: {
                    id: id,
                }
            }).done(function(data) {
                item.html(oldtext);
                $('#exampleModalLabel').html('{{ trans('labutton.edit') }}');
                $("input[name=name]").val(data.model.name);
                $("input[name=name_en]").val(data.model.name_en);
                $("input[name=level]").val(data.model.level);
                $("input[name=level]").prop('readOnly', true);
                $("#note1").val(data.model.description);
                $('#myModal2').modal();
                return false;
            }).fail(function(data) {
                show_message('Lỗi dữ liệu', 'error');
                return false;
            });
        }

        function create() {
            $('#exampleModalLabel').html('{{ trans('labutton.add_new') }}');
            $("input[name=name]").val('');
            $("input[name=name_en]").val('');
            $("input[name=level]").val('');
            $("#level").prop('readonly', false);
            $("#note1").val('');
            $('#myModal2').modal();
        }
    </script>
@endsection
