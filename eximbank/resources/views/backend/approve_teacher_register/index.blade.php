@extends('layouts.backend')

@section('page_title', trans('lamenu.teacher_register'))

@php
    $tabs = Request::segment(3);
@endphp

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lamenu.teacher_register'),
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
                    <input type="text" class="form-control" name="search" value="">
                    <button type="submit" class="btn"><i class="fa fa-search"></i>&nbsp;{{ trans('labutton.search') }}</button>
                </form>
            </div>
            <div class="col-md-4 text-right act-btns">
                <div class="pull-right">
                    <div class="btn-group">
                        <button class="btn" id="delete-item"><i class="fa fa-trash"></i> {{ trans('labutton.delete') }}</button>
                    </div>
                    <div class="btn-group">
                        <button class="btn" onclick="approve(1)">
                            <i class="fa fa-check-circle"></i> &nbsp; {{ trans('labutton.approve') }}
                        </button>
                        <button class="btn" onclick="approve(0)">
                            <i class="fa fa-exclamation-circle"></i> &nbsp; {{ trans('labutton.deny') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <br>

        <table class="tDefault table table-hover bootstrap-table text-nowrap" id="contact_table">
            <thead>
                <tr>
                    <th data-field="state" data-checkbox="true"></th>
                    <th data-field="name" data-width="15%" data-formatter="name_formatter">{{ trans('laother.teacher_name') }}</th>
                    <th data-field="name" data-width="15%" data-formatter="course_formatter">{{ trans('lamenu.course') }}</th>
                    <th data-field="dateRegister" data-width="10%" data-align="center">{{ trans('latraining.teach_day') }}</th>
                    <th data-field="approve" data-align="center" data-formatter="status_formatter" data-width="5%">{{ trans('latraining.status') }}</th>
                    <th data-field="note" data-formatter="note_formatter">{{ trans('latraining.note') }}</th>
                </tr>
            </thead>
        </table>
    </div>

    <script type="text/javascript">
        function name_formatter(value, row, index) {
            return '<span>'+ row.name +'</span><br /><span>('+ row.code +')</span>'
        }

        function course_formatter(value, row, index) {
            return '<span>'+ row.course_name +'</span><br /><span>('+ row.course_code +')</span>'
        }

        function class_formatter(value, row, index) {
            return '<span>'+ row.class_name +'</span><br /><span>('+ row.class_code +')</span>'
        }

        function note_formatter(value, row, index) {
            return '<textarea id="note_'+ row.id +'" rows="3" class="w-100 p-1 form-control" onblur="saveNoteRegister('+ row.id +')">'+ row.note +'</textarea>'
        }

        function status_formatter(value, row, index) {
            var approve = parseInt(row.approve);
            switch (approve) {
                case 0:
                    return '<span class="text-danger">{{ trans("latraining.deny") }}</span>';
                case 1:
                    return '<span class="text-success">{{ trans("latraining.approved") }}</span>';
                case 2:
                    return '<span class="text-warning">{{ trans("latraining.not_approved") }}</span>';
            }
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('backend.approve_teacher_register.getdata') }}',
            remove_url: '{{ route('backend.approve_teacher_register.remove') }}'
        });

        var ajax_approve = "{{ route('backend.approve_teacher_register.approve') }}";
        var ids = [];
        var statusApprove = '';

        function approve(status) {
            statusApprove = status;
            ids = $("input[name=btSelectItem]:checked").map(function(){return $(this).val();}).get();
            if (ids.length <= 0) {
                show_message('Vui lòng chọn ít nhất 1 bài viết', 'error');
                return false;
            }
            $.ajax({
                url: ajax_approve,
                type: 'post',
                data: {
                    ids: ids,
                    status: statusApprove,
                }
            }).done(function(data) {
                show_message(data.message, data.status);
                $(table.table).bootstrapTable('refresh');
                return false;
            }).fail(function(data) {
                show_message('Lỗi hệ thống', 'error');
                return false;
            });
        };

        function saveNoteRegister(id) {
            var note = $('#note_'+ id).val();
            $.ajax({
                url: "{{ route('backend.approve_teacher_register.save_note') }}",
                type: 'post',
                data: {
                    id: id,
                    note: note,
                }
            }).done(function(data) {
                return false;
            }).fail(function(data) {
                show_message('Lỗi hệ thống', 'error');
                return false;
            });
        }
    </script>
@endsection
