@extends('layouts.backend')

@section('page_title', trans('lamenu.career_roadmap'))

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lamenu.career_roadmap'),
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
                    <input type="text" name="search" value="" class="form-control" placeholder="{{trans('lacareer_path.enter_code_name_title')}}">
                    <button type="submit" class="btn"><i class="fa fa-search"></i>&nbsp;{{trans('labutton.search')}}</button>
                </form>
            </div>
        </div>
        <br>

        <table class="tDefault table table-hover bootstrap-table">
            <thead>
                <tr>
                    <th data-field="state" data-checkbox="true"></th>
                    <th data-field="code" data-width="10%">{{trans('lacareer_path.title_code')}}</th>
                    <th data-field="name" data-formatter="name_formatter">{{trans('lacareer_path.title_name')}}</th>
                </tr>
            </thead>
        </table>
    </div>
    <script type="text/javascript">
        function name_formatter(value, row, index) {
            return '<a href="'+ row.edit_url +'" class="a-color">'+ row.name +'</a>';
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.career_roadmap.getdata') }}',
            locale: '{{ App::getLocale() }}',
        });
    </script>
@endsection
