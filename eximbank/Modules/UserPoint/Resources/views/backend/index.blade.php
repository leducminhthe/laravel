@extends('layouts.backend')

@section('page_title', $title)

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lamenu.category'),
                'url' => route('backend.category')
            ],
            [
                'name' => $title,
                'url' => ''
            ],
        ]
    @endphp
    @include('layouts.backend.menu_breadcum',$breadcum)
@endsection

@section('content')
    <div role="main">
        @if ($type != 10)
            <div class="row mb-2">
                <div class="col-md-8">
                    <form class="form-inline form-search mb-3" id="form-search">
                        <input type="text" name="search" value="" class="form-control" placeholder='{{trans("lacategory.enter_name")}}'>
                        <button type="submit" class="btn"><i class="fa fa-search"></i>&nbsp;{{ trans('labutton.search') }}</button>
                    </form>
                </div>
                <div class="col-md-4 text-right act-btns">

                </div>
            </div>
            <table class="tDefault table table-hover bootstrap-table" id="table_reward">
                <thead>
                    <tr>
                        <th data-field="state" data-checkbox="true"></th>
                        <th data-field="ikey" data-align="left" data-width="5%">{{ trans('lacategory.key') }}</th>
                        <th data-sortable="true" data-field="name" data-formatter="name_formatter">{{ trans('lacategory.name') }}</th>
                        <th data-field="default_value" data-align="center" data-width="5%">{{ trans('lacategory.point_default') }}</th>
                    </tr>
                </thead>
            </table>
        @else
        <div class="tPanel">
            <ul class="nav nav-pills mb-4" role="tablist" id="mTab">
                <li class="nav-item"><a href="#base" class="nav-link active" role="tab" data-toggle="tab">{{ trans('latraining.other') }}</a></li>
                <li class="nav-item"><a href="#reward-points" class="nav-link" data-toggle="tab">{{ trans('latraining.number_logins') }}</a></li>
            </ul>
            <div class="tab-content">
                <div id="base" class="tab-pane active">
                    <div class="row mb-2">
                        <div class="col-md-8">
                            <form class="form-inline form-search mb-3" id="form-search">
                                <input type="text" name="search" value="" class="form-control" placeholder='{{trans("lacategory.enter_name")}}'>
                                <button type="submit" class="btn"><i class="fa fa-search"></i>&nbsp;{{ trans('labutton.search') }}</button>
                            </form>
                        </div>
                        <div class="col-md-4 text-right act-btns">
                        </div>
                    </div>
                    <table class="tDefault table table-hover bootstrap-table" id="table_reward">
                        <thead>
                            <tr>
                                <th data-field="state" data-checkbox="true"></th>
                                <th data-field="ikey" data-align="left" data-width="5%">{{ trans('lacategory.key') }}</th>
                                <th data-sortable="true" data-field="name" data-formatter="name_formatter">{{ trans('lacategory.name') }}</th>
                                <th data-field="default_value" data-align="center" data-width="5%">{{ trans('lacategory.point_default') }}</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <div id="reward-points" class="tab-pane">
                    <div class="row mb-2">
                        <div class="col-md-8">
                            {{-- <form class="form-inline form-search mb-3" id="form-search-reward"> --}}
                                {{-- <input type="text" name="search" value="" class="form-control" placeholder='{{trans("lacategory.enter_name")}}'> --}}
                                {{-- <button type="submit" class="btn"><i class="fa fa-search"></i>&nbsp;{{ trans('labutton.search') }}</button> --}}
                            {{-- </form> --}}
                        </div>
                        <div class="col-md-4 text-right act-btns">
                            <div class="btn-group">
                                <button type="button" class="btn" onclick="addNewPoint()">
                                    <i class="fa fa-plus-circle"></i> {{ trans('labutton.add_new') }}
                                </button>
                                <button class="btn" id="delete-reward"><i class="fa fa-trash"></i> {{ trans('labutton.delete') }}</button>
                            </div>
                        </div>
                    </div>
                    <table class="tDefault table table-hover bootstrap-table" id="table_reward_point_login">
                        <thead>
                            <tr>
                                <th data-field="state" data-checkbox="true"></th>
                                <th data-field="start_date">{{trans('latraining.start_date')}}</th>
                                <th data-field="end_date">{{trans('latraining.end_date')}}</th>
                                <th data-field="number_login">{{ trans('latraining.number_logins') }}</th>
                                <th data-field="reward_point" data-align="center">{{ trans('lacategory.reward_points') }}</th>
                                <th data-field="edir" data-align="center" data-formatter="edit_reward_formatter">{{ trans('labutton.edit') }}</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
        @endif
    </div>

    {{-- MODAL ĐIỂM THƯỞNG ĐĂNG NHẬP --}}
    <div class="modal fade" id="modal-reward-login" tabindex="-1" role="dialog" aria-labelledby="modal_reward_login" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog" id="ajax-modal-popup" role="document">
            <form action="" method="post" class="form-ajax" id="form_save_reward_login" onsubmit="return false;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modal_reward_login"></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="body_modal">
                        <input type="hidden" name="id_reward" value="">
                        <div class="form-group row">
                            <div class="col-sm-3 control-label">
                                <label>{{trans('latraining.start_date')}}</label>
                            </div>
                            <div class="col-md-6">
                                <input name="start_date" type="text" class="datepicker form-control" placeholder="{{trans('latraining.start_date')}}" autocomplete="off">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3 control-label">
                                <label>{{trans('latraining.end_date')}}</label>
                            </div>
                            <div class="col-md-6">
                                <input name="end_date" type="text" class="datepicker form-control" placeholder="{{trans('latraining.end_date')}}" autocomplete="off">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3 control-label">
                                <label>{{ trans('latraining.number_logins') }}</label>
                            </div>
                            <div class="col-md-6">
                                <input name="number_login" type="text" class="form-control" placeholder="{{ trans('latraining.number_logins') }}" value="" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3 control-label">
                                <label>{{ trans('lacategory.reward_points') }}</label>
                            </div>
                            <div class="col-md-6">
                                <input name="reward_point" type="text" class="form-control" placeholder="{{ trans('lacategory.reward_points') }}" value="" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        @canany(['category-userpoint-item', 'category-userpoint-item-edit'])
                            <button type="button" onclick="saveRewardLogin(event)" class="btn">{{ trans('labutton.save') }}</button>
                        @endcan
                        <button type="button" class="btn" data-dismiss="modal">{{ trans('labutton.close') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL ĐIỂM THƯỜNG --}}
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
                            <div class="col-sm-3 control-label">
                                <label>{{ trans('lacategory.code') }}<span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-6">
                                <input name="ikey" type="text" class="form-control ikey_point" value="" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3 control-label">
                                <label>{{ trans('lacategory.name') }}<span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-6">
                                <input name="name" type="text" class="form-control" value="" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3 control-label">
                                <label>{{ trans('lacategory.value') }}<span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-6">
                                <input name="default_value" type="text" class="form-control is-number" value="" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        @canany(['category-userpoint-item', 'category-userpoint-item-edit'])
                            <button type="button" onclick="save(event)" class="btn">{{ trans('labutton.save') }}</button>
                        @endcan
                        <button type="button" class="btn" data-dismiss="modal">{{ trans('labutton.close') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script type="text/javascript">
        function name_formatter(value, row, index) {
            return '<a style="cursor: pointer;" onclick="edit('+ row.id +')">'+ row.name +'</a>' ;
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.userpoint.getdata',[$type]) }}',
            table: '#table_reward',
            // remove_url: '{{ route('module.userpoint.remove',[$type]) }}'
        });

        function edit(id){
            $.ajax({
                url: "{{ route('module.userpoint.edit',[$type]) }}",
                type: 'post',
                data: {
                    id: id,
                }
            }).done(function(data) {
                $('.ikey_point').attr('disabled', 'disabled');
                $('#exampleModalLabel').html(data.name);
                $("input[name=id]").val(data.id);
                $("input[name=name]").val(data.name);
                $("input[name=ikey]").val(data.ikey);
                $("input[name=default_value]").val(data.default_value);
                $('#modal-popup').modal();
                return false;
            }).fail(function(data) {
                show_message('{{ trans('laother.data_error') }}', 'error');
                return false;
            });
        }

        function save(event) {
            var form = $('#form_save');
            var name =  $("input[name=name]").val();
            var default_value = $("input[name=default_value]").val();
            var id =  $("input[name=id]").val();

            event.preventDefault();
            $.ajax({
                url: "{{ route('module.userpoint.save',[$type]) }}",
                type: 'post',
                data: {
                    'name': name,
                    'default_value': default_value,
                    'id': id,
                }
            }).done(function(data) {
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
            $("input[name=ikey]").val('');
            $("input[name=default_value]").val('');
            $("input[name=id]").val('');
            $('#exampleModalLabel').html('{{$title}}');
            $('#modal-popup').modal();
        }

        var table_reward_login = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.userpoint.reward_login.getdata') }}',
            remove_url: '{{ route('module.userpoint.reward_login.remove') }}',
            table: '#table_reward_point_login',
            detete_button: '#delete-reward',
        });

        function edit_reward_formatter(value, row, index) {
            return '<a style="cursor: pointer;" onclick="editRewardLogin('+ row.id +')"><i class="fas fa-edit"></i></a>' ;
        }

        function addNewPoint() {
            $('#form_save_reward_login')[0].reset();
            $('#modal_reward_login').html('{{ trans('labutton.add_new') }}');
            $('#modal-reward-login').modal();
        }

        function saveRewardLogin(event) {
            var start_date =  $("input[name=start_date]").val();
            var end_date =  $("input[name=end_date]").val();
            var number_login =  $("input[name=number_login]").val();
            var reward_point =  $("input[name=reward_point]").val();
            var id =  $("input[name=id_reward]").val();
            event.preventDefault();
            $.ajax({
                url: "{{ route('module.userpoint.reward_login.save') }}",
                type: 'post',
                data: {
                    'start_date': start_date,
                    'end_date': end_date,
                    'number_login': number_login,
                    'reward_point': reward_point,
                    'id': id,
                }
            }).done(function(data) {
                if (data && data.status == 'success') {
                    $('#modal-reward-login').modal('hide');
                    show_message(data.message, data.status);
                    $(table_reward_login.table).bootstrapTable('refresh');
                } else {
                    show_message(data.message, data.status);
                }
                return false;
            }).fail(function(data) {
                show_message('{{ trans('laother.data_error') }}', 'error');
                return false;
            });
        }

        function editRewardLogin(id){
            $.ajax({
                url: "{{ route('module.userpoint.reward_login.edit') }}",
                type: 'post',
                data: {
                    id: id,
                }
            }).done(function(data) {
                $('#modal_reward_login').html('{{ trans('labutton.edit') }}');
                $("input[name=id_reward]").val(data.id);
                $("input[name=start_date]").val(data.start_date);
                $("input[name=end_date]").val(data.end_date);
                $("input[name=number_login]").val(data.number_login);
                $("input[name=reward_point]").val(data.reward_point);
                $('#modal-reward-login').modal();
                return false;
            }).fail(function(data) {
                show_message('{{ trans('laother.data_error') }}', 'error');
                return false;
            });
        }
    </script>
@endsection
