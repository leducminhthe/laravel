@extends('layouts.backend')

@section('page_title', trans('backend.schedule_task'))

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('backend.schedule_task'),
                'url' => ''
            ],
        ]
    @endphp
    @include('layouts.backend.menu_breadcum',$breadcum)
@endsection

@section('content')
    <div role="main">
        <div class="row">
            <div class="col-md-6">
            </div>
            <div class="col-md-6 text-right act-btns">
{{--                <div class="pull-right">--}}
{{--                    <div class="btn-group">--}}
{{--                        <a class="btn  " href="{{route('module.cron.create')}}"><i class="fa fa-check-circle"></i> {{trans('backend.create')}}</a>--}}
{{--                    </div>--}}
{{--                    <button class="btn" id="delete-item" ><i class="fa fa-trash"></i> {{trans('labutton.delete')}}</button>--}}
{{--                </div>--}}
            </div>
        </div>
        <br>

        <table class="tDefault table table-hover bootstrap-table">
            <thead>
                <tr>
                    <th data-sortable="true" data-align="center" data-formatter="stt_formatter" data-width="50px">{{ trans('latraining.stt') }}</th>
                    <th  data-formatter="name_formatter">{{ trans('cron::language.task') }}</th>
                    <th data-field="last_run" >{{ trans('cron::language.last_run') }}</th>
                    <th data-field="duration" data-width="150px">{{ trans('backend.duration_cron') }}</th>
                    <th data-field="minute" data-align="center" data-width="100px">{{ trans('backend.minutes') }}</th>
                    <th data-field="hour" data-align="center" data-width="100px">{{ trans('backend.hour') }}</th>
                    <th data-field="day" data-align="center" data-width="100px" >{{ trans('backend.day') }}</th>
                    <th data-field="month" data-width="100px">{{ trans('backend.month') }}</th>
                    <th data-field="day_of_week" data-width="150px">{{ trans('backend.day_of_week') }}</th>
                    <th data-field="status"  data-width="100px">{{ trans('latraining.status') }}</th>
                    <th data-field="run_cron" data-formatter="run_cron_formatter" data-align="center" data-width="2%">Chạy cron</th>
                </tr>
            </thead>
        </table>
    </div>
    <script type="text/javascript">
        function stt_formatter(value, row, index) {
            return (index + 1);
        }

        function name_formatter(value,row,index) {
            return '<a href="'+row.edit+'">'+row.description+'</a>'+'<br>'+'<span style="color:#888; font-size: .75em">'+row.command+'</span>';
        }

        function run_cron_formatter(value,row,index) {
            return '<span class="cursor_pointer cron_'+ row.id +'" onclick="runCron('+ row.id +')"><i class="fas fa-play"></i></span>';
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.cron.getData') }}',
            remove_url: '{{ route('module.cron.remove') }}',
            delete_method: 'delete'
        });

        function runCron(id) {
            let item = $('.cron_'+ id);
            let oldtext = item.html();
            item.html('<i class="fa fa-spinner fa-spin"></i>');

            $.ajax({
                url: '{{ route('module.cron.run') }}',
                type: 'post',
                dataType: 'json',
                data: {
                    id: id,
                }
            }).done(function(data) {
                item.html(oldtext);
                $(table.table).bootstrapTable('refresh');
                return false;
            }).fail(function(data) {
                show_message('{{ trans('laother.data_error') }}', 'error');
                return false;
            });
        }
    </script>
    <script src="{{ asset('styles/module/movetrainingprocess/js/movetrainingprocess.js?v=1') }}"></script>

@endsection
