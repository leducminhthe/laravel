@extends('layouts.backend')

@section('page_title', trans('backend.capabilities'))

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('backend.capabilities'),
                'url' => ''
            ],
        ]
    @endphp
    @include('layouts.backend.menu_breadcum',$breadcum)
@endsection

@section('content')
<style>
    a.disabled {
        pointer-events: none;
        cursor: default;
    }
</style>
    <div role="main">
        @if(isset($errors))
            @foreach($errors as $error)
                <div class="alert alert-danger">{!! $error !!}</div>
            @endforeach
        @endif

        <div class="row">
            <div class="col-md-12 col-sm-12 ">
                <form class="form-inline form-search-user mb-3" id="form-search">
                    @for($i = 1; $i <= 5; $i++)
                        <div class="w-auto mr-1">
                            <select name="unit" id="unit-{{ $i }}" class="form-control load-unit" data-placeholder="-- {{ trans('lamenu.unit_level',["i"=>$i]) }} --" data-level="{{ $i }}" data-loadchild="unit-{{ $i+1 }}" data-parent="0"></select>
                        </div>
                    @endfor
                    <div class="w-auto mr-1">
                        <select name="title" class="form-control load-title" data-placeholder="-- {{ trans('latraining.title') }} --"></select>
                    </div>
                    <div class="w-auto mr-1">
                        <select name="area" id="area" class="form-control load-area" data-placeholder="-- {{ trans('lacategory.area') }} --"></select>
                    </div>
                    <div class="w-auto mr-1">
                        <input type="text" name="search" class="form-control" placeholder="{{ trans('latraining.enter_code_name_user') }}">
                        <input type="text" name="join_company" class="form-control datepicker" placeholder="{{ trans('backend.day_work') }}" autocomplete="true">
                        <button type="submit" class="btn"><i class="fa fa-search"></i>&nbsp;{{ trans('labutton.search') }}</button>
                    </div>
                </form>
            </div>
            <div class="col-md-12 text-right">
                <div class="pull-right">
                    @can('capabilities-result')
                        <a href="{{ route('module.capabilities.review.result.index') }}" class="btn">{{ trans('backend.dev_training_plan') }}</a>
                    @endcan
                </div>
            </div>
        </div>
        <br>
        <table class="tDefault table table-hover bootstrap-table">
            <thead>
                <tr>
                    <th data-field="state" data-checkbox="true"></th>
                    <th data-field="code" data-width="10%">{{ trans('backend.employee_code') }}</th>
                    <th data-field="fullname" data-formatter="fullname_formatter" data-width="20%">{{ trans('backend.employee_name') }}</th>
                    <th data-field="title_name">{{ trans('latraining.title') }}</th>
                    <th data-field="unit_name">{{ trans('backend.work_unit') }}</th>
                    <th data-field="unit_manager">{{ trans('backend.unit_manager') }}</th>
                    <th data-field="join_company">{{ trans('backend.day_work') }}</th>
                    <th data-field="action" data-align="center" data-formatter="action_formatter">{{ trans('backend.action') }}</th>
                    <th data-field="count_review" data-align="center">SL đánh giá</th>
                </tr>
            </thead>
        </table>
    </div>

    <script type="text/javascript">
        function fullname_formatter(value, row, index) {
            return row.lastname +' '+ row.firstname;
        }

        function action_formatter(value, row, index) {
            var html = '';
            html += '<a href="'+ row.review_url +'" class="btn btn-sm" title="Danh sách đánh giá"><i class="fa fa-list"></i></a> ';
            @can('capabilities-review-create')
                html += ' <a href="'+ row.create_url +'" class="btn btn-sm" title="{{ trans('backend.new_review') }}"><i class="fa fa-edit"></i></a> ';
            @endcan
            if(row.review){
                html += ' <a href="'+ row.course_url +'" class="btn btn-sm" title="Kết quả đánh giá"><i class="fa fa-eye"></i></a>';
            }

            return html;
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.capabilities.review.getdata') }}',
        });

    </script>

@endsection
