@extends('layouts.backend')

@section('page_title', trans('lamenu.point_history'))

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lamenu.point_history'),
                'url' => ''
            ],
        ]
    @endphp
    @include('layouts.backend.menu_breadcum',$breadcum)
@endsection
@section('content')
    <div role="main">
        <div class="row">
            <div class="col-md-3">
                @include('user::backend.user.filter')
            </div>
            <div class="col-md-9 text-right act-btns">
                <div class="pull-right">
                    <div class="btn-group">
                        <button class="btn" id="export">
                            <i class="fa fa-download"></i> {{ trans('labutton.export') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <table class="tDefault table table-hover bootstrap-table text-nowrap">
            <thead>
                <tr>
                    <th data-field="code" data-width="5%">{{ trans('laprofile.employee_code') }}</th>
                    <th data-field="full_name" data-formatter="name_formatter" data-width="20%">{{ trans('laprofile.employee_name') }}</th>
                    <th data-field="email" >{{ trans('laprofile.employee_email') }}</th>
                    <th data-field="title_name" >{{ trans('laprofile.title') }}</th>
                    <th data-field="unit_name" data-with="5%">{{ trans('laprofile.work_unit') }}</th>
                    <th data-field="parent_unit_name" data-with="5%">{{ trans('laprofile.unit_manager') }}</th>
                </tr>
            </thead>
        </table>
    </div>
    </div>

    <script type="text/javascript">
        $('#export').on('click', function () {
            let form_search = $("#form-search").serialize();
            window.location = '{{ route('module.promotion.history.export') }}?'+form_search;
        });

        function name_formatter(value, row, index) {
            return '<a href="'+ row.user_detail +'" style="cursor: pointer;">'+ row.full_name +'</a>' ;
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.promotion.history.getdata') }}',
        });
    </script>
@endsection
