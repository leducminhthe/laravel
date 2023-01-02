@extends('layouts.backend')

@section('page_title', $page_title)

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lamenu.unit'),
                'url' => ''
            ],
            [
                'name' => trans('lamenu.authorized_unit_manager'),
                'url' => route('module.authorized_unit')
            ],
            [
                'name' => $page_title,
                'url' => ''
            ]
        ]
    @endphp
    @include('layouts.backend.menu_breadcum',$breadcum)
@endsection


@section('content')
    <div role="main">
        <div class="row">
            <div class="col-md-8 form-inline">
                <form class="form-inline form-search-user" id="form-search">
                    {{-- @for($i = 1; $i <= 5; $i++)
                        <div class="w-24">
                            <select name="unit_id" id="unit-{{ $i }}" class="form-control load-unit" data-placeholder="-- {{ trans('lamenu.unit_level',["i"=>$i]) }} --" data-level="{{ $i }}" data-loadchild="unit-{{ $i+1 }}" data-parent="0"></select>
                        </div>
                    @endfor --}}
                    <div class="w-24">
                        @include('backend.form_choose_unit')
                    </div>
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
            <div class="col-md-4 text-right act-btns">
                <div class="pull-right">
                    <div class="btn-group">
                        <a href="javascript:void(0)" class="btn" id="save"><i class="fa fa-plus-circle"></i> {{ trans('labutton.save') }}</a>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <table class="tDefault table table-hover bootstrap-table" data-page-list="[10, 50, 100, 200, 500]">
            <thead>
            <tr>
                <th data-field="state" data-checkbox="true"></th>
                <th data-field="code" data-width="5%">{{ trans('backend.employee_code') }}</th>
                <th data-field="fullname" data-formatter="fullname_formatter" data-width="20%">{{ trans('backend.employee_name') }}</th>
                <th data-field="email" >{{ trans('backend.employee_email') }}</th>
                <th data-field="title_name">{{ trans('latraining.title') }}</th>
                <th data-field="unit_name" data-formatter="unit_formatter" data-with="5%">{{ trans('backend.work_unit') }}</th>
                <th data-field="unit_manager">{{ trans('backend.unit_manager') }}</th>
                <th data-field="status" data-align="center" data-formatter="status_formatter" data-width="5%">{{ trans('latraining.status') }}</th>
            </tr>
            </thead>
        </table>
    </div>

    <script type="text/javascript">
        function fullname_formatter(value, row, index) {
            return  row.lastname + ' ' + row.firstname;
        }

        function unit_formatter(value, row, index) {
            return row.unit_name + ' <a href="javascript:void(0)" class="load-modal" data-url="'+row.unit_url+'"> <i class="fa fa-info-circle"></i></a>';
        }

        function status_formatter(value, row, index) {
            value = parseInt(value);
            switch (value) {
                case 0:
                    return '<span>{{ trans('backend.inactivity') }}</span>';
                case 1:
                    return '<span>{{ trans('backend.doing') }}</span>';
                case 2:
                    return '<span>{{ trans('backend.probationary') }}</span>';
                case 3:
                    return '<span>{{ trans('backend.pause') }}</span>';
            }
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.authorized_unit.getdata_nomanager') }}',
            field_id: 'user_id'
        });

        $('#save').on('click', function () {
            var ids = $("input[name=btSelectItem]:checked").map(function(){return $(this).val();}).get();

            if (ids.length <= 0) {
                show_message('Vui lòng chọn ít nhất 1 nhân sự', 'error');
                return false;
            }

            $.ajax({
                url: "{{ route('module.authorized_unit.save') }}",
                type: 'post',
                data: {
                    ids: ids,
                }
            }).done(function(data) {
                show_message(data.message);

                $(table.table).bootstrapTable('refresh');
                return false;
            }).fail(function(data) {
                show_message('{{ trans('laother.data_error') }}', 'error');
                return false;
            });
        });
    </script>
@endsection
