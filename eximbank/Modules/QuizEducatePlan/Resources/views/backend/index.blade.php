@extends('layouts.backend')

@section('page_title', 'Kế hoạch đào tạo dành cho đơn vị')

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('latraining.unit'),
                'url' => ''
            ],
            [
                'name' => trans('lamenu.quiz_plan_suggest'),
                'url' => route('module.quiz_educate_plan_suggest')
            ],
            [
                'name' => 'Kỳ thi dành cho đơn vị',
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
                <form id="form-search">
                    <div class="form-row align-items-center">
                        <div class="col-sm-2 my-1">
                            <input type="text" name="search" value="" class="form-control" autocomplete="off" placeholder="{{ trans('backend.code_name_quiz') }}">
                        </div>
                        <div class="col-sm-2 my-1">
                            <input name="start_date" type="text" class="datepicker form-control" placeholder="{{ trans('latraining.start_date') }}" autocomplete="off">
                        </div>
                        <div class="col-sm-2 my-1">
                            <input name="end_date" type="text" class="datepicker form-control" placeholder="{{ trans('latraining.end_date') }}" autocomplete="off">
                        </div>
                        <button type="submit" class="btn ml-2"><i class="fa fa-search"></i>&nbsp;{{ trans('labutton.search') }}</button>
                    </div>
                </form>
            </div>
            <div class="col-md-12 text-right act-btns">
                <div class="pull-right">
                    <div class="btn-group">
                        <button class="btn approve" data-status="1">
                            <i class="fa fa-check-circle"></i> {{ trans('labutton.approve') }}
                        </button>
                        <button class="btn approve" data-status="0">
                            <i class="fa fa-exclamation-circle"></i> {{ trans('labutton.deny') }}
                        </button>
                    </div>
                    <div class="btn-group">
                        <a href="{{ route('module.quiz_educate_plan.create',["idsg"=>$idsg]) }}" class="btn"><i class="fa fa-plus-circle"></i> {{ trans('labutton.add_new_quiz') }}</a>
                        <button class="btn" id="delete-item"><i class="fa fa-trash"></i> {{ trans('labutton.delete') }}</button>
                    </div>
                </div>
            </div>
        </div>
        <br>

        <table class="tDefault table
        table-hover bootstrap-table"
               id="table-quiz-educate-plan">
            <thead>
                <tr>
                    <th data-field="state" data-width="5%" data-checkbox="true"></th>
                    <th data-width="5%" data-field="status" data-formatter="status_formatter">{{ trans('latraining.status') }}</th>
                    <th data-width="8%" data-field="code">Mã</th>
                    <th data-field="name" data-sortable="true" data-formatter="name_formatter">{{ trans('latraining.quiz_name') }}</th>
                    <th data-field="time" data-align="center" data-width="18%">{{ trans('backend.time') }}</th>
                    <th data-width="15%" data-field="type_name">{{ trans('lareport.organize_method') }}</th>
                      <th data-align="center" data-width="10%" data-formatter="convert_formatter">{{ trans('latraining.result') }}</th>
                    <th data-align="center"
                        data-field="creat_course" data-width="8%">Tạo kỳ thi</th>
                    <th
                        data-field="actions"
                        data-align="center"
                        data-width="15%">Thao tác
                    </th>
                </tr>
            </thead>
        </table>
    </div>

    <script type="text/javascript">
        function name_formatter(value, row, index) {
            return '<a href="'+ row.edit_url +'">'+ row.name +'</a>';
        }

        function status_formatter(value, row, index) {
            value = parseInt(value);
            switch (value) {
                case 0: return '<span class="text-danger">{{ trans("backend.deny") }}</span>';
                case 1: return '<span class="text-success">{{trans("backend.approve")}}</span>';
                case 2: return '<span class="text-warning">{{ trans("backend.not_approved") }}</span>';
            }
        }

        function convert_formatter(value, row, index) {
            if(row.status_convert == 1){
                return 'Đã chuyển';
            }
            return '<a href="javascript::void(0)" class="form-control convert" data-quiz_id="'+ row.id +'" > <i class="fa fa-exchange-alt"></i></a>';
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.quiz_educate_plan.getdata',["idsg"=>$idsg]) }}',
            remove_url: '{{ route('module.quiz_educate_plan.remove',["idsg"=>$idsg]) }}'
        });

        $('.approve').on('click', function () {
            var ids = $("input[name=btSelectItem]:checked").map(function(){return $(this).val();}).get();
            var status = $(this).data('status');

            if (ids.length <= 0) {
                show_message('{{ trans('lacourse.min_one_course ') }}', 'error');
                return false;
            }

            $.ajax({
                url: '{{ route('module.quiz_educate_plan.approve') }}',
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

        $('#table-quiz-educate-plan').on ('click', '.convert', function () {
            var quiz_id = $(this).data('quiz_id');
            $.ajax({
                url: '{{ route('module.quiz_educate_plan.convert',["idsg"=>$idsg]) }}',
                type: 'post',
                data: {
                    quiz_id: quiz_id,
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
