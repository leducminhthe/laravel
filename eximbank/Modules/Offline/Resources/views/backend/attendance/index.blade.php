@extends('layouts.backend')

@section('page_title', trans('lamenu.attendance'))
@section('header')
    <script src="{{asset('styles/vendor/jqueryplugin/printThis.js')}}"></script>
@endsection('header')

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lamenu.training_organizations'),
                'url' => ''
            ],
            [
                'name' => trans('lamenu.offline_course'),
                'url' => route('module.offline.management')
            ],
            [
                'name' => $page_title,
                'url' => route('module.offline.edit', ['id' => $course->id])
            ],
            /*[
                'name' => trans('latraining.classroom'),
                'url' => route('module.offline.class', ['id' => $course->id]),
                'drop-menu'=>$classArray
            ],*/
            [
                'name' => trans('latraining.attendance_class').": ".$class->name,
                'url' => ''
            ],
        ]
    @endphp
    @include('layouts.backend.menu_breadcum',$breadcum)
@endsection

@section('content')
    <div role="main" id="attendance" class="form_offline_course">
        @if(isset($errors))
            @foreach($errors as $error)
                <div class="alert alert-danger">{!! $error !!}</div>
            @endforeach
        @endif

        @include('offline::backend.includes.navgroup')
        <br>
        <div class="row pb-2">
            <div class="col-md-3">
                @include('offline::backend.attendance.filter')
            </div>
            <div class="col-md-9 text-right act-btns">
                <div class="pull-right">
                    <a class="btn" id="export_schedule" href="{{ route('module.offline.attendance.export',['id' => $course->id, 'class_id' => $class->id, 'schedule' => $schedule ? $schedule : 0]) }}"><i class="fa fa-download"></i>
                        {{ trans('labutton.export') }}</a>
                    <div class="btn-group">
                        <button class="btn" id="import-plan" type="submit" name="task" value="import">
                            <i class="fa fa-upload"></i> {{ trans('labutton.import') }}
                        </button>
                    </div>
                    <div class="btn-group">
                        <select name="schedules_id" id="schedules_id" class="form-control select2" data-placeholder="-- {{ trans('latraining.choose_session') }} --" {{ $course->lock_course == 0 ? '' : 'disabled' }}>
                            <option value=""></option>
                            @if(count($schedules) != 0)
                                @foreach($schedules as $key => $item)
                                    @php
                                        if($item->type_study == 1) {
                                            $name_type = trans('latraining.type_study_class');
                                        } else if ($item->type_study == 2) {
                                            $name_type = trans('latraining.type_study_teams');
                                        } else {
                                            $name_type = 'Elearning';
                                        }
                                    @endphp
                                    <option value="{{ $item->id }}" {{ $item->id == $schedule ? "selected" : "" }}>
                                        {{ trans('latraining.session') .' '. ($key + 1) .' ('. get_date($item->start_time, 'H:i') }}
                                        <i class="uil uil-angle-right"></i>
                                        {{ get_date($item->end_time, 'H:i') .' - '. get_date($item->lesson_date, 'd/m/Y') . ')' }}
                                        ({{ $name_type }})
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
            </div>
        </div>
        @if ($schedule>0)
            <div class="row pb-2">
                <div class="col-md-12 pull-right text-right">
                    <a href="javascript:void(0)" id="modal_qrcode">{{ trans('latraining.get_attendanece_qr') }}</a>
                </div>
            </div>
        @endif
        <table class="tDefault table table-hover bootstrap-table text-nowrap" >
            <thead>
            <tr>
                <th data-field="code">{{ trans('latraining.employee_code') }}</th>
                <th data-sortable="true" data-field="name" data-formatter="name_formatter">{{ trans('latraining.employee_name') }}</th>
                <th data-field="email">{{ trans('latraining.email') }}</th>
                <th data-field="title_name">{{ trans('latraining.title') }}</th>
                <th data-field="unit_name">{{ trans('latraining.work_unit') }}</th>
                <th data-field="parent">{{ trans('latraining.unit_manager') }}</th>
                <th data-field="" data-formatter="type_formatter" data-align="center">
                    {{ trans('latraining.join') }} <br> <input type="checkbox" name="checkAllType" class="check-all-type" {{ $course->lock_course == 0 ? '' : 'disabled' }}>
                </th>
                <th data-field="percent" data-align="center"  data-formatter="percent_formatter">% <br> {{ trans('latraining.join') }}</th>
                <th data-field="type_attendan" data-align="center">{!! trans('latraining.attendance_br_method') !!}</th>
                <th data-field="note" data-formatter="note_formatter">{{ trans('latraining.note') }}</th>
                <th data-field="reference" data-align="center" data-formatter="reference_formatter">{{ trans('latraining.permission_form') }}</th>
                <th data-field="discipline" data-align="left">{{ trans('latraining.violation') }}</th>
                <th data-field="absent" data-align="left">{{ trans('latraining.vacation_type') }}</th>
                <th data-field="absent_reason" data-align="left">{{ trans('latraining.reason_absence') }}</th>
            </tr>
            </thead>
        </table>
    </div>
    <div class="modal fade" id="modal-qrcode" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ trans('latraining.attendance_code_qr_code') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="text-center">
                        @if ($qrcode_attendance)
                            <div id="qrcode" >
                                {!! QrCode::size(300)->generate($qrcode_attendance); !!}
                                <p>{{ trans('latraining.scan_code_attendance') }}</p>
                            </div>
                        @endif
                        <a href="javascript:void(0)" id="print_qrcode">{{ trans('latraining.print_qr_code') }}</a>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn" data-dismiss="modal">{{ trans('labutton.close') }}</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-import" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog" role="document">
            <form action="{{ route('module.offline.attendance.import', ['id' => $course->id, 'class_id' => $class->id]) }}" method="post" class="form-ajax">
                <input type="hidden" name="unit" value="{{ $course->id }}">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">{{ trans('latraining.import_user') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="file" name="import_file" id="import_file" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn" data-dismiss="modal">{{ trans('labutton.close') }}</button>
                        <button type="submit" class="btn">{{ trans('labutton.import') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script type="text/javascript">
        $('#print_qrcode').on("click", function () {
            $('#qrcode').printThis();
        });
        function name_formatter(value, row, index) {
            return row.lastname +' ' + row.firstname;
        }
        function percent_formatter(value, row, index) {
            return '<input type="text" name="percent" {{ $schedule ? '' : 'disabled' }} data-id="'+ row.id +'" ' +
                'class="form-control is-number change-percent" value="' + (row.percent ? row.percent : "") +'" '+ (row.checked == 1 ? '': 'disabled') +' >';
        }
        function note_formatter(value, row, index) {
            return '<textarea type="text" {{ $schedule ? '' : 'disabled'}} name="note" data-id="'+ row.id +'" ' +
                'class="form-control change-note w-auto">'+ (row.note ? row.note : "") +'</textarea>';
        }
        function reference_formatter(value, row, index) {
            return '<button type="button" {{ $schedule ? '' : 'disabled'}} class="import-reference btn" data-id="'+
                row.id +'" ><i class="fa fa-envelope-square" ></i></button> <button type="button" {{ $schedule ? '' :
                'disabled'}} class="btn"><a href="'+ row.download_reference +'" class="download {{ $schedule ? '' :
                'disabled'}}" ><i class="fa fa-download"></i></a></button>';
        }
        function type_formatter(value, row, index) {
            return '<input name="type" {{ $schedule ? '' : 'disabled' }} type="checkbox" class="check-item" value="'+ row.id
                +'" '+ (row.checked == 1 ? "checked": "") +' >';
        }
        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.offline.get_attendance', ['id' => $course->id, 'class_id' => $class->id]) }}?schedule={{ $schedule }}',
        });
        var ajax_save_all_register = "{{ route('module.offline.save_all_attendance', ['id' => $course->id, 'class_id' => $class->id]) }}";
        var ajax_save_register = "{{ route('module.offline.save_attendance', ['id' => $course->id]) }}";
        var ajax_save_percent = "{{ route('module.offline.save_percent', ['id' => $course->id]) }}?schedule={{ $schedule }}";
        var ajax_attendance_save_note = "{{ route('module.offline.attendance.save_note', ['id' => $course->id]) }}?schedule={{ $schedule }}";
        var ajax_get_reference = "{{ route('module.offline.modal_reference', ['id' => $course->id]) }}?schedule={{ $schedule }}";

        var ajax_save_absent = "{{ route('module.offline.save_absent', ['id' => $course->id]) }}?schedule={{ $schedule }}";
        var ajax_save_discipline = "{{ route('module.offline.save_discipline', ['id' => $course->id]) }}?schedule={{ $schedule }}";
        var ajax_save_absent_reason= "{{ route('module.offline.save_absent_reason', ['id' => $course->id]) }}?schedule={{ $schedule }}";

        function form_reference(form) {
            $("#app-modal #modal-reference").hide();
            window.location = '';
        }

        $('#modal_qrcode').on('click',function () {
            $("#modal-qrcode").modal();
        })

        $('#import-plan').on('click', function() {
            $('#modal-import').modal();
        });
    </script>
    <script src="{{ asset('styles/module/offline/js/attendance.js?v='.time()) }}"></script>
@endsection
