@extends('layouts.backend')

@section('page_title', trans('lamenu.coaching_teacher'))

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lamenu.coaching_teacher'),
                'url' => ''
            ],
        ]
    @endphp
    @include('layouts.backend.menu_breadcum',$breadcum)
@endsection

@section('content')

    <div role="main">
        <div class="row">
            <div class="col-md-8">
                <form class="form-inline form-search mb-3" id="form-search">
                    <input type="text" name="search" value="" class="form-control" placeholder="{{trans('backend.search_code_name')}}">
                    <button type="submit" class="btn"><i class="fa fa-search"></i>&nbsp;{{ trans('labutton.search') }}</button>
                </form>
            </div>
            <div class="col-md-4 text-right act-btns">
                <div class="pull-right">
                    @can('coaching-teacher')
                    <div class="btn-group">
                        <button class="btn" onclick="changeStatus(event, 1)" data-status="1">
                            <i class="fa fa-check-circle"></i> &nbsp;{{ trans('labutton.approve') }}
                        </button>
                        <button class="btn" onclick="changeStatus(event, 0)" data-status="0">
                            <i class="fa fa-exclamation-circle"></i> &nbsp;{{ trans('labutton.deny') }}
                        </button>
                    </div>
                    @endcan
                </div>
            </div>
        </div>
        <br>

        <table class="tDefault table table-hover bootstrap-table">
            <thead>
                <tr>
                    <th data-field="check" data-checkbox="true" data-width="2%"></th>
                    <th data-field="image" data-formatter="image_formatter" data-align="center" data-width="10%">{{ trans('latraining.picture') }}</th>
                    <th data-formatter="user_formatter">{{ trans('latraining.teacher') }}</th>
                    <th data-field="technique">{{ trans('latraining.technique') }}</th>
                    <th data-formatter="coaching_group_formatter">{{ trans('lamenu.coaching_group') }}</th>
                    <th data-formatter="time_formatter" data-align="center" data-width="5%">{{ trans('latraining.time') }}</th>
                    <th data-field="number_coaching" data-align="center" data-width="5%">{{ trans('latraining.number_coaching') }}</th>
                    <th data-formatter="status_formatter" data-align="center" data-width="5%">{{ trans('lacategory.status') }}</th>
                </tr>
            </thead>
        </table>
    </div>

    <script type="text/javascript">
        function user_formatter(value, row, index){
            return row.user.full_name +' ('+ row.user.code +')';
        }
        function coaching_group_formatter(value, row, index){
            return row.coaching_group.name +' ('+ row.coaching_group.code +')';
        }
        function image_formatter(value, row, index){
            return '<image src="'+ row.image +'" class="w-100">';
        }
        function time_formatter(value, row, index){
            return row.start_date + '<i class="fa fa-arrow-right"></i>' + row.end_date;
        }
        function status_formatter(value, row, index){
            return row.status == 1 ? '<span class="text-success">{{ trans("latraining.approve") }}</span>' : (row.status == 0 ? '<span class="text-danger">{{ trans("latraining.deny") }}</span>' : '<span class="text-warning">{{ trans("latraining.not_approved") }}</span>');
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.coaching.backend.getdata') }}',
        });

        function changeStatus(event, status) {
            var btn = $(event.target);
            var oldText = btn.html();

            btn.html('<i class="fa fa-spinner fa-spin"></i> {{ trans("laother.processing") }}');
            btn.attr('disabled', true);

            var ids = $("input[name=btSelectItem]:checked").map(function(){return $(this).val();}).get();
            if (ids.length <= 0) {

                btn.html(oldText);
                btn.attr('disabled', false);

                show_message('Vui lòng chọn ít nhất 1 dòng', 'error');
                return false;
            }
            
            $.ajax({
                url: "{{ route('module.coaching.backend.update_status') }}",
                type: 'post',
                data: {
                    ids: ids,
                    status: status
                }
            }).done(function(data) {
                btn.html(oldText);
                btn.attr('disabled', false);

                show_message(data.message, data.status);
                $(table.table).bootstrapTable('refresh');
                return false;
                
            }).fail(function(data) {

                show_message('Lỗi hệ thống', 'error');
                return false;
            });
        };
    </script>
@endsection
