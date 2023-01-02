@extends('layouts.backend')

@section('page_title', trans('lamenu.user'))

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lamenu.management'),
                'url' => ''
            ],
            [
                'name' => trans('lamenu.user'),
                'url' => ''
            ],
        ]
    @endphp
    @include('layouts.backend.menu_breadcum',$breadcum)
@endsection

@section('content')
    <div role="main">
        <div class="row">
            <div class="col-md-12">
                <form class="form-inline" id="form-search">
                    <div class="w-50">
                        <input type="text" name="search" class="form-control" placeholder="{{ trans('latraining.enter_code_name_user') }}">
                        <button type="submit" class="btn"><i class="fa fa-search"></i>&nbsp;{{ trans('labutton.search') }} </button>
                    </div>
                </form>
            </div>
        </div>
        <p></p>
        <div class="row">
            <div class="col-md-12 text-right act-btns">
                <div class="pull-right">
                    <div class="btn-group">
                        <button class="btn approve" data-status="1">
                            <i class="fa fa-check-circle"></i> {{ trans('labutton.approve') }}
                        </button>
                        <button class="btn approve" data-status="0">
                            <i class="fa fa-exclamation-circle"></i> {{trans("backend.deny")}}
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <table class="tDefault table table-hover bootstrap-table">
            <thead>
            <tr>
                <th data-field="state" data-checkbox="true"></th>
                <th data-field="user_code" data-width="10%">{{ trans('backend.employee_code') }}</th>
                <th data-field="full_name">{{ trans('backend.employee_name') }}</th>
                <th data-field="unit_name">{{ trans('backend.work_unit') }}</th>
                <th data-field="unit_manager">{{ trans('backend.unit_manager') }}</th>
                <th data-field="value_old">{{ trans('latraining.old_value') }}</th>
                <th data-field="value_new">{{ trans('latraining.new_value') }}</th>
                <th data-field="status" data-align="center" data-formatter="status_formatter" data-width="5%">{{ trans('latraining.status') }}</th>
            </tr>
            </thead>
        </table>
    </div>

    <script type="text/javascript">
        function value_old_formatter(value, row, index) {
            if(row.key == 'avatar'){
                return '<img src="" alt="">';
            }
        }

        function status_formatter(value, row, index) {
            value = parseInt(value);
            switch (value) {
                case 0:
                    return '<span>{{trans("backend.deny")}}</span>';
                case 1:
                    return '<span>{{trans("backend.approve")}}</span>';
                case 2:
                    return '<span>{{ trans("backend.pending") }}</span>';
            }
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.backend.user.getdata_history_change_info') }}',
        });

        $('.approve').on('click', function () {
            var ids = $("input[name=btSelectItem]:checked").map(function(){return $(this).val();}).get();
            var status = $(this).data('status');

            if (ids.length <= 0) {
                show_message('Vui lòng chọn ít nhất 1 nhân viên', 'error');
                return false;
            }

            $.ajax({
                url: '{{ route('module.backend.user.approve_info_change') }}',
                type: 'post',
                data: {
                    ids: ids,
                    status: status
                }
            }).done(function(data) {
                show_message(data.message, data.status);
                $(table.table).bootstrapTable('refresh');
                return false;
            }).fail(function(data) {
                show_message('{{ trans('laother.data_error') }}', 'error');
                return false;
            });
        });
    </script>
@endsection
