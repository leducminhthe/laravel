@extends('layouts.backend')

@section('page_title', trans('lamenu.badge'))

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            <a href="{{ route('backend.category') }}">{{ trans('lamenu.category') }}</a>
            <i class="uil uil-angle-right"></i>
            <a href="{{ route('module.usermedal.list') }}">{{ trans('lamenu.compete_title') }}</a>
            <i class="uil uil-angle-right"></i>
            <span class="font-weight-bold">{{ trans('lacategory.add_new') }}</span>
        </h2>
    </div>
@endsection

@section('content')
    <form action="{{ route('module.usermedal.save') }}" method="post" class="form-ajax" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-6"></div>
            <div class="col-md-6 text-right act-btns">
                <div class="btn-group">
                    @can(['category-usermedal-create', 'category-usermedal-edit'])
                        @if(!$ro)
                        <button type="submit" class="btn"><i class="fa fa-save"></i> {{ trans('labutton.save') }}</button>
                        @endif
                    @endcan
                    <a href="{{ route('module.usermedal.list') }}" class="btn"><i class="fa fa-times"></i> {{ trans('labutton.cancel') }}</a>
                </div>
            </div>
        </div>
        <div class="mt-3"></div>
        <div class="tPanel">
            <ul class="nav nav-tabs" role="tablist">
                <li class="active"><a href="#base">{{ trans('lacategory.info') }}</a></li>
            </ul>
            @if($ro)
                <div class="alert alert-danger" role="alert">
                    {{ trans('latraining.notify_edit_emulation_program') }}
                </div>
            @endif
            <div class="tab-content">
                <div id="base" class="tab-pane active">
                    <div>&nbsp;</div>
                    <div class="form-group row">
                        <div class="col-sm-2 control-label">
                            <label>{{ trans('lacategory.code') }} <span class="text-danger">*</span> </label>
                        </div>
                        <div class="col-md-10">
                            <input type="text" name="code" class="form-control" value="{{ $model->code }}" autocomplete="off">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-2 control-label">
                            <label>{{ trans('lacategory.name') }} <span class="text-danger">*</span></label>
                        </div>
                        <div class="col-md-10">
                            <input type="text" name="name" class="form-control" value="{{ $model->name }}" autocomplete="off">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-2 control-label">
                            <label>{{ trans('lacategory.image') }} (600x400) <span class="text-danger">*</span></label>
                        </div>
                        <div class="col-md-5">
                            <a href="javascript:void(0)" id="select-image">{{trans('lacategory.choose_picture')}}</a>
                            <div id="image-review" class="border-0">
                                @if($model->photo)
                                    <img src="{{ image_file($model->photo) }}" alt="" class="img-reponsive w-30">
                                @endif
                            </div>
                            <input type="hidden" id="image-select" name="photo" value="">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-2 control-label">
                            <label>{{ trans('lacategory.description') }}</label>
                        </div>
                        <div class="col-md-10">
                            <textarea name="content" id="content1" class="form-control" rows="3">{{$model->content}}</textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-2 control-label">
                            <label>{{ trans('lamenu.rule_badge') }}</label>
                        </div>
                        <div class="col-md-10">
                            <textarea name="rule" id="rule" data-rule-html="1" data-msg-html="">{{$model->rule}}</textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-2 control-label">
                            <label>{{ trans('lacategory.status') }}</label>
                        </div>
                        <div class="col-md-10">
                            <input type="radio" name="status" value="1" {{ $model->status == 1 ? 'checked' :  '' }}>&nbsp;&nbsp;{{ trans('lacategory.enable') }}
                            <input type="radio" name="status" value="0" {{ $model->status == 0 ? 'checked' :  '' }} >&nbsp;&nbsp;{{ trans('lacategory.disable') }}
                        </div>
                    </div>

                    @if($model->id)
                        <div class="row">
                            <div class="col-md-6"></div>
                            <div class="col-md-6 text-right">
                                @if(!$ro)
                                <a href="javascript:void(0)" class="btn" data-toggle="modal" data-target="#modal-child"><i class="fa fa-plus"></i> {{ trans('lacategory.add_child_badge') }}</a>
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mt-1">
                                <table class="tDefault table table-hover bootstrap-table" id="table-child">
                                    <thead>
                                        <tr>
                                            <th data-align="center" data-width="5%" data-formatter="index_formatter">#</th>
                                            <th data-field="vphoto" data-align="center" data-width="10%">{{ trans('lacategory.image') }}</th>
                                            <th data-field="code" data-align="left" data-width="5%">{{trans("lacategory.code")}}</th>
                                            <th data-field="name">{{ trans('lacategory.name') }}</th>
                                            <th data-field="content">{{ trans('lacategory.description') }}</th>
                                            <th data-field="rank" data-align="center" data-width="5%">{{ trans('lacategory.rank') }}</th>
                                            @if(!$ro)
                                            <th data-align="center" data-align="center" data-width="8%" data-formatter="action_formatter">{{ trans('lacategory.action') }}</th>
                                            @endif
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                        <script>
                            var table = new LoadBootstrapTable({
                                locale: '{{ \App::getLocale() }}',
                                url: '{{ route('module.usermedal.getdata_child', ['id' => $model->id]) }}',
                            });
                        </script>
                    @endif
                </div>

                <input type="hidden" name="id" value="{{ $model->id }}">
            </div>
        </div>
    </form>

    <div class="modal fade" id="modal-child" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-success="add_child_success" data-backdrop="static" data-keyboard="false">
        <form action="{{ route('module.usermedal.save') }}" method="post" class="form-ajax" enctype="multipart/form-data">
            <input type="hidden" name="idc" value="">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">{{ trans('lacategory.child_badge') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group row">
                            <div class="col-sm-2 control-label">
                                <label>{{ trans('lacategory.code') }} <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-10">
                                <div class="input-group text-bottom">
                                    {{ $model->code }}_<input type="text" name="code" id="code_child" class="form-control" value="" autocomplete="off">
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-2 control-label">
                                <label>{{ trans('lacategory.name') }} <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-10">
                                <input type="text" name="name" id="name_child" class="form-control" value="" autocomplete="off">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-2 control-label">
                                <label>{{ trans('lacategory.rank') }}</label>
                            </div>
                            <div class="col-md-10">
                                <input type="number" min="1" max="20" class="form-control" name="rank" id="rank_child" value="" autocomplete="off">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-2 control-label">
                                <label>{{ trans('lacategory.image') }} <span class="text-danger">*</span> (600x400) </label>
                            </div>
                            <div class="col-md-10">
                                <a href="javascript:void(0)" id="select-image-child">{{trans('lacategory.choose_picture')}}</a>
                                <div id="image-review-child"></div>
                                <input type="hidden" id="image-select-child" name="photo" value="">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-2 control-label">
                                <label>{{ trans('lacategory.description') }}</label>
                            </div>
                            <div class="col-md-10">
                                <textarea name="content" id="content2" class="form-control" rows="3"></textarea>
                            </div>
                        </div>

                        <input type="hidden" name="parent_id" value="{{ $model->id }}">
                    </div>

                    <div class="modal-footer">
                        @can(['category-usermedal-create', 'category-usermedal-edit'])
                            <button type="submit" class="btn"><i class="fa fa-save"></i> {{ trans('labutton.save') }}</button>
                        @endcan
                        <button type="button" class="btn" data-dismiss="modal">{{ trans('labutton.close') }}</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script type="text/javascript">
        function index_formatter(value, row, index) {
            return (index + 1);
        }

        function action_formatter(value, row, index) {
            return '<a href="javascript:void(0)" class="text-primary edit-child" data-id="'+row.id+'"><i class="fa fa-1x fa-edit"></i></a> <a href="javascript:void(0)" class="text-danger remove-child" data-id="'+row.id+'"><i class="fa fa-1x fa-trash"></i></a>';
        }

        CKEDITOR.replace('rule', {
            filebrowserImageBrowseUrl: '/filemanager?type=image',
            filebrowserBrowseUrl: '/filemanager?type=file',
            filebrowserUploadUrl : null, //disable upload tab
            filebrowserImageUploadUrl : null, //disable upload tab
            filebrowserFlashUploadUrl : null, //disable upload tab
        });

        function add_child_success(form) {
            //   window.location = "{{ url()->current() }}";
            location.reload();
        }

        $('#table-child').on('click', '.remove-child', function () {
            let item = $(this);
            let id = $(this).data('id');

            if (!id) {
                return false;
            }

            if (!confirm('Bạn có chắc muốn xóa huy hiệu này?')) {
                return false;
            }

            $.ajax({
                type: "POST",
                url: "{{ route('module.usermedal.remove') }}",
                dataType: 'json',
                data: {
                    'ids': [id]
                },
                success: function (result) {
                    item.closest('tr').remove();
                }
            });
        });

        $('#table-child').on('click', '.edit-child', function () {
            let id = $(this).data('id');

            if (!id) {
                return false;
            }

            $.ajax({
                type: "POST",
                url: "{{ route('module.usermedal.edit_promotion_child') }}",
                data: {
                    'id': id
                }
            }).done (function (result) {
                $('input[name=idc]').val(id);
                $('#code_child').val(result.code).trigger('change');
                $('#name_child').val(result.name).trigger('change');
                $('#rank_child').val(result.rank).trigger('change');
                $('#content2').val(result.content).trigger('change');
             /*   if(result.content){
                    tinymce.activeEditor.setContent(result.content);
                }*/

                if(result.image_view){
                    $('#image-review-child').html(result.image_view);
                }

                $('#modal-child').modal('show');
            });
        });

        $('#modal-child').on('hidden.bs.modal', function () {
            $('input[name=idc]').val('');
            $('#code_child').val('').trigger('change');
            $('#name_child').val('').trigger('change');
            $('#rank_child').val('').trigger('change');
            $('#image-review-child').html('');
            $('#content2').val('').trigger('change');
        })

        $("#select-image").on('click', function () {
            var lfm = function (options, cb) {
                var route_prefix = '/filemanager';
                window.open(route_prefix + '?type=' + options.type || 'file', 'FileManager', 'width=1100,height=600');
                window.SetUrl = cb;
            };

            lfm({type: 'image'}, function (url, path) {
                $("#image-review").html('<img style="height: 100px; width: auto;" src="'+ path +'">');
                $("#image-select").val(path);
            });
        });

        $("#select-image-child").on('click', function () {
            var lfm = function (options, cb) {
                var route_prefix = '/filemanager';
                window.open(route_prefix + '?type=' + options.type || 'file', 'FileManager', 'width=1100,height=600');
                window.SetUrl = cb;
            };

            lfm({type: 'image'}, function (url, path) {
                $("#image-review-child").html('<img style="height: 100px; width: auto;" src="'+ path +'">');
                $("#image-select-child").val(path);
            });
        });

    /*  $(document).ready(function () {

            $(".modal").on("shown.bs.modal", function() {
                CKEDITOR.replace('content1', {
                    filebrowserImageBrowseUrl: '/filemanager?type=image',
                    filebrowserBrowseUrl: '/filemanager?type=file',
                    filebrowserUploadUrl : null, //disable upload tab
                    filebrowserImageUploadUrl : null, //disable upload tab
                    filebrowserFlashUploadUrl : null, //disable upload tab
                });
            });

        });*/

    /*    function initEditor(){
            tinymce.init({
                selector: 'textarea.editor',
                convert_urls: true,
                relative_urls: false,
                remove_script_host: false,
                height: 300,
                menubar: 'table format insert edit',
                fontsize_formats: "8px 10px 12px 14px 18px 24px 36px 48px 64px 120px",
                plugins : ['autolink link image lists charmap responsivefilemanager table code fullscreen textcolor'],
                toolbar1: 'undo redo | styleselect | bold italic underline forecolor backcolor fontsizeselect fontselect | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | responsivefilemanager | link unlink anchor | image media | print preview code fullscreen',
                theme_advanced_fonts : "Andale Mono=andale mono,times;"+
                    "Arial=arial,helvetica,sans-serif;"+
                    "Arial Black=arial black,avant garde;"+
                    "Book Antiqua=book antiqua,palatino;"+
                    "Comic Sans MS=comic sans ms,sans-serif;"+
                    "Courier New=courier new,courier;"+
                    "Georgia=georgia,palatino;"+
                    "Helvetica=helvetica;"+
                    "Impact=impact,chicago;"+
                    "Symbol=symbol;"+
                    "Tahoma=tahoma,arial,helvetica,sans-serif;"+
                    "Terminal=terminal,monaco;"+
                    "Times New Roman=times new roman,times;"+
                    "Trebuchet MS=trebuchet ms,geneva;"+
                    "Verdana=verdana,geneva;"+
                    "Webdings=webdings;"+
                    "Wingdings=wingdings,zapf dingbats",
                image_advtab: true,
                apply_source_formatting: false,
                external_filemanager_path:"../asset/filemanager/",
                filemanager_title:"Filemanager" ,
                filemanager_access_key: '3df199403c395007d955476a62dfd3c2',
                external_plugins: { "filemanager" : "../../vendor/filemanager/plugin.min.js" }
            });
        }

        $(document).on('buildEditor',function(){
            initEditor();
        });

        initEditor();*/

        $('.zoom_image').on('click', function () {
            let url = $(this).data('url');

            $.ajax({
                type: 'POST',
                url: url,
                dataType: 'html',
                data: {},
            }).done(function(data) {
                $("#show-modal").html(data);
                $("#show-modal #myModal").modal();
            }).fail(function(data) {
                show_message('Lỗi dữ liệu', 'error');
                return false;
            });

        });
    </script>

@endsection
