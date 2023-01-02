@extends('themes.mobile.layouts.app')

@section('page_title', data_locale('Khoá học yêu thích', 'Favorite course').' ('.$total.')')

@section('content')
    <div class="container wrapped_my_course">
        @if(count($my_course) > 0)
            <div class="row">
                @foreach($my_course as $item)
                    @php
                        if ($item->course_type == 1){
                            $result = \Modules\Online\Entities\OnlineCourse::checkCompleteCourse($item->course_id, profile()->user_id);
                            $percent = \Modules\Online\Entities\OnlineCourse::percentCompleteCourseByUser($item->course_id, profile()->user_id);
                            $isRating = \Modules\Online\Entities\OnlineRating::getRating($item->course_id, profile()->user_id);
                            $route = route('themes.mobile.frontend.online.detail', ['course_id' => $item->course_id, 'my_course_like' => 1]);
                            $type = 'Online';

                            $image = image_online($item->image);
                        }else{
                            $result = \Modules\Offline\Entities\OfflineCourse::checkCompleteCourse($item->course_id, profile()->user_id);
                            $percent = \Modules\Offline\Entities\OfflineCourse::percent($item->course_id, profile()->user_id);
                            $isRating = \Modules\Offline\Entities\OfflineRating::getRating($item->course_id, profile()->user_id);
                            $route = route('themes.mobile.frontend.offline.detail', ['course_id' => $item->course_id, 'my_course_like' => 1]);
                            $type = trans('lamenu.offline_course');
                            $image = image_offline($item->image);
                        }
                    @endphp
                    <div class="col-12 col-sm-6 col-md-4 col-lg-4 col-xl-3 p-1">
                        <div class="card shadow border-0 mb-2">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12 p-1">
                                        <a href="javascript:void(0);" onclick="loadSpinner('{{ $route }}', 1, 3)">
                                            <img src="{{ $image }}" alt="" class="w-100 image_my_course">
                                        </a>
                                    </div>
                                    <div class="col-12 p-1 align-self-center">
                                        <a href="javascript:void(0);" onclick="loadSpinner('{{ $route }}', 1, 3)">
                                            <div class="mb-2 mt-1 title">
                                                <span class="font-weight-normal h6">{{ $item->name }}</span>
                                                {{--  <span class="text-mute">({{ $item->code }})</span>  --}}
                                            </div>
                                        </a>
                                        <p class="text-mute">
                                            <b><i class="fa fa-calendar-alt" aria-hidden="true"></i></b> {{ get_date($item->start_date) }} @if($item->end_date) {{' - '. get_date($item->end_date) }} @endif
                                            <br>
                                            <b>@lang('app.status'):</b> {{ $result ? data_locale('Hoàn thành', 'Complete') : data_locale('Chưa hoàn thành', 'Incomplete') }}
                                            <span class="float-right">{{ $type }}</span>
                                        </p>
                                        <div class="progress progress2">
                                            <div class="progress-bar w-70" role="progressbar" style="width: {{ $percent }}%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100">
                                                {{ round($percent, 2) }}%
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            @include('themes.mobile.layouts.paginate', ['items' => $my_course])
        @else
            <div class="row">
                <div class="col text-center">
                    <span>@lang('app.not_found')</span>
                </div>
            </div>
        @endif
    </div>
@endsection
