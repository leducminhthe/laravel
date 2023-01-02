@extends('layouts.backend')

@section('header')
    <link rel="stylesheet" href="{{ asset('styles/module/report/css/list.css') }}">
    <script src="{{ asset('js/charts-loader.js') }}" type="text/javascript"></script>
    {{--<script src="{{asset('styles/js/BootstrapTable.js')}}" type="text/javascript"></script>--}}
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
        .table-bordered > thead > tr > th{
            border: 1px solid #b9b5b5 !important;
        }
        .body_list_repost {
            overflow: auto;
        }
        #search_unit_id-error {
            display: block;
            margin-bottom: 2px
        }
    </style>
@endsection

@section('page_title', trans('lareport.view_report'))

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title">
            <i class="uil uil-apps"></i>
            <a href="javascript:void(0)" class="cusror_pointer" onclick="showListReport()">{{ trans('backend.new_report') }}</a>
            <i class="uil uil-angle-right"></i>
            <span class="font-weight-bold">{{ trans('backend.view_report') }}</span>
        </h2>
    </div>
@endsection

@section('content')
    <div role="main" id="report" class="pt-5">
        <div class="text-center mb-5 text-uppercase"><h3>{{$name}}</h3></div>
        @include('reportnew::'. strtolower($report) .'.review')
    </div>

    <div id="modal-list-report" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">{{ trans('lareport.list_report') }}</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body body_list_repost">
                    @foreach($reportList as $group_key => $group)
                        @php
                            $countChild = 0;
                        @endphp
                        <div class="group-list">
                            <div class="h5 mb-1 mt-2">
                                {{ trans('lamenu.'.$group_key) }}
                            </div>
                            @foreach ($group as $key => $item)
                                @can('report-'.(str_replace('BC','',$key)))
                                    @php
                                        $countChild += 1;
                                    @endphp
                                    <a href="{{route('module.report_new.review', ['id'=>$key])}}" class="report-title pl-3">
                                        @if ($report == strtolower($key))
                                            <span>{{ ($countChild < 10 ? '0'.$countChild : $countChild) }}./ </span>
                                           <u>{{ $item }}</u>
                                        @else
                                            {{ ($countChild < 10 ? '0'.$countChild : $countChild) . './ ' . $item }}
                                        @endif
                                    </a>
                                    <p class="mb-1"></p>
                                @endcan
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <script>
        function showListReport() {
            $('#modal-list-report').modal();
        }

        {{--  jQuery.validator.addMethod("lessStart", function (value, element, params) {
            console.log(value, $('input[name='+params+']').val(), new Date("01/10/2022"));

            return new Date(value) < new Date($('input[name='+params+']').val());

        },'Must be greater than start date.');  --}}
    </script>
@stop

