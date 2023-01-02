@extends('layouts.backend')

@section('page_title', trans('latraining.emulation_badge'))

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('latraining.emulation_badge'),
                'url' => route('module.emulation_badge.list')
            ],
            [
                'name' => $model->name .': '. trans('latraining.result'),
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
        </div>
        <br>

        <table class="tDefault table table-hover bootstrap-table">
            <thead>
                <tr>
                    <th data-formatter="index_formatter_emulation" data-align="center" data-width="5%">#</th>
                    <th data-field="code">{{ trans("laprofile.code") }}</th>
                    <th data-field="full_name">{{ trans("laprofile.full_name") }}</th>
                    <th data-field="image" data-align="center" data-formatter="fastest_learning_badge_formatter" data-width="15%">{{ trans('latraining.fastest_learning_badge') }}</th>
                    <th data-field="image" data-align="center" data-formatter="top_score_badge_formatter" data-width="15%">{{ trans('latraining.top_score_badge') }}</th>
                    <th data-field="image" data-align="center" data-formatter="earliest_interactive_badge_formatter" data-width="15%">{{ trans('latraining.earliest_interactive_badge') }}</th>
                </tr>
            </thead>
        </table>
    </div>

    <script type="text/javascript">
        function index_formatter_emulation(value, row, index) {
            return (index + 1);
        }

        function fastest_learning_badge_formatter(value, row, index) {
            if(row.time) {
                return '<p class="mb-0">{{ trans("lacategory.rank") }} '+ row.rank_time +'</p><image src="'+ row.time +'" width="120px"/>';
            } else {
                return '-'
            }
        }

        function top_score_badge_formatter(value, row, index) {
            if(row.score) {
                return '<p class="mb-0">{{ trans("lacategory.rank") }} '+ row.rank_score +'</p><image src="'+ row.score +'" width="120px"/>';
            } else {
                return '-'
            }
        }

        function earliest_interactive_badge_formatter(value, row, index) {
            if(row.complete) {
                return '<p class="mb-0">{{ trans("lacategory.rank") }} '+ row.rank_complete +'</p><image src="'+ row.complete +'" width="120px"/>';
            } else {
                return '-'
            }
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.emulation_badge.getdata_result', ["id" => $model->id]) }}',
        });
    </script>
@endsection
