@extends('layouts.backend')

@section('page_title', trans('backend.unit_manager_setup'))

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('backend.unit_manager_setup'),
                'url' => ''
            ],
        ]
    @endphp
    @include('layouts.backend.menu_breadcum',$breadcum)
@endsection

@section('content')
    <div role="main">
        @if(isset($errors))
            @foreach($errors as $error)
                <div class="alert alert-danger">{!! $error !!}</div>
            @endforeach
            {{session()->forget('errors')}}
        @endif

        <div class="row">
            <div class="col-md-12 form-inline">
                <form class="form-inline" id="form-search">
                    <input type="text" name="search" value="" class="form-control" placeholder="{{ trans('backend.enter_code_name') }}">
                    <input type="text" name="user_code" value="" class="form-control" placeholder="{{ trans('backend.enter_unit_manager_code') }}">
                    <button type="submit" class="btn"><i class="fa fa-search"></i>&nbsp;{{ trans('labutton.search') }}</button>
                </form>
            </div>
            <div class="col-md-12 text-right act-btns mt-2">
                <div class="btn-group">
                    @can('unit-manager-setting-import')
                    <div class="btn-group">
                        <a class="btn" href="{{download_template('mau_import_tdv.xlsx')}}"><i class="fa fa-download"></i> {{ trans('labutton.import_template') }}</a>
                        <button class="btn" id="btnImportManager"><i class="fa fa-download"></i> {{ trans('labutton.import') }}</button>
                    </div>
                    @endcan
                    <div class="btn-group">
                     @can('unit-manager-setting-create')
                        <a href="{{route('backend.permission.unitmanager.create')}}" class="btn">
                            <i class="fa fa-plus-circle"></i> {{ trans('labutton.add_new') }}
                        </a>
                     @endcan
                     @can('unit-manager-setting-delete')
                        <button class="btn" id="delete-item" disabled=""><i class="fa fa-trash"></i> {{ trans('labutton.delete') }}</button>
                     @endcan
                    </div>
                </div>
            </div>
        </div>
        <br>
        <table class="tDefault table table-hover bootstrap-table">
            <thead>
                <tr>
                    <th data-field="id" data-formatter="stt_formatter" data-align="text-center" data-width="30px"></th>
                    <th data-field="state" data-checkbox="true"></th>
                    <th data-sortable="false" data-field="code" data-width="10%">{{ trans('lacategory.unit_code') }}</th>
                    <th data-sortable="false" data-field="name" data-formatter="name_formatter">{{ trans('lamenu.unit') }}</th>
                    <th data-sortable="false" data-field="priority1" data-width="150px">{{ trans('backend.priority') }} 1</th>
                    <th data-sortable="false" data-field="priority2" data-width="150px">{{ trans('backend.priority') }} 2</th>
                    <th data-sortable="false" data-field="priority3" data-width="150px">{{ trans('backend.priority') }} 3</th>
                    <th data-sortable="false" data-field="priority4" data-width="150px">{{ trans('backend.priority') }} 4</th>
                </tr>
            </thead>
        </table>

    </div>
    <div class="modal fade" id="modal-import" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog" role="document">
            <form action="{{ route('backend.permission.unitmanager.import') }}" method="post" class="form-ajax">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Import trưởng {{ trans('lamenu.unit') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="file" name="import_file" id="import_file" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn">{{ trans('labutton.import') }}</button>
                        <button type="button" class="btn" data-dismiss="modal">{{ trans('labutton.close') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script type="text/javascript">
        function stt_formatter(value, row, index) {
            return (index + 1);
        }
        function name_formatter(value, row, index) {
            return '<a href="'+ row.edit_url +'">'+ value +'</a>';
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('backend.permission.unitmanager') }}',
            remove_url: '{{ route('backend.permission.unitmanager.remove') }}',
            delete_method: 'delete'
        });

        $('#btnImportManager').on('click', function() {
            $('#modal-import').modal();
        });

        $('#import-plan-update').on('click', function() {
            $('#modal-import-update').modal();
        });
    </script>

@endsection
