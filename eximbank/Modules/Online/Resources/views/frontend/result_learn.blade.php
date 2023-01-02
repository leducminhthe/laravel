@extends('online::survey.app')

@section('page_title', 'Kết quả học tập')

@section('header')
    <style>
        body{
            background: white !important;
        }
    </style>
@endsection

@section('content')
<div class="container-fluid body_content_detail">
    <div class="my-3 text-justify">
        <div class="row">
            <div class="col-12 text-center">
                @if ($result)
                    <h3 class="text-success">BẠN ĐÃ HOÀN THÀNH KHOÁ HỌC NÀY</h3>
                @else
                    <h3 class="text-danger">BẠN CHƯA HOÀN THÀNH KHOÁ HỌC NÀY</h3>
                @endif
            </div>
            <div class="col-12 col-md-12 mt-3 border-top">
                <div class="text-center h5 mt-2">Thông tin chi tiết kết quả hoạt động bài học</div>
                <table class="table table-striped mt-3">
                    <thead>
                        <tr>
                            <th scope="col">{{ trans('laother.content_name') }}</th>
                            <th scope="col" class="text-center">{{ trans('latraining.conditions') }}</th>
                            <th scope="col" class="text-center">{{ trans('latraining.result') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($get_activity_courses->count() > 0)
                            @foreach ($get_activity_courses as $get_activity_course)
                                @php
                                    $get_activity_completion = Modules\Online\Entities\OnlineCourseActivityCompletion::select('status')->where('course_id',$get_activity_course->course_id)->where('activity_id',$get_activity_course->id)->where('user_id', $user_id)->where('user_type', 1)->first();
                                @endphp
                                <tr>
                                    <th>
                                        @if($get_activity_course->activity_id == 1)
                                            <i class="uil uil-suitcase-alt crse_icon"></i>
                                        @elseif ($get_activity_course->activity_id == 2)
                                            <i class="uil uil-file-check crse_icon"></i>
                                        @elseif ($get_activity_course->activity_id == 3)
                                            <i class="uil uil-file crse_icon"></i>
                                        @elseif ($get_activity_course->activity_id == 4)
                                            <i class="uil uil-link crse_icon"></i>
                                        @elseif ($get_activity_course->activity_id == 5)
                                            <i class="uil uil-video crse_icon"></i>
                                        @elseif ($get_activity_course->activity_id == 7)
                                            <i class="uil uil-suitcase-alt crse_icon"></i>
                                        @elseif ($get_activity_course->activity_id == 8)
                                            <i class="uil uil-suitcase-alt crse_icon"></i>
                                        @endif
                                        <span class="section-title-text">{{ $get_activity_course->name }} </span>
                                    </th>
                                    <td class="text-center">
                                        {{ !empty($condition_activity) && in_array($get_activity_course->id, $condition_activity) ? trans('latraining.yes') : trans('latraining.no') }}
                                    </td>
                                    <td class="text-center">
                                        {{ $get_activity_completion && $get_activity_completion->status == 1 ? trans('latraining.completed') : trans('latraining.incomplete') }}
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                {{ trans('backend.not_found') }}
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@stop
