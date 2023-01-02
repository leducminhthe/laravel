@extends('layouts.backend')

@section('page_title', trans('lamenu.coaching_group'))

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lamenu.coaching_group'),
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
                    <input type="text" name="search" value="" class="form-control" placeholder="{{trans('backend.search_code_name')}}">
                    <button type="submit" class="btn"><i class="fa fa-search"></i>&nbsp;{{ trans('labutton.search') }}</button>
                </form>
            </div>
            <div class="col-md-4 text-right act-btns">
                <div class="pull-right">
                    @can('coaching-group-create')
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
                        @can('coaching-group-create')
                            <button style="cursor: pointer;" onclick="create()" class="btn"><i class="fa fa-plus-circle"></i> {{ trans('labutton.add_new') }}</button>
                        @endcan
                        @can('coaching-group-delete')
                            <button class="btn" id="delete-item" ><i class="fa fa-trash"></i> {{ trans('labutton.delete') }}</button>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
        <br>

        <table class="tDefault table table-hover bootstrap-table">
            <thead>
                <tr>
                    <th data-field="check" data-checkbox="true" data-width="2%"></th>
                    <th data-field="code" data-width="10%" data-align="center">{{trans('backend.code')}}</th>
                    <th data-field="name" data-formatter="name_formatter">{{trans('backend.name')}}</th>
                    <th data-field="regist" data-align="center" data-formatter="created_formatter" data-width="5%">{{ trans('laother.creator') }}</th>
                    <th data-field="regist" data-align="center" data-formatter="updated_formatter" data-width="5%">{{ trans('laother.editor') }}</th>
                    <th data-field="status" data-align="center" data-formatter="status_formatter" data-width="5%">{{ trans('lacategory.status') }}</th>
                </tr>
            </thead>
        </table>
    </div>

    <div class="modal right fade" id="modal-popup" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog" id="ajax-modal-popup" role="document">
            <form action="" method="post" class="form-ajax" id="form_save" onsubmit="return false;">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <div class="btn-group act-btns">
                            @canany(['coaching-group-create', 'coaching-group-edit'])
                                <button type="button" onclick="save(event)" class="btn save">{{ trans('labutton.save') }}</button>
                            @endcan
                            <button type="button" class="btn" data-dismiss="modal">{{ trans('labutton.close') }}</button>
                        </div>
                    </div>
                    <div class="modal-body" id="body_modal">
                        <input type="hidden" name="id" value="">
                        <div class="form-group row">
                            <div class="col-sm-3 control-label">
                                <label for="code">{{trans('backend.code')}} <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-6">
                                <input name="code" type="text" class="form-control" value="" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3 control-label">
                                <label for="name">{{trans('backend.name')}} <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-6">
                                <input name="name" type="text" class="form-control" value="" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3 control-label">
                                <label>{{ trans('lacategory.status') }} <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-6">
                                <label class="radio-inline"><input id="enable" type="radio" required name="status" value="1"> {{ trans('lacategory.enable') }}</label>
                                <label class="radio-inline"><input id="disable" type="radio" required name="status" value="0"> {{ trans('lacategory.disable') }}</label>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script type="text/javascript">
        function created_formatter(value, row, index) {
            return '<a href="javascript:void(0)" class="load-modal" data-url="'+row.user_created+'"><i class="fa fa-user"></i></a>';
        }

        function updated_formatter(value, row, index) {
            return '<a href="javascript:void(0)" class="load-modal" data-url="'+row.user_updated+'"><i class="fa fa-user"></i></a>';
        }

        function name_formatter(value, row, index) {
            return '<a id="edit_'+ row.id +'" style="cursor: pointer;" onclick="edit('+ row.id +')">'+ row.name +'</a>' ;
        }

        function status_formatter(value, row, index) {
            var checked = row.status == 1 ? 'checked' : '';
            var html = `<div class="custom-control custom-switch">
                            <input type="checkbox" `+ checked +` onclick="changeStatus(`+row.id+`)" class="custom-control-input" id="customSwitch_`+row.id+`">
                            <label class="custom-control-label" for="customSwitch_`+row.id+`"></label>
                        </div>`;
            return html;
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.coaching_group.getdata') }}',
            remove_url: '{{ route('module.coaching_group.remove') }}',
        });

        function changeStatus(id, status) {
            if (id && !status) {
                var ids = [id];
                var checked = $('#customSwitch_' + id).is(":checked");
                var status = checked == true ? 1 : 0;
            } else {
                var ids = $("input[name=btSelectItem]:checked").map(function(){return $(this).val();}).get();
                if (ids.length <= 0) {
                    show_message('Vui lòng chọn ít nhất 1 dòng', 'error');
                    return false;
                }
            }
            $.ajax({
                url: "{{ route('module.coaching_group.update_status') }}",
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
                url: "{{ route('module.coaching_group.edit') }}",
                type: 'post',
                data: {
                    id: id,
                }
            }).done(function(data) {
                item.html(oldtext);
                $('#exampleModalLabel').html('{{ trans("labutton.edit") }} ' + data.model.name);

                $("input[name=id]").val(data.model.id);
                $("input[name=code]").val(data.model.code);
                $("input[name=name]").val(data.model.name);

                if (data.model.status == 1) {
                    $('#enable').prop( 'checked', true );
                    $('#disable').prop( 'checked', false );
                } else {
                    $('#enable').prop( 'checked', false );
                    $('#disable').prop( 'checked', true );
                }

                $('#modal-popup').modal();
                return false;
            }).fail(function(data) {
                show_message('{{ trans('laother.data_error') }}', 'error');
                return false;
            });
        }

        function save(event) {
            event.preventDefault();

            let item = $('.save');
            let oldtext = item.html();
            item.html('<i class="fa fa-spinner fa-spin"></i> {{ trans("laother.processing") }}');
            $('.save').attr('disabled',true);

            var form = $('#form_save');
            var id =  $("input[name=id]").val();
            var code =  $("input[name=code]").val();
            var name =  $("input[name=name]").val();
            var status = $("input[name=status]:checked").val();

            $.ajax({
                url: "{{ route('module.coaching_group.save') }}",
                type: 'post',
                data: {
                    'id': id,
                    'code': code,
                    'name': name,
                    'status': status,
                }
            }).done(function(data) {
                item.html(oldtext);
                $('.save').attr('disabled',false);

                show_message(data.message, data.status);

                if (data && data.status == 'success') {
                    $('#modal-popup').modal('hide');
                    $(table.table).bootstrapTable('refresh');
                }

                return false;
            }).fail(function(data) {

                show_message('{{ trans('laother.data_error') }}', 'error');
                return false;
            });
        }

        function create() {
            $("input[name=id]").val('');
            $("input[name=code]").val('');
            $("input[name=name]").val('');

            $('#enable').prop( 'checked', true );

            $('#exampleModalLabel').html('Thêm mới');
            $('#modal-popup').modal();
        }
    </script>
@endsection
