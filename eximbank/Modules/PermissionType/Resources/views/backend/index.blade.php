@extends('layouts.backend')

@section('page_title', trans('lamenu.permission_group'))

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lamenu.permission_group'),
                'url' => ''
            ],
        ]
    @endphp
    @include('layouts.backend.menu_breadcum',$breadcum)
@endsection

@section('content')
    <div role="main" id="permissions-type">
        <div class="row">
            <div class="col-md-8">

            </div>
            <div class="col-md-4 text-right act-btns">
                <div class="pull-right">
                    <div class="btn-group">
                        @can('permission-group-create')
                            <a href="javascript:void(0)" class="btn add_permission" onclick="showModal(0,0)"><i class="fa fa-plus-circle"></i> {{ trans('labutton.add_new') }}</a>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
        <br>
        <table class="tDefault table table-hover bootstrap-table">
            <thead>
            <tr>
                <th data-field="index" data-formatter="index_formatter" data-width="5%" data-class="text-center"># </th>
                <th data-sortable="true" data-field="type" data-width="10%" data-formatter="type_formatter" data-class="text-center">{{ trans('backend.type') }}</th>
                <th data-sortable="true" data-field="name" data-width="20%" data-class="text-center">{{ trans('backend.name') }} </th>
                <th data-field="description" data-width="30%">{{trans('latraining.description')}}</th>
                <th data-field="created_by" data-width="10%" data-class="text-center">{{ trans('backend.user_create') }}</th>
                <th data-field="updated_by" data-width="10%" data-class="text-center">{{ trans('backend.user_updated') }}</th>
                <th data-formatter="action_formatter" data-width="15%" data-class="text-center">{{ trans('backend.actions') }}</th>
            </tr>
            </thead>
        </table>
    </div>
    <script type="text/javascript">
        function index_formatter(value, row, index) {
            return (index+1);
        }
        function type_formatter(value, row, index) {
            return value==1? "{{trans('backend.system')}}" : '{{trans("backend.custom")}}';
        }
        function action_formatter(value, row, index) {
            if(row.type==1)
                return '';
            else{
                var html ='';
                if(row.permission_edit)
                    html+= '<button type="button" class="btn edit-item edit_'+row.id+'" onclick="showModal('+row.id+',1)"><i class="fa fa-edit"></i> {{trans("backend.edit")}}</button>';
                if(row.permission_delete)
                    html+=' <a href="javascript:void(0)" data-id="'+row.id+'" class="btn remove-item"><i class="fa fa-trash"></i> {{trans("backend.delete")}}</a>';
                return html;
            }

        }
        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.permission.type.getdata') }}',
            sort_name: 'sort',
            sort_order: 'asc',
            remove_url: '{{ route('module.permission.type.delete') }}'
        });

        var page = 1;
        var empty = 0;

        function showModal(id, type) {
            if (type == 0) {
                var item = $('.add_permission');
            } else {
                var item = $('.edit_'+ id);
            }
            var oldtext = item.html();
            item.html('<i class="fa fa-spinner fa-spin"></i> Đang chờ');
            $.ajax({
                type: 'POST',
                url: '{{ route('module.permission.type.get_modal') }}',
                dataType: 'html',
                data: {
                    'id': id,
                },
            }).done(function(data) {
                item.html(oldtext);
                $("#app-modal").html(data);
                page = 1;
                empty = 0;
                load_unit_permission(page, id);
                $("#app-modal #myModal").modal();
            }).fail(function(data) {
                item.html(oldtext);
                show_message('{{ trans('laother.data_error') }}', 'error');
                return false;
            });
        }

        function load_unit_permission(page, id){
            $.ajax({
                url: '{{ route('module.permission.type.load_units') }}' + "?page=" + page + "&id=" + id,
                type: "get",
                datatype: "html",
                beforeSend: function()
                {
                    $('.ajax-loading').show();
                }
                })
                .done(function(data) {
                    if(data.length == 0){
                    empty = 1;
                    $('.ajax-loading').hide();
                    return;
                }
                $('.ajax-loading').hide();
                $("#results").append(data);
            })
            .fail(function(jqXHR, ajaxOptions, thrownError)
            {
                alert('No response from server');
            });
        }
    </script>
@endsection
