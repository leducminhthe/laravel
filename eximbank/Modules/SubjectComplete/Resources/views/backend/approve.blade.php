@extends('layouts.backend')

@section('page_title', $page_title)

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lamenu.learning_manager'),
                'url' => route('module.subjectcomplete.index')
            ],
            [
                'name' => $page_title. ': '. trans('subjectcomplete::subjectcomplete.approved_subject'),
                'url' => ''
            ],
        ]
    @endphp
    @include('layouts.backend.menu_breadcum',$breadcum)
@endsection

@section('content')
<div role="main">
    <form method="post" action=" " class="form-horizontal form-ajax" role="form" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-8">
            </div>
            <div class="col-md-4 text-right">
                @can('subjectcomplete-approved')
                    <div class="btn-group act-btns">
                        <button class="btn approve" data-status="1"><i class="fa fa-check-circle"></i> {{trans('labutton.approve')}}</button>
                        <button class="btn approve" data-status="0"><i class="fa fa-exclamation-circle"></i> {{trans('labutton.deny')}}</button>
                    </div>
                @endcan
            </div>
        </div>
        <div class="clear"></div>
        <br>
        <table class="tDefault table table-hover bootstrap-table text-nowrap">
            <thead>
            <tr>
                <th data-field="state" data-checkbox="true"></th>
                <th data-field="subject_code" data-width="5%">{{ trans('laprofile.subject_code') }}</th>
                <th data-field="subject_name"  data-width="20%">{{ trans('latraining.subject_name') }}</th>
                <th data-field="full_name" data-formatter="full_name_formatter">{{ trans('latraining.fullname') }}</th>
                <th data-field="titles_name">{{ trans('latraining.title') }}</th>
                <th data-field="unit_name">{{ trans('backend.work_unit') }}</th>
                <th data-field="parent_unit_name">{{ trans('backend.unit_manager') }}</th>
                <th data-field="note" >{{ trans('latraining.note') }}</th>
                <th data-field="status" data-with="5%">{{ trans("latraining.status") }}</th>
            </tr>
            </thead>
        </table>
    </form>
</div>
<script type="text/javascript">
    function full_name_formatter(value, row,index) {
        return row.full_name+' (<b>'+row.code+'</b>)';
    }
    var table = new LoadBootstrapTable({
        locale: '{{ \App::getLocale() }}',
        url: '{{ route('module.subjectcomplete.approve.getData') }}',
    });
</script>
<script src="{{ asset('styles/module/subjectcomplete/js/subjectcomplete.js?v=1.2') }}"></script>
@stop
