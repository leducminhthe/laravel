@extends('layouts.backend')

@section('page_title', trans('lasetting.contact'))

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lasetting.contact'),
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
                    <input type="text" class="form-control" name="search" value="">
                    <button type="submit" class="btn"><i class="fa fa-search"></i>&nbsp;{{ trans('labutton.search') }}</button>
                </form>
            </div>
            <div class="col-md-4 text-right act-btns">
                <div class="pull-right">
                    <div class="btn-group">
                        @can('contact-create')
                            <a href="{{ route('backend.contact.create') }}" class="btn"><i class="fa fa-plus-circle"></i> {{ trans('labutton.add_new') }}</a>
                        @endcan
                        @can('contact-delete')
                            <button class="btn" id="delete-item"><i class="fa fa-trash"></i> {{ trans('labutton.delete') }}</button>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
        <br>

        <table class="tDefault table table-hover bootstrap-table text-nowrap" id="contact_table">
            <thead>
                <tr>
                    <th data-field="state" data-checkbox="true"></th>
                    <th data-field="name" data-width="25%" data-formatter="name_formatter">{{ trans('lasetting.name') }}</th>
                    <th data-field="description" data-formatter="description">{{ trans('lasetting.content') }}</th>
                </tr>
            </thead>
        </table>
    </div>

    <script type="text/javascript">
        function name_formatter(value, row, index) {
            return '<a href="'+ row.edit_url +'">'+ row.name +' </a>';
        }

        function description(value, row, index) {
            return '<span class="contact_posts">'+ row.description +'</span>'
        }
        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('backend.contact.getdata') }}',
            remove_url: '{{ route('backend.contact.remove') }}'
        });
    </script>
@endsection
