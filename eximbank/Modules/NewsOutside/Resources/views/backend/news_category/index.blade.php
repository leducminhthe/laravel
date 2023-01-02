@extends('layouts.backend')

@section('page_title', trans('lamenu.cate_news_general'))

@section('header')
    <link rel="stylesheet" type="text/css" href="{{ asset('styles/vendor/jquery-treegrid/jquery.treegrid.min.css') }}" />
    <script type="text/javascript" src="{{ asset('styles/vendor/jquery-treegrid/jquery.treegrid.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('styles/vendor/jquery-treegrid/bootstrap-table-treegrid.min.js') }}"></script>
@endsection

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lamenu.cate_news_general'),
                'url' => ''
            ],
        ]
    @endphp
    @include('layouts.backend.menu_breadcum',$breadcum)
@endsection

@section('content')
    <div role="main" id="category_new_outside">
        <div class="row">
            <div class="col-md-8">
                {{-- <form class="form-inline form-search mb-3" id="form-search">
                    <input type="text" name="search" value="" class="form-control" placeholder="{{ trans('backend.enter_category') }}">
                    <button type="submit" class="btn"><i class="fa fa-search"></i>&nbsp;{{ trans('labutton.search') }}</button>
                </form> --}}
            </div>
            <div class="col-md-4 text-right act-btns">
                <div class="pull-right">
                    <div class="btn-group">
                        @can('news-outside-category-create')
                            <button type="button" class="btn btn-demo" onclick="create()">
                                <i class="fa fa-plus-circle"></i> {{ trans('labutton.add_new') }}
                            </button>
                        @endcan
                        @can('news-outside-category-delete')
                            <button class="btn" id="delete-item-news-outside-category"><i class="fa fa-trash"></i> {{ trans('labutton.delete') }}</button>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
        <table class="tDefault table table-hover bootstrap-table" id="table-news-outside-category" data-tree-enable="true">
            <thead>
                <tr>
                    <th data-field="state" data-checkbox="true"></th>
                    <th data-field="name" data-formatter="name_formatter">{{ trans('backend.category_name') }}</th>
                </tr>
            </thead>
        </table>
    </div>

    <div class="modal right fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2" data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
                <form id="form_save" onsubmit="return false;" method="post" action="{{ route('backend.category.titles.save') }}" class="form-validate form-ajax" role="form" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="">
                    <div class="modal-header">
                        <div class="btn-group">
                            <h5 class="modal-title" id="exampleModalLabel"></h5>
                        </div>
                        <div class="btn-group act-btns">
                            @canany(['news-category-create', 'news-category-edit'])
                                <button type="button" id="btn_save" onclick="save(event)" class="btn save" data-must-checked="false"><i class="fa fa-save"></i> &nbsp;{{ trans('labutton.save') }}</button>
                            @endcanany
                            <button data-dismiss="modal" aria-label="Close" class="btn"><i class="fa fa-times-circle"></i> {{ trans('labutton.cancel') }}</button>
                        </div>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <div class="col-sm-4 control-label">
                                        <label for="name">{{ trans('backend.category_post_name') }} <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-md-7">
                                        <input name="name" type="text" class="form-control" value="" required>
                                    </div>
                                </div>

                                {{-- DANH MỤC CHA --}}
                                <div class="form-group row" id="category_parent_id">
                                    <div class="col-sm-4 control-label">
                                        <label for="parent_id">{{ trans('backend.father_level') }}</label>
                                    </div>
                                    <div class="col-md-7">
                                        <select name="parent_id" id="parent_id" class="form-control load-category-new-outside" data-placeholder="--{{trans('backend.choose_category_parent')}}--" >
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row"  id="stt_sort_category_parent">
                                    <div class="col-sm-4 control-label">
                                        <label for="stt_sort_parent">{{ trans('lanews.sort_parent') }}</label>
                                    </div>
                                    <div class="col-md-7">
                                        <input type="text" name="stt_sort_parent" id="stt_sort_parent" class="form-control" placeholder="{{ trans('lanews.enter_parent_sort_number') }}" value="">
                                    </div>
                                </div>

                                <div class="form-group row"  id="status_category_parent">
                                    <div class="col-sm-4 control-label">
                                        <label for="status">{{ trans('lanews.show_home_page') }}</label>
                                    </div>
                                    <div class="col-md-7">
                                        <input type="checkbox" id="status" name="status" value="1">
                                    </div>
                                </div>

                                {{-- DANH MỤC CON --}}
                                <div class="form-group row" id="stt_sort_category">
                                    <div class="col-sm-4 control-label">
                                        <label for="stt_sort">{{ trans('latraining.stt') }}</label>
                                    </div>
                                    <div class="col-md-7">
                                        <input type="text" name="stt_sort" id="stt_sort" class="form-control" placeholder="{{ trans('lanews.enter_sort_number') }}" value="">
                                    </div>
                                </div>

                                <div class="form-group row" id="sort_category">
                                    <div class="col-sm-4 control-label">
                                        <label for="sort">{{ trans('lanews.sort_right') }}</label>
                                    </div>
                                    <div class="col-md-7">
                                        <input type="checkbox" id="sort" name="sort" value="2">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
			</div>
		</div>
	</div>

    <script type="text/javascript">
        var $table_news_outside_category = $('#table-news-outside-category');
        var $delete_item_news_outside_category = $('#delete-item-news-outside-category');

        $table_news_outside_category.bootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.news_outside.category.getdata') }}',
            // striped: true,
            // sidePagination: 'server',
            // pagination: true,
            idField: 'id',
            search: true,
            treeShowField: 'name',
            parentIdField: "parent_id",
            treeEnable: true,
            onPostBody: function() {
                var columns = $table_news_outside_category.bootstrapTable('getOptions').columns;
                if (columns && columns[0][1].visible) {
                    $table_news_outside_category.treegrid({
                        treeColumn: 1,
                        onChange: function() {
                            $table_news_outside_category.bootstrapTable('resetView')
                        }
                    })
                }
            },
            customSearch: function (data, text) {
                if (!text) {
                    return data
                }
                const index = []
                for (let i = 0; i < data.length; i++) {
                    const row = data[i]
                    for (const value of Object.values(row)) {
                        if ((value + '').includes(text)) {
                            index.push(...getIndexArray(data, row, i, 1))
                        }
                    }
                }
                return data.filter((row, i) => {
                    return index.includes(i)
                })
            }
        });

        function getIndexArray(data, row, index, search) {
            const arr = [index]
            if (search == 1) {
                rowtext = data.find(it => it.id == row.parent_id)
                arr.push(data.indexOf(rowtext))
            }
            return arr
        }

        function icon_formatter(value, row, index) {
            return '<img src="" alt="icon" class="img-responsive w-75"/>';
        }

        function name_formatter(value, row, index) {
            // return '<a href="'+ row.edit_url +'">'+ row.name +'</a>';
            return '<a class="edit" id="edit_'+ row.id +'" style="cursor: pointer;" onclick="edit('+ row.id +')">'+ row.name +'</a>' ;
        }

        $delete_item_news_outside_category.prop('disabled', true);

        $delete_item_news_outside_category.on('click', function () {
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
                        url: '{{ route('module.news_outside.category.remove') }}',
                        dataType: 'json',
                        data: {
                            'ids': ids
                        },
                        success: function (result) {
                            if (result.status === "success") {
                                $table_news_outside_category.bootstrapTable('refresh');
                                return false;
                            }
                            else {
                                show_message(result.message, result.status);
                                return false;
                            }
                        }
                    });
                }
            });

            return false;
        });

        $table_news_outside_category.on('check.bs.table uncheck.bs.table check-all.bs.table uncheck-all.bs.table', () => {
            $delete_item_news_outside_category.prop('disabled', !$table_news_outside_category.bootstrapTable('getSelections').length);
        });

        function edit(id){
            let item = $('#edit_'+id);
            let oldtext = item.html();
            item.html('<i class="fa fa-spinner fa-spin"></i> {{ trans("laother.please_wait") }}');
            var level =  $("input[name=level]").val();
            $.ajax({
                url: "{{ route('module.news_outside.category.edit') }}",
                type: 'post',
                data: {
                    id: id,
                }
            }).done(function(data) {
                item.html(oldtext);
                $('#exampleModalLabel').html('{{ trans('labutton.edit') }}');
                $("input[name=id]").val(data.model.id);
                $("input[name=name]").val(data.model.name);

                $("#parent_id").html('');
                $("input[name=stt_sort_parent]").val('');
                $("input[name=stt_sort]").val('');

                if (!data.model.parent_id) {
                    loadParent();
                    $("input[name=stt_sort_parent]").val(data.model.stt_sort_parent);
                    $('#sort').prop( 'checked', false )
                    if (data.model.status == 1) {
                        $('#status').prop( 'checked', true )
                    } else {
                        $('#status').prop( 'checked', false )
                    }
                } else {
                    $("#parent_id").html('<option value="'+ data.parent_cate.id +'">'+ data.parent_cate.name +'</option>');
                    loadParent();
                    $('#status').prop( 'checked', false )
                    if (data.model.sort == 2) {
                        $('#sort').prop( 'checked', true )
                    } else {
                        $('#sort').prop( 'checked', false )
                    }
                    $("input[name=stt_sort]").val(data.model.stt_sort);
                }
                $('#myModal2').modal();
                return false;
            }).fail(function(data) {
                show_message('Lỗi dữ liệu', 'error');
                return false;
            });
        }

        function save(event) {
            // let item = $('.save');
            // let oldtext = item.html();
            // item.html('<i class="fa fa-spinner fa-spin"></i> {{ trans("laother.processing") }}');
            // $('.save').attr('disabled',true);
            event.preventDefault();
            $.ajax({
                url: "{{ route('module.news_outside.category.save') }}",
                type: 'post',
                data: $("#form_save").serialize(),

            }).done(function(data) {
                // item.html(oldtext);
                // $('.save').attr('disabled',false);
                if (data && data.status == 'success') {
                    $('#myModal2').modal('hide');
                    show_message(data.message, data.status);
                    $table_news_outside_category.bootstrapTable('refresh');
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
            $('#exampleModalLabel').html('{{ trans('labutton.add_new') }}');
            $('#form_save').trigger("reset");
            $("#parent_id").html('');
            $("input[name=id]").val('');
            $("input[name=stt_sort_parent]").val('');
            $("input[name=stt_sort]").val('');
            $('#status').prop( 'checked', false )
            $('#sort').prop( 'checked', false )
            $('#myModal2').modal();
            loadParent();
        }

        function loadParent() {
            var check_stt_sort_parent = $('#stt_sort_parent').val();
            if (check_stt_sort_parent) {
                $('#category_parent_id').hide();
            }

            var check_parent_id = $('#parent_id').val();
            if (check_parent_id && !check_stt_sort_parent) {
                $('#sort_category').show();
                $('#stt_sort_category').show();
                $('#stt_sort_category_parent').hide();
                $('#stt_sort_parent').val('');
                $('#status_category_parent').hide();
            } else {
                $('#sort_category').hide();
                $('#stt_sort_category').hide();
                $('#stt_sort_category_parent').show();
                $('#status_category_parent').show();
            }
        }

        $('#parent_id').on('change',function() {
            var parent_id = $('#parent_id').val();
            console.log(parent_id);
            if (parent_id) {
                $('#sort_category').show();
                $('#stt_sort_category').show();
                $('#stt_sort_category_parent').hide();
                $('#stt_sort_parent').val('');
                $('#status_category_parent').hide();
                $('#sort').prop( 'checked', false )
            } else {
                $('#sort_category').hide();
                $('#stt_sort_category').hide();
                $('#stt_sort').val('');
                $('#parent_id').val('');
                $('#stt_sort_category_parent').show();
                $('#status_category_parent').show();
                $('#status').prop( 'checked', false )
            }
        })
    </script>
@endsection
