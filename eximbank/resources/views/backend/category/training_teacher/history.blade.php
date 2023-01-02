@extends('layouts.backend')

@section('page_title', $page_title)

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lacategory.teacher'),
                'url' => ''
            ],
            [
                'name' => trans('lacategory.list_teacher'),
                'url' => route('backend.category.training_teacher')
            ],
            [
                'name' => $page_title,
                'url' => ''
            ],
        ]
    @endphp
    @include('layouts.backend.menu_breadcum',$breadcum)
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <table class="tDefault table table-hover bootstrap-table" id="table_history">
            <thead>
                <tr>
                    <th data-field="course">{{ trans('latraining.course') }}</th>
                    <th data-field="class_name">{{ trans('latraining.class_name') }}</th>
                    <th data-field="schedule" data-align="center" data-width="10%">{{ trans('latraining.num_session') }}</th>
                    <th data-field="teacher_main" data-align="center" data-width="5%">{{ trans('latraining.main_lecturer') }} <br> ({{ trans('app.times') }})</th>
                    <th data-field="tutors" data-align="center" data-width="5%">{{ trans('latraining.tutors') }} <br> ({{ trans('app.times') }})</th>
                    <th data-field="num_hour" data-align="center" data-width="5%">{{ trans('latraining.hour') }}</th>
                    <th data-field="tbc_num_star" data-align="center" data-width="5%">{{ trans('latraining.num_star',['num'=>'Số']) }}</th>
                    <th data-field="num_student" data-align="center" data-width="5%">{{ trans('latraining.student') }}</th>
                    <th data-field="cost" data-align="center" data-width="10%">{{ trans('latraining.cost') }}</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<script type="text/javascript">
    var table_history = new LoadBootstrapTable({
        locale: '{{ \App::getLocale() }}',
        url: "{{ route('backend.category.training_teacher.history.getdata', ['teacher_id' => $model->id]) }}",
        table: '#table_history',
    });
</script>
@stop
