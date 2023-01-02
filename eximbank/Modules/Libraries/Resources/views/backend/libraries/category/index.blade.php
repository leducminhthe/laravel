@extends('layouts.backend')

@section('page_title', trans('lamenu.category'))

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lamenu.category'),
                'url' => ''
            ],
        ]
    @endphp
    @include('layouts.backend.menu_breadcum',$breadcum)
@endsection

@section('content')
    <div role="main libraries_category">
        <div class="row">
            <div class="col-md-8">
                <form class="form-inline mb-3" id="form-search">
                    <input type="text" name="search" value="" class="form-control" placeholder="{{ trans('backend.enter_category') }}">
                    <div class="w-24">
                        <select name="category_type" class="select2 form-control" data-placeholder="{{ trans('lalibrary.select_category_type') }}">
                            <option value=""></option>
                            <option value="1">{{trans('backend.category_book')}}</option>
                            <option value="2">{{trans("backend.ebook_category")}}</option>
                            <option value="3">{{trans("backend.document_category")}}</option>
                            <option value="4">{{ trans('backend.video_category') }}</option>
                            <option value="5">{{ trans('lalibrary.audio_category') }}</option>
                            {{--  <option value="6">Danh mục Salekit</option>  --}}
                        </select>
                    </div>
                    <button type="submit" class="btn"><i class="fa fa-search"></i>&nbsp;{{ trans('labutton.search') }}</button>
                </form>
            </div>
            <div class="col-md-4 text-right act-btns">
                <div class="pull-right">
                    <div class="btn-group">
                        @can('libraries-category-create')
                            <button style="cursor: pointer;" onclick="create()" class="btn"><i class="fa fa-plus-circle"></i> {{ trans('labutton.add_new') }}</button>
                        @endcan
                        @can('libraries-category-delete')
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
                    <th data-field="index" data-formatter="index_formatter" data-align="center" data-width="2%">{{ trans('latraining.stt') }}</th>
                    <th data-field="state" data-checkbox="true" data-width="2%"></th>
                    <th data-field="name" data-formatter="name_formatter">{{ trans('backend.category_name') }}</th>
                    <th data-field="parent_name">{{ trans('lalibrary.parent_category') }}</th>
                    <th data-field="type">{{ trans('lalibrary.category_type') }}</th>
                </tr>
            </thead>
        </table>
    </div>


    <div class="modal right fade" id="modal-popup" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog" id="ajax-modal-popup" role="document">
            <form action="" method="post" class="form-ajax" id="form_save" onsubmit="return false;">
                <div class="modal-content">
                    <div class="modal-header">
                        {{-- <h5 class="modal-title" id="exampleModalLabel"></h5> --}}
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <div class="btn-group act-btns">
                            @canany(['category-absent-create', 'category-absent-edit'])
                                <button type="button" onclick="save(event)" class="btn save">{{ trans('labutton.save') }}</button>
                            @endcan
                            <button type="button" class="btn" data-dismiss="modal">{{ trans('labutton.close') }}</button>
                        </div>
                    </div>
                    <div class="modal-body" id="body_modal">
                        <input type="hidden" name="id" value="">
                        <div class="form-group row">
                            <div class="col-sm-3 control-label">
                                <label for="name">{{ trans('backend.category_name') }}<span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-7">
                                <input name="name" type="text" class="form-control" value="" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3 control-label">
                                <label for="parent_id">{{trans('backend.category_type')}} <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-7" id="libraries_type">
                                <select name="type" id="type" class="form-control select2" data-placeholder="--{{trans('backend.choose_category_type')}}--">
                                    <option value=""></option>
                                    <option value="1">{{trans('backend.category_book')}}</option>
                                    <option value="2">{{trans("backend.ebook_category")}}</option>
                                    <option value="3">{{trans("backend.document_category")}}</option>
                                    <option value="4">{{ trans('backend.video_category') }}</option>
                                    <option value="5">{{ trans('lalibrary.audio_category') }}</option>
                                    {{--  <option value="6">Danh mục Salekit</option>  --}}
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3 control-label">
                                <label for="parent_id">{{trans('backend.father_level')}}</label>
                            </div>
                            <div class="col-md-7">
                                <select name="parent_id" id="parent_id" class="form-control select2" data-placeholder="--{{trans('backend.choose_category')}}--">
                                    <option value=""></option>
                                    @foreach($categories as $parent)
                                        <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3 control-label">
                                <label for="name">Màu nền</label>
                            </div>
                            <div class="col-md-7">
                                <input name="bg_mobile" type="color" class="avatar avatar-40 shadow-sm" value="">
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script type="text/javascript" src="{{ asset('styles/module/news/js/news.js') }}"></script>
    <script type="text/javascript">
        function name_formatter(value, row, index) {
            return '<a id="edit_'+ row.id +'" style="cursor: pointer;" onclick="edit('+ row.id +')">'+ row.name +'</a>' ;
        }

        function index_formatter(value, row, index) {
            return (index+1);
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.libraries.category.getdata') }}',
            remove_url: '{{ route('module.libraries.category.remove') }}'
        });

        function edit(id){
            let item = $('#edit_'+id);
            let oldtext = item.html();
            item.html('<i class="fa fa-spinner fa-spin"></i> {{ trans("laother.please_wait") }}');
            $.ajax({
                url: "{{ route('module.libraries.category.edit') }}",
                type: 'post',
                data: {
                    id: id,
                }
            }).done(function(data) {
                item.html(oldtext);
                $('#exampleModalLabel').html('{{ trans('labutton.edit') }} ' + data.model.name);
                $("input[name=id]").val(data.model.id);
                $("input[name=name]").val(data.model.name);
                $("input[name=bg_mobile]").val(data.model.bg_mobile);

                var type = '<option value=""></option>'
                type += '<option value="1" '+ ( data.model.type == 1 ? "selected" : "" ) +'>{{trans('backend.category_book')}}</option>';
                type += '<option value="2" '+ ( data.model.type == 2 ? 'selected' : '' ) +'>{{trans("backend.ebook_category")}}</option>';
                type += '<option value="3" '+ ( data.model.type == 3 ? 'selected' : '' ) +'>{{trans("backend.document_category")}}</option>';
                type += '<option value="4" '+ ( data.model.type == 4 ? 'selected' : '' ) +'>{{ trans('backend.video_category') }}</option>';
                type += '<option value="5" '+ ( data.model.type == 5 ? 'selected' : '' ) +'>{{ trans('lalibrary.audio_category') }}</option>';
                {{--  type += '<option value="6" '+ ( data.model.type == 6 ? 'selected' : '' ) +'>Danh mục Salekit</option>';  --}}
                $('#type').html(type);

                if (data.categories && data.model.parent_id) {
                    var categories = '';
                    $.each(data.categories, function (i, item){
                        if(item.id == data.model.parent_id) {
                            categories += `<option value="`+ item.id +`" selected>`+ item.name +`</option>`;
                        } else {
                            categories += `<option value="`+ item.id +`" >`+ item.name +`</option>`;
                        }
                    });
                    $('#parent_id').html(categories);
                } else {
                    var categories = '';
                    var categories = `<option value="" ></option>`;
                    $.each(data.categories, function (i, item){
                        categories += `<option value="`+ item.id +`" >`+ item.name +`</option>`;
                    });
                    $('#parent_id').html(categories);
                }
                $('#modal-popup').modal();
                return false;
            }).fail(function(data) {
                show_message('{{ trans('laother.data_error') }}', 'error');
                return false;
            });
        }

        function save(event) {
            let item = $('.save');
            let oldtext = item.html();
            item.html('<i class="fa fa-spinner fa-spin"></i> {{ trans("laother.processing") }}');
            $('.save').attr('disabled',true);

            var form = $('#form_save');
            var name =  $("input[name=name]").val();
            var id =  $("input[name=id]").val();
            var parent_id =  $("#parent_id").val();
            var type =  $("#type").val();
            var bg_mobile =  $("input[name=bg_mobile]").val();
            event.preventDefault();
            $.ajax({
                url: "{{ route('module.libraries.category.save') }}",
                type: 'post',
                data: {
                    'name': name,
                    'id': id,
                    'parent_id': parent_id,
                    'type': type,
                    'bg_mobile': bg_mobile,
                }
            }).done(function(data) {
                item.html(oldtext);
                $('.save').attr('disabled',false);
                if (data && data.status == 'success') {
                    $('#modal-popup').modal('hide');
                    show_message(data.message, data.status);
                    $(table.table).bootstrapTable('refresh');
                } else {
                    show_message(data.message, data.status);
                }
                return false;
            }).fail(function(data) {
                show_message('{{ trans('laother.data_error') }}', 'error');
                return false;
            });
        }

        function create() {
            var type = '<option value=""></option>'
            type += '<option value="1">{{trans('backend.category_book')}}</option>';
            type += '<option value="2">{{trans("backend.ebook_category")}}</option>';
            type += '<option value="3">{{trans("backend.document_category")}}</option>';
            type += '<option value="4">{{ trans('backend.video_category') }}</option>';
            type += '<option value="5">{{ trans('lalibrary.audio_category') }}</option>';
            {{--  type += '<option value="6">Danh mục Salekit</option>';  --}}
            $('#type').html(type);

            $("input[name=name]").val('');
            $("input[name=id]").val('');
            $("#parent_id").val('');
            $('#exampleModalLabel').html('{{ trans('labutton.add_new') }}');
            $('#modal-popup').modal();
        }

        $('#type').on('change',function() {
            $('#parent_id').attr("disabled", false);
            var type = $('#type').val();
            $.ajax({
                type: "POST",
                url: "{{ route('module.libraries.category.ajax_load_parent') }}",
                dataType: 'json',
                data: {
                    type: type,
                },
                success: function (result) {
                    let html = '';
                    $.each(result, function (i, item){
                        html+='<option value=""></option>';
                        html+='<option value='+ item.id +'>'+ item.name +'</option>';
                    });
                    $("#parent_id").html(html);

                    show_message(result.message, result.status);
                    return false;
                }
            });
        })
    </script>
@endsection
