@extends('layouts.backend')

@section('page_title', trans('lareport.report'))

@section('header')
    <link rel="stylesheet" href="{{ asset('styles/module/report/css/list.css') }}">
@endsection

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lareport.report'),
                'url' => ''
            ],
        ]
    @endphp
    @include('layouts.backend.menu_breadcum',$breadcum)
@endsection

@section('content')
<style>
    .class-hidden{
        display: none;
    }
</style>
    <div role="main" id="report">
        <div class="row">
            <div class="col-md-12">
                <a href="{{route('module.report_new.history_export')}}" class="btn report-title"> {{ trans('labutton.history_export') }} </a>
            </div>
            <div class="col-md-12">
                @foreach($group_reports as $group_key => $group)
                    @php
                        $countChild = 0;
                    @endphp
                    <div class="group-list">
                        <div class="h5 mb-1 mt-2 border-bottom">
                            {{ trans('lamenu.'.$group_key) }} ({{ $count[$group_key] }})
                        </div>
                        @foreach ($group as $key => $report)
                            @can('report-'.(str_replace('BC','',$key)))
                                @php
                                    $countChild += 1;
                                @endphp
                                <div class="report_item ml-3 p-2 {{ $loop->iteration > 5 ? 'class-hidden' : '' }}">
                                    <a href="{{route('module.report_new.review', ['id'=>$key])}}" class="report-title">
                                        {{ ($countChild < 10 ? '0'.$countChild : $countChild) . './' . $report }}
                                    </a>
                                </div>
                            @endcan
                        @endforeach
                        @if (count($group) > 5)
                            <div class="ml-3 p-2">
                                <a href="javascript:void(0)" class="show-all">{{ trans('laforums.view_all').'...' }}</a>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <script>
        $('.show-all').on('click', function(){
            $(this).closest('.group-list').find('.report_item').removeClass('class-hidden');
            $(this).hide();
        })
    </script>
@stop
