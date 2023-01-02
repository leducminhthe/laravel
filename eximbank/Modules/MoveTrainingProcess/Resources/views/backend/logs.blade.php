@extends('layouts.backend')

@section('page_title', trans('lamenu.move_training_process'))

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lamenu.learning_manager'),
                'url' => route('module.movetrainingprocess.index')
            ],
            [
                'name' => trans('lamenu.move_training_process') . ': ' .trans('movetrainingprocess::language.move_training_process_log'),
                'url' => ''
            ],
        ]
    @endphp
    @include('layouts.backend.menu_breadcum',$breadcum)
@endsection

@section('content')
    <div role="main">
        <table class="tDefault table table-hover bootstrap-table">
            <thead>
                <tr>
                    <th data-sortable="true" data-align="center" data-formatter="stt_formatter" data-width="3%">{{ trans('latraining.stt') }}</th>
                    <th data-field="action" >{{ trans('backend.action') }}</th>
                    <th data-field="full_name" data-formatter="fullname_formatter">{{ trans('backend.user_create') }}</th>
                    <th data-field="created_date" >{{ trans('backend.created_at') }}</th>
                </tr>
            </thead>
        </table>
    </div>

    <script type="text/javascript">
        function stt_formatter(value, row, index) {
            return (index + 1);
        }
        function fullname_formatter(value, row, index) {
            return  row.full_name + ' <b>(' + row.code + ')</b>';
        }


        function area_formatter(value, row, index) {
            return row.area_name ? row.area_name + ' <a href="javascript:void(0)" class="load-modal" data-url="'+row.area_url+'"> <i class="fa fa-info-circle"></i></a>' : '';
        }
        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.movetrainingprocess.logs.getData') }}',
        });

    </script>
@endsection
