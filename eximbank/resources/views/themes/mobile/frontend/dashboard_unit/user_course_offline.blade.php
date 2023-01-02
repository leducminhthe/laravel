@extends('themes.mobile.layouts.app')

@section('page_title', trans('app.dashboard'))

@section('content')
    <div class="container wrapped_info">
        <div class="row mt-2">
            <div class="col-6">
                <span class="p-1 mr-1 mb-1 w-5 studying"></span> Đang học ({{ $data_model[0] }}) <br>
                <span class="p-1 mr-1 mb-1 w-5 not_learned"></span> Chưa học ({{ $data_model[1] }})<br>
            </div>
            <div class="col-6">
                <span class="p-1 mr-1 mb-1 w-5 completed"></span> Hoàn thành ({{ $data_model[2] }})<br>
                <span class="p-1 mr-1 mb-1 w-5 uncomplete"></span>Chưa hoàn thành ({{ $data_model[3] }})<br>
            </div>
        </div>

        <form name="frm" action="" id="form-search-offline" method="post" autocomplete="off">
            <input type="hidden" name="model_id" id="model_id_2" value="{{ $model_id }}">
            <input type="hidden" name="year_course" id="model_year_course_2" value="{{ $year_course }}">
            <input type="hidden" name="month_course" id="model_month_course_2" value="{{ $month_course }}">
            <input type="hidden" name="status_course" id="model_status_course_2" value="{{ $status_course }}">
            <input type="hidden" name="filter_name" id="model_filter_name_2" value="{{ $filter_name }}">
        </form>
        <div id="bootstraptableOffline" class="">
            <table id="bootstraptable_2" class="tDefault table table-hover table-bordered bootstrap-table" data-url="{{ route('themes.mobile.frontend.dashboard_unit.data_user_offline') }}" >
                <thead>
                    <tr class="tbl-heading">
                        <th data-formatter="info_formatter">{{ trans('app.info') }}</th>
                        <th data-formatter="status_formatter" data-align="center" data-width="5%">{{ trans('app.status') }}</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection

@section('footer')
    <script type="text/javascript">
        function info_formatter(value, row, index) {
            return (index + 1) +'./ '+ row.full_name + '<br>' + row.code + '<br>' + row.unit_name + '<br>' + row.model_info;
        }
        function status_formatter(value, row, index){
            return '<span class="p-2 '+row.bg_color+'">'+ row.percent +'</span>';
        }

        var table_2 = new LoadBootstrapTable({
            table: '#bootstraptable_2',
            locale: '{{ \App::getLocale() }}',
            url: $('#bootstraptable_2').data('url'),
            form_search: '#form-search-offline',
        });
    </script>
@endsection
