{{-- @extends('layouts.backend')

@section('page_title', 'Lịch sử truy cập')

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            <span class="font-weight-bold">Lịch sử truy cập</span>
        </h2>
    </div>
@endsection --}}

@section('header')
    <link rel="stylesheet" href="{{ asset('styles/module/report/css/list.css') }}">
    <script src="{{ asset('js/charts-loader.js') }}" type="text/javascript"></script>
    <script src="{{asset('styles/module/report/js/report.js')}}" type="text/javascript"></script>

    <style>
        .table > thead > tr > .th-second{
            top: 40px;
        }

        table video {
            width: 50%;
            height: auto;
        }

        table img {
            width: 50% !important;
            height: auto !important;
        }
    </style>
@endsection

{{-- @section('content') --}}
<div role="main" id="report" class="pt-2">
    <form name="frm" action="{{route('module.report.export')}}" id="form-search" method="post" autocomplete="off">
        @csrf
        <input type="hidden" name="report" value="BC18">
        <div class="row">
            <div class="col-md-3">

            </div>
            <div class="col-md-7">
                <div class="form-group row">
                    <div class="col-md-3 control-label">
                        <label>{{ trans('lahistory_management.unit') }}</label>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-2">
                            @include('backend.form_choose_unit')
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-3 control-label">
                        <label>{{ trans('lahistory_management.area') }}</label>
                    </div>
                    <div class="col-md-6">
                        <select name="area" id="area" class="form-control load-area" data-placeholder="-- {{ trans('lahistory_management.area') }} --"></select>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-3 control-label">
                        <label>{{ trans('lahistory_management.user_code') }}</label>
                    </div>
                    <div class="col-md-6">
                        <input type="text" name="userCode" class="form-control">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-3 control-label">
                        <label>{{ trans('lahistory_management.fullname') }}</label>
                    </div>
                    <div class="col-md-6">
                        <input type="text" name="userName" class="form-control">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-3 control-label">
                        <label>{{trans('lahistory_management.from_date')}} <span class="text-danger">*</span></label>
                    </div>
                    <div class="col-md-6">
                        <input type="text" name="from_date" class="form-control datepicker">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-3 control-label">
                        <label>{{trans('lahistory_management.to_date')}} <span class="text-danger">*</span></label>
                    </div>
                    <div class="col-md-6">
                        <input type="text" name="to_date" class="form-control datepicker">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-3"></div>
                    <div class="col-md-6">
                        <button type="submit" id="btnSearch" class="btn">{{ trans('labutton.view_login_history') }}</button>
                        <button id="btnExport" class="btn" name="btnExport">
                            <i class="fa fa-file-excel-o" aria-hidden="true"></i> {{ trans('labutton.export_excel') }}
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </form>
    <br>
    <div class="table-responsive">
        <table id="bootstraptable" class="tDefault table table-hover table-bordered bootstrap-table" data-url="{{route('module.report.getData')}}">
            <thead>
                <tr class="tbl-heading">
                    <th data-formatter="index_formatter" data-align="center" data-width="3%">#</th>
                    <th data-field="user_code">{{ trans('lahistory_management.user_code') }}</th>
                    <th data-field="user_name">{{trans('lahistory_management.student')}}</th>
                    <th data-field="number_hits" data-align="center" data-width="5%">{{trans("lahistory_management.access_number")}}</th>
                    <th data-field="start_date" data-align="center" data-width="15%">{{trans("lahistory_management.time_start")}}</th>
                    <th data-field="end_date" data-align="center" data-width="15%">{{trans("lahistory_management.last_access")}}</th>
                    <th data-field="ip_address" data-align="center" data-width="10%">IP</th>
                </tr>
            </thead>
        </table>
    </div>
    <script type="text/javascript">
        function index_formatter(value, row, index) {
            return (index + 1);
        }

    </script>
    <script src="{{asset('styles/module/report/js/bc18.js')}}" type="text/javascript"></script>
{{-- @endsection --}}
