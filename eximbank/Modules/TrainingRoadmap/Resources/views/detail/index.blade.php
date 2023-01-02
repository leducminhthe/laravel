@extends('layouts.backend')

@section('page_title', $page_title)

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lamenu.learning_manager'),
                'url' => route('module.trainingroadmap')
            ],
            [
                'name' => trans('backend.trainingroadmap'). ': '. $page_title,
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
                <form class="form-inline" id="form-search">
                    <div class="w-24">
                        <select name="training_program" class="form-control load-training-program" data-placeholder="-- {{ trans('latraining.training_program') }} --"></select>
                    </div>
                    <div class="w-24">
                        <select name="subject" class="form-control load-subject" data-placeholder="-- {{ trans('backend.subject') }} --"></select>
                    </div>
                    <div class="w-24">
                        <button type="submit" class="btn"><i class="fa fa-search"></i>&nbsp;{{ trans('labutton.search') }}</button>
                    </div>
                </form>
            </div>
            <div class="col-md-4 text-right act-btns">
                <div class="pull-right">
                    @can('training-roadmap-create')
                        <div class="btn-group">
                            <a class="btn" href="javascript:void(0)" id="export-excel">
                                <i class="fa fa-download"></i> {{ trans('labutton.export') }}
                            </a>
                        </div>
                    @endcan
                    <div class="btn-group">
                        @can('training-roadmap-create')
                            <a  href="{{ route('module.trainingroadmap.detail.create', ['id' => $title_id]) }}" class="btn" >
                                <i class="fa fa-plus-circle"></i> {{ trans('labutton.add_new') }}
                            </a>
                        @endcan
                        @can('training-roadmap-delete')
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
                    <th data-field="index" data-formatter="index_formatter" data-align="center" data-width="2%">#</th>
                    <th data-field="check" data-checkbox="true" data-width="2%"></th>
                    <th data-field="order"  data-align="center" data-formatter="order_formatter" data-width="2%"><a href="javascript:void(0)"><i class="far fa-save fa-1x saveSortOrder"></i></a></th>
                    <th data-field="training_program_code">{{trans('backend.training_program_code')}}</th>
                    <th data-field="training_program_name">{{trans('latraining.training_program')}}</th>
                    <th data-field="subject_code" >{{trans('backend.subject_code')}}</th>
                    <th data-field="subject_name" >{{trans('latraining.subject_name')}}</th>
                    <th data-field="created_at2" data-align="center" >{{trans('backend.created_at')}}</th>
                    <th data-field="updated_at2" data-align="center" >{{trans('backend.edit_at')}}</th>
                    <th data-field="edit" data-width="5%" data-align="center" data-formatter="edit_formatter" >{{trans('labutton.edit')}}</th>
                </tr>
            </thead>
        </table>
    </div>

    <script type="text/javascript">
        function order_formatter(value, row, index) {
            return '<input type="number" id="'+row.id+'" value="'+row.order+'" name="order['+row.id+']" style="width: 40px; text-align: center" />';
        }
        function index_formatter(value, row, index) {
            return (index+1);
        }
        function edit_formatter(value, row, index) {
            return  '<a href="'+ row.edit_url +'" class="btn" style="cursor:pointer" ><i class="fa fa-edit"></i></a>';
        }
        $("#export-excel").on('click', function () {
            let form_search = $("#form-search").serialize();
            window.location = '{{ route('module.trainingroadmap.detail.export',['id' => $title_id]) }}?'+form_search;
        });
        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.trainingroadmap.detail.getdata',['id' => $title_id]) }}',
            remove_url: '{{ route('module.trainingroadmap.detail.remove',['id'=> $title_id ]) }}'
        });
        $('.saveSortOrder').on('click',function (e) {
            var ids ={};
            $("input[name^=order]").map(function(key){
                var element_id = $(this).attr('id');
                // let id = element_id.substring(8,element_id.length - 1);
                ids[element_id] = $(this).val();
            });
            $.ajax({
                url: "{{ route('module.trainingroadmap.saveOrder') }}",
                type: 'post',
                data: {ids:ids}
            }).done(function(data) {
                show_message('Cập nhật thành công');
            }).fail(function(data) {
                show_message('{{ trans('laother.data_error') }}', 'error');
                return false;
            });
        })
    </script>
@endsection
