@extends('layouts.backend')

@section('page_title', trans('latraining.object'))

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lamenu.target_manager'),
                'url' => ''
            ],
            [
                'name' => $target_manager_parent->name,
                'url' => route('module.target_manager_parent')
            ],
            [
                'name' => trans('latraining.object'),
                'url' => ''
            ],
        ]
    @endphp
    @include('layouts.backend.menu_breadcum',$breadcum)
@endsection

@section('content')
    @if(isset($errors))
        @foreach($errors as $error)
            <div class="alert alert-danger">{!! $error !!}</div>
        @endforeach
    @endif
    <div role="main">
        <div class="row">
            <div class="col-md-6">
                <form class="form-inline form-search mb-3" id="form-search">
                    <input type="text" name="search" value="" class="form-control " placeholder='{{ trans('latraining.enter_name') }}'>
                    <button type="submit" class="btn"><i class="fa fa-search"></i>&nbsp;{{trans('labutton.search')}}</button>
                </form>
            </div>
            <div class="col-md-6 text-right act-btns">
                <div class="pull-right">
                    <div class="btn-group">
                        <a class="btn" href="{{ download_template('mau_import_doi_tuong_quan_ly_chi_tieu_nam.xlsx') }}">
                            <i class="fa fa-download"></i> {{trans('labutton.import_template')}}
                        </a>
                        <button class="btn" id="import" type="submit" name="task" value="import">
                            <i class="fa fa-upload"></i> {{ trans('labutton.import') }}
                        </button>
                    </div>
                    <div class="btn-group">
                        @can('target-manager-copy')
                            <button class="btn" id="copy"><i class="fa fa-file"></i> {{ trans('labutton.copy') }}</button>
                        @endcan
                        @can('target-manager-create')
                            <button style="cursor: pointer;" onclick="create()" class="btn"><i class="fa fa-plus-circle"></i> {{ trans('labutton.add_new') }}</button>
                        @endcan
                        @can('target-manager-delete')
                            <button class="btn" id="delete-item"><i class="fa fa-trash"></i> {{trans('labutton.delete')}}</button>
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
                    <th data-sortable="true" data-field="name" data-formatter="name_formatter">{{ trans('lacategory.name') }}</th>
                    <th data-field="type" data-align="center" data-width="10%">{{ trans('lacategory.group') }}</th>
                    <th data-field="num_hour_student" data-align="center" data-width="5%">{{ trans('latraining.num_hour_student') }}</th>
                    <th data-field="num_course_student" data-align="center" data-width="5%">{{ trans('latraining.num_course_student') }}</th>
                    <th data-field="num_hour_teacher" data-align="center" data-width="5%">{{ trans('latraining.num_hour_teacher') }}</th>
                    <th data-field="num_course_teacher" data-align="center" data-width="5%">{{ trans('latraining.num_course_teacher') }}</th>
                </tr>
            </thead>
        </table>
    </div>

    <div class="modal right fade" id="modal-popup" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog" id="ajax-modal-popup" role="document">
            <form action="" method="post" class="form-ajax" id="form_save" onsubmit="return false;">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="btn-group">
                            <h5 class="modal-title" id="exampleModalLabel"></h5>
                        </div>
                        <div class="btn-group act-btns">
                            @canany(['target-manager-create', 'target-manager-edit'])
                                <button type="button" onclick="save(event)" class="btn save">{{ trans('labutton.save') }}</button>
                            @endcan
                            <button type="button" class="btn" data-dismiss="modal">{{ trans('labutton.close') }}</button>
                        </div>
                    </div>
                    <div class="modal-body" id="body_modal">
                        <input type="hidden" name="id" value="">
                        <div class="form-group row">
                            <div class="col-sm-3 control-label">
                                <label>{{ trans('lacategory.group') }} <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-9">
                                <label class="radio-inline">
                                    <input id="check-title" type="radio" required name="type" value="1" checked>{{ trans('latraining.title') }}
                                </label>
                                <label class="radio-inline">
                                    <input id="check-user" type="radio" required name="type" value="2">{{ trans('latraining.student') }}
                                </label>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3 control-label">
                                <label for="name">{{ trans('lacategory.name') }} <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-9">
                                <input name="name" type="text" class="form-control" value="" required>
                            </div>
                        </div>
                        <div class="form-group row" id="group-title">
                            <div class="col-sm-3 control-label">
                                <label for="title">{{ trans('laprofile.title') }}</label>
                            </div>
                            <div class="col-md-9">
                                <select name="title[]" id="title" class="load-title form-control" multiple data-title_rank_id="" data-placeholder="-- {{ trans('laprofile.title') }} --">
                                </select>
                            </div>
                        </div>
                        <div class="form-group row" id="group-user">
                            <div class="col-sm-3 control-label">
                                <label for="user">{{ trans('laprofile.user') }}</label>
                            </div>
                            <div class="col-md-9">
                                <select name="user[]" id="user" class="load-user form-control" multiple data-placeholder="-- {{ trans('laprofile.user') }} --">
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3 control-label">
                                <label for="num_hour_student">{{ trans('latraining.num_hour_student') }}</label>
                            </div>
                            <div class="col-md-9">
                                <input name="num_hour_student" type="text" class="form-control is-number" value="">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3 control-label">
                                <label for="num_course_student">{{ trans('latraining.num_course_student') }}</label>
                            </div>
                            <div class="col-md-9">
                                <input name="num_course_student" type="text" class="form-control is-number" value="">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3 control-label">
                                <label for="num_hour_teacher">{{ trans('latraining.num_hour_teacher') }}</label>
                            </div>
                            <div class="col-md-9">
                                <input name="num_hour_teacher" type="text" class="form-control is-number" value="">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3 control-label">
                                <label for="num_course_teacher">{{ trans('latraining.num_course_teacher') }}</label>
                            </div>
                            <div class="col-md-9">
                                <input name="num_course_teacher" type="text" class="form-control is-number" value="">
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="modal-popup-copy" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog" id="ajax-modal-popup" role="document">
            <form action="" method="post" class="form-ajax" id="form_save_copy" onsubmit="return false;">
                <div class="modal-content">
                    <div class="modal-body" id="body_modal">
                        <div class="form-group row">
                            <div class="col-sm-3 control-label">
                                <label>{{ trans('lamenu.target_manager') }} <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-9">
                                <select name="parent_new_id" id="parent_new_id" class="select2 form-control">
                                    @foreach ($target_manager_parent_other as $parent_other)
                                        <option value="{{ $parent_other->id }}">
                                            {{ $parent_other->name .' ('. $parent_other->year .')' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="btn-group act-btns">
                            @canany(['target-manager-copy'])
                                <button type="button" onclick="saveCopy(event)" class="btn save_copy">{{ trans('labutton.save') }}</button>
                            @endcan
                            <button type="button" class="btn" data-dismiss="modal">{{ trans('labutton.close') }}</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="modal-import" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabelImport" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog" role="document">
            <form action="{{ route('module.target_manager.import', [$target_manager_parent->id]) }}" method="post" class="form-ajax">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabelImport">IMPORT {{trans('backend.object')}}</h5>
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
        $('#import').on('click', function() {
            $('#modal-import').modal();
        });

        function name_formatter(value, row, index) {
            return '<a id="edit_'+ row.id +'" style="cursor: pointer;" onclick="edit('+ row.id +')">'+ row.name +'</a>' ;
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.target_manager.getdata', ['parent_id' => $target_manager_parent->id]) }}',
            remove_url: '{{ route('module.target_manager.remove', ['parent_id' => $target_manager_parent->id]) }}'
        });

        function edit(id){
            let item = $('#edit_'+id);
            let oldtext = item.html();
            item.html('<i class="fa fa-spinner fa-spin"></i> {{ trans("laother.please_wait") }}');
            $.ajax({
                url: "{{ route('module.target_manager.edit', ['parent_id' => $target_manager_parent->id]) }}",
                type: 'post',
                data: {
                    id: id,
                }
            }).done(function(data) {
                item.html(oldtext);
                $('#exampleModalLabel').html('{{ trans('labutton.edit') }}');
                $("input[name=id]").val(data.model.id);
                $("input[name=name]").val(data.model.name);
                $("input[name=num_hour_student]").val(data.model.num_hour_student);
                $("input[name=num_course_student]").val(data.model.num_course_student);
                $("input[name=num_hour_teacher]").val(data.model.num_hour_teacher);
                $("input[name=num_course_teacher]").val(data.model.num_course_teacher);

                if (data.model.type == 1) {
                    $('#check-title').prop( 'checked', true );
                    $('#check-user').prop( 'checked', false );

                    $('#group-title').show();
                    $('#group-user').hide();

                    if(data.titles){
                        var list_titles = '';
                        (data.titles).forEach(function(item) {
                            list_titles += '<option value="'+ item.id +'" selected>'+ item.name +'</option>';
                        });

                        $('#title').html(list_titles);
                    }
                } else {
                    $('#check-title').prop( 'checked', false );
                    $('#check-user').prop( 'checked', true );

                    $('#group-user').show();
                    $('#group-title').hide();

                    if(data.profile){
                        var list_user = '';
                        (data.profile).forEach(function(item) {
                            list_user += '<option value="'+ item.user_id +'" selected>'+ item.code +' - '+ item.full_name +'</option>';
                        });

                        $('#user').html(list_user);
                    }
                }

                $('#modal-popup').modal();
                return false;
            }).fail(function(data) {
                show_message('{{ trans('laother.data_error') }}', 'error');
                return false;
            });
        }

        function save(event) {
            let item = $('.save');
            let oldtext = item.html();
            item.html('<i class="fa fa-spinner fa-spin"></i> {{ trans("laother.processing") }}');
            $('.save').attr('disabled',true);

            var form = $('#form_save');

            event.preventDefault();
            $.ajax({
                url: "{{ route('module.target_manager.save', ['parent_id' => $target_manager_parent->id]) }}",
                type: 'post',
                data: form.serialize(),
            }).done(function(data) {
                item.html(oldtext);
                $('.save').attr('disabled',false);
                if (data && data.status == 'success') {
                    $('#modal-popup').modal('hide');
                    show_message(data.message, data.status);
                    $(table.table).bootstrapTable('refresh');
                } else {
                    show_message(data.message, data.status);
                }
                return false;
            }).fail(function(data) {
                show_message('{{ trans('laother.data_error') }}', 'error');
                return false;
            });
        }

        function create() {
            $('#exampleModalLabel').html('{{ trans('labutton.add_new') }}');
            $("input[name=id]").val('');
            $("input[name=name]").val('');
            $("input[name=num_hour_student]").val('0');
            $("input[name=num_course_student]").val('0');
            $("input[name=num_hour_teacher]").val('0');
            $("input[name=num_course_teacher]").val('0');
            $('#user').html('');
            $('#title').html('');
            $('#modal-popup').modal();
        }

        var type = $('input[name=type]:checked').val();
        if(type == 1){
            $('#group-title').show();
            $('#group-user').hide();
        }else{
            $('#group-user').show();
            $('#group-title').hide();
        }

        $('#check-title').on('click', function(){
            $('#group-title').show();
            $('#group-user').hide();
            $('#user').val('').trigger('change');
        });

        $('#check-user').on('click', function(){
            $('#group-user').show();
            $('#group-title').hide();
            $('#title').val('').trigger('change');
        });

        $('#copy').on('click', function(){
            var ids = $("input[name=btSelectItem]:checked").map(function(){return $(this).val();}).get();
            if (ids.length <= 0) {
                show_message('Mời chọn đối tượng', 'error');
                return false;
            }

            $('#modal-popup-copy').modal();
        });

        function saveCopy(event) {
            let item = $('.save_copy');
            let oldtext = item.html();
            item.html('<i class="fa fa-spinner fa-spin"></i> {{ trans("laother.processing") }}');
            $('.save_copy').attr('disabled',true);

            event.preventDefault();

            var ids = $("input[name=btSelectItem]:checked").map(function(){return $(this).val();}).get();
            var parent_new_id = $('#parent_new_id option:selected').val();

            $.ajax({
                url: "{{ route('module.target_manager.copy', ['parent_id' => $target_manager_parent->id]) }}",
                type: 'post',
                data: {
                    ids: ids,
                    parent_new_id: parent_new_id,
                },
            }).done(function(data) {
                item.html(oldtext);
                $('.save_copy').attr('disabled',false);
                if (data && data.status == 'success') {
                    $('#modal-popup-copy').modal('hide');
                    show_message(data.message, data.status);

                    window.location = data.redirect;
                } else {
                    show_message(data.message, data.status);

                    $(table.table).bootstrapTable('refresh');
                }
                return false;
            }).fail(function(data) {
                show_message('{{ trans('laother.data_error') }}', 'error');
                return false;
            });
        }
    </script>

@endsection
