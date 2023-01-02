@extends('layouts.backend')

@section('page_title', 'Cấu hình Bot chat gợi ý')

{{--@section('breadcrumb')--}}
{{--    <div class="ibox-content forum-container">--}}
{{--        @include('layouts.backend.breadcum')--}}
{{--    </div>--}}
{{--@endsection--}}
@section('header')
    <link rel="stylesheet" href="{{ asset('styles/module/chatbot/css/tagsinput.css?v='.time()) }}">
    <script src="{{asset('styles/module/chatbot/js/tagsinput.js')}}"></script>
    <link rel="stylesheet" href="{{ asset('styles/vendor/hierarchy-select/css/hierarchy-select.min.css') }}">
    <script src="{{asset('styles/vendor/hierarchy-select/js/hierarchy-select.min.js')}}"></script>
@endsection
@section('content')

    <div role="main">

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
                    <th data-field="name"  data-align="left" data-formatter="name_formatter">{{trans('lachat.chat_question')}}</th>
                    <th data-field="url" data-width="30%" >url</th>
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
                        <form action="{{route('module.botconfig.post')}}" method="post" class="form-ajax" id="form_save_suggest" enctype="multipart/form-data">
                            <div class="wrap-suggest-form">
                                <div class="offset-3"><p><h3>{{ trans('labutton.add_new') }}</h3></p></div>
                                <div class="form-group row">
                                    <div class="col-sm-3 control-label">
                                        <label for="code">{{trans('lachat.chat_parent')}} <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="dropdown hierarchy-select" id="parent-id">
                                            <button type="button" class="btn dropdown-toggle" id="example-one-button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
                                            <div class="dropdown-menu" aria-labelledby="example-one-button">
                                                <div class="hs-searchbox">
                                                    <input type="text" class="form-control" autocomplete="off">
                                                </div>
                                                <div class="hs-menu-inner">
                                                    <a class="dropdown-item" data-value="0"  data-level="0" href="#">-- Root --</a>
                                                    @foreach($suggests as $item)
                                                    <a class="dropdown-item" data-value="{{$item->id}}"  data-level="{{$item->level}}" href="#">{{$item->name}}</a>
                                                    @endforeach
                                                </div>
                                            </div>
                                            <input class="d-none" name="parent" readonly="readonly" aria-hidden="true" type="text" />
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row hidden">
                                    <div class="col-sm-3 control-label">
                                        <label for="name">{{trans('lachat.suggest_parent')}} <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-md-9" style="background: #ced7e7; padding:12px">
                                        <input type="text" name="suggest[]" placeholder="Câu gợi ý" class="form-control" />
                                        <input type="text" name="link[]" class="form-control mt-2" placeholder="link url">
                                        <textarea row="3" name="answer[]" placeholder="{{trans('lachat.suggest_bot_answer')}}" class="form-control"></textarea>
                                    </div>
                                </div>
                            <div class="wrap-suggest-item"></div>
                            </div>
                            <div class="form-group row">
                                <div class="offset-3 col-md-9">
                                    <button class="btn" id="add-answer-suggest">Thêm câu trả lời</button>
                                </div>
                            </div>
                            <input type="hidden" name="id" value="">
                        </form>
                    </div>
                </div>
        </div>
    </div>
    <template id="answer-suggest-template">
        <div class="form-group row">
            <div class="offset-3 col-md-9" style="background: #ced7e7; padding:12px">
                <input type="text" name="suggest[]" placeholder="Câu trả lời" class="form-control" />
                <input type="text" name="link[]" class="form-control mt-2" placeholder="link url">
                <textarea row="3" name="answer[]" placeholder="{{trans('lachat.suggest_bot_answer')}}" class="form-control"></textarea>
            </div>
        </div>
    </template>
    <script type="text/javascript">
        let template = $('#answer-suggest-template').contents();
        let form = $('#form_save_suggest');
        function created_formatter(value, row, index) {
            return '<a href="javascript:void(0)" class="load-modal" data-url="'+row.user_created+'"><i class="fa fa-user"></i></a>';
        }

        function updated_formatter(value, row, index) {
            return '<a href="javascript:void(0)" class="load-modal" data-url="'+row.user_updated+'"><i class="fa fa-user"></i></a>';
        }

        function name_formatter(value, row, index) {

            return '<a id="edit_'+ row.id +'" style="cursor: pointer;" onclick="edit('+ row.id +')">'+ row.name +'</a>' ;
        }

        function image_formatter(value, row, index) {
            return '<img src="'+ row.image +'" class="img-responsive" width="100" height="100">';
        }

        // var table = new LoadBootstrapTable({
        {{--    url: '{{ route('module.botconfig.suggest.getdata') }}',--}}
        {{--    locale: '{{ \App::getLocale() }}',--}}
        {{--    delete_method: 'delete'--}}
        {{--});--}}

        let $table = $('.bootstrap-table');
        $(function() {
            $('#parent-id').hierarchySelect({hierarchy: true, initialValueSet: true,});
            $table.bootstrapTable({
                url: '{{ route('module.botconfig.suggest.getdata') }}',
                idField: 'id',
                treeShowField: 'name',
                parentIdField: 'parent_id',
                onPostBody: function() {
                    let columns = $table.bootstrapTable('getOptions').columns;

                    $table.treegrid({
                        treeColumn: 1,
                        onChange: function() {
                            $table.bootstrapTable('resetView')
                        }
                    })
                }
            });
        })
        function edit(id){
            let item = $('#edit_'+id);
            let oldtext = item.html();
            item.html('<i class="fa fa-spinner fa-spin"></i> {{ trans("laother.please_wait") }}');
            $.ajax({
                url: "/admin-cp/botconfig/suggest/"+id,
                type: 'get',
                data: {
                }
            }).done(function(data) {
                item.html(oldtext);
                $('#exampleModalLabel').html('{{ trans('labutton.edit') }} ');
                let $form = $('#form_save');
                $('.wrap-suggest-item').children().remove();
                // template.find('input[name=\"answer[]\"]').val('');
                /*$('.wrap-suggest-item').children().remove();
                data.childs.forEach((item,key) => {
                    template.find('input[name=\"answer[]\"]').val(item.name);
                    $('.wrap-suggest-item').append(template.clone());
                });*/
                $('input[name=\"suggest[]\"]').val(data.name);
                $('input[name=\"link[]\"]').val(data.url);
                $('textarea[name=\"answer[]\"]').val(data.answer);
                $('input[name=id]','#form_save_suggest').val(data.id);
                $('#parent-id').hierarchySelect('setValue',data.parent_id);
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
            event.preventDefault();
            $.ajax({
                url: "{{ route('module.botconfig.suggest.post') }}",
                type: 'post',
                data: $('#form_save_suggest').serialize()
            }).done(function(data) {
                item.html(oldtext);
                $('.save').attr('disabled',false);
                if (data && data.status == 'success') {
                    $('#modal-popup').modal('hide');
                    show_message(data.message, data.status);
                    $table.bootstrapTable('refresh');
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
            $('.wrap-suggest-item').children().remove();
            // $('.wrap-suggest-item').append(template.clone());
            $('#form_save_suggest').trigger("reset");
            $('input[name=id]','#form_save_suggest').val("");
            $('textarea[name=answer]','#form_save_suggest').val("");
            let $valParent = $('.hs-menu-inner a.dropdown-item:first').data('value');
            $('#parent-id').hierarchySelect('setValue',$valParent>0?$valParent:0);
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

        $('#add-answer-suggest').on('click',(e)=>{
            e.preventDefault();
            let template = $('#answer-suggest-template').html();
            $('.wrap-suggest-item').append(template);
        })
        $('#delete-item').on('click',(e)=>{
            let $this = $(this);
            e.preventDefault();
            let ids = $("input[name=btSelectItem]:checked").map(function(){return $(this).val();}).get();
            Swal.fire({
                title: '',
                text: 'Bạn có chắc muốn xóa các mục đã chọn?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '{{ trans("laother.agree") }}!',
                cancelButtonText: '{{ trans("labutton.cancel") }}!',
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        type: 'delete',
                        url: '/admin-cp/botconfig/suggest',
                        dataType: 'json',
                        data: {
                            'ids': ids
                        },
                        success: function (result) {
                            if (result.status === "success") {
                                show_message(result.message, result.status);
                                $table.bootstrapTable('refresh');
                                $this.attr('disabled', true);
                                return false;
                            } else {
                                show_message(result.message, result.status);
                                return false;
                            }
                        }
                    });
                }
            })
        })
    </script>

@endsection
