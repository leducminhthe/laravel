@extends('layouts.backend')

@section('page_title', trans('lasetting.mailtemplate'))

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lasetting.mailtemplate'),
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
                    <input type="text" name="search" class="form-control" placeholder="{{trans('lasetting.enter_name_title_code')}}">
                    <button type="submit" class="btn"><i class="fa fa-search"></i>&nbsp;{{ trans('labutton.search') }}</button>
                </form>
            </div>
        </div>
        <br>

        <table class="tDefault table table-hover bootstrap-table" id="table-mail-template">
            <thead>
                <tr>
                    <th data-field="name" data-sortable="true" data-formatter="name_formatter" data-width="20%">{{ trans('lasetting.name') }}</th>
                    <th data-field="title" data-sortable="true" data-width="25%">{{trans('lasetting.titles')}}</th>
                    <th data-field="content">{{ trans('lasetting.content') }}</th>
                    <th data-field="" data-formatter="setting_formatter" data-width="10%" data-align="center">{{ trans('latraining.action') }}</th>
                </tr>
            </thead>
        </table>
    </div>

    <script type="text/javascript">
        function name_formatter(value, row, index) {
            return '<a href="'+ row.edit_url +'">'+ row.name +' </a>';
        }

        function setting_formatter(value, row, index){
            if(row.update_day){
                return '<input name="num_day" class="form-control is-number update_date_send_mail text-center" value="'+row.num_day+'" data-mail_code="'+row.code+'" placeholder="Nhập X ngày"> <br>' + row.note;
            }
            return ''; 
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('backend.mailtemplate.getdata') }}',
            sort_order: 'asc'
        });

        $('#table-mail-template').on('change', '.update_date_send_mail', function() {
            var mail_code = $(this).data('mail_code');
            var num_day = $(this).val();

            $.ajax({
                type: 'POST',
                url: '{{ route('backend.mailtemplate.update_time_send') }}',
                data:{
                    mail_code: mail_code,
                    num_day: num_day,
                }
            }).done(function(data) {

                return false;
            }).fail(function(data) {

                show_message(
                    'Lỗi dữ liệu',
                    'error'
                );
                return false;
            });
        });
    </script>
@endsection
