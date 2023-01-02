@extends('layouts.backend')

@section('page_title', trans('latraining.classroom'))

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
                'name' => $course->name,
                'url' => route('module.offline.edit', ['id' => $course->id])
            ],
            /*[
                'name' => trans('latraining.classroom'),
                'url' => route('module.offline.class', ['id' => $course->id]),
                'drop-menu'=>$classArray
            ],*/
            [
                'name' => trans('latraining.schedule').": ".$class->name,
                'url' => ''
            ],
        ]
    @endphp
    @include('layouts.backend.menu_breadcum',$breadcum)
@endsection

@section('content')
<div role="main" class="form_offline_course">
    @if(isset($errors))
        @foreach($errors as $error)
            <div class="alert alert-danger">{!! $error !!}</div>
        @endforeach
    @endif
        @include('offline::backend.includes.navgroup')
        <br>
        <div class="row">
            @if($course->lock_course == 0)
                <div class="col-md-12 act-btns">
                    <div class="pull-right">
                        <div class="wrraped_register text-right">
                            @canany(['offline-course-create'])
                                <div class="btn-group">
                                    <a href="{{ route('module.offline.create_schedule', ['courseId' => $course->id, 'classId' => $class->id]) }}" class="btn create_schedule">
                                        <i class="fa fa-plus-circle"></i> {{ trans('labutton.add_new') }}
                                    </a>
                                    <button class="btn" id="delete-item"><i class="fa fa-trash"></i> {{ trans('labutton.delete') }}</button>
                                </div>
                            @endcanany
                        </div>
                    </div>
                </div>
            @endif
        </div>
        <br>
        <table class="tDefault table table-hover" id="table-schedule-parent">
            <thead>
            <tr>
                <th data-field="state" data-checkbox="true"></th>
                <th data-field="day" data-align="center" data-formatter="day_formatter">{{ trans('latraining.session') }}</th>
                <th data-field="time" data-align="center" data-formatter="time_formatter">{{ trans('latraining.time') }}</th>
                {{--  <th data-field="lesson_date" data-align="center">{{ trans('latraining.start_date') }}</th>  --}}
                <th data-field="type_study" data-align="center" data-formatter="type_study_formatter">{{ trans('latraining.type_study') }}</th>
                <th data-field="created_by">{{ trans('latraining.created_by') }}</th>
                <th data-field="teacherName">{{ trans('latraining.main_lecturer') }}</th>
                <th data-field="tutors" data-formatter="tutors_formatter">{{ trans('latraining.tutors') }}</th>
                <th data-field="cost_teach_type" data-align="center">{{ trans('latraining.cost') }} <br> ({{ trans('latraining.tutors') }})</th>
                {{-- <th data-formatter="edit_formatter" data-width="5%" data-align="center">{{ trans('latraining.edit') }}</th> --}}
                <th data-field="type_formatter" data-formatter="type_formatter" data-width="5%" data-align="center">Hành động</th>
                {{-- <th data-field="link_teams" data-formatter="link_teams_formatter" data-width="5%" data-align="center">Link MS Teams</th> --}}
                {{-- <th data-field="offline_activity" data-formatter="offline_activity_formatter" data-width="5%" data-align="center">{{ trans('latraining.activiti') }}</th> --}}
            </tr>
            </thead>
        </table>
    </div>

    <div class="modal fade" id="modal-link-teams" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Link học Teams</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="input-group">
                        <input id="link_teams_modal" type="text" class="form-control" readonly>
                        <div class="input-group-append" id="button-addon1">
                            <button class="btn" type="button" data-title="copy" onclick="copyLinkHandle()">
                                <i class="far fa-copy"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        function day_formatter(value, row, index) {
            return (index + 1);
        }
        function type_study_formatter(value, row, index) {
            let str = '';

            if(row.type_study == 1){
                str = '<div style="text-align:center" title="Học tại lớp"> <img src="{{asset('images/inclass.png')}}" width="24px" height="24px"/><p class="mb-0">Tại lớp</p></div>';
            }else if(row.type_study == 2){
                str = '<div style="text-align:center" title="Học qua teams"><img class="cursor_pointer" src="{{ asset("images/teams.png") }}" height="23px" width="23px" alt="microsoft teams" onclick="showLinkTeamsHandel('+ row.id +', event)"><p class="mb-0">Microsoft Teams</p></div><input type="hidden" id="link_teams_'+ row.id +'" value="'+ row.link_teams +'"/>';
            }else{
                str = '<div style="text-align:center" title="Elearning" ><img src="{{asset('images/elearning.png')}}" width="24px" height="24px" /><p class="mb-0">Elearning</p></div>';
            }

            return str;
        }
        function time_formatter(value, row, index) {
            if(row.type_study == 3){
                return row.start_time +' '+ row.lesson_date +' <i class="fa fa-arrow-right"></i> ' + row.end_time +' '+ row.end_date;
            }else{
                return row.start_time +' <i class="fa fa-arrow-right"></i> ' + row.end_time +' - '+ row.lesson_date;
            }
        }

        function tutors_formatter(value, row, index) {
            var html = '';
            var teacher = row.tutorsName;
            if(teacher){
                teacher.forEach(element => {
                    html += '<span>'+ element +'</span><br />';
                });
            }
            return html
        }

        function type_formatter(value, row, index){
            let str = '';
            str += '<a class="mr-1" href="'+ row.edit +'" title="Chỉnh sửa Buổi học"><i class="fa fa-edit" aria-hidden="true"></i></a>';

            if(row.type_study == 2){
                str += '<a href="'+ row.url_report_teams +'" title="Báo cáo MS Teams"><i class="far fa-chart-bar" aria-hidden="true"></i></a>';
            } else if(row.type_study == 3) {
                str += '<a href="'+ row.activity_url +'" class="mr-1" title="Thêm hoạt động Elearning"><i class="fa fa-plus-circle" aria-hidden="true"></i></a>';
                str += '<a href="'+ row.url_report_elearning +'" title="Báo cáo Elearning"><i class="far fa-chart-bar" aria-hidden="true"></i></a>';
            }

            return str;
        }

        var table = new LoadBootstrapTable({
            url: '{{ route('module.offline.schedule', ['id' => $class->course_id, 'class_id' => $class->id]) }}',
            remove_url: '{{ route('module.offline.remove_schedule', ['courseId' => $class->course_id, 'class_id' => $class->id]) }}',
            table: "#table-schedule-parent"
        });

        function showLinkTeamsHandel(id, event) {
            event.stopPropagation();
            let link_teams = $('#link_teams_'+ id).val();
            $('#link_teams_modal').val(link_teams)
            $('#modal-link-teams').modal()
        }

        function copyLinkHandle() {
            var copyText = document.getElementById("link_teams_modal");
            copyText.select();
            copyText.setSelectionRange(0, 99999);
            navigator.clipboard.writeText(copyText.value);
        }
    </script>
@endsection
