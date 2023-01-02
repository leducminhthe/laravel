@extends('layouts.app')

@section('page_title', trans('lamenu.usermedal_setting'))

@section('header')

@endsection

@section('content')

<style>
    #pills-tab .nav-link.active:hover {
        color: white !important;
    }
</style>
    <div class="sa4d25">
        <div class="container-fluid bg-white">

            <h2>{{$info->name}}</h2>
            <p><i class="fa fa-calculator"></i> {{ trans('latraining.time') }}: {{date('d/m/Y', $info->start_date)}} <i class="fa fa-arrow-right" aria-hidden="true"></i> {{date('d/m/Y', $info->end_date)}}</p>
            <div>{{ trans('latraining.description') }}: {!! $info->content !!}</div>
            <div>&nbsp;</div>
            <div>{{ trans('laother.rules') }}: {!! $info->rule !!}</div>
            <div>&nbsp;</div>
            <div>&nbsp;</div>

            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="pills-info-tab" data-toggle="pill" href="#pills-info" role="tab" aria-controls="pills-info" aria-selected="true">{{ trans('latraining.info') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="pills-result-tab" data-toggle="pill" href="#pills-result" role="tab" aria-controls="pills-result" aria-selected="false">{{ trans('latraining.result') }}</a>
                </li>
            </ul>
            <div class="tab-content mb-4" id="pills-tabContent">
                <div class="tab-pane fade show active" id="pills-info" role="tabpanel" aria-labelledby="pills-info-tab">
                    @if($arrOnline)
                    <h2>{{ trans('lamenu.online_course') }}</h2>
                    <table class="table table-bordered table-hover">
                        <thead>
                        <tr class="tbl-heading"><th style="width:200px;">{{ trans('latraining.course_code') }}</th>
                            <th>{{ trans('latraining.course_name') }}</th>
                            <th style="width:200px;">{{ trans('lamenu.time_complete') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($arrOnline as $item)
                            <tr>
                                <td>{{$item["code"]}}</td>
                                <td>{{$item["name"]}}</td>
                                <td>{{date('d-m-Y',$item["start_date"])}} <i class="fa fa-arrow-right" aria-hidden="true"></i> {{date('d-m-Y',$item["end_date"])}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    @endif

                    @if($arrOffline)
                    <h2>{{ trans('lamenu.offline_course') }}</h2>
                    <table class="table table-bordered table-hover">
                        <thead>
                        <tr class="tbl-heading"><th style="width:200px;">{{ trans('latraining.course_code') }}</th>
                            <th>{{ trans('latraining.course_name') }}</th>
                            <th style="width:200px;">{{ trans('latraining.time_complete') }}</th>
                        </tr></thead>
                        <tbody>
                        @foreach($arrOffline as $item)
                            <tr>
                                <td>{{$item["code"]}}</td>
                                <td>{{$item["name"]}}</td>
                                <td>{{date('d-m-Y',$item["start_date"])}} <i class="fa fa-arrow-right" aria-hidden="true"></i> {{date('d-m-Y',$item["end_date"])}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    @endif

                    @if($arrQuiz)
                    <h2>{{ trans('lamenu.quiz') }}</h2>
                    <table class="table table-bordered table-hover">
                        <thead>
                        <tr class="tbl-heading"><th style="width:200px;">{{ trans('lasetting.code') }}</th>
                            <th>{{ trans('lacategory.name') }}</th>
                            <th style="width:200px;">{{ trans('lamenu.time_complete') }}</th>
                        </tr></thead>
                        <tbody>
                        @foreach($arrQuiz as $item)
                            <tr>
                                <td>{{$item["code"]}}</td>
                                <td>{{$item["name"]}}</td>
                                <td>{{date('d-m-Y',$item["start_date"])}} <i class="fa fa-arrow-right" aria-hidden="true"></i> {{date('d-m-Y',$item["end_date"])}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    @endif

                    @if ($arrMedal)
                    <h2>{{ trans('lamenu.user_level_setting') }}</h2>
                    <table class="table table-bordered table-hover">
                        <thead>
                        <tr class="tbl-heading"><th style="width:200px;">{{ trans('latraining.picture') }}</th>
                            <th>{{ trans('laother.name_badge') }}</th>
                            <th style="width:200px;">{{ trans('latraining.description') }}</th>
                            <th style="width:150px;">{{ trans('laother.badge_class') }}</th>
                            <th style="width:100px;">{{ trans('laother.score_from') }}</th>
                            <th style="width:100px;">{{ trans('laother.score_to') }}</th>
                        </tr></thead>
                        <tbody>
                        @foreach($arrMedal as $item)
                            <tr>
                                <td style="text-align: center;"><img src="{{image_course($item["photo"])}}" style="height:100px; width: auto;"/></td>
                                <td>{{$item["name"]}}</td>
                                <td>{{$item["content"]}}</td>
                                <td style="text-align: center;">{{$item["rank"]}}</td>
                                <td style="text-align: center;">{{$item["min_score"]}}</td>
                                <td style="text-align: center;">{{$item["max_score"]}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    @endif
                </div>
                <div class="tab-pane fade" id="pills-result" role="tabpanel" aria-labelledby="pills-result-tab">
                    <h2>{{ trans('laother.list_student_badge') }}</h2>
                    <div class="table-responsive">
                        <table id="dg" class="table bootstrap-table">
                            <thead class="thead-s">
                            <tr class="tbl-heading">
                                <th width="40px" data-formatter="index_formatter">#</th>
                                <th data-field="code">{{ trans('latraining.employee_code') }}</th>
                                <th data-field="full_name">{{ trans('latraining.fullname') }}</th>
                                <th data-field="email">Email</th>
                                <th data-field="title_name">{{ trans('latraining.title') }}</th>
                                <th data-field="unit_name">{{ trans('latraining.unit') }}</th>
                                <th data-field="submedal_name"  data-align="center">{{ trans('laother.name_badge') }}</th>
                                <th data-field="submedal_rank" data-align="center">{{ trans('lacategory.rank') }}</th>
                                <th data-field="point">{{ trans('latraining.score') }}</th>
                                <th data-field="datecreated" data-width="260px">{{ trans('laother.achieved_date') }}</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.frontend.usermedal.dataresult',$info->id) }}',
        });

        function index_formatter(value, row, index) {
            return (index + 1);
        }
        function result(value, row, index) {
            return value == 1 ? '{{trans("backend.finish")}}' : '{{trans('backend.incomplete')}}';
        }

    </script>

@endsection
