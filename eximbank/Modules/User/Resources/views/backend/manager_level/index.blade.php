@extends('layouts.backend')

@section('page_title', trans('backend.manager_level'))

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            <span class="font-weight-bold">{{ trans('backend.manager_level') }}</span>
        </h2>
    </div>
@endsection

@section('content')
    <div role="main">
        <div class="row">
            <div class="col-md-12 form-inline">
                <form class="form-inline form-search-user mb-3" id="form-search">
                    @for($i = 1; $i <= 5; $i++)
                        <div class="w-24">
                            <select name="unit" id="unit-{{ $i }}" class="form-control load-unit" data-placeholder="-- {{ trans('lamenu.unit_level',["i"=>$i]) }} --" data-level="{{ $i }}" data-loadchild="unit-{{ $i+1 }}" data-parent="0"></select>
                        </div>
                    @endfor

                    @for($i = 1; $i <= $max_area; $i++)
                        <div class="w-24">
                            <select name="area" id="area-{{ $i }}" class="form-control load-area" data-placeholder="-- {{ data_locale($level_name_area($i)->name, $level_name_area($i)->name_en) }} --" data-level="{{ $i }}" data-loadchild="area-{{ $i+1 }}" data-parent="0"></select>
                        </div>
                    @endfor

                    <div class="w-24">
                        <select name="title" class="form-control load-title" data-placeholder="-- {{ trans('latraining.title') }} --"></select>
                    </div>

                    <div class="w-24">
                        <input type="text" name="search" class="form-control w-100" placeholder="{{ trans('latraining.enter_code_name_user') }}">
                    </div>
                    <div class="w-24">
                        <button type="submit" class="btn"><i class="fa fa-search"></i>&nbsp;{{ trans('labutton.search') }} </button>
                    </div>
                </form>
            </div>
            <div class="col-md-12 text-right mt-2">
                    <div class="btn-group">
                        <button class="btn approve" data-status="1">
                            <i class="fa fa-check-circle"></i> {{ trans('labutton.approve') }}
                        </button>
                        <button class="btn approve" data-status="0">
                            <i class="fa fa-exclamation-circle"></i> {{ trans('labutton.deny') }}
                        </button>
                    </div>
            </div>
        </div>
        <br>
        <table class="tDefault table table-hover bootstrap-table text-nowrap">
            <thead>
            <tr>
                <th data-field="state" data-checkbox="true"></th>
                <th data-field="code" data-width="10%">{{ trans('backend.employee_code') }}</th>
                <th data-field="fullname" data-formatter="fullname_formatter">{{ trans('backend.employee_name') }}</th>
                <th data-field="title_name">{{ trans('latraining.title') }}</th>
                <th data-field="unit_name" data-formatter="unit_formatter">{{ trans('backend.work_unit') }}</th>
                <th data-field="unit_manager">{{ trans('backend.unit_manager') }}</th>
                <th data-field="area_name" data-formatter="area_formatter">{{ trans('backend.work_location') }}</th>
                <th data-field="manager_level" data-formatter="manager_level_formatter" data-align="center" data-width="5%">{{ trans('backend.manager_level') }}</th>
                <th data-field="approve" data-align="center" data-formatter="status_formatter" data-width="5%">{{ trans('latraining.status') }}</th>
            </tr>
            </thead>
        </table>
    </div>

    <script type="text/javascript">
        function fullname_formatter(value, row, index) {
            return '<a href="' + row.edit_url + '">' + row.lastname + ' ' + row.firstname + '</a>';
        }
        function unit_formatter(value, row, index) {
            return row.unit_name + ' <a href="javascript:void(0)" class="load-modal" data-url="'+row.unit_url+'"> <i class="fa fa-info-circle"></i></a>';
        }

        function area_formatter(value, row, index) {
            return row.area_name ? row.area_name + ' <a href="javascript:void(0)" class="load-modal" data-url="'+row.area_url+'"> <i class="fa fa-info-circle"></i></a>' : '';
        }
        function manager_level_formatter(value, row, index) {
            return '<a href="javascript:void(0)" class="load-modal" data-url="'+row.manager_url+'"> <i class="fa fa-user"></i></a>';
        }
        function status_formatter(value, row, index) {
            value = parseInt(value);
            switch (value) {
                case 0: return '<span class="text-danger">{{ trans('labutton.deny') }}</span>';
                case 1: return '<span class="text-success">{{ trans('labutton.approve') }}</span>';
                case 2: return '<span class="text-warning">{{ trans('backend.not_approved') }}</span>';
                default: return '';
            }
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.backend.manager_level.getdata') }}',
            field_id: 'user_id'
        });

        $('.approve').on('click', function () {
            var ids = $("input[name=btSelectItem]:checked").map(function(){return $(this).val();}).get();
            var status = $(this).data('status');

            if (ids.length <= 0) {
                show_message('Vui lòng chọn ít nhất 1 học viên', 'error');
                return false;
            }

            $.ajax({
                url: base_url +'/admin-cp/manager-level/approve',
                type: 'post',
                data: {
                    ids: ids,
                    status: status
                }
            }).done(function(data) {
                $(table.table).bootstrapTable('refresh');
                return false;
            }).fail(function(data) {
                show_message('{{ trans('laother.data_error') }}', 'error');
                return false;
            });
        });
    </script>
@endsection
