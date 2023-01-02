@extends('layouts.backend')

@section('page_title', trans('latraining.reimbursement_costs') .' '.$full_name)

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            {{ trans('lamenu.training') }} <i class="uil uil-angle-right"></i>
            <a href="{{route('module.indemnify')}}">{{ trans('lamenu.indemnify') }}</a>
            <i class="uil uil-angle-right"></i>
            <span class="font-weight-bold">{{ trans('latraining.reimbursement_costs') .' '. $full_name }}</span>
        </h2>
    </div>
@endsection

@section('content')
    <div role="main">

        <div class="row">
            <div class="col-md-8">
                <form class="form-inline form-search mb-3" id="form-search">
                    <input type="text" name="search" value="" class="form-control " placeholder="{{trans('backend.code_name_course')}}">
                    <button type="submit" class="btn"><i class="fa fa-search"></i>&nbsp;{{ trans('labutton.search') }}</button>
                </form>
            </div>
            <div class="col-md-4 text-right act-btns">
            </div>
        </div>
        <br>

        <form action="{{ route('module.indemnify.user.save',['id'=>$user_id]) }}" method="post" role="form" class="form-ajax" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-10">
                    <div class="row">
                        <div class="col-md-3">
                            <label>{{ trans('latraining.total_reimbursement_costs') }}</label>
                            <input type="text" value="{{ isset($indem) ? number_format($indem->total_indemnify, 0, ',', '.') : ''}}" class="form-control" disabled>
                        </div>
                        <div class="col-md-3">
                            <label>{{ trans('latraining.percent_discount') }}</label>
                            <input type="text" id="percent" name="percent" value="{{ isset($indem) ? $indem->percent : ''}}" class="form-control is-number" {{ $check_indemnify ? '' : 'disabled' }}>
                        </div>
                        <div class="col-md-3">
                            <label>{{ trans('latraining.amount_not_reduced') }}</label>
                            <input type="text" id="exemption_amount" name="exemption_amount" value="{{ isset($indem) ? number_format($indem->exemption_amount, 0, ',', '.'): ''}}" class="form-control is-number" {{ $check_indemnify ? '' : 'disabled' }}>
                        </div>
                        <div class="col-md-3">
                            <label>{{ trans('latraining.money_paid') }}</label>
                            <input type="text" id="total_cost" value="{{ isset($indem) ? number_format($indem->total_cost, 0, ',', '.') :''}}" class="form-control is-number" {{ $check_indemnify ? '' : 'disabled' }}>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="pull-right" style="padding-top: 33px">
                        @can('indemnify-update-committed-date')
                            <button type="submit" name="btnSave" class="btn"><i class="fa  fa-save"></i>&nbsp;{{ trans('latraining.chargeback_update') }}</button>
                        @endcan
                    </div>
                </div>
            </div>
            <p></p>
            <div class="row">
                <div class="col-md-10">
                    <div class="row">
                        <div class="col-md-3">
                            {{ trans('latraining.day_off') }}
                            <input type="text" name="day_off" class="form-control datepicker" placeholder="{{ trans('latraining.day_off') }}" value="{{isset($indem) ? get_date($indem->day_off, 'd/m/Y') : '' }}">
                        </div>
                        <div class="col-md-3">
                            {{ trans('latraining.reimbursement') }} <input type="checkbox" id="compensated" {{isset($indem) ? $indem->compensated == 1 ? 'checked' : '' : '' }}>
                            <input type="text" disabled class="form-control" value="{{ isset($indem) ? $indem->compensated == 1 ? trans('latraining.reimbursed') : trans('latraining.not_reimbursed') : '' }}">
                        </div>
                    </div>
                </div>
            </div>
            <p></p>
            <table class="tDefault table table-hover bootstrap-table text-nowrap" id="indem">
                <thead>
                <tr>
                    <th data-width="1%" data-sortable="true" data-align="center" data-field="code">{{ trans('latraining.course_code') }}</th>
                    <th data-sortable="true" data-field="name">{{ trans('latraining.course_name') }}</th>
                    <th data-sortable="true" data-field="start_date"  data-width="100px">{{trans('latraining.start_date')}}</th>
                    <th data-sortable="true" data-field="end_date"  data-width="100px">{{trans('latraining.end_date')}}</th>
                    <th data-field="cost_commit" data-sortable="true" data-align="right" data-width="100px">{{ trans('backend.commitment_amount') }}</th>
                    <th data-field="cost_indemnify" data-sortable="true" data-align="right" data-width="100px">{{trans('backend.refund_amount')}}</th>
                    <th data-field="start_commit" data-sortable="true" data-align="center" data-width="100px">{{trans('backend.start_date_commitment')}}</th>
                    <th data-field="commit_date" data-sortable="true" data-align="center" data-width="80px">{{trans('backend.coimmitted_date')}}</th>
                    <th data-field="date_diff" data-sortable="true" data-align="center" data-width="80px">{{ trans('latraining.number_day_remaining') }}</th>
                    <th data-formatter="contract" data-align="center">{{ trans('latraining.number_committed_contracts') }}</th>
                </tr>
                </thead>
            </table>
        </form>
    </div>

    <script type="text/javascript">
        function contract(value,row,index) {
            return '<input type="hidden" name="course[]" value="'+row.course_id+'" /> <input type="text" name="contract[]" data-course="'+row.course_id+'" class="form-control save-contract" value="'+row.contract+'"/>'
        }
        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.indemnify.user.getdata',['id'=>$user_id]) }}',
        });

        var save_percent = "{{ route('module.indemnify.user.save_percent', ['id' => $user_id]) }}";
        var save_exemption_amount = "{{ route('module.indemnify.user.save_exemption_amount', ['id' => $user_id]) }}";
        var save_total_cost = "{{ route('module.indemnify.user.save_total_cost', ['id' => $user_id]) }}";
        var save_contract = "{{ route('module.indemnify.user.save_contract', ['id' => $user_id]) }}";
        var save_compensated = "{{ route('module.indemnify.user.save_compensated', ['id' => $user_id]) }}";
    </script>

    <script src="{{ asset('styles/module/indemnify/js/indemnify.js') }}"></script>

@endsection
