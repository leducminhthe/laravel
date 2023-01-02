@extends('themes.mobile.layouts.app')

@section('page_title', 'Tình hình học tập nhân viên')
@section('header')

@endsection
@section('content')
    <div class="container mt-2">
        <div class="card shadow overflow-hidden">
            <div class="card-header d_flex_align">
                {{ trans('app.user_info') }}
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <h6 class="mb-1">{{ $profile->full_name .' ('. $profile->code .')' }}</h6>
                        <p class="mb-0" style="font-size: 85%">
                            @lang('lamenu.unit'): {{ $profile->unit_name }}
                            <br>
                            @lang('app.title'): {{ $profile->title_name }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-2">
            <div class="row">
                @if ($type == 3)
                    <div class="col-12">
                        <span class="p-1 mr-1 mb-1 w-5 not_learned"></span> Chưa thi <br>
                        <span class="p-1 mr-1 mb-1 w-5 completed"></span> Hoàn thành <br>
                        <span class="p-1 mr-1 mb-1 w-5 uncomplete"></span>Chưa hoàn thành <br>
                    </div>
                @else
                    <div class="col-6">
                        <span class="p-1 mr-1 mb-1 w-5 studying"></span> Đang học <br>
                        <span class="p-1 mr-1 mb-1 w-5 not_learned"></span> Chưa học <br>
                    </div>
                    <div class="col-6">
                        <span class="p-1 mr-1 mb-1 w-5 completed"></span> Hoàn thành <br>
                        <span class="p-1 mr-1 mb-1 w-5 uncomplete"></span>Chưa hoàn thành <br>
                    </div>
                @endif
            </div>

            <table id="bootstraptable" class="tDefault table table-hover table-bordered bootstrap-table mt-2" data-url="{{ $table_url }}" >
                <thead>
                    <tr class="tbl-heading">
                        <th data-formatter="info_formatter">{{ trans('app.info') }}</th>
                        <th data-formatter="status_formatter" data-align="center" data-width="5%">{{ trans('app.status') }}</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@stop

@section('footer')
    <script type="text/javascript">
        function info_formatter(value, row, index) {
            return (index + 1) +'./ '+ row.name + '<br>' + row.code + '<br>' + row.time;
        }
        function status_formatter(value, row, index){
            return '<span class="p-2 '+row.bg_color+'">'+ row.percent +'</span>';
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: $('#bootstraptable').data('url'),
        });
    </script>
@stop
