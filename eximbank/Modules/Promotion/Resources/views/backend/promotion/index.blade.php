@extends('layouts.backend')

@section('page_title', trans('lamenu.promotions'))

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lamenu.promotions'),
                'url' => ''
            ],
        ]
    @endphp
    @include('layouts.backend.menu_breadcum',$breadcum)
@endsection

@section('content')
    <div role="main" class="wrapped_promotion">
        <div class="row">
            <div class="col-md-8">
                <form class="form-inline form-search mb-3" id="form-search">
                    <input type="text" name="search" value="" class="form-control " placeholder="{{ trans('backend.enter_gift_name') }}">
                    <button type="submit" class="btn"><i class="fa fa-search"></i>&nbsp;{{ trans('labutton.search') }}</button>
                </form>
            </div>
            <div class="col-md-4 text-right act-btns">
                <div class="pull-right">
                    <div class="btn-group">
                        @can('promotion-create')
                            <button class="btn" onclick="changeStatus(0,1)" data-status="1">
                                <i class="fa fa-check-circle"></i> &nbsp; {{ trans('labutton.enable') }}
                            </button>
                            <button class="btn" onclick="changeStatus(0,0)" data-status="0">
                                <i class="fa fa-exclamation-circle"></i> &nbsp; {{ trans('labutton.disable') }}
                            </button>
                        @endcan
                    </div>
                    <div class="btn-group">
                        @can('promotion-create')
                            <a href="{{ route('module.promotion.create') }}" class="btn"><i class="fa fa-plus-circle"></i> {{ trans('labutton.add_new') }}</a>
                        @endcan
                        @can('promotion-delete')
                            <button class="btn" id="delete-item"><i class="fa fa-trash"></i> {{ trans('labutton.delete') }}</button>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
        <br>
        <div class="table-responsive">
            <table class="tDefault table table-hover bootstrap-table text-nowrap">
                <thead>
                    <tr>
                        <th data-field="state" data-checkbox="true"></th>
                        <th data-field="status" data-align="center" data-formatter="status_formatter" data-width="5%">{{trans('latraining.status')}}</th>
                        <th data-field="code" data-align="center">{{ trans('backend.gift_code') }}</th>
                        <th data-sortable="true" data-width="150px" data-field="name" data-formatter="name_formatter">{{ trans('backend.gift_name') }}</th>
                        <th data-field="images" data-align="center" data-formatter="image_formatter">{{trans('backend.picture')}}</th>
                        <th data-field="point" data-align="center">{{ trans('backend.points_change') }}</th>
                        <th data-field="amount" data-align="center">{{ trans('backend.quantity') }}</th>
                        <th data-field="period" data-align="center">{{ trans('backend.duration') }}</th>
                        <th data-field="rules" data-align="center">{{ trans('backend.regulations') }}</th>
                        <th data-field="contact" data-align="center">{{ trans('backend.contacts') }}</th>
                        <th data-field="groupname" data-align="center">{{ trans('backend.category_group') }}</th>
                        <th data-field="info" data-align="center" data-formatter="info_formatter" data-width="5%">{{ trans('latraining.info') }}</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <div class="modal fade" id="modal-info" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ trans('latraining.info') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="body_modal">
                    <div class="form-group row">
                        <div class="col-sm-4 control-label">
                            <label>{{ trans('backend.user_create') }}</label>
                        </div>
                        <div class="col-md-6">
                            <span class="user_create"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-4 control-label">
                            <label>{{ trans('backend.user_updated') }}</label>
                        </div>
                        <div class="col-md-6">
                            <span class="user_updated"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-4 control-label">
                            <label>{{ trans('backend.created_at') }}</label>
                        </div>
                        <div class="col-md-6">
                            <span class="created_at"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-4 control-label">
                            <label>{{ trans('backend.date_updated') }}</label>
                        </div>
                        <div class="col-md-6">
                            <span class="date_updated"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        function info_formatter(value, row, index) {
            return '<a class="cursor_pointer" onclick="showModalInfo('+ row.id +')"><i class="fas fa-info-circle"></i></a>';
        }

        function name_formatter(value, row, index) {
            return '<a style="width: 150px" href="'+ row.edit_url +'">'+ row.name+'</a>';
        }
        function image_formatter(value,row,index) {
            return '<img src="'+row.images+'" width="150px" height="150px">'
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
            url: '{{ route('module.promotion.getdata') }}',
            remove_url: '{{ route('module.promotion.remove') }}'
        });

        function changeStatus(id,status) {
            if (id && !status) {
                var ids = id;
                var checked = $('#customSwitch_' + id).is(":checked");
                var status = checked == true ? 1 : 0;
            } else {
                var ids = $("input[name=btSelectItem]:checked").map(function(){return $(this).val();}).get();
                if (ids.length <= 0) {
                    show_message('Vui lòng chọn ít nhất 1 nhóm quà tặng', 'error');
                    return false;
                }
            }
            $.ajax({
                url: "{{ route('module.promotion.ajax_is_open') }}",
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

        function showModalInfo(id) {
            $.ajax({
                url: "{{ route('module.promotion.ajax_info') }}",
                type: 'post',
                data: {
                    id: id,
                }
            }).done(function(data) {
                $('.user_create').html(data.created_by);
                $('.user_updated').html(data.updated_by);
                $('.created_at').html(data.created_at2);
                $('.date_updated').html(data.updated_at2);
                $('#modal-info').modal();
                return false;
            }).fail(function(data) {
                show_message('Lỗi hệ thống', 'error');
                return false;
            });
        }
    </script>

@endsection
