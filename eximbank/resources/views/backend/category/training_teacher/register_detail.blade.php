@extends('layouts.backend')

@section('page_title', trans('laother.sign_teach'))

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lamenu.teacher_permission'),
                'url' => route('backend.category.training_teacher.list_permission')
            ],
            [
                'name' => $model->name,
                'url' => route('backend.category.training_teacher.register_teach')
            ],
            [
                'name' => trans('latraining.detail'),
                'url' => ''
            ],
        ]
    @endphp
    @include('layouts.backend.menu_breadcum', $breadcum)
@endsection

@section('content')
    <div role="main">
        <div class="row">
            <div class="col-md-12" id="schedule-parent">
                <table class="tDefault table table-hover bootstrap-table">
                    <thead>
                        <tr>
                            <th data-field="code" data-width="8%">{{ trans('latraining.class_room_code') }}</th>
                            <th data-sortable="true" data-width="20%" data-field="name"  >{{ trans('latraining.class_room_name') }}</th>
                            <th data-field="" data-width="20%" data-formatter="training_time_formatter">{{ trans('latraining.training_time') }}</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        function training_time_formatter(value, row, index) {
            return row.start_date+' <i class="fa fa-arrow-right"></i> '+row.end_date;
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('backend.category.training_teacher.getdata_detail_register_teach', ['id' => $model->id]) }}',
        });
    </script>
@endsection
