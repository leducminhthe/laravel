@extends('layouts.backend')

@section('page_title', trans('latraining.result_evaluation'))

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lamenu.training_organizations'),
                'url' => ''
            ],
            [
                'name' => trans('lamenu.offline_course'),
                'url' => route('module.offline.management')
            ],
            [
                'name' => $page_title,
                'url' => route('module.offline.edit', ['id' => $course_id])
            ],
            /*[
                'name' => trans('latraining.classroom'),
                'url' => route('module.offline.class', ['id' => $course->id]),
                'drop-menu'=>$classArray
            ],*/
            [
                'name' => trans('latraining.result_evaluation').": ".$class->name,
                'url' => ''
            ],
        ]
    @endphp
    @include('layouts.backend.menu_breadcum',$breadcum)
@endsection

@section('content')
    <div role="main" class="form_offline_course">
        @include('offline::backend.includes.navgroup')
        <br>
        <div class="row">
            <div class="col-md-12 ">
                <form class="form-inline form-search-user mb-3" id="form-search">
                    <div class="w-24">
                        <input type="text" name="search" class="form-control w-100" placeholder="{{ trans("latraining.rating_name") }}">
                    </div>

                    <div class="w-24">
                        <button type="submit" class="btn"><i class="fa fa-search"></i>&nbsp;{{ trans('labutton.search') }} </button>
                    </div>
                </form>
            </div>
        </div>
        <br>
        <table class="tDefault table table-hover bootstrap-table" id="table-list-report">
            <thead>
                <tr>
                    <th data-field="rating_name" data-formatter="rating_name_formatter" >{{ trans("latraining.rating_name") }}</th>
                    <th data-field="level" data-align="center">{{ trans("laother.levels") }}</th>
                    <th data-field="count_user" data-align="center">{{trans('latraining.join')}} / {{trans('latraining.object')}}</th>
                    <th data-field="export" data-formatter="export_formatter" data-align="center">{{trans('latraining.report')}}</th>
                </tr>
            </thead>
        </table>

        <br>
        <table class="tDefault table table-hover bootstrap-table" id="table-list-user-rating">
            <thead>
            <tr>
                <th data-field="code">{{ trans("latraining.employee_code") }}</th>
                <th data-field="full_name">{{ trans("latraining.fullname") }}</th>
                <th data-field="unit_name">{{ trans("latraining.work_unit") }}</th>
                <th data-field="parent_unit_name">{{ trans("latraining.unit_manager") }}</th>
                <th data-field="object_type">{{ trans("latraining.role") }}</th>
                <th data-field="object_rating">{{ trans("latraining.evaluation_object") }}</th>
                <th data-field="rating_level" data-align="center">{{ trans("latraining.level_rating") }}</th>
                <th data-field="rating_time" data-align="center">{{ trans("latraining.time_rating") }}</th>
                <th data-field="rating_status" data-align="center">{{ trans("latraining.status") }}</th>
                <th data-field="result" data-width="10%" data-align="center" data-formatter="result_formatter">{{ trans("latraining.detail") }}</th>
                <th data-field="export_word" data-width="10%" data-align="center" data-formatter="export_word_formatter">{{ trans("latraining.export") }}</th>
            </tr>
            </thead>
        </table>
    </div>

    <script type="text/javascript">
        function export_formatter(value, row, index) {
            return '<a href="'+ row.export +'" class="btn"> <i class="fa fa-download"></i> {{ trans('latraining.download') }} </a>';
        }

        function rating_name_formatter(value, row, index) {
            return '<a href="javascript:void(0)" class="get-list-user-rating text-primary" data-course_rating_level_id="'+ row.id +'">'+ row.rating_name +'</a>';
        }

        function export_word_formatter(value, row, index) {
            let str = '';
            if (row.export_word) {
                str += ' <a href="'+ row.export_word +'" class="btn btn-link"><i class="fa fa-download"></i> In Word</a>';
            }
            return str;
        }

        function result_formatter(value, row, index) {
            if (row.result_url){
                return '<a href="javascript:void(0)" class="btn load-modal" data-url="'+ row.result_url +'"> <i class="fa fa-eye"></i> </a>';
            }
            return '';
        }

        var table_list_report = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.offline.rating_level.list_report.getdata', [$course_id]) }}',
            table: '#table-list-report',
        });

        $('#table-list-user-rating').hide();
        $('#table-list-report').on('click', '.get-list-user-rating', function () {
            var course_rating_level_id = $(this).data('course_rating_level_id');

            $('#table-list-user-rating').show();
            $('#table-list-user-rating').bootstrapTable('destroy');

            var table_list_user_rating = new LoadBootstrapTable({
                locale: '{{ \App::getLocale() }}',
                url: base_url + '/admin-cp/offline/rating-level/{{ $course_id }}/list-user-rating/'+course_rating_level_id+'/getdata',
                table: '#table-list-user-rating',
            });
        });
    </script>
@endsection
