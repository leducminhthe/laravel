@extends('layouts.backend')

@section('page_title', trans('lamenu.plan_suggest'))
@section('header')
    <link rel="stylesheet" href="{{ asset('styles/vendor/sweetalert2/animate.min.css') }}">
    <link rel="stylesheet" href="{{ asset('styles/module/user/css/user.css') }}">
    <script src="{{asset('styles/vendor/bootstrap/js/bs-custom-file-input.min.js')}}"></script>
    <script src="{{ asset('styles/module/plansuggest/js/plan_suggest.js') }}"></script>
@endsection
@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lamenu.plan_suggest'),
                'url' => ''
            ],
        ]
    @endphp
    @include('layouts.backend.menu_breadcum',$breadcum)
@endsection

@section('content')
    <div role="main">
        <div class="row">
            <div class="col-md-2 form-inline">
                @include('plansuggest::backend.filter')
            </div>
            <div class="col-md-10 text-right act-btns">
                <div class="pull-right">
                    <div class="btn-group">
                        @if (userCan('plan-suggest-approve'))
                            <button id="btnApproved" data-url="{{route('module.plan_suggest.approved')}}" class="btn" name="approved"><i class="fa fa-check"></i> {{trans('labutton.approve')}}</button>
                            <button id="btnDeny" data-url="{{route('module.plan_suggest.deny')}}" class="btn" name="deny" ><i class="fa fa-times"></i> {{trans('labutton.deny')}}</button>
                        @endif
                        @if(\App\Models\Permission::isUnitManager() || userCan('plan-suggest-create'))
                            <a href="{{ route('module.plan_suggest.create') }}" class="btn"><i class="fa fa-plus-circle"></i> {{trans('labutton.add_new')}}</a>
                        @endif
                        @if(\App\Models\Permission::isUnitManager() || userCan('plan-suggest-delete'))
                            <button class="btn" id="btnDelete" value="3" data-url="{{route('module.plan_suggest.remove')}}"><i class="fa fa-trash"></i> {{trans('labutton.delete')}}</button>
                        @endif

                        @if(\App\Models\Permission::isUnitManager() || userCan('plan-suggest-export'))
                        <a class="btn" href="javascript:void(0)" id="export-excel">
                            <i class="fa fa-download"></i> {{ trans('labutton.export') }}
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <br>
        <div class="table-responsive">
            <table id="dg" class="tDefault table table-hover table-bordered bootstrap-table">
                <thead>
                <tr class="tbl-heading">
                    <th data-checkbox="true"></th>
                    @if(\App\Models\Permission::isAdmin())
                    <th data-field="unit_name" data-align="left">{{trans('lasuggest_plan.unit')}}</th>
                    @endif
                    <th data-field="subject_name" data-formatter="name_formatter">{{trans('lasuggest_plan.training_content')}}</th>
                    <th data-field="amount" data-align="center">{{trans('lasuggest_plan.quantity')}}</th>
                    <th data-field="type" data-align="left">{{trans('lasuggest_plan.form')}}</th>
                    <th data-field="training_form" data-align="left">{{trans('lasuggest_plan.type')}}</th>
                    <th data-field="time" data-align="center">{{trans('lasuggest_plan.time')}}</th>
                    <th data-field="attach" data-align="center" data-formatter="attach_formatter">{{trans('lasuggest_plan.attach_file')}}</th>
                    <th data-field="attach_report" data-align="center" data-formatter="attach_report_formatter">{{trans('lasuggest_plan.report_file')}}</th>
                    <th data-align="center" data-width="5%" data-formatter="status_formatter" >{{trans('lasuggest_plan.status')}}</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>
    <script type="text/javascript">
        function name_formatter(value, row, index) {
            return '<a href="'+row.edit_url+'">' +row.subject_name+ '</a>';
        }
        function status_formatter(value, row, index) {
            if (row.status==1)
                return '<span class="text-warning">{{trans("backend.pending")}}</span>';
            else if (row.status==2)
                return '<span class="text-success">{{trans("backend.approved")}}</span>';
            else if (row.status==3)
                return '<span class="text-danger">{{trans("backend.deny")}}</span>';
            else
                return '{{trans("backend.unsent")}}';
        }
        function attach_formatter(value,row,index) {
            if (row.download_file){
                return '<a href="'+row.download_file+'"><i class="fa fa-file" aria-hidden="true"></i></a>'
            }
        }
        function attach_report_formatter(value,row,index) {
            if (row.download_report){
                return '<a href="'+row.download_report+'"><i class="fa fa-file" aria-hidden="true"></i></a>'
            }
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.plan_suggest.getData') }}',
            remove_url: '{{ route('module.plan_suggest.remove') }}'
        });

        $("#export-excel").on('click', function () {
            let form_search = $("#form-search").serialize();
            window.location = '{{ route('module.plan_suggest.export') }}?'+form_search;
        });

    </script>

@endsection
