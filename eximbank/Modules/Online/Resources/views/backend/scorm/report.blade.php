@extends('layouts.backend')

@section('page_title', trans('latraining.report'))

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lamenu.training'),
                'url' => ''
            ],
            [
                'name' => trans('lamenu.online_course'),
                'url' => route('module.online.management')
            ],
            [
                'name' => $course_name,
                'url' => route('module.online.edit', ['id' => $course_id])
            ],
            [
                'name' => trans('latraining.report').': '.$scorm_name,
                'url' => ''
            ],
        ]
    @endphp
    @include('layouts.backend.menu_breadcum',$breadcum)
@endsection

@section('content')
    <div role="main" class="quiz_course_online_report">
        <div class="row">
            <div class="col-md-8 form-inline">
                <form class="form-inline form-search mb-3" id="form-search">
                    <input type="text" name="search" value="" class="form-control" placeholder="{{trans('latraining.enter_code_name_user')}}">
                    <button type="submit" class="btn"><i class="fa fa-search"></i>&nbsp;{{ trans('labutton.search') }}</button>
                </form>
            </div>
            <div class="col-md-4 text-right act-btns" id="btn-quiz">
                <div class="pull-right">
                    <div class="btn-group">
                        <button class="btn" id="delete-item"><i class="fa fa-trash"></i> Xóa</button>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <table class="tDefault table table-hover bootstrap-table ">
            <thead>
                <tr>
                    <th data-field="state"  data-width="5%" data-checkbox="true"></th>
                    <th data-field="code" user.code data-width="10%" data-align="center">{{trans('laprofile.employee_code')}}</th>
                    <th data-field="full_name" user.full_name data-width="30%"  >{{trans('latraining.fullname')}}</th>
                    <th data-field="attempt" data-width="10%">{{trans('latraining.attemps')}}</th>
                    <th data-field="time_start" data-width="15%" data-align="center">{{trans('latraining.start')}}</th>
                    <th data-field="time_finish" data-width="15%" data-align="center">{{trans('latraining.over')}}</th>
                    <th data-field="score" data-width="15%" data-align="center">{{trans('latraining.score')}}</th>
                </tr>
            </thead>
        </table>
    </div>

    <script type="text/javascript">
        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.online.scorm.report', ['id' => $course_id,'sid'=>$scorm_id]) }}',
            sort_name: 'full_name',
            remove_url: '{{route('module.online.scorm.report.remove', ['id' => $course_id,'sid'=>$scorm_id])}}'
        });
    </script>
@endsection
