@extends('layouts.app')

@section('page_title', 'Lịch sử điểm thưởng')

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
                            <th data-field="name">{{ trans('latraining.activity') }}</th>
                            <th data-field="content">{{ trans('latraining.content') }}</th>
                            <th data-field="datecreated" data-width="10%" data-align="center">{{ trans('laother.achieved_date') }}</th>
                            <th data-field="type_promotion" data-formatter="type_promotion_formatter" data-width="10%" data-align="center">{{ trans('laother.type_promotion') }}</th>
                            <th data-field="point" data-width="5%" data-align="center">{{ trans('latraining.score') }}</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.frontend.userpoint.datahistory') }}',
        });

        function index_formatter(value, row, index) {
            return (index + 1);
        }

        function type_promotion_formatter(value, row, index) {
            if (row.type_promotion == 1) {
                return '<span>Click to earn</span>';
            } else {
                return '<span>Learn to earn</span>';
            }
        }

        function result(value, row, index) {
            return value == 1 ? '{{trans("backend.finish")}}' : '{{trans('backend.incomplete')}}';
        }
    </script>
@endsection