@extends('layouts.backend')

@section('page_title', trans('latraining.capability'))

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lamenu.category'),
                'url' => route('backend.category')
            ],
            [
                'name' => 'Năng lực chuyên môn (C)',
                'url' => '',
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
                    <input type="text" name="search" value="" class="form-control" placeholder='{{trans("backend.enter_capabilities_sympol_name")}}'>
                    <button type="submit" class="btn"><i class="fa fa-search"></i>&nbsp;{{ trans('labutton.search') }}</button>
                </form>
            </div>
            <div class="col-md-4 text-right act-btns">
                <div class="pull-right">
                    <div class="btn-group">
                        @can('category-capabilities-create')
                        <a href="{{ route('module.capabilities.create') }}" class="btn"><i class="fa fa-plus-circle"></i> {{ trans('labutton.add_new') }}</a>
                        @endcan
                        @can('category-capabilities-delete')
                        <button class="btn" id="delete-item"><i class="fa fa-trash"></i> {{ trans('labutton.delete') }}</button>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
        <br>

        <table class="tDefault table table-hover bootstrap-table" data-page-list="[10, 50, 100, 200, 500]">
            <thead>
                <tr>
                    <th data-field="state" data-checkbox="true"></th>
                    <th data-field="code" data-width="5%">Mã</th>
                    <th data-field="name" data-formatter="name_formatter">Tên năng lực chuyên môn (C)</th>
                    <th data-field="category_name" data-width="15%">Khung năng lực (A)</th>
                    <th data-field="group_name" data-width="15%">Phân nhóm năng lực (ASK)</th>
                </tr>
            </thead>
        </table>
    </div>

    <script type="text/javascript">
        function name_formatter(value, row, index) {
            return '<a href="'+ row.edit_url +'">'+ row.name +'</a>';
        }

        var table = new LoadBootstrapTable({
            url: '{{ route('module.capabilities.getdata') }}',
            remove_url: '{{ route('module.capabilities.remove') }}',
            locale: '{{ App::getLocale() }}',
        });
    </script>
@endsection
