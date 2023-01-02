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
                'name' => $page_title. ': ' . $profile->full_name,
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
                <div class="btn-group act-btns">
                    @canany(['movetrainingprocess-move', 'movetrainingprocess-edit'])
                        <button type="submit" class="btn load-modal" data-url="{{ route('module.subjectcomplete.user.get_modal',['user_id'=>$user_id]) }}" data-must-checked="false"><i class="fa fa-check"></i> &nbsp;{{ trans('lasuggest_plan.choose_subject') }}</button>
                    @endcanany
                    <a href="{{ route('module.subjectcomplete.index') }}" class="btn"><i class="fa fa-times-circle"></i> {{ trans('labutton.back') }}</a>
                </div>
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
                <th data-field="titles_name" >{{ trans('latraining.title') }}</th>
                <th data-field="unit_name" >{{ trans('lamenu.unit') }}</th>
                <th data-field="course_type" >{{ trans('latraining.training_form') }}</th>
                <th data-field="process_type" >{{ trans('lacategory.form') }}</th>
                <th data-field="start_date" >{{ trans('latraining.from_date') }}</th>
                <th data-field="end_date">{{ trans('latraining.to_date') }}</th>
                <th data-field="result" data-with="5%">{{ trans('latraining.result') }}</th>
                <th data-field="status" data-with="5%">{{ trans('latraining.status') }}</th>
            </tr>
            </thead>
        </table>
    </form>
</div>
<script type="text/javascript">

    var table = new LoadBootstrapTable({
        locale: '{{ \App::getLocale() }}',
        url: '{{ route('module.subjectcomplete.user.getData',['user_id'=>$user_id]) }}',
    });
</script>
@stop
