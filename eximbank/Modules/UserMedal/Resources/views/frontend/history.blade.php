@extends('layouts.app')

@section('page_title', trans('latraining.medal_history'))

@section('header')

@endsection

@section('content')
    <div class="sa4d25">
        <div class="container-fluid">
            <div class="table-responsive">
                <table id="dg" class="table bootstrap-table">
                    <thead class="thead-s">
                        <tr class="tbl-heading">
                            <th width="40px" data-formatter="index_formatter">#</th>
                            <th data-field="name">{{ trans('lamenu.usermedal_setting') }}</th>
                            <th data-field="submedal_name"  data-align="center">{{ trans('laother.name_badge') }}</th>
                            <th data-field="submedal_rank" data-align="center">{{ trans('laother.badge_class') }}</th>
                            <th data-field="datecreated" data-width="260px">{{ trans('laother.achieved_date') }}</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.frontend.usermedal.datahistory') }}',
        });

        function index_formatter(value, row, index) {
            return (index + 1);
        }
        function result(value, row, index) {
            return value == 1 ? '{{trans("backend.finish")}}' : '{{trans('backend.incomplete')}}';
        }
    </script>
@endsection
