@extends('layouts.backend')

@section('page_title', 'Dashboard')

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lamenu.dashboard'),
                'url' => route('module.dashboard_teacher')
            ],
            [
                'name' => 'Chi tiết',
                'url' => ''
            ],
        ]
    @endphp
    @include('layouts.backend.menu_breadcum',$breadcum)
@endsection

@section('content')
    @php
        $year = date('Y');
    @endphp
    <div role="main" class="dashboard_teacher_detail">
        <div class="row">
            <div class="col-md-12 form-inline">
                <form class="form-inline form-search mb-3 w-100" id="form-search">
                    <input type="text" name="search" value="" class="form-control w-30" placeholder="{{ trans('lacategory.enter_code_name') }}">
                    <div class="w-20">
                        <select name="year" class="form-control select2" data-placeholder="{{ trans('lanote.year') }}">
                            <option value=""></option>
                            @for($i = 2020; $i <= date('Y'); $i++)
                                <option value="{{ $i }}" {{ $i == $year ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <button type="submit" class="btn"><i class="fa fa-search"></i>&nbsp;{{ trans('labutton.search') }}</button>
                </form>
            </div>
        </div>
        <table class="tDefault table table-hover bootstrap-table" id="table_history">
            <thead>
                <tr>
                    <th data-field="course">{{ trans('latraining.course') }}</th>
                    <th data-field="class_name">{{ trans('latraining.class_name') }}</th>
                    <th data-field="schedule" data-align="center" data-width="10%">{{ trans('latraining.num_session') }}</th>
                    <th data-field="teacher_main" data-align="center" data-width="5%">{{ trans('latraining.main_lecturer') }} <br> ({{ trans('app.times') }})</th>
                    <th data-field="tutors" data-align="center" data-width="5%">{{ trans('latraining.tutors') }} <br> ({{ trans('app.times') }})</th>
                    <th data-field="num_hour" data-align="center" data-width="5%">{{ trans('latraining.hour') }}</th>
                    <th data-field="cost" data-align="center" data-width="10%">{{ trans('latraining.cost') }}</th>
                </tr>
            </thead>
        </table>
    </div>
@endsection
@section('footer')
    <script>
        var table_history = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: "{{ route('backend.category.training_teacher.history.getdata', ['teacher_id' => $trainingTeacher]) }}",
            table: '#table_history',
        });
    </script>
@endsection
