@extends('layouts.backend')

@section('page_title', trans('latraining.emulation_badge'))

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('latraining.emulation_badge'),
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
                    <input type="text" name="search" value="" class="form-control" placeholder='{{trans("lacategory.enter_name")}}'>
                    <button type="submit" class="btn"><i class="fa fa-search"></i>&nbsp;{{ trans('labutton.search') }}</button>
                </form>
            </div>
            <div class="col-md-4 text-right act-btns">
                <div class="pull-right">
                    <div class="btn-group">
                        @can('emulation-badge-create')
                            <a href="{{ route("module.emulation_badge.create") }}" class="btn"><i class="fa fa-plus-circle"></i> {{ trans('labutton.add_new') }}</a>
                        @endcan
                        @can('emulation-badge-delete')
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
                    <th data-field="code" data-align="left" data-width="5%">{{trans("lacategory.code")}}</th>
                    <th data-sortable="true" data-field="name" data-formatter="name_formatter">{{ trans('lacategory.name') }}</th>
                    <th data-field="status" data-align="center" data-width="8%">{{ trans('lacategory.status') }}</th>
                    <th data-field="createdby" data-align="center" data-width="12%">{{ trans('lacategory.creator') }}</th>
                    <th data-field="createdat" data-align="center" data-width="8%">{{ trans('lacategory.created_at') }}</th>
                    <th data-field="createdat" data-align="center" data-width="8%" data-formatter="result_formatter">{{ trans('latraining.result') }}</th>
                </tr>
            </thead>
        </table>
    </div>

    <script type="text/javascript">
        function name_formatter(value, row, index) {
            return '<a href="'+ row.edit_url +'">'+ row.name +'</a>' ;
        }

        function result_formatter(value, row, index) {
            return '<a href="'+ row.result_url +'"><i class="fas fa-eye"></i></a>'
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.emulation_badge.getdata') }}',
            remove_url: '{{ route('module.emulation_badge.remove') }}'
        });
    </script>
@endsection
