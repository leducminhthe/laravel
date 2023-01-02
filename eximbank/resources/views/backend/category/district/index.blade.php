@extends('layouts.backend')

@section('page_title', trans('lacategory.district'))

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lamenu.category'),
                'url' => route('backend.category')
            ],
            [
                'name' => trans('lacategory.district'),
                'url' => ''
            ],
        ]
    @endphp
    @include('layouts.backend.menu_breadcum',$breadcum)
@endsection

@section('content')
    <div role="main">
        <div class="row">
            <div class="col-md-8">
                <form class="form-inline form-search mb-3" id="form-search">
                    <input type="text" name="search" value="" class="form-control w-25" placeholder="{{ trans('lacategory.enter_code_name') }}">
                    <button type="submit" class="btn"><i class="fa fa-search"></i>&nbsp;{{ trans('labutton.search') }}</button>
                </form>
            </div>
            <div class="col-md-4 text-right act-btns">
                <div class="pull-right">
                    <div class="btn-group">
                        @can('category-district-create')
                            <button style="cursor: pointer;" onclick="create()" class="btn"><i class="fa fa-plus-circle"></i> {{ trans('labutton.add_new') }}</button>
                        @endcan
                        @can('category-district-delete')
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
                    <th data-sortable="true" data-field="id" data-width="10%">{{ trans('lacategory.code') }}</th>
                    <th data-sortable="true" data-field="name" data-formatter="name_formatter">{{ trans('lacategory.name') }}</th>
                    <th data-sortable="true" data-field="province" data-width="20%">{{ trans('lacategory.province') }}</th>
                    <th data-field="regist" data-align="center" data-formatter="info_formatter" data-width="5%">{{ trans('latraining.info') }}</th>
                </tr>
            </thead>
        </table>
    </div>

    <div class="modal right fade" id="modal-popup" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" id="ajax-modal-popup" role="document">
            <form action="" method="post" class="form-ajax" id="form_save" onsubmit="return false;">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="btn-group">
                            <h5 class="modal-title" id="exampleModalLabel"></h5>
                        </div>
                        <div class="btn-group act-btns">
                            @canany(['category-district-create', 'category-district-edit'])
                                <button type="button" onclick="save(event)" class="btn save">{{ trans('labutton.save') }}</button>
                            @endcan
                            <button type="button" class="btn" data-dismiss="modal">{{ trans('labutton.close') }}</button>
                        </div>
                    </div>
                    <div class="modal-body" id="body_modal">
                        <div class="form-group row">
                            <div class="col-sm-3 control-label">
                                <label>{{ trans('lacategory.province') }}<span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-8" id="province">
                                <select name="province_id" id="province_id" class="form-control select2">
                                    <option value="">-- {{ trans('lacategory.province') }} --</option>
                                    @foreach($province as $item)
                                        <option value="{{$item->code}}">{{$item->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3 control-label">
                                <label>{{ trans('lacategory.code') }} <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-8">
                                <input type="number" name="id" value="" class="form-control">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3 control-label">
                                <label>{{ trans('lacategory.name') }} <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" name="name" value="" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script type="text/javascript">
        function info_formatter(value, row, index) {
            return '<a href="javascript:void(0)" class="load-modal" data-url="'+row.info+'"><i class="fa fa-user"></i></a>';
        }

        function name_formatter(value, row, index) {
            return '<a id="edit_'+ row.id +'" style="cursor: pointer;" onclick="edit('+ row.id +')">'+ row.name +'</a>' ;
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('backend.category.district.getdata') }}',
            remove_url: '{{ route('backend.category.district.remove') }}'
        });

        function edit(id){
            let item = $('#edit_'+id);
            let oldtext = item.html();
            item.html('<i class="fa fa-spinner fa-spin"></i> {{ trans("laother.please_wait") }}');
            $.ajax({
                url: "{{ route('backend.category.district.edit') }}",
                type: 'post',
                data: {
                    id: id,
                }
            }).done(function(data) {
                item.html(oldtext);
                $('#exampleModalLabel').html('{{ trans("labutton.edit") }}');
                $("input[name=id]").val(data.id);
                $("input[name=name]").val(data.name);
                $("#province select").val(data.province_id);
                $("#province select").val(data.province_id).change();
                $("input[name=id]").attr('disabled',true);
                $('#modal-popup').modal();
                return false;
            }).fail(function(data) {
                show_message('Lỗi dữ liệu', 'error');
                return false;
            });
        }

        function save(event) {
            // let item = $('.save');
            // let oldtext = item.html();
            // item.html('<i class="fa fa-spinner fa-spin"></i> {{ trans("laother.processing") }}');
            // $('.save').attr('disabled',true);

            var form = $('#form_save');
            var name =  $("input[name=name]").val();
            var id =  $("input[name=id]").val();
            var province_id = $('#province_id').val();
            event.preventDefault();
            $.ajax({
                url: "{{ route('backend.category.district.save') }}",
                type: 'post',
                data: {
                    'name': name,
                    'id': id,
                    'province_id': province_id,
                }
            }).done(function(data) {
                // item.html(oldtext);
                // $('.save').attr('disabled',false);
                if (data && data.status == 'success') {
                    $('#modal-popup').modal('hide');
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
            $("input[name=id]").attr('disabled',false);
            $("input[name=name]").val('');
            $("input[name=id]").val('');
            $('#exampleModalLabel').html('Thêm Quận huyện');
            $('#modal-popup').modal();
        }
    </script>
@endsection
