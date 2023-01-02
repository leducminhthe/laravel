@extends('layouts.backend')

@section('page_title', trans('lasetting.list_position'))

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lasetting.training_position'),
                'url' => route('backend.google.map')
            ],
            [
                'name' => trans('lasetting.list_position'),
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
                        @can('google-map-delete')
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
                    <th data-field="title" data-width="25%" data-formatter="name_formatter">{{ trans('lasetting.name') }}</th>
                    <th data-field="description" data-formatter="description">{{ trans('lasetting.description') }}</th>
                    <th data-field="lat" data-width="15%">{{ trans('lasetting.lat') }}</th>
                    <th data-field="lng" data-width="15%">{{ trans('lasetting.lng') }}</th>
                    <th data-field="note">{{ trans('latraining.note') }}</th>
                </tr>
            </thead>
        </table>
    </div>

    <script type="text/javascript">
        function name_formatter(value, row, index) {
            return '<a href="'+ row.edit_url +'">'+ row.title +' </a>';
        }
        function description(value, row, index) {
            return '<span class="contact_posts">'+ row.description +'</span>'
        }
        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('backend.google.map.getdata') }}',
            remove_url: '{{ route('backend.google.map.remove') }}'
        });
    </script>
@endsection
