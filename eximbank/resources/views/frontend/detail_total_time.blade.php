@extends('layouts.app')

@section('page_title', trans('lamenu.guide'))

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <form enctype="multipart/form-data" id="form-search" class="form-inline">
                    <input type="text" name="search" class="form-control w-30" placeholder="{{ trans('latraining.enter_code_name_course') }}">
                    <input type="text" name="start_date" class="form-control datetimepicker" placeholder="{{ trans('latraining.start_date') }}">
                    <input type="text" name="end_date" class="form-control datetimepicker" placeholder="{{ trans('latraining.end_date') }}">
                    <button  id="btnsearch" class="btn"><i class="fa fa-search"></i>&nbsp;{{ trans('labutton.search') }} </button>
                </form>
            </div>
            <div class="col-12 mt-3">
                <table id="bootstraptable" class="tDefault table table-hover table-bordered bootstrap-table">
                    <thead>
                        <tr class="tbl-heading">
                            <th data-field="course" data-formatter="course_formatter">{{ trans('lasetting.name') }}</th>
                            <th data-field="course_time" data-align="center">{{ trans('ladashboard.time') }}</th>
                            <th data-field="type" data-align="center">{{ trans('lacategory.form') }}</th>
                            <th data-field="total_time" data-width="15%" data-align="center">{{ trans('lareport.spend_learned_summary') }} (Giờ)</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <script>
        $('.datetimepicker').datetimepicker({
            locale:'vi',
            format: 'DD/MM/YYYY'
        });

        function course_formatter(value, row, index) {
            return '<p class="mb-0">'+ row.name +'</p><p class="mb-0">('+ row.code +')</p>';
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('frontend.getdata_detail_total_time_user') }}',
        });
    </script>
@endsection

