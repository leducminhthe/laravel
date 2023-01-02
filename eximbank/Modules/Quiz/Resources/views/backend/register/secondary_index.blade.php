@extends('layouts.backend')

@section('page_title', trans('lamenu.user_secondary'))

@section('breadcrumb')
    @php
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
                'name' => trans('lamenu.user_secondary'),
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
            <div class="col-md-12 mb-3">
                <form class="form-inline" id="form-search">
                    <input type="text" name="search" value="" class="form-control" placeholder="{{trans('backend.enter_code_name')}}">
                    <div class="w-24">
                        <select name="part" class="form-control select2" data-placeholder="-- {{trans('backend.exams')}} --">
                            <option value=""></option>
                            @foreach ($quiz_part as $part)
                                <option value="{{ $part->id }}" >{{ $part->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn"><i class="fa fa-search"></i>&nbsp;{{ trans('labutton.search') }}</button>
                </form>
            </div>
            <p></p>
            <div class="col-md-12 text-right act-btns">
                <div class="pull-right">
                    <a href="{{ route('module.quiz.register', ['id' => $quiz_id]) }}" class="btn"><i class="fa fa-users"></i> {{ trans("latraining.internally") }}</a>

                    <button type="button" class="btn" id="send-mail-user-registed"><i class="fa fa-send"></i> {{ trans('labutton.send_mail_registed') }}</button>

                    <a href="{{ route('module.quiz.register.user_secondary.create_new_user', ['id' => $quiz_id]) }}" class="btn"><i class="fa fa-plus-circle"></i> {{trans('laquiz.create_user_external')}}</a>

                    <a class="btn" href="{{ download_template('mau_import_nhan_vien_ghi_danh_ky_thi.xlsx') }}"><i class="fa fa-download"></i> {{ trans('backend.import_template') }}</a>
                    <button class="btn" id="import-plan" type="submit" name="task" value="import">
                        <i class="fa fa-upload"></i> Import
                    </button>
                    <a class="btn" href="{{ route('module.quiz.register.export_register_secondary', ['id' => $quiz_id]) }}">
                        <i class="fa fa-download"></i> Export
                    </a>
                    <div class="btn-group">
                        <a href="{{ route('module.quiz.register.user_secondary.create', ['id' => $quiz_id]) }}" class="btn"><i class="fa fa-plus-circle"></i> {{ trans('labutton.add_new') }}</a>
                        <button class="btn" id="delete-item"><i class="fa fa-trash"></i> {{ trans('labutton.delete') }}</button>
                    </div>
                </div>
            </div>
        </div>
        <br>

        <table class="tDefault table table-hover bootstrap-table">
            <thead>
                <tr>
                    <th data-field="state" data-checkbox="true"></th>
                    <th data-sortable="true" data-field="code">{{ trans('backend.employee_outside_code') }}</th>
                    <th data-field="full_name">{{ trans('backend.employee_outside_name') }}</th>
                    <th data-field="dob" data-align="center">{{ trans('backend.dob') }}</th>
                    <th data-field="identity_card" data-align="center">{{ trans('backend.identity_card') }}</th>
                    <th data-field="email" data-align="center">Email</th>
                    <th data-field="part_name" data-align="center">{{trans('backend.exams')}}</th>
                    <th data-field="part_date" data-align="center" data-formatter="part_date_formatter" data-width="30%">{{trans('backend.time')}}</th>
                </tr>
            </thead>
        </table>

        <div class="modal fade" id="modal-import" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog" role="document">
                <form action="{{ route('module.quiz.register.user_secondary.import_register', ['id' => $quiz_id]) }}" method="post" class="form-ajax">
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
    </div>

    <script type="text/javascript">

        function part_date_formatter(value, row, index) {
            return row.part_start_date + ' => ' + row.part_end_date;
        }

        $('#import-plan').on('click', function() {
            $('#modal-import').modal();
        });

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.quiz.register.user_secondary.getdata', ['id' => $quiz_id]) }}',
            remove_url: '{{ route('module.quiz.register.user_secondary.remove', ['id' => $quiz_id]) }}'
        });

        $('#send-mail-user-registed').on('click', function () {
            let ids = $("input[name=btSelectItem]:checked").map(function(){return $(this).val();}).get();
            if (ids.length <= 0) {
                show_message('Vui lòng chọn ít nhất 1 học viên!', 'error');
                return false;
            }
            $.ajax({
                type: 'POST',
                url: '{{ route('module.quiz.register.send_mail_user_registed', ['id'=>$quiz_id, 'type'=>2]) }}',
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
