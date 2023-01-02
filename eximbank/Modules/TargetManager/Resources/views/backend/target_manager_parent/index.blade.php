@extends('layouts.backend')

@section('page_title', trans('lamenu.target_manager'))

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lamenu.target_manager'),
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
                    <input type="text" name="search" value="" class="form-control " placeholder='{{ trans('latraining.enter_name') }}'>
                    <button type="submit" class="btn"><i class="fa fa-search"></i>&nbsp;{{trans('labutton.search')}}</button>
                </form>
            </div>
            <div class="col-md-6 text-right act-btns">
                <div class="pull-right">
                    <div class="btn-group">
                        @can('target-manager-parent-create')
                            <button style="cursor: pointer;" onclick="create()" class="btn"><i class="fa fa-plus-circle"></i> {{ trans('labutton.add_new') }}</button>
                        @endcan
                        @can('target-manager-parent-delete')
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
                    <th data-field="year" data-align="center" data-width="5%">{{ trans('lanote.year') }}</th>
                    <th data-sortable="true" data-field="name" data-formatter="name_formatter">{{ trans('lacategory.name') }}</th>
                    <th data-formatter="object_formatter" data-align="center" data-width="5%">{{ trans('latraining.object') }}</th>
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
                            @canany(['target-manager-parent-create', 'target-manager-parent-edit'])
                                <button type="button" onclick="save(event)" class="btn save">{{ trans('labutton.save') }}</button>
                            @endcan
                            <button type="button" class="btn" data-dismiss="modal">{{ trans('labutton.close') }}</button>
                        </div>
                    </div>
                    <div class="modal-body" id="body_modal">
                        <input type="hidden" name="id" value="">
                        <div class="form-group row">
                            <div class="col-sm-3 control-label">
                                <label for="code">{{ trans('lanote.year') }} <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-9">
                                <select name="year" id="year" class="form-control select2" data-placeholder="{{ trans('lanote.year') }}">
                                    <option value=""></option>
                                    @for ($i = 2020; $i <= (date('Y') + 1); $i++)
                                        <option value={{ $i }}> {{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3 control-label">
                                <label for="name">{{ trans('lacategory.name') }} <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-9">
                                <input name="name" type="text" class="form-control" value="" required>
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

        function object_formatter(value, row, index){
            return '<a href="'+ row.group_object_url +'" class="btn"><i class="fa fa-user"></i></a>';
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.target_manager_parent.getdata') }}',
            remove_url: '{{ route('module.target_manager_parent.remove') }}'
        });

        function edit(id){
            let item = $('#edit_'+id);
            let oldtext = item.html();
            item.html('<i class="fa fa-spinner fa-spin"></i> {{ trans("laother.please_wait") }}');
            $.ajax({
                url: "{{ route('module.target_manager_parent.edit') }}",
                type: 'post',
                data: {
                    id: id,
                }
            }).done(function(data) {
                item.html(oldtext);
                $('#exampleModalLabel').html('{{ trans('labutton.edit') }}');
                $("input[name=id]").val(data.model.id);
                $("input[name=name]").val(data.model.name);
                $("#year").val(data.model.year).trigger('change');
                
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

            event.preventDefault();
            $.ajax({
                url: "{{ route('module.target_manager_parent.save') }}",
                type: 'post',
                data: form.serialize(),
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
            $('#exampleModalLabel').html('{{ trans('labutton.add_new') }}');
            $("input[name=name]").val('');
            $("input[name=id]").val('');
            $('#modal-popup').modal();
        }
    </script>

@endsection
