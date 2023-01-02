@extends('layouts.backend')

@section('page_title', trans('lamenu.certificate'))

{{--@section('breadcrumb')--}}
{{--    <div class="ibox-content forum-container">--}}
{{--        @include('layouts.backend.breadcum')--}}
{{--    </div>--}}
{{--@endsection--}}
@section('header')
    <link rel="stylesheet" href="{{ asset('styles/module/chatbot/css/tagsinput.css?v='.time()) }}">
    <script src="{{asset('styles/module/chatbot/js/tagsinput.js')}}"></script>
@endsection
@section('content')

    <div role="main">
        <div class="col-md-12 text-center">
            <a href="{{route('module.botconfig')}}" class="btn" style="background: #203fd9 !important">
                <div><i class="fa fa-tree" aria-hidden="true"></i></div>
                <div>Bot từ khóa</div>
            </a>
            <a href="{{route('module.botconfig.suggest')}}" class="btn" style="background: #203fd9 !important">
                <div><i class="fa fa-hashtag" aria-hidden="true"></i></div>
                <div>Bot gợi ý</div>
            </a>
        </div>
        <div class="row">
              <div class="col-md-8">
                {{-- <form class="form-inline form-search mb-3" id="form-search">
                    <input type="text" name="search" value="" class="form-control" placeholder="{{trans('backend.search_code_name')}}">
                    <button type="submit" class="btn"><i class="fa fa-search"></i>&nbsp;{{ trans('labutton.search') }}</button>
                </form> --}}
            </div>
            <div class="col-md-4 text-right act-btns">
                <div class="pull-right">
                    <div class="btn-group">
                        @can('certificate-template-create')
                            <button style="cursor: pointer;" onclick="create()" class="btn"><i class="fa fa-plus-circle"></i> {{ trans('labutton.add_new') }}</button>
                        @endcan
                            <button class="btn" id="delete-item"><i class="fa fa-trash"></i> {{ trans('labutton.delete') }}</button>
                    </div>
                </div>
            </div>
        </div>
        <br>

        <table class="tDefault table table-hover bootstrap-table text-nowrap" data-page-list="[10, 50, 100, 200, 500]">
            <thead>
                <tr>
                    <th data-field="check" data-checkbox="true" data-width="2%"></th>
                    <th data-field="question" data-width="10%" data-align="center">{{trans('lachat.chat_question')}}</th>
                    <th data-field="answer" data-formatter="name_formatter">{{trans('lachat.chat_answer')}}</th>
                    <th data-field="updated_formatter" data-align="center"  data-width="170px">{{ trans('laother.updated_at') }}</th>
                </tr>
            </thead>
        </table>
    </div>
    <div class="modal right fade" id="modal-popup" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg" id="ajax-modal-popup" role="document">
                <input type="hidden" name="id" value="">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <div class="btn-group act-btns">
                            @canany(['certificate-template-create', 'certificate-template-edit'])
                                <button type="button" onclick="save(event)"  class="btn save">{{ trans('labutton.save') }}</button>
                            @endcan
                            <button type="button" class="btn" data-dismiss="modal">{{ trans('labutton.close') }}</button>
                        </div>
                    </div>
                    <div class="modal-body" id="body_modal">
                        @include('botconfig::backend.keyword')
                    </div>
                </div>
        </div>
    </div>




    <script type="text/javascript">
        function created_formatter(value, row, index) {
            return '<a href="javascript:void(0)" class="load-modal" data-url="'+row.user_created+'"><i class="fa fa-user"></i></a>';
        }

        function updated_formatter(value, row, index) {
            return '<a href="javascript:void(0)" class="load-modal" data-url="'+row.user_updated+'"><i class="fa fa-user"></i></a>';
        }

        function name_formatter(value, row, index) {
            return '<a id="edit_'+ row.id +'" style="cursor: pointer;" onclick="edit('+ row.id +')">'+ row.answer +'</a>' ;
        }

        function image_formatter(value, row, index) {
            return '<img src="'+ row.image +'" class="img-responsive" width="100" height="100">';
        }

        var table = new LoadBootstrapTable({
            url: '{{ route('module.botconfig.getdata') }}',
            locale: '{{ \App::getLocale() }}',
            delete_method: 'delete'
        });

        function edit(id){
            let item = $('#edit_'+id);
            let oldtext = item.html();
            item.html('<i class="fa fa-spinner fa-spin"></i> {{ trans("laother.please_wait") }}');
            $.ajax({
                url: "/admin-cp/botconfig/"+id,
                type: 'get',
                data: {
                }
            }).done(function(data) {
                item.html(oldtext);
                $('#exampleModalLabel').html('{{ trans('labutton.edit') }} ');
                let $form = $('#form_save');

                $("#botquestion").tagsinput("removeAll");
                data.questions.forEach((item,key) => {
                    $("#botquestion").tagsinput('add',item.question);
                });
                $("input[name=id]",$form).val(data.id);
                // $("textarea[name=answer]",$form).val(data.answer);
                $("#bot-answer",$form).val(data.answer);
                $('#modal-popup').modal();

                return false;
            }).fail(function(data) {
                show_message('{{ trans('laother.data_error') }}', 'error');
                return false;
            });
        }

        function save(event) {
            $('.tab-content .tab-pane.active #form_save')
            CKEDITOR.instances['bot-answer'].updateElement();
            let item = $('.save');
            let oldtext = item.html();
            item.html('<i class="fa fa-spinner fa-spin"></i> {{ trans("laother.processing") }}');
            $('.save').attr('disabled',true);
            event.preventDefault();
            $.ajax({
                url: "{{ route('module.botconfig.post') }}",
                type: 'post',
                data: $('#form_save_keyword').serialize()
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
            CKEDITOR.instances['bot-answer'].setData( '', function() { this.updateElement(); } )
            $('#form_save').trigger("reset");
            $("#botquestion").tagsinput("removeAll");
            $("#bot-answer").val('');
            $('#exampleModalLabel').html('{{ trans('labutton.add_new') }}');
            $('#modal-popup').modal();
        }

        $("#select-image").on('click', function () {
            var lfm = function (options, cb) {
                var route_prefix = '/filemanager';
                window.open(route_prefix + '?type=' + options.type || 'file', 'FileManager', 'width=1100,height=600');
                window.SetUrl = cb;
            };

            lfm({type: 'image'}, function (url, path) {
                $("#image-review").html('<img class="w-100" src="'+ path +'">');
                $("#image-select").val(path);
            });
        });
        CKEDITOR.replace('answer', {
            toolbar: [
                [ 'Bold', 'Italic', '-', 'NumberedList', 'BulletedList', '-', 'Link', 'Unlink' ],
                [ 'FontSize', 'TextColor', 'BGColor' ]
            ]
        });
        CKEDITOR.replace('answer-suggest', {
            toolbar: [
                [ 'Bold', 'Italic', '-', 'NumberedList', 'BulletedList', '-', 'Link', 'Unlink' ],
                [ 'FontSize', 'TextColor', 'BGColor' ]
            ]
        });
    </script>
@endsection
