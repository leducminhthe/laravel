@extends('layouts.backend')

@section('page_title', trans('backend.capability'))

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('backend.capabilities'),
                'url' => route('module.capabilities.review')
            ],
            [
                'name' => trans('backend.student').': '. $user->lastname .' '. $user->firstname,
                'url' => ''
            ],
        ]
    @endphp
    @include('layouts.backend.menu_breadcum',$breadcum)
@endsection

@section('content')
    <link rel="stylesheet" href="{{ myasset('styles/module/capabilities/css/capabilities.css') }}">
    <script src="{{ asset('js/charts-loader.js') }}" type="text/javascript"></script>
    @php
        $review_last = \Modules\Capabilities\Entities\CapabilitiesResult::getLastReviewUser($user->user_id);
        $course_standard = \Modules\Capabilities\Entities\CapabilitiesResult::getCourseStandard($user->user_id);
        $course_need_add = \Modules\Capabilities\Entities\CapabilitiesResult::getCourseNeedAdditional($user->user_id);
        $percent =  \Modules\Capabilities\Entities\CapabilitiesResult::getPercent($user->user_id);
    @endphp
    <div role="main" id="course-review">
        <div class="tPanel">
            <div class="tab-content">
                <div id="base" class="tab-pane active">
                    <div class="row">
                        <div class="col-md-12">
                            <h3 class="text-center pb-2 title_color">CHỦ ĐỀ THEO KHUNG NĂNG LỰC</h3>
                            <div class="row">
                                <div class="col-8 vdtodt">
                                    <div class="">
                                        <span class="font-weight-bold">{{ trans('backend.employee_name') }}: </span>
                                        {{ $user->lastname .' '. $user->firstname .' ('. $user->code .')' }}
                                    </div>
                                    <div class="">
                                        <span class="font-weight-bold">{{ trans('lamenu.unit') }}: </span>
                                        @if(isset($unit->name))
                                            {{ $unit->name }}
                                        @endif
                                    </div>
                                    <div class="">
                                        <span class="font-weight-bold">{{ trans('latraining.title') }}: </span>
                                        @if(isset($title->name))
                                            {{ $title->name }}
                                        @endif
                                    </div>
                                    <div class="">
                                        <span class="font-weight-bold">{{trans('latraining.status')}}: </span>
                                        @if($percent == 0)
                                            Đang lên lộ trình cải thiện
                                        @elseif($percent == 100)
                                            Đã đáp ứng yêu cầu
                                        @else
                                            Đang phát triển
                                        @endif
                                    </div>
                                    <div class="">
                                        <span class="font-weight-bold">Ngày cập nhật đánh giá: </span>
                                        {{  $review_last ? get_date($review_last->updated_at, 'd/m/Y') : '' }}
                                    </div>
                                </div>
                                <div class="col-4">
                                    <img src="{{ image_user($user->avatar, 150) }}" alt="" class=""  style="width: 100px;">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mt-2">
                            <h4 class="border-bottom title_color">TIẾN TRÌNH ĐÀO TẠO THEO NĂNG LỰC</h4>
                            <div class="progress progress2">
                                <div class="progress-bar w-{{$percent}}" role="progressbar" style="width: {{ $percent }}%" aria-valuenow="{{ $percent }}" aria-valuemin="0" aria-valuemax="100">
                                    {{ $percent }}%
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <h4 class="border-bottom title_color">BIỂU ĐỒ PHÁT TRIỂN NĂNG LỰC DỰA THEO KHÓA HỌC</h4>
                            <div id="chart-capability"></div>
                        </div>
                        <div class="col-md-12">
                            <h3 class="text-center title_color">CHI TIẾT CÁC KHÓA HỌC THEO NĂNG LỰC CỦA BẠN</h3>

                            <h4 class="title_color">CHI TIẾT CÁC KHÓA HỌC THEO NĂNG LỰC CỦA CHỨC DANH HIỆN TẠI</h4>
                            <table class="tDefault table table-bordered table-review">
                                <thead>
                                    <tr>
                                        <th>{{ trans('latraining.stt') }}</th>
                                        <th>Tên năng lực yêu cầu</th>
                                        <th>Đánh giá kỹ năng</th>
                                        <th>{{ trans('backend.result') }}</th>
                                        <th>{{trans('backend.form')}}, {{trans('backend.date_finish')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($course_standard)
                                        @foreach($course_standard as $key => $item)
                                            @php
                                                $result = \Modules\Capabilities\Entities\CapabilitiesResult::getCourseComplete($user->user_id, $item->course_id, $user->user_id);
                                                if ($item->course_type == 1){
                                                    $type = 'Online';
                                                }else{
                                                    $type = 'Offline';
                                                }
                                            @endphp
                                                <tr>
                                                    <td>{{ $key + 1 }}</td>
                                                    <td class="text-left">
                                                        {{ $item->capabilities_name .' - Cấp độ '. $item->standard_level .' ('. $item->capabilities_code . ')'  }} <br>
                                                        <span class="text-info">NHÓM: {{ $item->group_name }}</span> <br>
                                                        <span class="text-danger">KHÓA: {{ $item->course_name }}</span>
                                                    </td>
                                                    <td>{{ $result ? 'Đáp ứng' : '' }}</td>
                                                    <td>@if($result) <i class="fa fa-check text-success"></i> @endif</td>
                                                    <td>
                                                        @if($result)
                                                            {{ $type }} <br>
                                                            {{ get_date($result->created_at, 'h:i d/m/Y') }}
                                                        @endif
                                                    </td>
                                                </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>

                            <h4 class="title_color">CHI TIẾT CÁC KHÓA HỌC CẦN BỔ SUNG CHO NĂNG LỰC HIỆN TẠI</h4>
                            <table class="tDefault table table-bordered table-review">
                                <thead>
                                <tr>
                                    <th>{{ trans('latraining.stt') }}</th>
                                    <th>Tên năng lực yêu cầu</th>
                                    <th>Đánh giá kỹ năng</th>
                                    <th>{{ trans('backend.result') }}</th>
                                    <th>{{trans('backend.form')}}, {{trans('backend.date_finish')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if($course_need_add)
                                    @foreach($course_need_add as $key => $item)
                                        @php
                                            $result = \Modules\Capabilities\Entities\CapabilitiesResult::getCourseComplete($user->user_id, $item->course_id, $user->user_id);
                                            if ($item->course_type == 1){
                                                 $type = 'Online';
                                            }else{
                                                 $type = 'Offline';
                                            }
                                        @endphp
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td class="text-left">
                                                    {{ $item->capabilities_name .' - Cấp độ '. $item->standard_level .' ('. $item->capabilities_code . ')'  }} <br>
                                                    <span class="text-info">NHÓM: {{ $item->group_name }}</span> <br>
                                                    <span class="text-danger">KHÓA: {{ $item->course_name }}</span>
                                                </td>
                                                <td>{{ $result ? 'Đáp ứng' : '' }}</td>
                                                <td>@if($result) <i class="fa fa-check text-success"></i> @endif</td>
                                                <td>
                                                    @if($result)
                                                        {{ $type }} <br>
                                                        {{ get_date($result->created_at, 'h:i d/m/Y') }}
                                                    @endif
                                                </td>
                                            </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('footer')
    <script type="text/javascript">
        /* charts */
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            var jsonData = $.ajax({
                type: "POST",
                url: "{{ route('module.capabilities.review.user.chart_course', ['user_id' => $user->user_id]) }}",
                dataType: "json",
                async: false,
                data: {
                    "_token": "{{ csrf_token() }}",
                },
            }).responseText;

            jsonData = JSON.parse(jsonData);
            var data = google.visualization.arrayToDataTable(jsonData);

            var options = {
                title: '',
                curveType: 'function',
                legend: { position: 'top' },
            };

			if (zeroAxis()) {
				options.vAxis = {};
				options.vAxis.ticks = [0];
			}

            var chart = new google.visualization.LineChart(document.getElementById('chart-capability'));

            chart.draw(data, options);

			// determine if all y-axis values are zero
			function zeroAxis() {
				var yAxisRange = {
				  max: null,
				  min: null
				};
				for (var i = 1; i < data.getNumberOfColumns(); i++) {
				  var range = data.getColumnRange(i);
				  yAxisRange.max = yAxisRange.max || range.max;
				  yAxisRange.max = Math.max(yAxisRange.max, range.max);
				  yAxisRange.min = yAxisRange.min || range.min;
				  yAxisRange.min = Math.min(yAxisRange.min, range.min);
				}
				return ((yAxisRange.max === 0) && (yAxisRange.min === 0));
			}
        }
    </script>
@endsection
