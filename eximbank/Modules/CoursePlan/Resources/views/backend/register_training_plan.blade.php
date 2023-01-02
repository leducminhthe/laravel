@extends('layouts.backend')

@section('page_title', trans('lamenu.register_training_plan'))

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lamenu.training_organizations'),
                'url' => ''
            ],
            [
                'name' => trans('latraining.monthly_training_plan'),
                'url' => route('module.course_plan.management')
            ],
            [
                'name' => trans('lamenu.register_training_plan'),
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
                    <div class="w-25">
                        <select name="course_belong_to" id="course_belong_to" class="select2" data-placeholder="-- {{ trans('latraining.course_belong_to') }} --">
                            <option value=""></option>
                            <option value="1"> {{ trans('latraining.internal') }}</option>
                            <option value="2"> {{ trans('latraining.cross_training') }}</option>
                        </select>
                    </div>
                    <input name="start_date" type="text" class="datepicker form-control" placeholder="{{ trans('latraining.start_date') }}" autocomplete="off">
                    <input name="end_date" type="text" class="datepicker form-control" placeholder="{{ trans('latraining.end_date') }}" autocomplete="off">
                    <button type="submit" class="btn"><i class="fa fa-search"></i>&nbsp;{{ trans('labutton.search') }}</button>
                </form>
            </div>
            <div class="col-md-4 text-right act-btns">
                <div class="btn-group">
                    <button class="btn approved-register" data-status="1">
                        <i class="fa fa-check-circle"></i> {{ trans('labutton.approve') }}
                    </button>
                    <button class="btn approved-register" data-status="0">
                        <i class="fa fa-exclamation-circle"></i> {{ trans('labutton.deny') }}
                    </button>
                </div>
            </div>
        </div>
        <table class="tDefault table table-hover bootstrap-table text-nowrap mt-2" id="table_register_training_plan">
            <thead>
                <tr>
                    <th data-field="state" data-checkbox="true" data-formatter="state_formatter"></th>
                    <th data-field="created_at2">{{ trans('backend.date_register') }}</th>
                    <th data-field="name" data-sortable="true" data-formatter="name_formatter">{{ trans('backend.course') }}</th>
                    <th data-field="subject_name">{{ trans('app.subject') }}</th>
                    <th data-field="course_time" data-align="center" >{{ trans('lasuggest_plan.timer') }}</th>
                    <th data-field="course_type" data-width="10%" data-align="center" data-formatter="course_type_formatter">{{ trans('app.form') }}</th>
                    <th data-field="training_form">{{ trans('laprofile.training_form') }}</th>
                    <th data-field="training_area">{{ trans('backend.area') }}</th>
                    <th data-field="teachers">{{ trans('app.teacher') }}</th>
                    <th data-field="course_employee" data-align="center">{{ trans('latraining.course_for') }}</th>
                    <th data-field="course_belong_to" data-width="10%" data-align="center" data-formatter="course_belong_to_formatter">{{ trans('latraining.course_belong_to') }}</th>
                    <th data-field="target" data-width="20%" data-formatter="target_formatter">{{ trans('backend.target') }}</th>
                    <th data-field="content" data-width="20%" data-formatter="content_formatter">{{ trans('app.content') }}</th>
                    <th data-formatter="created_by_formatter">{{ trans('backend.code_user_create') }}</th>
                    <th data-field="status" data-align="center" data-formatter="status_formatter" data-width="5%">{{ trans('latraining.status') }}</th>
                </tr>
            </thead>
        </table>
    </div>

    <div class="modal" id="modal-info" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ trans('latraining.info') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-justify">

                </div>
            </div>
        </div>
    </div>

    <div class="modal fade modal-note-approved" id="modal-note-approved-register">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Ghi chú từ chối phê duyệt</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="idapproved" id="idapproved" value="">
                    <div>
                        <div class="form-group">
                            <label>{{ trans('latraining.note') }}</label>
                            <textarea class="form-control" id="txta-note-approved" rows="3" name="note"></textarea>
                            <input type="hidden" name="table" value="bootstrap-table">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="note-approved-register" class="btn"><i class="fa fa-save"></i> {{trans('labutton.save')}}</button>
                    <button type="button" id="closed" class="btn" data-dismiss="modal"><i class="fa fa-times-circle"></i> {{ trans('labutton.close') }}</button>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        function state_formatter(value, row, index) {
            return (row.status == 1) ? {disabled: true} : value;
        }

        function name_formatter(value, row, index) {
            return row.name +'<br>' + row.start_date  + (row.end_date ? ' <i class="fa fa-arrow-right"></i> ' + row.end_date : ' ');
        }

        function course_type_formatter(value, row, index) {
            return (row.course_type == 1) ? 'Trực tuyến' : '{{ trans("latraining.offline") }}';
        }

        function target_formatter(value, row, index) {
            return row.sub_target ? row.sub_target + ` <i class="fa fa-info-circle sub_target_info" style="cursor: pointer;" data-value='${row.target}'></i>` : '';
        }

        function content_formatter(value, row, index) {
            return row.sub_content ? row.sub_content + ` <i class="fa fa-info-circle sub_content_info" style="cursor: pointer;" data-value='${row.content}'></i>` : '';
        }

        function created_by_formatter(value, row, index) {
            return row.full_name + '<br>' + row.unit_name;
        }

        function status_formatter(value, row, index) {
            value = parseInt(value);
            var text_status = '';
            switch (value) {
                case 0: text_status = '<span class="text-danger">{{ trans("backend.deny") }}</span>' + ` <i class="fa fa-info-circle note_status_info" style="cursor: pointer;" data-value='${row.note_status}'></i>`; break;
                case 1: text_status = '<span class="text-success">{{trans("backend.approve")}}</span>'; break;
                case 2 || null: text_status = '<span class="text-warning">{{ trans("backend.not_approved") }}</span>'; break;
            }

            return text_status;
        }

        function course_belong_to_formatter(value, row, index) {
            return row.course_belong_to ? (row.course_belong_to == 1) ? 'Đào tạo nội bộ' : 'Đào tạo chéo' : '';
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.course_plan.getdata_register_training_plan') }}',
        });

        $('#table_register_training_plan').on('click', '.sub_target_info', function(){
            var item_val = $(this).data('value');

            $('#modal-info .modal-body').html(item_val);

            $('#modal-info').modal();
        });

        $('#table_register_training_plan').on('click', '.sub_content_info', function(){
            var item_val = $(this).data('value');

            $('#modal-info .modal-body').html(item_val);

            $('#modal-info').modal();
        });

        $('#table_register_training_plan').on('click', '.note_status_info', function(){
            var item_val = $(this).data('value');

            $('#modal-info .modal-body').html(item_val);

            $('#modal-info').modal();
        });

        $('.approved-register').on('click',function (e) {
            e.preventDefault();
            var ids = $("input[name=btSelectItem]:checked").map(function(){return $(this).val();}).get();
            var status = $(this).data('status');

            let item = $(this);
            let current_icon = item.find('i').attr('class');

            item.find('i').attr('class', 'fa fa-spinner fa-spin');
            item.prop("disabled", true);

            if (ids.length <= 0) {

                item.find('i').attr('class', current_icon);
                item.prop("disabled", false);

                show_message('Vui lòng chọn ít nhất dòng dữ liệu', 'error');
                return false;
            }
            if (!status){
                item.find('i').attr('class', current_icon);
                item.prop("disabled", false);

                $('#modal-note-approved-register').modal();

                return  false;
            }

            $.ajax({
                url: '{{ route('module.course_plan.approve_register_training_plan') }}',
                type: 'post',
                // dataType: 'json',
                data: {
                    ids: ids,
                    status: status,
                }
            }).done(function(result) {
                console.log(result);

                item.find('i').attr('class', current_icon);
                item.prop("disabled", false);

                if (result.status == 'success'){
                    $(table.table).bootstrapTable('refresh');
                }

                show_message(result.message, result.status);

                return false;
            }).fail(function(result) {
                show_message('Lỗi dữ liệu', 'error');

                item.find('i').attr('class', current_icon);
                item.prop("disabled", false);

                return false;
            });
        });

        $('#note-approved-register').on('click', function(){
            var ids = $("input[name=btSelectItem]:checked").map(function(){return $(this).val();}).get();
            var note = $('#txta-note-approved').val();

            let btn = $(this);
            let current_icon = btn.find('i').attr('class');
            btn.find('i').attr('class', 'fa fa-spinner fa-spin');
            btn.prop("disabled", true);

            $.ajax({
                url: '{{ route('module.course_plan.approve_register_training_plan') }}',
                type: 'post',
                data: {
                    ids: ids,
                    status: 0,
                    note:note,
                }
            }).done(function(result) {

                btn.find('i').attr('class', current_icon);
                btn.prop("disabled", false);

                $(table.table).bootstrapTable('refresh');
                $('#modal-note-approved-register').modal('hide');

                show_message(result.message, result.status);

                return false;
            }).fail(function(result) {

                btn.find('i').attr('class', current_icon);
                btn.prop("disabled", false);

                show_message('Lỗi dữ liệu', 'error');

                return false;
            });
        });
    </script>
@endsection
