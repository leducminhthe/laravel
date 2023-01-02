@extends('layouts.backend')

@section('page_title', trans('latraining.teacher'))

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
                'name' => trans('latraining.teacher_class')." : ".$class->name,
                'url' => ''
            ],
        ]
    @endphp
    @include('layouts.backend.menu_breadcum',$breadcum)
@endsection

@section('content')
    <div role="main" id="teacher_offline" class="form_offline_course">
        @include('offline::backend.includes.navgroup')
        <br>
        <div class="row">
            <div class="col-md-8">
                <form class="form-inline form-search mb-3" id="form-search">
                    <input type="text" name="search" value="" class="form-control" placeholder="{{ trans('latraining.enter_teacher_name') }}">
                    <button type="submit" class="btn"><i class="fa fa-search"></i>&nbsp;{{ trans('labutton.search') }}</button>
                </form>
            </div>
            <div class="col-md-4 text-right act-btns">
                <div class="pull-right">
                    <div class="btn-group">
                        @can('offline-course-teacher-create')
                            @if($course->lock_course == 0)
                                <a href="javascript:void(0)" id="import-teacher" class="btn"><i class="fa fa-plus-circle"></i> {{ trans('labutton.add_new') }}</a>
                                <button class="btn" id="delete-item"><i class="fa fa-trash"></i> {{ trans('labutton.delete') }}</button>
                            @endif
                        @endcan
                    </div>
                </div>
            </div>
        </div>
        <br>
        <table class="tDefault table table-hover bootstrap-table">
            <thead>
            <tr>
                <th data-field="state" data-checkbox="true"></th>
                <th data-sortable="true" data-field="teacher_name">{{ trans('latraining.fullname') }}</th>
                <th data-field="teacher_email">{{ trans('latraining.email') }}</th>
                <th data-field="teacher_phone">{{ trans('latraining.phone') }}</th>
                <th data-field="note" data-formatter="note_formatter">{{ trans('latraining.note') }}</th>
            </tr>
            </thead>
        </table>
    </div>
    <div class="modal fade" id="modal-import" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog" role="document">
            <form action="{{ route('module.offline.teacher.class.save', ['id' => $course->id, 'class_id'=>$class->id]) }}" method="post" class="form-ajax">
                <input type="hidden" name="unit" value="{{ $course->unit_id }}">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel"> {{ trans('latraining.teacher') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group row">
                            <div class="col-sm-3 control-label">
                                <label>{{ trans('latraining.teacher') }} <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-9">
                                <select name="teacher_id" id="teacher_id" class="form-control select2" data-placeholder="{{ trans('latraining.choose_teacher') }}" required>
                                    <option value=""></option>
                                    @foreach($teachers as $teacher)
                                        <option value="{{ $teacher->id }}"> {{ $teacher->code .' - '. $teacher->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn" data-dismiss="modal">{{ trans('labutton.close') }}</button>
                        @if($course->lock_course == 0)
                            <button type="submit" class="btn">{{trans('labutton.save')}}</button>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script type="text/javascript">

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.offline.teacher', ['id' => $course->id,'class_id'=>$class->id]) }}',
            remove_url: '{{ route('module.offline.remove_teacher', ['id' => $course->id]) }}'
        });

        function note_formatter(value, row, index) {
            return '<textarea type="text" name="note" data-id="'+ row.id +'" class="form-control change-note" {{ $course->lock_course == 0 ? '' : 'readonly' }}>'+ (row.note ? row.note : "") +'</textarea>';
        }

        $('#import-teacher').on('click', function() {
            $('#modal-import').modal();
        });

        $('#teacher_offline').on('change', '.change-note', function() {
            var note = $(this).val();
            var off_teacher_id = $(this).data('id');

            $.ajax({
                url: '{{ route('module.offline.teacher.save_note', ['id' => $course->id]) }}',
                type: 'post',
                data: {
                    note: note,
                    off_teacher_id : off_teacher_id,
                },

            }).done(function(data) {

                return false;

            }).fail(function(data) {

                show_message(
                    'Lỗi hệ thống',
                    'error'
                );
                return false;
            });
        });
    </script>
@endsection
