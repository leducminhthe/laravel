@extends('layouts.backend')

@section('page_title', trans('lacategory.partner'))

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lamenu.category'),
                'url' => route('backend.category')
            ],
            [
                'name' => trans('lacategory.partner'),
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
                    <input type="text" name="search" value="" class="form-control" placeholder="{{ trans('lacategory.enter_code_name') }}">
                    <button type="submit" class="btn"><i class="fa fa-search"></i>&nbsp;{{ trans('labutton.search') }}</button>

                </form>
            </div>
            <div class="col-md-4 text-right act-btns">
                <div class="pull-right">
                    <div class="btn-group">
                        @can('user-create')
                            <div class="btn-group">
                                <!-- <button class="btn" id="model-list-template-import"><i class="fa fa-download"></i> {{ trans('backend.import_template') }}</button> -->
                                <!-- <button class="btn" id="model-list-import"><i class="fa fa-upload"></i> Import</button> -->
                                <a class="btn" href="{{ route('backend.training_partner_export') }}"><i class="fa fa-download"></i> {{ trans('labutton.export') }}</a>
                            </div>
                        @endcan
                        @can('category-partner-create')
                            <button style="cursor: pointer;" onclick="create()" class="btn"><i class="fa fa-plus-circle"></i> {{ trans('labutton.add_new') }}</button>
                        @endcan
                        @can('category-partner-delete')
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
                    <th data-sortable="true" data-field="code" data-width="10%">{{ trans('lacategory.code') }}</th>
                    <th data-sortable="true" data-field="name" data-formatter="name_formatter">{{ trans('lacategory.name') }}</th>
                    <th data-field="people" >{{ trans('lacategory.contact_person') }}</th>
                    <th data-field="address">{{ trans('lacategory.address') }}</th>
                    <th data-field="email">{{ trans('lacategory.email') }}</th>
                    <th data-field="phone" data-width="10%">{{ trans('lacategory.phone') }}</th>
                    <th data-field="phone" data-width="5%" data-align="center" data-formatter="cost_formatter">{{ trans('latraining.cost') }}</th>
                </tr>
            </thead>
        </table>
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
                            @canany(['category-partner-create', 'category-partner-edit'])
                                <button type="button" onclick="save(event)" class="btn save">{{ trans('labutton.save') }}</button>
                            @endcan
                            <button type="button" class="btn" data-dismiss="modal">{{ trans('labutton.close') }}</button>
                        </div>
                    </div>
                    <div class="modal-body" id="body_modal">
                        <input type="hidden" name="id" value="">
                        <div class="form-group row">
                            <div class="col-sm-4 control-label">
                                <label for="code">{{ trans('lacategory.code') }} <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-7">
                                <input name="code" type="text" class="form-control" value="" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-4 control-label">
                                <label for="name">{{ trans('lacategory.name') }}<span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-7">
                                <input name="name" type="text" class="form-control" value="" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-4 control-label">
                                <label for="people">{{ trans('lacategory.contact_person') }}</label>
                            </div>
                            <div class="col-md-7">
                                <input name="people" type="text" class="form-control" value="">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-4 control-label">
                                <label for="address">{{ trans('lacategory.address') }}</label>
                            </div>
                            <div class="col-md-7">
                                <input name="address" type="text" class="form-control" value="">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-4 control-label">
                                <label for="email">{{ trans('lacategory.email') }}</label>
                            </div>
                            <div class="col-md-7">
                                <input name="email" type="text" class="form-control" value="">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-4 control-label">
                                <label for="phone">{{ trans('lacategory.phone') }}</label>
                            </div>
                            <div class="col-md-7">
                                <input name="phone" type="text" class="form-control" value="">
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

        function cost_formatter(value, row, index) {
            return '<a href="'+ row.cost +'" style="cursor: pointer;"><i class="fas fa-eye"></i></a>' ;
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('backend.category.training_partner.getdata') }}',
            remove_url: '{{ route('backend.category.training_partner.remove') }}'
        });

        function edit(id){
            let item = $('#edit_'+id);
            let oldtext = item.html();
            item.html('<i class="fa fa-spinner fa-spin"></i> {{ trans("laother.please_wait") }}');
            $.ajax({
                url: "{{ route('backend.category.training_partner.edit') }}",
                type: 'post',
                data: {
                    id: id,
                }
            }).done(function(data) {
                item.html(oldtext);
                $('#exampleModalLabel').html('{{ trans("labutton.edit") }}');
                $("input[name=id]").val(data.id);
                $("input[name=code]").val(data.code);
                $("input[name=name]").val(data.name);
                $("input[name=people]").val(data.people);
                $("input[name=address]").val(data.address);
                $("input[name=email]").val(data.email);
                $("input[name=phone]").val(data.phone);
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
            var people = $("input[name=people]").val();
            var address = $("input[name=address]").val();
            var email = $("input[name=email]").val();
            var phone = $("input[name=phone]").val();
            event.preventDefault();
            $.ajax({
                url: "{{ route('backend.category.training_partner.save') }}",
                type: 'post',
                data: {
                    'name': name,
                    'code': code,
                    'id': id,
                    'people': people,
                    'address': address,
                    'email': email,
                    'phone': phone,
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
            $("input[name=name]").val('');
            $("input[name=id]").val('');
            $("input[name=code]").val('');
            $("input[name=people]").val('');
            $("input[name=address]").val('');
            $("input[name=email]").val('');
            $("input[name=phone]").val('');
            $('#exampleModalLabel').html('Thêm đối tác');
            $('#modal-popup').modal();
        }
    </script>
@endsection
