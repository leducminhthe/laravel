@extends('layouts.backend')

@section('page_title', 'Sales Kit')

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => 'Sales Kit',
                'url' => ''
            ],
        ]
    @endphp
    @include('layouts.backend.menu_breadcum',$breadcum)
@endsection

@section('content')
<link rel="stylesheet" type="text/css" href="{{ asset('styles/vendor/jquery-treegrid/jquery.treegrid.min.css?v='.time()) }}" />
<script type="text/javascript" src="{{ asset('styles/vendor/jquery-treegrid/jquery.treegrid.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('styles/vendor/jquery-treegrid/bootstrap-table-treegrid.min.js') }}"></script>

    <div role="main">
        <div class="row">
            <div class="col-md-8">
                <form class="form-inline mb-3" id="form-search">
                    <input type="text" name="search" value="" class="form-control" placeholder="{{ trans('backend.enter_category') }}">
                    <button type="submit" class="btn"><i class="fa fa-search"></i>&nbsp;{{ trans('labutton.search') }}</button>
                </form>
            </div>
            <div class="col-md-4 text-right act-btns">
                <div class="pull-right">
                    <div class="btn-group">
                        @can('saleskit-category-create')
                            <button style="cursor: pointer;" onclick="create()" class="btn"><i class="fa fa-plus-circle"></i> {{ trans('labutton.add_new') }}</button>
                        @endcan
                        @can('saleskit-category-delete')
                            <button class="btn" id="delete-item-saleskit-category"><i class="fa fa-trash"></i> {{ trans('labutton.delete') }}</button>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
        <br>

        <table class="tDefault table table-hover bootstrap-table" id="table-saleskit-category"
            data-tree-enable="true"
            data-page-list="[10, 50, 100, 200, 500]"
            data-page-size="50"
        >
            <thead>
                <tr>
                    <th data-field="state" data-checkbox="true" data-width="2%"></th>
                    <th data-field="index" data-formatter="index_formatter" data-align="center" data-width="2%">{{ trans('latraining.stt') }}</th>
                    <th data-field="name" data-formatter="name_formatter">{{ trans('backend.category_name') }}</th>
                    <th data-field="bg_mobile" data-formatter="bg_mobile_formatter" data-align="center" data-width="5%">Màu</th>
                    {{--  <th data-field="parent_name">{{ trans('lalibrary.parent_category') }}</th>  --}}
                    <th data-field="saleskit" data-formatter="saleskit_formatter" data-align="center" data-width="5%">Sales Kit</th>
                </tr>
            </thead>
        </table>
    </div>


    <div class="modal right fade" id="modal-popup" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog" id="ajax-modal-popup" role="document">
            <form action="" method="post" class="form-ajax" id="form_save" onsubmit="return false;">
                <div class="modal-content">
                    <div class="modal-header">
                        {{-- <h5 class="modal-title" id="exampleModalLabel"></h5> --}}
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <div class="btn-group act-btns">
                            @canany(['category-absent-create', 'category-absent-edit'])
                                <button type="button" onclick="save(event)" class="btn save">{{ trans('labutton.save') }}</button>
                            @endcan
                            <button type="button" class="btn" data-dismiss="modal">{{ trans('labutton.close') }}</button>
                        </div>
                    </div>
                    <div class="modal-body" id="body_modal">
                        <input type="hidden" name="id" value="">
                        <div class="form-group row">
                            <div class="col-sm-3 control-label">
                                <label for="name">{{ trans('backend.category_name') }}<span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-7">
                                <input name="name" type="text" class="form-control" value="" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3 control-label">
                                <label for="parent_id">{{trans('backend.father_level')}}</label>
                            </div>
                            <div class="col-md-7">
                                <select name="parent_id" id="parent_id" class="form-control load-saleskit-category" data-not_id="" data-placeholder="--{{trans('backend.choose_category')}}--">
                                    <option value=""></option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3 control-label">
                                <label for="name">Màu nền</label>
                            </div>
                            <div class="col-md-7">
                                <input name="bg_mobile" type="color" class="avatar avatar-40 shadow-sm" value="">
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script type="text/javascript">
        var $table_saleskit_category = $('#table-saleskit-category');

        $table_saleskit_category.bootstrapTable({
            url: '{{ route('module.saleskit.category.getdata') }}',
            striped: true,
            sidePagination: 'server',
            pagination: true,
            idField: 'id',
            treeShowField: 'name',
            parentIdField: "parent_id",
            onPostBody: function() {
                $table_saleskit_category.treegrid({
                    treeColumn: 2,
                    onChange: function() {
                        $table_saleskit_category.bootstrapTable('resetView')
                    }
                });
                $table_saleskit_category.treegrid('getAllNodes').each(function() {
                    if ($(this).treegrid("getRootNodes")) {
                        if(! /(^|\s)treegrid-parent/.test($(this).attr('class'))){
                            ($(this).find('td:eq(2)').prepend('<span class="treegrid-indent-root"></span>'))
                        }
                        if(/(^|\s)treegrid-expanded/.test($(this).attr('class'))) {
                            $(this).find('td:eq(2) span.treegrid-indent').last().css('background-image', 'url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABGdBTUEAAK/INwWK6QAAABl0RVh0U29mdHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAAGrSURBVDjLxZO7ihRBFIa/6u0ZW7GHBUV0UQQTZzd3QdhMQxOfwMRXEANBMNQX0MzAzFAwEzHwARbNFDdwEd31Mj3X7a6uOr9BtzNjYjKBJ6nicP7v3KqcJFaxhBVtZUAK8OHlld2st7Xl3DJPVONP+zEUV4HqL5UDYHr5xvuQAjgl/Qs7TzvOOVAjxjlC+ePSwe6DfbVegLVuT4r14eTr6zvA8xSAoBLzx6pvj4l+DZIezuVkG9fY2H7YRQIMZIBwycmzH1/s3F8AapfIPNF3kQk7+kw9PWBy+IZOdg5Ug3mkAATy/t0usovzGeCUWTjCz0B+Sj0ekfdvkZ3abBv+U4GaCtJ1iEm6ANQJ6fEzrG/engcKw/wXQvEKxSEKQxRGKE7Izt+DSiwBJMUSm71rguMYhQKrBygOIRStf4TiFFRBvbRGKiQLWP29yRSHKBTtfdBmHs0BUpgvtgF4yRFR+NUKi0XZcYjCeCG2smkzLAHkbRBmP0/Uk26O5YnUActBp1GsAI+S5nRJJJal5K1aAMrq0d6Tm9uI6zjyf75dAe6tx/SsWeD//o2/Ab6IH3/h25pOAAAAAElFTkSuQmCC');
                        }
                    }
                });
            },
            queryParams: function (params) {
                let field_search = $('#form-search').serializeArray();
                $.each(field_search, function (i, item) {
                    if (params[item.name]) {
                        params[item.name] += ';' + item.value;
                    }
                    else {
                        params[item.name] = item.value;
                    }

                });
                return params;
            }
        });

        $('#form-search').on('submit', function (event) {
            if (event.isDefaultPrevented()) {
                return false;
            }
            event.preventDefault();
            $table_saleskit_category.bootstrapTable('refresh',{pageNumber: 1});
            return false;
        });

        function name_formatter(value, row, index) {
            return '<a id="edit_'+ row.id +'" style="cursor: pointer;" onclick="edit('+ row.id +')">'+ row.name +'</a>' ;
        }

        function saleskit_formatter(value, row, index){
            return '<a href="'+ row.saleskit +'" style="cursor: pointer;"><i class="fa fa-cog"></i></a>';
        }

        function index_formatter(value, row, index) {
            return (index+1);
        }

        function bg_mobile_formatter(value, row, index){
            return '<input type="color" class="avatar avatar-40 shadow-sm" value="'+row.bg_mobile+'">';
        }

        //var table = new LoadBootstrapTable({
        //    locale: '{{ \App::getLocale() }}',
        //    url: '{{ route('module.saleskit.category.getdata') }}',
        //    remove_url: '{{ route('module.saleskit.category.remove') }}'
        //});

        $('#delete-item-saleskit-category').prop('disabled', true);

        $('#delete-item-saleskit-category').on('click', function () {
            let ids = $("input[name=btSelectItem]:checked").map(function(){return $(this).val();}).get();
            Swal.fire({
                title: '',
                text: 'Bạn có chắc muốn xóa các mục đã chọn không ?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '{{ trans("laother.agree") }}!',
                cancelButtonText: '{{ trans("labutton.cancel") }}!',
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        type: "POST",
                        url: '{{ route('module.saleskit.category.remove') }}',
                        dataType: 'json',
                        data: {
                            'ids': ids
                        },
                        success: function (result) {
                            if (result.redirect) {
                                window.location = result.redirect;
                                return false;
                            } else {
                                show_message(result.message, result.status);
                                return false;
                            }
                        }
                    });
                }
            });

            return false;
        });

        $table_saleskit_category.on('check.bs.table uncheck.bs.table check-all.bs.table uncheck-all.bs.table', () => {
            $('#delete-item-saleskit-category').prop('disabled', !$table_saleskit_category.bootstrapTable('getSelections').length);
        });

        function edit(id){
            let item = $('#edit_'+id);
            let oldtext = item.html();
            item.html('<i class="fa fa-spinner fa-spin"></i> {{ trans("laother.please_wait") }}');
            $.ajax({
                url: "{{ route('module.saleskit.category.edit') }}",
                type: 'post',
                data: {
                    id: id,
                }
            }).done(function(data) {
                item.html(oldtext);
                $('#exampleModalLabel').html('{{ trans('labutton.edit') }} ' + data.model.name);
                $("input[name=id]").val(data.model.id);
                $("input[name=name]").val(data.model.name);
                $("input[name=bg_mobile]").val(data.model.bg_mobile);
                $('#parent_id').attr('data-not_id', data.model.id);

                if (data.categories && data.model.parent_id) {
                    var categories = '';
                    $.each(data.categories, function (i, item){
                        if(item.id == data.model.parent_id) {
                            categories += `<option value="`+ item.id +`" selected>`+ item.name +`</option>`;
                        } else {
                            categories += `<option value="`+ item.id +`" >`+ item.name +`</option>`;
                        }
                    });
                    $('#parent_id').html(categories);
                } else {
                    var categories = '';
                    var categories = `<option value="" ></option>`;
                    $.each(data.categories, function (i, item){
                        categories += `<option value="`+ item.id +`" >`+ item.name +`</option>`;
                    });
                    $('#parent_id').html(categories);
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
            var parent_id =  $("#parent_id").val();
            var bg_mobile =  $("input[name=bg_mobile]").val();

            event.preventDefault();
            $.ajax({
                url: "{{ route('module.saleskit.category.save') }}",
                type: 'post',
                data: {
                    'name': name,
                    'id': id,
                    'parent_id': parent_id,
                    'bg_mobile': bg_mobile,
                }
            }).done(function(data) {
                item.html(oldtext);
                $('.save').attr('disabled',false);
                if (data && data.status == 'success') {
                    $('#modal-popup').modal('hide');
                    show_message(data.message, data.status);

                    $table_saleskit_category.bootstrapTable('refresh',{pageNumber: 1});
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
            $("#parent_id").val('');
            $('#parent_id').attr('data-not_id', '');

            $('#exampleModalLabel').html('{{ trans('labutton.add_new') }}');
            $('#modal-popup').modal();
        }
    </script>
@endsection
