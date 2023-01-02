@extends('layouts.backend')

@section('page_title', trans('laquiz.internal_user'))

@section('breadcrumb')
    @php
        if($quiz_name->course_type == 1){
            $route_edit = route('module.online.edit', ['id' => $course_id]);
            $route_quiz = route('module.online.quiz', ['course_id' => $course_id]);

            $breadcum= [
                [
                    'name' => trans('lamenu.training_organizations'),
                    'url' => ''
                ],
                [
                    'name' => trans('lamenu.online_course'),
                    'url' => route('module.online.management')
                ],
                [
                    'name' => $course->name,
                    'url' => $route_edit
                ],
                [
                    'name' => trans('latraining.quiz_list'),
                    'url' => $route_quiz
                ],
                [
                    'name' => $quiz_name->name,
                    'url' => route('module.online.quiz.edit', ['course_id' => $course_id, 'id' => $quiz_name->id])
                ],
                [
                    'name' => trans('laquiz.internal_user'),
                    'url' => ''
                ],
            ];
        }elseif($quiz_name->course_type == 2){
            $route_edit = route('module.offline.edit', ['id' => $course_id]);
            $route_quiz = route('module.offline.quiz', ['course_id' => $course_id]);

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
                    'url' => $route_edit
                ],
                [
                    'name' => trans('latraining.quiz_list'),
                    'url' => $route_quiz
                ],
                [
                    'name' => $quiz_name->name,
                    'url' => route('module.offline.quiz.edit', ['course_id' => $course_id, 'id' => $quiz_name->id])
                ],
                [
                    'name' => trans('laquiz.internal_user'),
                    'url' => ''
                ],
            ];
        }else{
            $breadcum= [
                [
                    'name' => trans('latraining.quiz_list'),
                    'url' => route('module.quiz.manager')
                ],
                [
                    'name' => $quiz_name->name,
                    'url' => route('module.quiz.edit', ['id' => $quiz_id])
                ],
                [
                    'name' => trans('laquiz.internal_user'),
                    'url' => ''
                ],
            ];
        }
    @endphp
    @include('layouts.backend.menu_breadcum',$breadcum)
@endsection

@section('content')
    <div role="main">
    @if(isset($errors))

    @foreach($errors as $error)
        <div class="alert alert-danger">{!! $error !!}</div>
    @endforeach

    @endif
        <div class="row">
            <div class="col-md-2">
                @include('quiz::backend.register.filter_register')
            </div>
            <div class="col-md-10 text-right act-btns">
                <div class="pull-right">
                    @if ($quiz_name->quiz_type == 3)
                        <a href="{{ route('module.quiz.register.user_secondary', ['id' => $quiz_id]) }}" class="btn"><i class="fa fa-users"></i> {{ trans("latraining.outsides") }}</a>
                    @endif
                    <button type="button" class="btn" id="send-mail-user-registed">
                        <i class="fa fa-send"></i> {{ trans('labutton.send_mail_registed') }} ({{ $count_quiz_register }})
                    </button>

                    <div class="btn-group">
                        <button class="btn block-quiz" data-status="1">
                            <i class="fa fa-lock"></i> {{ trans('labutton.lock') .' '. trans('laquiz.entry_exam') }}
                        </button>
                        <button class="btn block-quiz" data-status="0">
                            <i class="fa fa-unlock"></i> {{ trans('labutton.open') .' '. trans('laquiz.entry_exam') }}
                        </button>
                    </div>

                    <div class="btn-group">
                        <a class="btn" href="{{ route('module.quiz.register.export_template_register', ['id' => $quiz_id]) }}">
                            <i class="fa fa-download"></i> {{ trans('backend.import_template') }}
                        </a>
                        <button class="btn" id="import-plan" type="submit" name="task" value="import">
                            <i class="fa fa-upload"></i> Import
                        </button>
                        <a class="btn" href="{{ route('module.quiz.register.export_register', ['id' => $quiz_id]) }}">
                            <i class="fa fa-download"></i> Export
                        </a>
                    </div>

                    <div class="btn-group">
                        <a href="{{ ($quiz_name->unit == 1) ? route('module.training_unit.quiz.register.create', ['id' => $quiz_id]) : route('module.quiz.register.create', ['id' => $quiz_id]) }}" class="btn"><i class="fa fa-plus-circle"></i> {{ trans('labutton.add_new') }}</a>
                        <button class="btn" id="delete-item"><i class="fa fa-trash"></i> {{ trans('labutton.delete') }}</button>
                    </div>
                </div>
            </div>
        </div>
        <br>

        <table class="tDefault table table-hover bootstrap-table" id="table_quiz_register">
            <thead>
                <tr>
                    <th data-field="state" data-checkbox="true"></th>
                    <th data-field="name" data-formatter="name_formatter">{{ trans('backend.employee_name') }}</th>
                    <th data-field="title_name">{{ trans('latraining.title') }}</th>
                    <th data-field="unit_name">{{ trans('backend.work_unit') }}</th>
                    <th data-field="parent_name">{{ trans('backend.unit_manager') }}</th>
                    <th data-field="part_name" data-align="center">{{trans('backend.exams')}}</th>
                    <th data-field="part_date" data-align="center" data-formatter="part_date_formatter" data-width="13%">{{trans('backend.time')}}</th>
                    <th data-field="attempts_again" data-align="center" data-formatter="attempts_again_formatter" data-width="5%">Cho thi lại</th>
                    <th data-field="block_quiz" data-align="center" data-formatter="block_quiz_formatter" data-width="5%">Cấm thi</th>
                </tr>
            </thead>
        </table>
    </div>
    <div class="modal fade" id="modal-import" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog" role="document">
            <form action="{{ route('module.quiz.register.import_register', ['id' => $quiz_id]) }}" method="post" class="form-ajax">
                <input type="hidden" name="unit" value="{{ $quiz_name->unit_id }}">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">IMPORT {{trans("backend.user")}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="file" name="import_file" id="import_file" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn" data-dismiss="modal">{{ trans('labutton.close') }}</button>
                        <button type="submit" class="btn">Import</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="modal-block-quiz" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{trans("backend.note")}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <textarea name="block_quiz_note" id="block_quiz_note" class="form-control w-100" placeholder="{{ trans('app.content') }}"></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn" data-dismiss="modal">{{ trans('labutton.close') }}</button>
                    <button type="button" class="btn" id="save-block-quiz">{{ trans('latraining.lock') }}</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-show-block-quiz-note" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{trans("backend.note")}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                </div>
            </div>
        </div>
    </div>


    <script type="text/javascript">

        function name_formatter(value, row, index) {
            return row.lastname +' '+ row.firstname +' ('+ row.code +') <br>' + row.email;
        }

        function part_date_formatter(value, row, index) {
            return row.part_start_date + ' => ' + row.part_end_date;
        }

        function attempts_again_formatter(value, row, index){
            return (row.count_attempt_again > 0 ? row.count_attempt_again : '') + (row.attempts_again > 0 ? ' <span class="attempts_again" data-part_id="'+row.part_id+'" data-user_id="'+row.user_id+'"><i class="fa fa-sync" style="cursor: pointer;"></i></span>' : '');
        }

        function block_quiz_formatter(value, row, index){
            return row.block_quiz == 1 ? '<i class="fa fa-lock show_block_quiz_note" data-note="'+row.block_quiz_note+'"></i>' : '';
        }

        $('#import-plan').on('click', function() {
            $('#modal-import').modal();
        });
        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.quiz.register.getdata', ['id' => $quiz_id]) }}',
            remove_url: '{{ route('module.quiz.register.remove', ['id' => $quiz_id]) }}'
        });

        $('#send-mail-user-registed').on('click', function () {
            let ids = $("input[name=btSelectItem]:checked").map(function(){return $(this).val();}).get();
            if (ids.length <= 0) {
                show_message('Vui lòng chọn ít nhất 1 học viên!', 'error');
                return false;
            }
            $.ajax({
                type: 'POST',
                url: '{{ route('module.quiz.register.send_mail_user_registed', ['id'=>$quiz_id, 'type'=>1]) }}',
                dataType: 'json',
                data: {
                    'ids': ids,
                }
            }).done(function(data) {
                show_message(data.message, data.status);
                table.refresh();
                return false;
            }).fail(function(data) {
                return false;
            });
        })

        $('#table_quiz_register').on('click', '.attempts_again', function(){
            var part_id = $(this).data('part_id');
            var user_id = $(this).data('user_id');

            var btn = $(this);
            var icon_current = btn.html();
            btn.html('<i class="fa fa-spinner fa-spin"></i>');
            btn.prop("disabled", true);

            Swal.fire({
                title: '',
                text: 'Bạn chắc chắn muốn cho thi lại?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Đồng ý!',
                cancelButtonText: 'Hủy!',
            }).then((result) => {
                if (result.value) {
                    console.log(part_id, user_id);

                    $.ajax({
                        type: 'POST',
                        url: '{{ route('module.quiz.register.attempts_again', ['id'=>$quiz_id]) }}',
                        dataType: 'json',
                        data: {
                            part_id: part_id,
                            user_id: user_id,
                        }
                    }).done(function(data) {
                        btn.html(icon_current);
                        btn.prop("disabled", false);

                        show_message(data.message, data.status);
                        table.refresh();
                        return false;

                    }).fail(function(data) {
                        btn.html(icon_current);
                        btn.prop("disabled", false);

                        return false;
                    });
                }else{
                    btn.html(icon_current);
                    btn.prop("disabled", false);

                    return false;
                }
            });
        });

        $('.block-quiz').on('click', function () {
            let ids = $("input[name=btSelectItem]:checked").map(function(){return $(this).val();}).get();
            if (ids.length <= 0) {
                show_message('Vui lòng chọn ít nhất 1 học viên!', 'error');
                return false;
            }
            let status = $(this).data('status');
            if(status == 1){
                $('#modal-block-quiz').modal();

                return false;
            }else{
                var btn = $(this);
                var icon_current = btn.html();
                btn.html('<i class="fa fa-spinner fa-spin"></i> Mở khoá');
                btn.prop("disabled", true);

                $.ajax({
                    type: 'POST',
                    url: '{{ route('module.quiz.register.block_quiz', ['id'=>$quiz_id]) }}',
                    dataType: 'json',
                    data: {
                        'ids': ids,
                        'status': status
                    }
                }).done(function(data) {

                    btn.html(icon_current);
                    btn.prop("disabled", false);

                    show_message(data.message, data.status);
                    table.refresh();

                    return false;
                }).fail(function(data) {

                    btn.html(icon_current);
                    btn.prop("disabled", false);

                    return false;
                });
            }
        });

        $('#modal-block-quiz').on('click', '#save-block-quiz', function(){
            let ids = $("input[name=btSelectItem]:checked").map(function(){return $(this).val();}).get();
            var block_quiz_note = $('#block_quiz_note').val();

            if (block_quiz_note.length <= 0) {
                show_message('Vui lòng nhập nội dung', 'error');
                return false;
            }

            var btn = $(this);
            var icon_current = btn.html();
            btn.html('<i class="fa fa-spinner fa-spin"></i> Khoá');
            btn.prop("disabled", true);

            $.ajax({
                type: 'POST',
                url: '{{ route('module.quiz.register.block_quiz', ['id'=>$quiz_id]) }}',
                dataType: 'json',
                data: {
                    'ids': ids,
                    'block_quiz_note': block_quiz_note,
                    'status': 1
                }
            }).done(function(data) {

                btn.html(icon_current);
                btn.prop("disabled", false);

                show_message(data.message, data.status);

                $('#modal-block-quiz').modal('hide');

                table.refresh();

                return false;
            }).fail(function(data) {

                btn.html(icon_current);
                btn.prop("disabled", false);

                return false;
            });
        });

        $('#table_quiz_register').on('click', '.show_block_quiz_note', function(){
            var note = $(this).data('note');

            $('#modal-show-block-quiz-note .modal-body').html(note);

            $('#modal-show-block-quiz-note').modal();
        });
    </script>
@endsection
