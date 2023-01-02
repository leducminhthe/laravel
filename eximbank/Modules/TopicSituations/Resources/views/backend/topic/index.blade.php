@extends('layouts.backend')

@section('page_title', trans('lamenu.situations_proccessing'))

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lamenu.situations_proccessing'),
                'url' => ''
            ],
        ]
    @endphp
    @include('layouts.backend.menu_breadcum',$breadcum)
@endsection

@section('content')
    <div role="main">
        <div class="row">
            <div class="col-md-6">
                <form class="form-inline form-search mb-3" id="form-search">
                    <input type="text" name="search" value="" class="form-control " placeholder='{{ trans('lahandle_situations.enter_code_name') }}'>
                    <button type="submit" class="btn"><i class="fa fa-search"></i>&nbsp;{{trans('labutton.search')}}</button>
                </form>
            </div>
            <div class="col-md-6 text-right act-btns">
                <div class="pull-right">
                    <div class="btn-group">
                        @can('topic-situation-isopen')
                            <button class="btn" onclick="changeStatus(0,1)" data-status="1">
                                <i class="fa fa-check-circle"></i> &nbsp;{{ trans('labutton.enable') }}
                            </button>
                            <button class="btn" onclick="changeStatus(0,0)" data-status="0">
                                <i class="fa fa-exclamation-circle"></i> &nbsp;{{ trans('labutton.disable') }}
                            </button>
                        @endcan
                    </div>
                    <div class="btn-group">
                        @can('topic-create')
                            <button style="cursor: pointer;" onclick="create()" class="btn"><i class="fa fa-plus-circle"></i> {{ trans('labutton.add_new') }}</button>
                        @endcan
                        @can('topic-delete')
                            <button class="btn" id="delete-item"><i class="fa fa-trash"></i> {{trans('labutton.delete')}}</button>
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
                    <th data-field="status" data-align="center" data-formatter="status_formatter" data-width="5%">{{ trans('lahandle_situations.status') }}</th>
                    <th data-field="image" data-align="center" data-formatter="image_formatter" data-width="15%">{{ trans('lahandle_situations.image') }}</th>
                    <th data-sortable="true" data-field="name" data-formatter="name_formatter">{{ trans('lahandle_situations.situation_topic_name') }}</th>
                    <th data-field="code">{{ trans('lahandle_situations.situation_topic_code') }}</th>
                    <th data-field="image" data-align="center" data-formatter="situation_formatter" data-width="15%">{{ trans('lahandle_situations.situations_discuss') }}</th>
                </tr>
            </thead>
        </table>
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
                                <button type="button" onclick="save(event)" class="btn save">{{ trans('labutton.save') }}</button>
                            @endcan
                            <button type="button" class="btn" data-dismiss="modal">{{ trans('labutton.close') }}</button>
                        </div>
                    </div>
                    <div class="modal-body" id="body_modal">
                        <input type="hidden" name="id" value="">
                        <div class="form-group row">
                            <div class="col-sm-4 control-label">
                                <label for="code">{{ trans('lahandle_situations.situation_topic_code') }} <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-7">
                                <input name="code" type="text" class="form-control" value="" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-4 control-label">
                                <label for="name">{{ trans('lahandle_situations.situation_topic_name') }} <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-7">
                                <input name="name" type="text" class="form-control" value="" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-4 control-label">
                                <label>{{trans('lahandle_situations.image')}} ({{trans('lahandle_situations.size')}}: 300 x 200)</label>
                            </div>
                            <div class="col-md-7">
                                <a href="javascript:void(0)" id="select-image">{{trans('lahandle_situations.choose_picture')}}</a>
                                <div id="image-review">
                                </div>
                                <input name="image" id="image-select" type="text" class="d-none" value="">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-4 control-label">
                                <label>{{trans('lahandle_situations.status')}} <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-7">
                                <label class="radio-inline">
                                    <input id="enable" class="status" type="radio" required name="status" value="1" checked>{{ trans('lahandle_situations.enable') }}
                                </label>
                                <label class="radio-inline">
                                    <input id="disable" class="status" type="radio" required name="status" value="0">{{ trans('lahandle_situations.disable') }}
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script type="text/javascript">
        function image_formatter(value,row,index) {
            return '<img src="'+ row.image + '" width="100%" height="auto">'
        }

        function name_formatter(value, row, index) {
            return '<a id="edit_'+ row.id +'" style="cursor: pointer;" onclick="edit('+ row.id +')">'+ row.name +'</a>' ;
        }

        function situation_formatter(value, row, index) {
            return '<a href="' + row.all_situation + '"><i class="fas fa-edit"></i></a> / <a href="' + row.add_situation + '"><i class="fa fa-plus-circle" aria-hidden="true"></i></a>'
        }

        function status_formatter(value, row, index) {
            var status = row.isopen == 1 ? 'checked' : '';
            var html = `<div class="custom-control custom-switch">
                            <input type="checkbox" `+ status +` onclick="changeStatus(`+row.id+`)" class="custom-control-input" id="customSwitch_`+row.id+`">
                            <label class="custom-control-label" for="customSwitch_`+row.id+`"></label>
                        </div>`;
            return html;
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.topic_situations.getdata') }}',
            remove_url: '{{ route('module.topic_situations.remove') }}'
        });

        // BẬT/TẮT
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
                url: "{{ route('module.topic_situations.ajax_isopen_publish') }}",
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
                url: "{{ route('module.topic_situations.edit') }}",
                type: 'post',
                data: {
                    id: id,
                }
            }).done(function(data) {
                item.html(oldtext);
                $('#exampleModalLabel').html('{{ trans("labutton.edit") }}');
                $("input[name=id]").val(data.model.id);
                $("input[name=code]").val(data.model.code);
                $("input[name=name]").val(data.model.name);
                $("input[name=image]").val(data.model.image);
                $("#image-review").html('<img class="w-100" src="'+ data.image +'" alt="">');
                
                if (data.model.isopen == 1) {
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
            var status = $('.status:checked').val();
            var image = $("input[name=image]").val();
            event.preventDefault();
            $.ajax({
                url: "{{ route('module.topic_situations.save') }}",
                type: 'post',
                data: {
                    'name': name,
                    'code': code,
                    'id': id,
                    'status': status,
                    'image' : image
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
