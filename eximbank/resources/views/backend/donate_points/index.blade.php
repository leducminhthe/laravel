@extends('layouts.backend')

@section('page_title', trans('lamenu.donate_points'))

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lamenu.donate_points'),
                'url' => ''
            ],
        ]
    @endphp
    @include('layouts.backend.menu_breadcum',$breadcum)
@endsection

@section('content')

    <div role="main">
        @if(isset($notifications))
            @foreach($notifications as $notification)
                @if(@$notification->data['messages'])
                    @foreach($notification->data['messages'] as $message)
                    <div class="alert alert-{{ @$notification->data['status'] == 'success' ? 'success' : 'danger' }}">{{ @$notification->data['title'] }}: {!! $message !!}</div>
                    @endforeach
                @else
                    <div class="alert alert-{{ @$notification->data['status'] == 'success' ? 'success' : 'danger' }}">{{ @$notification->data['title'] }}</div>
                @endif
                @php
                $notification->markAsRead();
                @endphp
            @endforeach
        @endif

        <div class="row">
            <div class="col-md-5">
                <form class="form-inline form-search mb-3" id="form-search">
                    <input type="text" name="search" class="form-control" value="" placeholder="{{trans('backend.enter_name')}}">
                    <button type="submit" class="btn"><i class="fa fa-search"></i>&nbsp;{{ trans('labutton.search') }}</button>
                </form>
            </div>
            <div class="col-md-7 text-right act-btns">
                <div class="pull-right">
                    <div class="btn-group">

                        @can('user-create')
                            <div class="btn-group">
                                <button class="btn" id="model-list-template-import"><i class="fa fa-download"></i> {{ trans('labutton.import_template') }}</button>
                                <button class="btn" id="model-list-import"><i class="fa fa-upload"></i> {{ trans('labutton.import') }}</button>
                                <a class="btn" href="{{ route('backend.donate_points.export') }}"><i class="fa fa-download"></i> {{ trans('labutton.export') }}</a>
                            </div>
                        @endcan

                        @can('donate-point-create')
                            <button style="cursor: pointer;" onclick="create()" class="btn"><i class="fa fa-plus-circle"></i> {{ trans('labutton.add_new') }}</button>
                        @endcan
                        @can('donate-point-delete')
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
                    <th data-formatter="name_formatter">{{ trans('backend.receiver') }}</th>
                    <th data-field="score">{{ trans('backend.score') }}</th>
                    <th data-field="note">{{ trans('backend.content') }}</th>
                </tr>
            </thead>
        </table>
    </div>

    <div class="modal fade" id="modal-import" tabindex="-1" role="dialog" aria-labelledby="modal-import" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-import">IMPORT</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <div class="col-md-7 control-label">{{ trans('backend.donate_points') }}</div>
                        <div class="col-md-5">
                            <button class="btn" id="import-user" type="submit" name="task" value="import"><i class="fa fa-upload"></i>
                                {{ trans('labutton.import') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-template-import" tabindex="-1" role="dialog" aria-labelledby="modal-template-import" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-template-import">{{ trans('backend.import_template') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <div class="col-md-7 control-label">{{ trans('backend.import_template') }}</div>
                        <div class="col-md-5">
                            <a class="btn" href="{{ download_template('mau_import_tang_diem.xlsx') }}">
                            <i class="fa fa-download">
                            </i> {{ trans('labutton.import_template') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-import-user" tabindex="-1" role="dialog" aria-labelledby="modal-import-user" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="{{ route('backend.donate_points.import') }}" method="post" class="form-ajax">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modal-import-user">IMPORT {{ (trans('backend.user')) }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="file" name="import_file" id="import_file" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
                        <div class="form-group row mt-2">
                            <div class="col-md-4">
                                <label for="">Chọn khóa chính <span class="text-danger">(*)</span></label>
                            </div>
                            <div class="col-md-8">
                                <label class="radio-inline">
                                    <input type="radio" name="type_import" class="mr-1" value="1" checked>
                                    {{ trans('latraining.employee_code') }}
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="type_import" class="mr-1" value="2">
                                    Username
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="type_import" class="mr-1" value="3">
                                    Email
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
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
                            @canany(['donate-point-create','donate-point-edit'])
                                <button type="button" onclick="save(event)" class="btn save">{{ trans('labutton.save') }}</button>
                            @endcan
                            <button type="button" class="btn" data-dismiss="modal">{{ trans('labutton.close') }}</button>
                        </div>
                    </div>
                    <div class="modal-body" id="body_modal">
                        <input type="hidden" name="id" value="">
                        <div class="form-group row">
                            <div class="col-sm-3 control-label">
                                <label for="user_id">{{ trans('backend.receiver') }}</label>
                            </div>
                            <div class="col-sm-8">
                                <select name="user_id" id="user_id" class="form-control load-user" data-placeholder="{{ trans('backend.receiver') }}">
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3 control-label">
                                <label for="">{{ trans('latraining.title') }}</label>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="title" id="title" value="" title="" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3 control-label">
                                <label for="">{{ trans('lamenu.unit') }}</label>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="unit" id="unit" value="" title="" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3 control-label">
                                <label for="score">{{ trans('backend.score') }}</label>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" class="form-control is-number" name="score" value="">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3 control-label">
                                <label for="note">{{ trans('backend.reason') }}</label>
                            </div>
                            <div class="col-sm-8">
                                <textarea class="form-control" id="note" name="note" rows="5"></textarea>
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

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('backend.donate_points.getdata') }}',
            remove_url: '{{ route('backend.donate_points.remove') }}'
        });

        $('#model-list-import').on('click', function () {
            $('#modal-import').modal();
        });

        $('#import-user').on('click', function () {
            $('#modal-import').hide();
            $('#modal-import-user').modal();
        });

        $('#model-list-template-import').on('click', function () {
            $('#modal-template-import').modal();
        });

        function edit(id){
            let item = $('#edit_'+id);
            let oldtext = item.html();
            item.html('<i class="fa fa-spinner fa-spin"></i> {{ trans("laother.please_wait") }}');
            $.ajax({
                url: "{{ route('backend.donate_points.edit') }}",
                type: 'post',
                data: {
                    id: id,
                }
            }).done(function(data) {
                item.html(oldtext);
                $('#exampleModalLabel').html('{{ trans("labutton.edit") }}');
                $("input[name=id]").val(data.model.id);
                $("#note").val(data.model.note);
                $("input[name=score]").val(data.model.score);
                if (data.title) {
                    $("input[name=title]").val(data.title.name);
                }
                if (data.unit) {
                    $("input[name=unit]").val(data.unit.name);
                }
                $("#user_id").prop('disabled', true);
                $("#user_id").html('<option value="'+ data.profile.user_id +'" selected>'+ data.profile.code +' - '+ data.profile.lastname +' '+ data.profile.firstname +'</option>');
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
            var note =  $("#note").val();
            var id =  $("input[name=id]").val();
            var score =  $("input[name=score]").val();
            var title = $("input[name=title]").val();
            var unit = $("input[name=unit]").val();
            var user_id = $("#user_id").val();
            event.preventDefault();
            $.ajax({
                url: "{{ route('backend.donate_points.save') }}",
                type: 'post',
                data: {
                    'note': note,
                    'score': score,
                    'id': id,
                    'title': title,
                    'unit' : unit,
                    'user_id' : user_id,
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
            $("#user_id").prop('disabled', false);
            
            $("input[name=title]").val('');
            $("input[name=user_id]").val('');
            $("input[name=unit]").val('');
            $("input[name=score]").val('');
            $("input[name=id]").val('');
            $("#note").val('');
            $('#exampleModalLabel').html('{{ trans('labutton.add_new') }}');
            $('#modal-popup').modal();
        }

        $('#user_id').on('change', function () {
            var user_id = $(this).val();

            $.ajax({
                url: "{{ route('backend.donate_points.get_title_unit') }}",
                type: "POST",
                data: {
                    user_id: user_id,
                }
            }).done(function(data) {
                    $('#title').val(data.title);
                    $('#unit').val(data.unit);

                return false;
            }).fail(function(data) {
                show_message('Lỗi hệ thống', 'error');
                return false;
            });
        });
    </script>

@endsection
