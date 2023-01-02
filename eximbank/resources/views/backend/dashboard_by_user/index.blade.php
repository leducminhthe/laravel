@extends('layouts.backend')

@section('page_title', trans('lamenu.user_summary'))

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lamenu.user_summary'),
                'url' => ''
            ],
        ]
    @endphp
    @include('layouts.backend.menu_breadcum',$breadcum)
@endsection

@section('content')
    <div role="main">
        <table class="tDefault table table-hover bootstrap-table text-nowrap">
            <thead>
                <tr>
                    <th data-field="name">{{ trans('lasetting.name') }}</th>
                    <th data-field="regist" data-align="center" data-formatter="info_formatter" data-width="5%">{{ trans('latraining.info') }}</th>
                    <th data-align="center" data-formatter="setting_formatter" data-width="5%">{{ trans('latraining.settings') }}</th>
                </tr>
            </thead>
        </table>
    </div>
    <div class="modal right fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="form_save" onsubmit="return false;" method="post" action="{{ route('backend.dashboard_by_user.save') }}" class="form-validate form-ajax" role="form" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="">
                    <div class="modal-header">
                        <div class="btn-group">
                            <h5 class="modal-title" id="exampleModalLabel"></h5>
                        </div>
                        <div class="btn-group act-btns">
                            @can('dashboard-by-user-edit')
                                <button type="button" id="btn_save" onclick="save(event)" class="btn save" data-must-checked="false"><i class="fa fa-save"></i> &nbsp;{{ trans('labutton.save') }}</button>
                            @endcan
                            <button data-dismiss="modal" aria-label="Close" class="btn"><i class="fa fa-times-circle"></i> {{ trans('labutton.cancel') }}</button>
                        </div>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group row" id="online_complete">
                                    <div class="col-sm-4 control-label">
                                        <label>Hoàn thành khóa học <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-md-7">
                                        <select name="condition_online_complete" id="condition_online_complete" class="form-control select2" data-placeholder="Chọn điều kiện">
                                            <option value=""></option>
                                            <option value="1">Sớm nhất</option>
                                            <option value="2">Gần đây nhất</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row" id="top_in_unit">
                                    <div class="col-sm-4 control-label">
                                        <label>Chọn cấp đơn vị <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-md-7">
                                        <select name="condition_top_in_unit" id="condition_top_in_unit" class="form-control select2" data-placeholder="Chọn điều kiện">
                                            <option value=""></option>
                                            <option value="3">Cấp 3</option>
                                            <option value="4">Cấp 4</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row" id="top_user">
                                    <div class="col-sm-4 control-label">
                                        <label>Nhập số lượng <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-md-7">
                                        <input name="condition_top_user" id="condition_top_user" class="form-control is-number" data-placeholder="Nhập số lượng"/>
                                    </div>
                                </div>
                                <div class="form-group row" id="row_year">
                                    <div class="col-sm-4 control-label">
                                        <label>Năm <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-md-7">
                                        <select name="year" id="year" class="form-control select2" data-placeholder="Chọn năm">
                                            <option value=""></option>
                                            <option value="{{ date('Y') }}"> {{ date('Y') }}</option>
                                            <option value="{{ date('Y') - 1 }}"> {{ date('Y') - 1 }}</option>
                                        </select>
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

        function setting_formatter(value, row, index) {
            return '<a class="edit" id="edit_'+ row.id +'" style="cursor: pointer;" onclick="edit('+ row.id +')" class="a-color"><i class="fa fa-cogs"></i></a>' ;
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('backend.dashboard_by_user.getdata') }}',
        });

        function edit(id){
            let item = $('#edit_'+id);
            let oldtext = item.html();
            item.html('<i class="fa fa-spinner fa-spin"></i> {{ trans("laother.please_wait") }}');
            document.querySelector('.edit').style.pointerEvents = 'none';

            $.ajax({
                url: "{{ route('backend.dashboard_by_user.edit') }}",
                type: 'post',
                data: {
                    id: id,
                }
            }).done(function(data) {
                item.html(oldtext);
                document.querySelector('.edit').style.pointerEvents = 'auto';
                $("input[name=id]").val(data.model.id);
                $('#exampleModalLabel').html('{{ trans('labutton.edit') }}');
                if(data.model.code == 'online_complete'){
                    $('#online_complete').show();
                    $('#row_year').hide();
                    $('#top_in_unit').hide();
                    $('#top_user').hide();

                    $("#condition_online_complete").val(data.model.condition).change();
                    $("#year").val('').change();
                    $("#condition_top_in_unit").val('').change();
                    $("#condition_top_user").val('').change();
                }else{
                    if(data.model.code == 'top_in_unit'){
                        $('#top_in_unit').show();
                        $("#condition_top_in_unit").val(data.model.condition).change();
                    }else{
                        $('#top_in_unit').hide();
                        $("#condition_top_in_unit").val('').change();
                    }

                    if(data.model.code == 'top_user'){
                        $('#top_user').show();
                        $("#condition_top_user").val(data.model.condition).change();
                    }else{
                        $('#top_user').hide();
                        $("#condition_top_user").val('').change();
                    }

                    $('#row_year').show();
                    $('#online_complete').hide();

                    $("#year").val(data.model.year).change();
                    $("#condition_online_complete").val('').change();

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
                url: "{{ route('backend.dashboard_by_user.save') }}",
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
    </script>
@endsection
