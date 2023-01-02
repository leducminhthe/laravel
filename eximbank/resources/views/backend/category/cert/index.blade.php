@extends('layouts.backend')

@section('page_title', trans('lacategory.level'))

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lamenu.category'),
                'url' => route('backend.category')
            ],
            [
                'name' => trans('lacategory.level'),
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
                    <input type="text" name="search" value="" class="form-control" placeholder="{{trans('lacategory.enter_code_name')}}">
                    <button type="submit" class="btn"><i class="fa fa-search"></i>&nbsp;{{ trans('labutton.search') }}</button>
                </form>
            </div>
            <div class="col-md-4 text-right act-btns">
                <div class="pull-right">
                    <div class="btn-group">
                        @can('category-cert-create')
                            <button style="cursor: pointer;" onclick="create()" class="btn"><i class="fa fa-plus-circle"></i> {{ trans('labutton.add_new') }}</button>
                        @endcan
                        @can('category-cert-delete')
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
                    <th data-sortable="true" data-field="certificate_code">{{ @trans('lacategory.code') }}</th>
                    <th data-sortable="true" data-field="certificate_name" data-formatter="name_formatter">{{ @trans('lacategory.name') }}</th>
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
                            @canany(['category-cert-create', 'category-cert-edit'])
                                <button type="button" onclick="save(event)" class="btn save">{{ trans('labutton.save') }}</button>
                            @endcan
                            <button type="button" class="btn" data-dismiss="modal">{{ trans('labutton.close') }}</button>
                        </div>
                    </div>
                    <div class="modal-body" id="body_modal">

                    </div>
                </div>
            </form>
        </div>
    </div>

    <script type="text/javascript">
        function name_formatter(value, row, index) {
            return '<a id="edit_'+ row.id +'" style="cursor: pointer;" onclick="edit('+ row.id +')">'+ row.certificate_name +'</a>' ;
        }
        function info_formatter(value, row, index) {
            return '<a href="javascript:void(0)" class="load-modal" data-url="'+row.info+'"><i class="fa fa-user"></i></a>';
        }
        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('backend.category.cert.getdata') }}',
            remove_url: '{{ route('backend.category.cert.remove') }}'
        });

        function edit(id){
            let item = $('#edit_'+id);
            let oldtext = item.html();
            item.html('<i class="fa fa-spinner fa-spin"></i> {{ trans("laother.please_wait") }}');
            $.ajax({
                url: "{{ route('backend.category.cert.edit') }}",
                type: 'post',
                data: {
                    id: id,
                }
            }).done(function(data) {
                item.html(oldtext);
                $('#exampleModalLabel').html('{{ trans('labutton.edit') }}');
                $('#body_modal').html(`<input type="hidden" name="id" value="`+ data.id +`">
                                        <div class="form-group row">
                                            <div class="col-sm-3 control-label">
                                                <label for="certificate_code">{{ @trans('lacategory.code') }}<span class="text-danger">*</span></label>
                                            </div>
                                            <div class="col-md-7">
                                                <input name="certificate_code" type="text" class="form-control" value="`+ data.certificate_code +`" required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-sm-3 control-label">
                                                <label for="certificate_name">{{ @trans('lacategory.name') }}<span class="text-danger">*</span></label>
                                            </div>
                                            <div class="col-md-7">
                                                <input name="certificate_name" type="text" class="form-control" value="`+ data.certificate_name +`" required>
                                            </div>
                                        </div>`)
                $('#modal-popup').modal();
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

            var form = $('#form_save');
            var name =  $("input[name=certificate_name]").val();
            var id =  $("input[name=id]").val();
            var code =  $("input[name=certificate_code]").val();
            event.preventDefault();
            $.ajax({
                url: "{{ route('backend.category.cert.save') }}",
                type: 'post',
                data: {
                    'certificate_name': name,
                    'certificate_code': code,
                    'id': id,
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
                show_message('Lỗi dữ liệu', 'error');
                return false;
            });
        }

        function create() {
            $('#exampleModalLabel').html('{{ trans('labutton.add_new') }}');
            $('#body_modal').html(`<input name="id" type="hidden" class="form-control" value="">
                                    <div class="form-group row">
                                        <div class="col-sm-3 control-label">
                                            <label for="certificate_code">{{ @trans('lacategory.code') }}<span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-md-7">
                                            <input name="certificate_code" type="text" class="form-control" value="" required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-3 control-label">
                                            <label for="certificate_name">{{ @trans('lacategory.name') }}<span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-md-7">
                                            <input name="certificate_name" type="text" class="form-control" value="" required>
                                        </div>
                                    </div>`)
            $('#modal-popup').modal();
        }
    </script>
@endsection
