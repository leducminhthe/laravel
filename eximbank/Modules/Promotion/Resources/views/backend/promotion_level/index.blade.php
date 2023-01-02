@extends('layouts.backend')

@section('page_title', trans('lamenu.learning_rank'))

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lamenu.learning_rank'),
                'url' => ''
            ],
        ]
    @endphp
    @include('layouts.backend.menu_breadcum',$breadcum)
@endsection

@section('content')
    <div role="main" class="wrapped_promotion_level">
        <div class="row">
            <div class="col-md-8">
                <form class="form-inline form-search mb-3" id="form-search">
                    <input type="text" name="search" value="" class="form-control " placeholder="{{ trans('latraining.enter_code_name') }}">
                    <button type="submit" class="btn"><i class="fa fa-search"></i>&nbsp;{{ trans('labutton.search') }}</button>
                </form>
            </div>
            <div class="col-md-4 text-right act-btns">
                <div class="pull-right">
                    <div class="btn-group">
                        @can('promotion-level-create')
                            <button class="btn" onclick="changeStatus(0,1)" data-status="1">
                                <i class="fa fa-check-circle"></i> &nbsp;{{ trans('labutton.enable') }}
                            </button>
                            <button class="btn" onclick="changeStatus(0,0)" data-status="0">
                                <i class="fa fa-exclamation-circle"></i> &nbsp;{{ trans('labutton.disable') }}
                            </button>
                        @endcan
                    </div>
                    <div class="btn-group">
                        @can('promotion-level-create')
                            <button style="cursor: pointer;" onclick="create()" class="btn"><i class="fa fa-plus-circle"></i> {{ trans('labutton.add_new') }}</button>
                        @endcan
                        @can('promotion-level-delete')
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
                    <th data-field="code" data-width="10%" data-align="center">{{ trans('backend.badge_code') }}</th>
                    <th data-sortable="true" data-width="25%" data-field="name" data-formatter="name_formatter">{{ trans('backend.badge_name') }}</th>
                    <th data-field="images" data-align="center" data-formatter="image_formatter">{{trans('backend.picture')}}</th>
                    <th data-sortable="true" data-field="level" data-align="center">{{ trans('backend.rank') }}</th>
                    <th data-field="point" data-align="center">{{ trans('backend.points_achieved') }}</th>
                    <th data-field="created_by" data-align="center">{{ trans('backend.user_create') }}</th>
                    <th data-field="created_at2" data-align="center">{{trans('backend.created_at')}}</th>
                    <th data-field="updated_by" data-align="center">{{trans('backend.user_updated')}}</th>
                    <th data-field="updated_at2" data-align="center">{{trans('backend.date_updated')}}</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>

    <div class="modal right fade" id="modal-popup" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog" id="ajax-modal-popup" role="document">
            <form action="" method="post" class="form-ajax" id="form_save" onsubmit="return false;">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="btn-group">
                            <h5 class="modal-title" id="exampleModalLabel"></h5>
                        </div>
                        <div class="btn-group act-btns">
                            @canany(['topic-create', 'topic-edit'])
                                <button type="button" onclick="save(event)" class="btn">{{ trans('labutton.save') }}</button>
                            @endcan
                            <button type="button" class="btn" data-dismiss="modal">{{ trans('labutton.close') }}</button>
                        </div>
                    </div>
                    <div class="modal-body" id="body_modal">
                        <input type="hidden" name="id" value="">
                        <div class="form-group row">
                            <div class="col-sm-4 control-label">
                                <label>{{ trans('backend.badge_code') }} <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-7">
                                <input name="code" type="text" class="form-control" value="" >
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-4 control-label">
                                <label>{{ trans('backend.badge_name') }} <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-7">
                                <input name="name" type="text" class="form-control" value="" >
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-4 control-label">
                                <label>{{ trans('backend.rank') }}<span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-7">
                                <input name="level" type="number" class="form-control" value=""  min="0">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-4 control-label">
                                <label>{{ trans('backend.points_achieved') }}<span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-7">
                                <input name="point" type="number" class="form-control" value=""  min="0">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-4 control-label">
                                <label>{{trans('backend.picture')}} (290 x 290)<span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-5">
                                <a href="javascript:void(0)" id="select-image">{{trans('latraining.choose_picture')}}</a>
                                <div id="image-review" style="border: dashed 1px;height: auto;width: 300px;">
                                </div>
                                <input name="images" id="image-select" type="text" class="d-none" value="">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-4 control-label">
                                <label>{{trans('latraining.status')}} <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-6">
                                <label class="radio-inline">
                                    <input id="enable" class="status" type="radio" required name="status" value="1" checked>{{ trans('latraining.enable') }}
                                </label>
                                <label class="radio-inline">
                                    <input id="disable" class="status" type="radio" required name="status" value="0">{{ trans('latraining.disable') }}
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script type="text/javascript">
        function name_formatter(value, row, index) {
            return '<a id="edit_'+ row.id +'" style="cursor: pointer;" onclick="edit('+ row.id +')">'+ row.name +'</a>' ;
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
            url: '{{ route('module.promotion.level.getdata') }}',
            remove_url: '{{ route('module.promotion.level.remove') }}'
        });

        function changeStatus(id,status) {
            if (id && !status) {
                var ids = id;
                var checked = $('#customSwitch_' + id).is(":checked");
                var status = checked == true ? 1 : 0;
            } else {
                var ids = $("input[name=btSelectItem]:checked").map(function(){return $(this).val();}).get();
                if (ids.length <= 0) {
                    show_message('{{ trans('lacourse.min_one_course ') }}', 'error');
                    return false;
                }
            }
            $.ajax({
                url: "{{ route('module.promotion.level.ajax_is_open') }}",
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
                url: "{{ route('module.promotion.level.edit') }}",
                type: 'post',
                data: {
                    id: id,
                }
            }).done(function(data) {
                item.html(oldtext);
                $('#exampleModalLabel').html('{{ trans("labutton.edit") }}');
                $("input[name=id]").val(data.model.id);
                $("input[name=code]").val(data.model.code);
                $("input[name=level]").val(data.model.level);
                $("input[name=point]").val(data.model.point);
                $("input[name=name]").val(data.model.name);
                $("input[name=images]").val(data.model.images);
                $("#image-review").html('<img class="w-100" src="'+ data.image +'" alt="">');
                if (data.model.status == 1) {
                    $('#enable').prop( 'checked', true )
                    $('#disable').prop( 'checked', false )
                } else {
                    $('#enable').prop( 'checked', false )
                    $('#disable').prop( 'checked', true )
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
            var code =  $("input[name=code]").val();
            var level =  $("input[name=level]").val();
            var point =  $("input[name=point]").val();
            var status = $('.status:checked').val();
            var image = $("input[name=images]").val();
            event.preventDefault();
            $.ajax({
                url: "{{ route('module.promotion.level.save') }}",
                type: 'post',
                data: {
                    'name': name,
                    'code': code,
                    'level': level,
                    'point': point,
                    'id': id,
                    'status': status,
                    'images' : image,
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
            $("input[name=name]").val('');
            $("input[name=id]").val('');
            $("input[name=code]").val('');
            $("input[name=level]").val('');
            $("input[name=point]").val('');
            $("input[name=image]").val('');
            $('#exampleModalLabel').html('{{ trans('labutton.add_new') }}');
            $('#modal-popup').modal();
            $("#image-review").html('<img src="" alt="">');
        }

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
    </script>

@endsection
