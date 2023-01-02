@extends('layouts.backend')

@section('page_title', trans('lacategory.training_program'))

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lamenu.category'),
                'url' => route('backend.category')
            ],
            [
                'name' => trans('lacategory.training_program'),
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

        <div class="row">
            <div class="col-md-5">
                <form class="form-inline form-search mb-3" id="form-search">
                    <input type="text" name="search" value="" class="form-control" placeholder="{{trans('lacategory.enter_code_name')}}">
                    <button type="submit" class="btn"><i class="fa fa-search"></i>&nbsp;{{ trans('labutton.search') }}</button>
                </form>
            </div>
            <div class="col-md-7 text-right act-btns">
                <div class="pull-right">
                    <div class="btn-group">
                        @can('category-training-program-create')
                            <div class="btn-group">
                                <a class="btn" href="{{ download_template('mau_import_chuong_trinh_dao_tao.xlsx') }}"><i class="fa fa-download"></i> {{ trans('labutton.import_template') }}</a>

                                <button class="btn" id="import-plan" type="submit" name="task" value="import">
                                    <i class="fa fa-upload"></i> {{ trans('labutton.import') }}
                                </button>
                            </div>
                            <div class="btn-group">
                                <a class="btn" href="{{ route('backend.category.training_program.export') }}">
                                    <i class="fa fa-download"></i> {{ trans('labutton.export') }}
                                </a>
                            </div>
                        @endcan
                        @can('category-training-program-edit')
                            <button class="btn" onclick="changeStatus(0,1)" data-status="1">
                                <i class="fa fa-check-circle"></i> &nbsp;{{ trans('labutton.enable') }}
                            </button>
                            <button class="btn" onclick="changeStatus(0,0)" data-status="0">
                                <i class="fa fa-exclamation-circle"></i> &nbsp;{{ trans('labutton.disable') }}
                            </button>
                        @endcan
                        @can('category-training-program-create')
                            <button style="cursor: pointer;" onclick="create()" class="btn"><i class="fa fa-plus-circle"></i> {{ trans('labutton.add_new') }}</button>
                        @endcan
                        @can('category-training-program-delete')
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
                    <th data-field="order"  data-align="center" data-formatter="order_formatter" data-width="2%">
                        <a href="javascript:void(0)" onclick="saveOrder()"><i class="far fa-save"></i></a>
                    </th>
                    <th data-sortable="true" data-field="code" data-width="10%">{{ trans('lacategory.code') }}</th>
                    <th data-sortable="true" data-field="name" data-formatter="name_formatter">{{ trans('lacategory.name') }}</th>
                    <th data-field="regist" data-align="center" data-formatter="info_formatter" data-width="5%">{{ trans('latraining.info') }}</th>
                    <th data-sortable="true" data-field="status" data-align="center" data-formatter="status_formatter" data-width="5%">{{ trans('lacategory.status') }}</th>
                </tr>
            </thead>
        </table>
    </div>

    <div class="modal fade" id="modal-import" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="{{ route('backend.category.training_program.import') }}" method="post" class="form-ajax">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">{{ trans('lacategory.import_training_program') }}</h5>
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

    <div class="modal right fade" id="modal-popup" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" id="ajax-modal-popup" role="document">
            <form action="" method="post" class="form-ajax" id="form_save" onsubmit="return false;">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="btn-group">
                            <h5 class="modal-title" id="exampleModalLabel"></h5>
                        </div>
                        <div class="btn-group act-btns">
                            @canany(['category-training-program-create', 'category-training-program-edit'])
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
        function order_formatter(value, row, index) {
            return '<input type="number" id="'+row.id+'" value="'+ row.order +'" name="order['+row.id+']" style="width: 40px; text-align: center" />';
        }

        function info_formatter(value, row, index) {
            return '<a href="javascript:void(0)" class="load-modal" data-url="'+row.info+'"><i class="fa fa-user"></i></a>';
        }

        function name_formatter(value, row, index) {
            return '<a id="edit_'+ row.id +'" style="cursor: pointer;" onclick="edit('+ row.id +')">'+ row.name +'</a>' ;
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
            url: '{{ route('backend.category.training_program.getdata') }}',
            remove_url: '{{ route('backend.category.training_program.remove') }}'
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
                url: "{{ route('backend.category.training_program.ajax_isopen_publish') }}",
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
            $.ajax({
                url: "{{ route('backend.category.training_program.edit') }}",
                type: 'post',
                data: {
                    id: id,
                }
            }).done(function(data) {
                item.html(oldtext);
                $('#exampleModalLabel').html('{{ trans("labutton.edit") }}');
                $('#body_modal').html(`<input type="hidden" name="id" value="`+ data.id +`">
                                        <div class="form-group row">
                                            <div class="col-sm-4 control-label">
                                                <label for="code">{{ trans('lacategory.code') }}</label>
                                            </div>
                                            <div class="col-md-7">
                                                <input name="code" type="text" class="form-control" value="`+ data.code +`" required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-sm-4 control-label">
                                                <label for="name">{{ trans('lacategory.name') }}</label>
                                            </div>
                                            <div class="col-md-7">
                                                <input name="name" type="text" class="form-control" value="`+ data.name +`" required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-sm-4 control-label">
                                                <label>{{ trans('lacategory.status') }} <span class="text-danger">*</span></label>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="radio-inline">
                                                    <input id="enable" class="status" required type="radio" name="status" value="1">{{ trans('lacategory.enable') }}
                                                </label>
                                                <label class="radio-inline">
                                                    <input id="disable" class="status" required type="radio" name="status" value="0">{{ trans('lacategory.disable') }}
                                                </label>
                                            </div>
                                        </div>`)
                $(".status").attr('checked', false);
                if (data.status == 1) {
                    $('#enable').attr( 'checked', true )
                } else {
                    $('#disable').attr( 'checked', true )
                }
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
            var name =  $("input[name=name]").val();
            var id =  $("input[name=id]").val();
            var code =  $("input[name=code]").val();
            var status = $('.status:checked').val();
            event.preventDefault();
            $.ajax({
                url: "{{ route('backend.category.training_program.save') }}",
                type: 'post',
                data: {
                    'name': name,
                    'code': code,
                    'id': id,
                    'status': status,
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
            $('#exampleModalLabel').html('Thêm Chủ đề');
            $('#body_modal').html(`<input name="id" type="hidden" class="form-control" value="">
                                    <div class="form-group row">
                                        <div class="col-sm-4 control-label">
                                            <label for="code">{{ trans('lacategory.code') }}</label>
                                        </div>
                                        <div class="col-md-7">
                                            <input name="code" type="text" class="form-control" value="" required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-4 control-label">
                                            <label for="name">{{ trans('lacategory.name') }}</label>
                                        </div>
                                        <div class="col-md-7">
                                            <input name="name" type="text" class="form-control" value="" required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-4 control-label">
                                            <label>{{ trans('lacategory.status') }} <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="radio-inline"><input required type="radio" class="status" name="status" value="1" checked>{{ trans('lacategory.enable') }}</label>
                                            <label class="radio-inline"><input required type="radio" class="status" name="status" value="0">{{ trans('lacategory.disable') }}</label>
                                        </div>
                                    </div>`)
            $('#modal-popup').modal();
        }

        function saveOrder() {
            var order ={};
            $("input[name^=order]").map(function(key){
                var element_id = $(this).attr('id');
                order[element_id] = $(this).val();
            });
            $.ajax({
                url: "{{ route('backend.category.training_program.save_order') }}",
                type: 'post',
                data: {
                    'order': order,
                }
            }).done(function(data) {
                if(data.status == 'success') {
                    $(table.table).bootstrapTable('refresh');
                } 
                show_message(data.message, data.status);
                return false;
            }).fail(function(data) {
                show_message('Lỗi dữ liệu', 'error');
                return false;
            });
        }
    </script>
@endsection
