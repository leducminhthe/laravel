@extends('layouts.backend')

@section('header')
    <link rel="stylesheet" href="{{ asset('styles/module/report/css/list.css') }}">
@endsection

@section('page_title', trans('lareport.view_report'))

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lareport.report'),
                'url' => route('module.report_new')
            ],
            [
                'name' => trans('lareport.history_download'),
                'url' => ''
            ],
        ]
    @endphp
    @include('layouts.backend.menu_breadcum',$breadcum)
@endsection

@section('content')
    <div role="main" id="report" class="mt-4">
        <div class="row">
            <div class="col-12 col-md-12">
                <div class="table-responsive">
                    <table id="dg" class="tDefault table table-hover table-bordered bootstrap-table">
                        <thead>
                        <tr class="tbl-heading">
                            <th data-formatter="index_formatter" data-align="center" data-width="3%">#</th>
                            <th data-field="report_name" data-align="left">{{trans('lareport.report')}}</th>
                            <th data-field="created_at2" data-align="center">{{trans('lareport.time_download')}}</th>
                            <th data-field="status" data-formatter="download_formatter" data-align="center">Download</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        var table = new LoadBootstrapTable({
            url: "{{ route('module.report_new.getDataHistoryExport') }}",
        });

        function index_formatter(value, row, index) {
            return (index + 1);
        }

        function download_formatter(value, row, index) {
           /* return row.size > 0 ? '<a href="'+row.download+'"><i class="fa fa-download"></i> Download ('+ row.size +' MB)</a>' : 'Chờ xử lý ....';*/
            switch (value) {
                case '0': return 'Lỗi xuất báo cáo: ' + row.error;
                case '1': return '<a href="'+ row.download +'"><i class="fa fa-download"></i> Download ('+ row.size +' MB)</a>';
                case '2': return 'Chờ xử lý ....';
                default : return 'Đang xuất báo cáo...';
            }
        }

    </script>
@stop
