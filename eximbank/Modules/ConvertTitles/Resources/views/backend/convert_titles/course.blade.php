@extends('layouts.backend')

@section('page_title', '{{ trans('lacourse.course') }}')

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('backend.convert_titles'),
                'url' => route('module.convert_titles')
            ],
            [
                'name' => trans('backend.course_of').': '. $profile->lastname .' '. $profile->firstname,
                'url' => ''
            ],
        ]
    @endphp
    @include('layouts.backend.menu_breadcum',$breadcum)
@endsection

@section('content')
    <div role="main">
        <div class="row">
            <div class="col-md-9"></div>
            <div class="col-md-3 text-right">
                <a class="btn" href="{{ route('module.convert_titles.export_course', ['user_id' => $profile->user_id]) }}">
                    <i class="fa fa-download"></i> Export
                </a>
            </div>
        </div>
        <p></p>
        <table class="tDefault table tab-content border">
            <thead class="border">
                <tr>
                    <th>{{ trans('latraining.stt') }}</th>
                    <th>Mã học phần</th>
                    <th>Tên học phần</th>
                    <th>{{trans('latraining.start_date')}}</th>
                    <th>{{trans('latraining.end_date')}}</th>
                    <th>{{ trans('backend.test_score') }}</th>
                    <th>{{ trans('backend.result') }}</th>
                </tr>
            </thead>
            @foreach($subject as $key => $item)
                @php
                    $courses = $course($item->training_program_id, $item->subject_id, $item->training_form, $profile->user_id);

                    if (!is_null($courses)){
                        $result = $result_course($profile->user_id, $courses->id, $courses->course_type);
                    }

                @endphp
                <tr class="text-center">
                    <th>{{ $key + 1 }}</th>
                    <th>{{ $item->code }}</th>
                    <th>{{ $item->name }}</th>
                    <th>{{ $courses ? get_date($courses->start_date) : '' }}</th>
                    <th>{{ $courses ? get_date($courses->end_date) : '' }}</th>
                    <th>{{ isset($result) ? $result->reexamine ? $result->reexamine : $result->grade : '' }}</th>
                    <th>{{ isset($result) ? ($result->result == 1 ? 'Hoàn thành' : 'Chưa hoàn thành') : 'Chưa hoàn thành' }}</th>
                </tr>
            @endforeach
        </table>
    </div>
@endsection
