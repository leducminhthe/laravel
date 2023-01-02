@extends('layouts.backend')

@section('page_title', trans('lamenu.forum'))

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lamenu.forum'),
                'url' => route('module.forum.category')
            ],
            [
                'name' => $cate->name,
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
                    <input type="text" name="search" value="" class="form-control" placeholder="{{trans('laforums.enter_name_forum')}}">
                    <button type="submit" class="btn"><i class="fa fa-search"></i>&nbsp;{{ trans('labutton.search') }}</button>
                </form>
            </div>
            <div class="col-md-6 text-right act-btns">
                <div class="pull-right">
                    @can('forum-status')
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
                        @can('forum-create')
                            <button style="cursor: pointer;" onclick="create()" class="btn"><i class="fa fa-plus-circle"></i> {{ trans('labutton.add_new') }}</button>
                        @endcan
                        @can('forum-delete')
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
                    <th data-field="check" data-checkbox="true" data-width="2%"></th>
                    <th data-field="name" data-formatter="name_formatter">{{ trans('laforums.category_name') }}</th>
                    <th data-align="center" data-width="10%" data-formatter="totalForums">{{ trans('latraining.approved') }}</th>
                    <th data-field="forum" data-align="center" data-width="10%" data-formatter="addForums">{{trans('laforums.posts')}}</th>
                    <th data-field="status" data-width="5%" data-formatter="status_formatter" data-align="center" >{{ trans('laforums.status') }}</th>
                    <th data-field="action" data-width="5%" data-align="center" data-formatter="action_formatter">{{ trans('laforums.approve') }}</th>
                    <th data-class="text-center" data-width="10%" data-formatter="reward_point_formatter">{{ trans('latraining.reward_points') }}</th>
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
                            @canany(['forum-create', 'forum-edit'])
                                <button type="button" onclick="save(event)" class="btn save">{{ trans('labutton.save') }}</button>
                            @endcan
                            <button type="button" class="btn" data-dismiss="modal">{{ trans('labutton.close') }}</button>
                        </div>
                    </div>
                    <div class="modal-body" id="body_modal">
                        <input type="hidden" name="id" value="">
                        <div class="form-group row">
                            <div class="col-sm-4 control-label">
                                <label for="image">{{ trans('laforums.icon') }} <br>({{ trans('laforums.size') }}: 50x50)</label>
                            </div>

                            <div class="col-sm-5">
                                <a href="javascript:void(0)" id="select-image-icon">{{ trans('laforums.choose_picture') }} (*.png, *.jpg)</a>
                                <div id="image-review-icon">
                                </div>
                                <input type="hidden" class="form-control" name="icon" id="image-select-icon" value="">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-4 control-label">
                                <label for="name">{{ trans('laforums.category_name') }} <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-6">
                                <input name="name" type="text" class="form-control" value="">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-4 control-label">
                                <label>{{trans('laforums.status')}} <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-6">
                                <label class="radio-inline">
                                    <input id="enable" class="status" type="radio" required name="status" value="1" checked>{{ trans('laforums.enable') }}
                                </label>
                                <label class="radio-inline">
                                    <input id="disable" class="status" type="radio" required name="status" value="0">{{ trans('laforums.disable') }}
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script type="text/javascript">
        function addForums (value, row, index) {
            return '<a href="'+ row.add_forum +'"><i class="fa fa-plus-circle"></i></a>';
        }

        function totalForums (value, row, index) {
            return row.total_thread_approved+'/'+row.total_thread;
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

        function action_formatter(value, row, index) {
            return '<a href="'+ row.action_url +'"><i class="fa fa-cog"></i></a>';
        }

        function reward_point_formatter(value, row, index) {
            return '<a href="'+row.reward_point+'"><i class="fas fa-edit"></i></a>';
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.forum.getdata', ['cate_id' => $cate_id]) }}',
            remove_url: '{{ route('module.forum.remove', ['cate_id' => $cate_id]) }}'
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
                url: "{{ route('module.forum.ajax_isopen_publish',['cate_id' => $cate_id]) }}",
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
                url: "{{ route('module.forum.edit',['cate_id' => $cate_id]) }}",
                type: 'post',
                data: {
                    id: id,
                }
            }).done(function(data) {
                item.html(oldtext);
                $('#exampleModalLabel').html('{{ trans('labutton.edit') }}');
                $("input[name=id]").val(data.model.id);
                $("input[name=icon]").val(data.model.icon);
                $("input[name=name]").val(data.model.name);
                $("#image-review-icon").html('<img src="'+ data.image +'" class="w-25"> ');
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
            var icon =  $("input[name=icon]").val();
            var status = $('.status:checked').val();
            event.preventDefault();
            $.ajax({
                url: "{{ route('module.forum.save',['cate_id' => $cate_id]) }}",
                type: 'post',
                data: {
                    'name': name,
                    'icon': icon,
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
                show_message('{{ trans('laother.data_error') }}', 'error');
                return false;
            });
        }

        function create() {
            $("input[name=name]").val('');
            $("input[name=id]").val('');
            $("input[name=icon]").val('');
            $("#image-review-icon").html('<img src="" alt="">');
            $('#exampleModalLabel').html('{{ trans('labutton.add_new') }}');
            $('#modal-popup').modal();
        }

        $("#select-image-icon").on('click', function () {
            var lfm = function (options, cb) {
                var route_prefix = '/filemanager';
                window.open(route_prefix + '?type=' + options.type || 'file', 'FileManager', 'width=1100,height=600');
                window.SetUrl = cb;
            };

            lfm({type: 'image'}, function (url, path) {
                $("#image-review-icon").html('<img src="' + path + '" class="w-25">');
                $("#image-select-icon").val(path);
            });
        });
    </script>
@endsection
