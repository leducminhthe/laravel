@extends('layouts.backend')

@section('page_title', trans('lamenu.video_category'))

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lamenu.video_category'),
                'url' => ''
            ],
        ]
    @endphp
    @include('layouts.backend.menu_breadcum',$breadcum)
@endsection

@section('content')
    <div role="main" id="daily-training-category">
        <div class="row">
            <div class="col-md-8">
                <form class="form-inline form-search mb-3" id="form-search">
                    <input type="text" name="search" value="" class="form-control" placeholder="{{ trans('backend.enter_category') }}">
                    <button type="submit" class="btn"><i class="fa fa-search"></i>&nbsp;{{ trans('labutton.search') }}</button>
                </form>
            </div>
            <div class="col-md-4 text-right act-btns">
                <div class="pull-right">
                    <div class="btn-group">
                        @can('daily-training-create')
                            <button style="cursor: pointer;" onclick="create()" class="btn"><i class="fa fa-plus-circle"></i> {{ trans('labutton.add_new') }}</button>
                        @endcan
                        @can('daily-tranining-delete')
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
                    <th data-field="name" data-formatter="name_formatter">{{ trans('lamenu.category') }}</th>
                    <th data-field="number_video" data-width="15%" data-align="center">{{ trans('backend.quantity') }}</th>
                    <th data-align="center" data-width="10%" data-formatter="video_formatter">{{ trans('lamenu.video') }}</th>
                    <th data-class="text-center" data-width="10%" data-formatter="reward_point_formatter">{{ trans('latraining.reward_points') }}</th>
                </tr>
            </thead>
        </table>
        <div id="modalTable" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ trans('backend.authorization') }}<b id="category-name"></b></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table id="table" data-toggle="table" data-sort-name ='a.user_id' data-side-pagination="server" data-pagination="true" data-page-size="20" data-id-field="user_id" data-search="true">
                            <thead>
                            <tr>
                                <th data-field="state" data-checkbox="true"></th>
                                <th data-sortable="true" data-field="code" data-sort-name="a.code">{{ trans('backend.code') }}</th>
                                <th data-field="full_name">{{ trans('backend.fullname') }}</th>
                                <th data-field="unit">{{ trans('lamenu.unit') }}</th>
                                <th data-field="title">{{ trans('latraining.title') }}</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btnSave" >{{ trans('labutton.save') }}</button>
                        <button type="button" class="btn" data-dismiss="modal">{{ trans('labutton.close') }}</button>
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" value="" name="category">
    </div>

    <div class="modal fade" id="modal-popup" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog" id="ajax-modal-popup" role="document">
            <form action="" method="post" class="form-ajax" id="form_save" onsubmit="return false;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel"></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="body_modal">
                        <input type="hidden" name="id" value="">
                        <div class="form-group row">
                            <div class="col-sm-4 control-label">
                                <label>{{ trans('backend.category_name') }}</label>
                            </div>
                            <div class="col-md-7">
                                <input name="name" type="text" placeholder="{{ trans('backend.category_name') }}" class="form-control" value="" >
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        @canany(['daily-training-create', 'daily-training-edit'])
                            <button type="button" onclick="save(event)" class="btn save">{{ trans('labutton.save') }}</button>
                        @endcanany
                        <button type="button" class="btn" data-dismiss="modal">{{ trans('labutton.close') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script type="text/javascript">
        function name_formatter(value, row, index) {
            if (row.name != 'Mặc định'){
                return '<a id="edit_'+ row.id +'" style="cursor: pointer;" onclick="edit('+ row.id +')" class="a-color">'+ row.name +'</a>' ;
            }else{
                return row.name;
            }
        }

        function permisstion_formatter(value, row, index) {
            // return '<a href="javascript:void(0)" data-toggle="modal" class="permission" data-target="#modalTable" data-category="'+row.id+'"><i class="fa fa-users"></i></a>';
            return '<a href="'+ row.permission_url +'" > <i class="fa fa-users"></i></a>';
        }

        function video_formatter(value, row, index) {
            return '<a href="'+row.video_url+'"><i class="fa fa-video"></i></a>';
        }

        function reward_point_formatter(value, row, index) {
            return '<a href="'+row.reward_point+'"><i class="fas fa-edit"></i></a>';
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.daily_training.getdata') }}',
            remove_url: '{{ route('module.daily_training.remove') }}'
        });

        function edit(id){
            let item = $('#edit_'+id);
            let oldtext = item.html();
            item.html('<i class="fa fa-spinner fa-spin"></i> {{ trans("laother.please_wait") }}');
            $.ajax({
                url: "{{ route('module.daily_training.edit') }}",
                type: 'post',
                data: {
                    id: id,
                }
            }).done(function(data) {
                item.html(oldtext);
                $('#exampleModalLabel').html('{{ trans('labutton.edit') }} ' + data.name);
                $("input[name=id]").val(data.id);
                $("input[name=name]").val(data.name);
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
            event.preventDefault();
            $.ajax({
                url: "{{ route('module.daily_training.save') }}",
                type: 'post',
                data: {
                    'name': name,
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
                show_message('{{ trans('laother.data_error') }}', 'error');
                return false;
            });
        }

        function create() {
            $("input[name=name]").val('');
            $("input[name=id]").val('');
            $('#exampleModalLabel').html('{{ trans('labutton.add_new') }}');
            $('#modal-popup').modal();
        }
    </script>
@endsection
