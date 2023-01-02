@extends('layouts.backend')

@section('page_title', trans('lahandle_situations.situations_discuss'))

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lamenu.situations_proccessing'),
                'url' => route('module.topic_situations')
            ],
            [
                'name' => $model->name . ': '. trans('lahandle_situations.situations_discuss'),
                'url' => ''
            ],
        ]
    @endphp
    @include('layouts.backend.menu_breadcum',$breadcum)
@endsection

@section('content')
    <div role="main">
        <div class="row">
            {{-- <div class="col-12 mb-3">
                <h4>{{ trans('lahandle_situations.situations_discuss') }}</h4>
            </div> --}}
            <div class="col-md-7">
                <form id="form-search" class="mb-3">
                    <div class="form-row align-items-center">
                        <div class="mr-1">
                            <input type="text" name="search" value="" class="form-control" autocomplete="off" placeholder="{{ trans('lahandle_situations.enter_code_name') }}">
                        </div>
                        <div class="mr-1">
                            <input name="time_created" type="text" class="datepicker form-control" placeholder="{{ trans('lahandle_situations.created_at') }}" autocomplete="off">
                        </div>
                        <button type="submit" class="btn"><i class="fa fa-search"></i>&nbsp;{{ trans('labutton.search') }}</button>
                    </div>
                </form>
            </div>
            <div class="col-md-5 text-right act-btns">
                <div class="pull-right">
                    <div class="btn-group">
                        @can('situation-create')
                            <a style="cursor: pointer;" onclick="createSituation()" class="btn"><i class="fa fa-plus-circle"></i> {{trans('labutton.add_new')}}</a>
                        @endcan
                        @can('situation-delete')
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
                    <th data-field="state" data-width="5%" data-checkbox="true"></th>
                    <th data-field="name" data-width="25%" data-formatter="name_formatter">{{ trans('lahandle_situations.situations_discuss_name') }}</th>
                    <th data-align="center" data-field="code" data-width="15%">{{ trans('lahandle_situations.situations_discuss_code') }}</th>
                    <th data-align="center" data-field="created_at2" data-width="15%">{{ trans('lahandle_situations.created_at') }}</th>
                    <th data-field="image" data-align="center" data-formatter="comment_formatter" data-width="10%">{{ trans('lahandle_situations.comment') }}</th>
                </tr>
            </thead>
        </table>
    </div>

    <div class="modal right fade" id="modal-situation" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog" role="document">
            <form action="" method="post" class="form-ajax" id="form_save_situation">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="btn-group">
                            <h5 class="modal-title" id="exampleModalLabel"></h5>
                        </div>
                        @can('situation-create')
                            <div class="btn-group act-btns">
                                <button type="button" onclick="saveEdit(event)" class="btn save">{{ trans('labutton.save') }}</button>
                                <button type="button" class="btn" data-dismiss="modal">{{ trans('labutton.close') }}</button>
                            </div>
                        @endcan
                    </div>
                    <div class="modal-body" id="body_modal">

                    </div>
                </div>
            </form>
        </div>
    </div>

    <script type="text/javascript">
        function name_formatter(value, row, index) {
            return '<a id="edit_'+ row.id +'" style="cursor: pointer;" onclick="edit('+ row.id +')">'+ row.name +'</a>' ;
        }

        function comment_formatter(value, row, index) {
            return '<a href="' + row.commentSituation + '"><i class="fas fa-edit"></i></a>'
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.get.situations',['id' => $topic_id]) }}',
            remove_url: '{{ route('module.remove.situations',['id' => $topic_id]) }}'
        });

        function edit(id){
            let item = $('#edit_'+id);
            let oldtext = item.html();
            item.html('<i class="fa fa-spinner fa-spin"></i> {{ trans("laother.please_wait") }}');

            $.ajax({
                url: "{{ route('module.ajax.edit.situations') }}",
                type: 'post',
                data: {
                    id: id,
                }
            }).done(function(data) {
                item.html(oldtext);
                $('#exampleModalLabel').html('{{ trans('labutton.edit') }}');
                $('#body_modal').html(`<input type="hidden" name="type" value="1">
                                        <div class="form-group row">
                                            <div class="col-sm-3 control-label">
                                                <label for="name_situations">{{ trans('lahandle_situations.situations_discuss_name') }}</label>
                                            </div>
                                            <div class="col-md-9">
                                                <input name="name_situations" type="text" class="form-control" value="`+ data.name +`" required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-sm-3 control-label">
                                                <label for="code_situations">{{ trans('lahandle_situations.situations_discuss_code') }}</label>
                                            </div>
                                            <div class="col-md-9">
                                                <input name="code_situations" type="text" class="form-control" value="`+ data.code +`" required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-sm-3 control-label">
                                                <label for="description_situations">{{ trans('lahandle_situations.description') }}</label>
                                            </div>
                                            <div class="col-md-9">
                                                <textarea id="content" name="description_situations" class="form-control" placeholder="{{ trans('lahandle_situations.description') }}">`+ data.description +`</textarea>
                                            </div>
                                        </div>`)
                $('#modal-situation').modal();
                CKEDITOR.replace('content', {
                    filebrowserImageBrowseUrl: '/filemanager?type=image',
                    filebrowserBrowseUrl: '/filemanager?type=file',
                    filebrowserUploadUrl : null, //disable upload tab
                    filebrowserImageUploadUrl : null, //disable upload tab
                    filebrowserFlashUploadUrl : null, //disable upload tab
                });
                return false;
            }).fail(function(data) {
                show_message('{{ trans('laother.data_error') }}', 'error');
                return false;
            });
        }

        function saveEdit(event) {
            let item = $('.save');
            let oldtext = item.html();
            item.html('<i class="fa fa-spinner fa-spin"></i> {{ trans("laother.processing") }}');
            $('.save').attr('disabled',true);

            var form = $('#form_save_situation');
            var description_situations = CKEDITOR.instances['content'].getData();
            var name_situations =  $("input[name=name_situations]").val();
            var code_situations =  $("input[name=code_situations]").val();
            event.preventDefault();
            $.ajax({
                url: "{{ route('module.save.situations', ['id' => $topic_id]) }}",
                type: 'post',
                data: {
                    'description_situations': description_situations,
                    'name_situations': name_situations,
                    'code_situations': code_situations,
                }
            }).done(function(data) {
                item.html(oldtext);
                $('.save').attr('disabled',false);
                $('#modal-situation').modal('hide');
                $(table.table).bootstrapTable('refresh');
                return false;
            }).fail(function(data) {
                show_message('{{ trans('laother.data_error') }}', 'error');
                return false;
            });
        }

        function createSituation() {
            $('#exampleModalLabel').html('{{ trans('labutton.add_new') }}');
            $('#body_modal').html(`<div class="form-group row">
                                        <div class="col-sm-3 control-label">
                                            <label for="name_situations">{{ trans('lahandle_situations.situations_discuss_name') }}</label>
                                        </div>
                                        <div class="col-md-9">
                                            <input name="name_situations" type="text" class="form-control" value="" required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-3 control-label">
                                            <label for="code_situations">{{ trans('lahandle_situations.situations_discuss_code') }}</label>
                                        </div>
                                        <div class="col-md-9">
                                            <input name="code_situations" type="text" class="form-control" value="" required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-3 control-label">
                                            <label for="description_situations">{{ trans('lahandle_situations.description') }}</label>
                                        </div>
                                        <div class="col-md-9">
                                            <textarea id="content" name="description_situations" class="form-control" placeholder="{{ trans('lahandle_situations.description') }}"></textarea>
                                        </div>
                                    </div>`)
            CKEDITOR.replace('content', {
                filebrowserImageBrowseUrl: '/filemanager?type=image',
                filebrowserBrowseUrl: '/filemanager?type=file',
                filebrowserUploadUrl : null, //disable upload tab
                filebrowserImageUploadUrl : null, //disable upload tab
                filebrowserFlashUploadUrl : null, //disable upload tab
            });
            $('#modal-situation').modal();
        }
    </script>
@endsection
