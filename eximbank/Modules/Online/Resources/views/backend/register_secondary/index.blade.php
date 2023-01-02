@extends('layouts.backend')

@section('page_title', trans('latraining.external_enrollment'))

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lamenu.training'),
                'url' => ''
            ],
            [
                'name' => trans('lamenu.online_course'),
                'url' => route('module.online.management')
            ],
            [
                'name' => $online->name,
                'url' => route('module.online.edit', ['id' => $online->id])
            ],
            [
                'name' => trans('latraining.external_enrollment'),
                'url' => ''
            ],
        ]
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
            <div class="col-md-12">
                <form class="form-inline form-search-user mb-3" id="form-search">
                    <div class="w-24">
                        <input type="text" name="search" class="form-control w-100" autocomplete="off" placeholder="{{ trans('latraining.enter_code_name') }}">
                    </div>
                    <div class="w-24">
                        <button type="submit" class="btn"><i class="fa fa-search"></i>&nbsp;{{ trans('labutton.search') }} </button>
                    </div>
                </form>
            </div>
            @if($online->lock_course == 0)
            <div class="col-md-12 text-right act-btns">
                <div class="pull-right">
                    @can('online-course-register-create')
                        <button type="button" class="btn" id="send-mail-user-registed"><i class="fa fa-send"></i> {{ trans('labutton.send_mail_registed') }}</button>

                        @if(count($quiz_exists) > 0)
                            <div class="btn-group">
                                <button type="button" class="btn" id="add-to-quiz"><i class="fa fa-plus"></i> {{ trans('labutton.add_student_quiz') }}</button>
                            </div>
                        @endif

                        <div class="btn-group">
                            <a class="btn" href="{{ download_template('mau_import_nhan_vien_ghi_danh_khoa_hoc.xlsx') }}"><i class="fa fa-download"></i> {{ trans('labutton.import_template') }}</a>
                            <button class="btn" id="import-plan" type="submit" name="task" value="import">
                                <i class="fa fa-upload"></i> {{ trans('labutton.import') }}
                            </button>
                        </div>
                    @endcan

                    <div class="btn-group">
                        {{-- @can('online-course-register-create')
                        <a href="{{ route('module.online.register_secondary.create', ['id' => $online->id]) }}"
                           class="btn"><i class="fa fa-plus-circle"></i> {{ trans('labutton.add_new') }}</a>
                        @endcan --}}
                        @can('online-course-register-delete')
                        <button class="btn" id="delete-item"><i class="fa fa-trash"></i> {{ trans('labutton.delete') }}</button>
                        @endcan
                    </div>
                </div>
            </div>
            @endif
        </div>
        <br>
        <table class="tDefault table table-hover bootstrap-table" data-page-list="[10, 50, 100, 200, 500]" id="list-user-registed">
            <thead>
                <tr>
                    <th data-field="state" data-checkbox="true"></th>
                    <th data-field="code" data-width="5px">{{ trans('latraining.employee_code') }}</th>
                    <th data-sortable="true" data-field="name" data-width="20%">{{ trans('latraining.employee_name') }}</th>
                    <th data-field="email">{{ trans('latraining.email') }}</th>
                    @if(count($quiz_exists) > 0)
                        <th data-field="quiz_name" data-align="center">{{ trans('latraining.exam') }}</th>
                    @endif
                    <th data-field="status" data-align="center" data-formatter="status_formatter" data-width="5%">{{ trans('latraining.status') }}</th>
                </tr>
            </thead>
        </table>
    </div>

    <div class="modal fade" id="modal-import" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog" role="document">
            <form action="{{ route('module.online.register_secondary.import_register', ['id' => $online->id]) }}" method="post" class="form-ajax">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">{{ trans('latraining.import_student') }}</h5>
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

    @if(count($quiz_exists) > 0)
    <div class="modal fade" id="modal-add-to-quiz" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">{{ trans('latraining.add_student') }}</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">
                    @if(count($quiz_exists) > 1)
                    <div class="form-group">
                        <label for="quiz_id">{{ trans('latraining.exam') }}</label>
                        <select name="quiz_id" id="quiz_id" class="form-control load-quiz-online" data-course="{{ $online->id }}"
                                data-placeholder="{{trans('latraining.choose_quiz')}}">
                            <option value=""></option>
                        </select>
                    </div>
                    @else
                        <input type="hidden" name="quiz_id" value="{{ $quiz_exists[0]->subject_id }}">
                    @endif

                    <div class="form-group">
                        <label for="part_id">{{trans('latraining.part')}}</label>
                        <select name="part_id" id="part_id" class="form-control load-part-quiz-online" data-quiz_id="" data-placeholder="{{trans('latraining.choose_part')}}">
                            <option value=""></option>
                        </select>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn" data-dismiss="modal" id="save"><i class="fa fa-plus"></i> {{trans('labutton.add_new')}}</button>
                    <button type="button" class="btn" data-dismiss="modal">{{trans('labutton.close')}}</button>
                </div>

            </div>
        </div>
    </div>
    @endif

    <script type="text/javascript">
        function status_formatter(value, row, index) {
            if (value == 0) {
                return '<span class="text-danger">{{ trans("backend.deny") }}</span>';
            }else if (value == 1) {
                return '<span class="text-success">{{ trans("backend.approved") }}</span>';
            }else{
                return '<span class="text-warning">{{ trans("backend.not_approved") }}</span>';
            }
        }

        $('#import-plan').on('click', function() {
            $('#modal-import').modal();
        });

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.online.register_secondary.getdata', ['id' => $online->id]) }}',
            remove_url: '{{ route('module.online.register_secondary.remove', ['id' => $online->id]) }}',
            table: '#list-user-registed',
        });

        $('#quiz_id').on('change', function () {
            var quiz_id = $('#quiz_id option:selected').val();
            $("#part_id").empty();
            $("#part_id").data('quiz_id', quiz_id);
            $('#part_id').trigger('change');
        });

        var quiz_id = $("input[name=quiz_id]").val();
        $("#part_id").empty();
        $("#part_id").data('quiz_id', quiz_id);
        $('#part_id').trigger('change');

        $("#add-to-quiz").on('click', function () {
            let ids = $("input[name=btSelectItem]:checked").map(function(){return $(this).val();}).get();
            if (ids.length <= 0) {
                show_message('Vui lòng chọn ít nhất 1 học viên!', 'error');
                return false;
            }
            $('#modal-add-to-quiz').modal();

            $('#save').on('click', function () {
                var quiz_id = $("input[name=quiz_id]").val() ? $("input[name=quiz_id]").val() : $('#quiz_id option:selected').val();
                var part_id = $('#part_id option:selected').val();

                $.ajax({
                    type: 'POST',
                    url: '{{ route('module.online.register_secondary.add_to_quiz', ['id' => $online->id]) }}',
                    dataType: 'json',
                    data: {
                        'ids': ids,
                        'part_id': part_id,
                        'quiz_id': quiz_id,
                    }
                }).done(function(data) {
                    show_message(data.message, data.status);
                    $('#modal-add-to-quiz').hide();
                    window.location = '';
                    return false;
                }).fail(function(data) {
                    return false;
                });
            });
        });

        $('#send-mail-user-registed').on('click', function () {
            let ids = $("input[name=btSelectItem]:checked").map(function(){return $(this).val();}).get();
            if (ids.length <= 0) {
                show_message('Vui lòng chọn ít nhất 1 học viên!', 'error');
                return false;
            }
            $.ajax({
                type: 'POST',
                url: '{{ route('module.online.register_secondary.send_mail_user_registed', ['id' => $online->id]) }}',
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

    </script>
@endsection
