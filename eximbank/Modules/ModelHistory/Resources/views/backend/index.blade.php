{{-- @extends('layouts.backend')

@section('page_title', 'Lịch sử cập nhật')

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            <span class="font-weight-bold">Lịch sử cập nhật</span>
        </h2>
    </div>
@endsection

@section('content') --}}

    <div role="main">
        <form name="frm" action="{{route('module.modelhistory.getdata')}}" id="form-search" method="post" autocomplete="off">
            @csrf
            <div class="row">
                <div class="col-md-3">

                </div>
                <div class="col-md-7">
                    <div class="form-group row">
                        <div class="col-md-3 control-label">
                            <label>{{trans('lahistory_management.from_date')}} <span class="text-danger">*</span></label>
                        </div>
                        <div class="col-md-6">
                            <input type="text" name="from_date" class="form-control datepicker-date">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-3 control-label">
                            <label>{{trans('lahistory_management.to_date')}} <span class="text-danger">*</span></label>
                        </div>
                        <div class="col-md-6">
                            <input type="text" name="to_date" class="form-control datepicker-date">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-3 control-label">
                            <label>{{ trans('lahistory_management.select_function') }}</label>
                        </div>
                        <div class="col-md-6">
                            <select name="model" class="form-control load-table" data-placeholder="-- {{ trans('lahistory_management.select_function') }} --"></select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-3 control-label">
                            <label>{{ trans('lahistory_management.user') }}</label>
                        </div>
                        <div class="col-md-6">
                            <select name="user" class="form-control load-user" data-placeholder="--{{ trans('lahistory_management.user') }}--"></select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-3"></div>
                        <div class="col-md-6">
                            <button type="submit" id="btnSearch" class="btn">{{ trans('labutton.query') }}</button>
                        </div>
                    </div>

                </div>
            </div>
        </form>
        <br>

        <table class="tDefault table table-hover bootstrap-table text-nowrap">
            <thead>
                <tr>
                    <th data-align="center" data-formatter="stt_formatter" data-width="50px">#</th>
                    {{--  <th data-field="model_id">ID</th>  --}}
                    <th data-field="action">{{ trans('lahistory_management.action') }}</th>
                    <th data-field="note">{{ trans('lahistory_management.note') }}</th>
                    <th data-field="created_name">{{ trans('lahistory_management.creator') }}</th>
                    <th data-field="created_date"  data-align="center">{{ trans('lahistory_management.time') }}</th>
                </tr>
            </thead>
        </table>
    </div>

    <script type="text/javascript">
        function stt_formatter(value, row, index) {
            return (index + 1);
        }
        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.modelhistory.getdata') }}',
        });
        $(document).ready(function () {
            $(".datepicker-date").datepicker({
                format: "dd/mm/yyyy",
                minViewMode: 0
            });
        });
    </script>
{{-- @endsection --}}
