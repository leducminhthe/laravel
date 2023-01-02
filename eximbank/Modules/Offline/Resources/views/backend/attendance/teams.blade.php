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
                'url' => route('module.offline.class', ['id' => $course->id])
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
            <div class="col-md-6">
                @include('offline::backend.attendance.filter')
            </div>
            <div class="col-md-6 text-right act-btns">
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
                                        {{ trans('latraining.session') .' '. ($key + 1) .' ('. get_date($item->start_time, 'H:i') }} <i class="uil uil-angle-right"></i> {{ get_date($item->end_time, 'H:i') .' - '. get_date($item->lesson_date, 'd/m/Y') . ')' }}
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
                    {{ trans('latraining.join') }}
                </th>
                <th data-field="percent" data-align="center"  data-formatter="percent_formatter">% <br> {{ trans('latraining.join') }}</th>
                <th data-field="type_attendan" data-align="center">{!! trans('latraining.attendance_br_method') !!}</th>
                <th data-field="note" data-formatter="note_formatter">{{ trans('latraining.note') }}</th>
            </tr>
            </thead>
        </table>
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
                'class="form-control is-number change-percent " value="' + (row.percent ? row.percent : "") +'"  disabled  >';
        }
        function note_formatter(value, row, index) {
            return '<textarea type="text" {{ $schedule ? '' : 'disabled'}} name="note" data-id="'+ row.id +'" ' +
                'class="form-control change-note w-auto">'+ (row.note ? row.note : "") +'</textarea>';
        }
        function type_formatter(value, row, index) {
            return '<input name="type" {{ $schedule ? '' : 'disabled' }} type="checkbox" disabled class="check-item" value="'+ row.id
                +'" '+ (row.checked == 1 ? "checked": "") +' >';
        }
        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.offline.get_attendance', ['id' => $course->id, 'class_id' => $class->id]) }}?schedule={{ $schedule }}',
        });
        var ajax_attendance_save_note = "{{ route('module.offline.attendance.save_note', ['id' => $course->id]) }}?schedule={{ $schedule }}";

    </script>
    <script src="{{ asset('styles/module/offline/js/attendance.js') }}"></script>
@endsection
