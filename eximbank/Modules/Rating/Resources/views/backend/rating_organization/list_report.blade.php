@extends('layouts.backend')

@section('page_title', 'Kết quả đánh giá')

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('latraining.training_evaluation'),
                'url' => ''
            ],
            [
                'name' => 'Mô hình Kirkpatrick',
                'url' => route('module.rating_organization')
            ],
            [
                'name' => $rating_levels->name,
                'url' => route('module.rating_organization.edit', ['id' => $rating_levels->id])
            ],
            [
                'name' => 'Kết quả đánh giá',
                'url' => ''
            ],
        ]
    @endphp
    @include('layouts.backend.menu_breadcum',$breadcum)
@endsection

@section('content')
    <div role="main">
        <div class="row">
            <div class="col-md-12 ">
                <form class="form-inline form-search-user mb-3" id="form-search">
                    <div class="w-24">
                        <input type="text" name="search" class="form-control w-100" placeholder="Tên đánh giá">
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
                    <th data-field="rating_name" data-formatter="rating_name_formatter" >Tên đánh giá</th>
                    <th data-field="level" data-align="center">{{ trans('laother.levels') }}</th>
                    <th data-field="text_object_type" data-align="center">Đối tượng đánh giá</th>
                    <th data-field="time_rating" data-align="center">{{ trans('latraining.time_rating') }}</th>
                    <th data-field="count_user" data-align="center">{{trans('backend.join')}} / {{trans('backend.object')}}</th>
                    <th data-field="export" data-formatter="export_formatter" data-align="center">Báo cáo</th>
                </tr>
            </thead>
        </table>

        <br>
        <table class="tDefault table table-hover bootstrap-table" id="table-list-user-rating">
            <thead>
            <tr>
                <th data-field="code">{{ trans("backend.employee_code") }}</th>
                <th data-field="full_name">{{ trans("backend.fullname") }}</th>
                <th data-field="unit_name">Đơn vị công tác</th>
                <th data-field="parent_unit_name">Đơn vị quản lý</th>
                <th data-field="object_type">Vai trò</th>
                <th data-field="object_rating">Đối tượng đánh giá</th>
                <th data-field="rating_level" data-align="center">Cấp độ đánh giá</th>
                <th data-field="rating_time" data-align="center">{{ trans('latraining.time_rating') }}</th>
                <th data-field="rating_status" data-align="center">Trình trạng</th>
                <th data-field="result" data-width="10%" data-align="center" data-formatter="result_formatter">Chi tiết</th>
                <th data-field="export_word" data-width="10%" data-align="center" data-formatter="export_word_formatter">Export</th>
            </tr>
            </thead>
        </table>
    </div>

    <script type="text/javascript">
        function export_formatter(value, row, index) {
            return '<a href="'+ row.export +'" class="btn"> <i class="fa fa-download"></i> Tải về </a>';
        }

        function rating_name_formatter(value, row, index) {
            return '<a href="javascript:void(0)" class="get-list-user-rating text-primary" data-course_rating_level_id="'+ row.course_rating_level_id +'" data-course_rating_level_object_id="'+ row.id +'">'+ row.rating_name +'</a>';
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
            url: '{{ route('module.rating_organization.list_report.getdata', [$rating_levels->id]) }}',
            table: '#table-list-report',
        });

        $('#table-list-user-rating').hide();
        $('#table-list-report').on('click', '.get-list-user-rating', function () {
            var course_rating_level_id = $(this).data('course_rating_level_id');
            var course_rating_level_object_id = $(this).data('course_rating_level_object_id');

            $('#table-list-user-rating').show();
            $('#table-list-user-rating').bootstrapTable('destroy');

            var table_list_user_rating = new LoadBootstrapTable({
                locale: '{{ \App::getLocale() }}',
                url: base_url + '/admin-cp/rating-organization/list-report/{{ $rating_levels->id }}/list-user-rating/'+ course_rating_level_id +'/getdata?course_rating_level_object_id='+course_rating_level_object_id,
                table: '#table-list-user-rating',
            });
        });
    </script>
@endsection
