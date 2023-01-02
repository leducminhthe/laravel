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
                        @can('category-usermedal-create')
                            <a href="{{route("module.usermedal.create")}}" class="btn"><i class="fa fa-plus-circle"></i> {{ trans('labutton.add_new') }}</a>
                        @endcan
                        @can('category-usermedal-delete')
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
                    <th data-field="vphoto" data-align="center" data-width="10%">{{ trans('lacategory.image') }}</th>
                    <th data-field="status" data-align="center" data-width="8%">{{ trans('lacategory.status') }}</th>
                    <th data-field="createdby" data-align="center" data-width="8%">{{ trans('lacategory.creator') }}</th>
                    <th data-field="createdat" data-align="center" data-width="8%">{{ trans('lacategory.created_at') }}</th>
                </tr>
            </thead>
        </table>
    </div>

    <script type="text/javascript">

        function name_formatter(value, row, index) {
            return '<a href="'+ row.edit_url +'">'+ row.name +'</a>' ;
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.usermedal.getdata') }}',
            remove_url: '{{ route('module.usermedal.remove') }}'
        });

    </script>
@endsection
