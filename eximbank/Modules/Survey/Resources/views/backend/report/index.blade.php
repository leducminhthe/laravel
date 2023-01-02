@extends('layouts.backend')

@section('page_title', trans("lasurvey.report"))

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lamenu.survey'),
                'url' => route('module.survey.index')
            ],
            [
                'name' => trans("lasurvey.report"),
                'url' => ''
            ],
        ]
    @endphp
    @include('layouts.backend.menu_breadcum',$breadcum)
@endsection

@section('content')
    <div role="main">
        <div class="row">
            <div class="col-md-12 ">
                @include('survey::backend.report.filter')
            </div>
            <div class="col-md-12 text-right act-btns">
            </div>
        </div>
        <br>
        <table class="tDefault table table-hover bootstrap-table">
            <thead>
                <tr>
                    <th data-field="code">{{trans('lasurvey.employee_code')}}</th>
                    <th data-field="profile_name" data-formatter="name_formatter">{{trans('lasurvey.fullname')}}</th>
                    <th data-field="email">{{trans('lasurvey.employee_email')}}</th>
                    <th data-field="title_name">{{ trans('lasurvey.title') }}</th>
                    <th data-field="unit_name">{{ trans('lasurvey.work_unit') }}</th>
                    <th data-field="unit_manager">{{ trans('lasurvey.unit_manager') }}</th>
                </tr>
            </thead>
        </table>
    </div>
    <script type="text/javascript">
        function name_formatter(value, row, index) {
            return '<a href="'+ row.edit_url +'">'+ row.profile_name +'</a>';
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.survey.report.getdata', ['survey_id' => $survey_id]) }}',
        });
    </script>
@endsection
